<?php 
include_once 'database.php';
/**
 * 
 */
class DRBFunc extends dbh
{
	public $id;
	public $occur_month;
	public $work_week;
	public $occur_date;
	public $drb_date;
	public $drb_number;
	public $rfc_no;
	public $drb_issue;
	public $block;
	public $process;
	public $product;
	public $issue_type;
	public $m5e1;
	public $affected_lot;
	public $lot_out;
	public $sheet_out;
	public $rank;
	public $drb_status;
	public $closing_validation_plan_date;
	public $closing_validation_date;
	public $meeting_status;
	public $machine_no;
	public $name_of_approval;
	public $drb_path;
	public $drb_number_upload;
	public $drb_first_upload;
	public $drb_last_upload;
	public $output ="";
	public $result ="";
	public function get_proc_opt($block)
	{
		try {
			$sql = pg_query($this->con,"SELECT * FROM tbl_affected_process WHERE block = '$block' ORDER BY process ASC");

			while ($rows = pg_fetch_array($sql)) {
				$process = $rows['process'];
				echo '<option class="used" value="'.$process.'">'.$process.'</option>';
			}
		} catch (Exception $e) {
			echo "Error: ".$e->getMessage();
		}
		

	}
	public function get_rank_opt($rank)
	{
		try {
			$sql = pg_query($this->con,"SELECT * FROM tbl_closing_approval WHERE rank = $rank");

			while ($rows = pg_fetch_array($sql)) {
				echo $rows['name_of_approval'];
			}
		} catch (Exception $e) {
			echo "Error: ".$e->getMessage();
		}
	}
	// List of ledger
	public function ledgerlist(){
		$record_per_page = 10;
		$page = '';

		if (isset($_POST["page"])) {
			$page = $_POST["page"];
			
		}
		else {
			$page = 1;
		}
		$start_from = ($page - 1) * $record_per_page;
		$sql = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger ORDER BY work_week DESC, drb_number DESC ,occur_date DESC  LIMIT $record_per_page OFFSET $start_from");

		$this->output .= '
		<table class="drblist table table-hover table-bordered table-responsive">
		<thead class="thead-dark">
		<tr>
		<th>Month</th>
		<th>WW#</th>
		<th>Occurrence Date</th>
		<th>DRB Date</th>
		<th>DRB Number</th>
		<th>DRB Issue</th>
		<th>RFC #</th>
		<th>Block</th>
		<th>Process Affected</th>
		<th>Product Affected</th>
		<th>5M1E</th>
		<th>Recurrence or New Issue</th>
		<th># of Affected Lots</th>
		<th># lotouts lots</th>
		<th>Rank</th>
		<th>Issue Status</th>
		<th>Close Validation Date plan</th>
		<th>Close validation Date actual</th>
		<th>Close Validation and Approval By</th>
		<th>Action</th>
		</tr>
		</thead>
		<tbody>';

		while ($row = pg_fetch_array($sql)) {
			$status = $row['drb_status'];
			$rank = $row['rank'];
			$date_plan = $row['closing_validation_plan_date'];
			if (empty($date_plan)) {
				$date_plan = "-";
			}
			else{
				$date_plan = date('F d, Y', strtotime($row['closing_validation_plan_date']));
			}

			if ($status == 1) {
				$stats = "Open";
				$date_actual = "-";
			}
			else{
				$stats ="Closed";
				$date_actual = date('F d, Y', strtotime($row['closing_validation_date']));
			}
			$get_approval = pg_query($this->con,"SELECT * FROM tbl_closing_approval WHERE rank = $rank");
			while ($get = pg_fetch_array($get_approval)) {
				$name_of_approval = $get['name_of_approval'];
			}
			$this->output .='<tr>
			<td>'.$row['occur_month'].'</td>
			<td>'.$row['work_week'].'</td>
			<td>'.date('F d, Y', strtotime($row['occur_date'])).'</td>
			<td>'.date('F d, Y', strtotime($row['drb_date'])).'</td>
			<td>'.strtoupper($row['drb_number']).'</td>
			<td>'.$row['drb_issue'].'</td>
			<td>'.$row['rfc_no'].'</td>
			<td>'.strtoupper($row['block']).'</td>
			<td>'.strtoupper($row['process']).'</td>
			<td>'.$row['product'].'</td>
			<td>'.$row['m5e1'].'</td>
			<td>'.$row['issue_type'].'</td>
			<td>'.$row['affected_lots'].'</td>
			<td>'.$row['lot_out'].'</td>
			<td>'.$rank.'</td>
			<td>'.$stats.'</td>
			<td>'.$date_plan.'</td>
			<td>'.$date_actual.'</td>
			<td>'.$name_of_approval.'</td>
			<td><a class="btn btn-primary text-white view_drb" data-response="'.strtoupper(md5(strtotime("now"))).'" data-id="'.$row['drb_number'].'"><span class="oi oi-eye"></span>View</a></td>
			</tr>';
		}
		$this->output .='
		</tbody>
		</table>
		<nav aria-label="Page navigation example">
		<ul class="pagination justify-content-center">';
		$pagi = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger");
		$total_records = pg_num_rows($pagi);
		$total_pages = ceil($total_records/$record_per_page);
		for ($i=1; $i <= $total_pages; $i++) { 
			$this->output .=
			'<li class="page-item tracking_class"><a class="tracking_link page-link" id='.$i.'>'.$i.'</a></li>'; 
		}
		$this->output .= '</ul>
		</nav>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".view_drb").on("click",function() {
				var id = $(this).data("id");
				var response = $(this).data("response");
				var url = "DRBMinutes";
					$.ajax({
						method: "post",
						url:url,
						data:{id:id},
						success:function(data){
							window.location.href=url+"?token="+response+"&drb="+id;
						}
					})
			});
		});
	</script>';
		echo $this->output;
	}
	// --Search Ledger
	public function search_ledger_list($columname,$key,$date1,$date2){

		if ($key == 'date_search_ipi') {
			$condition = "WHERE ".$columname." BETWEEN '".$date1."' AND '".$date2."'";
		}
		elseif($key == 'issue_search_ipi'){
			$condition = "WHERE ".$columname." = ".$date1;
		}
		else{
			$condition = "WHERE lower(".$columname.") LIKE lower('%".$key."%')";
		}

		$sql = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger ".$condition." ORDER BY occur_date desc");

		$this->output .= '
		<table class="drblist table table-hover table-bordered table-responsive">
		<thead class="thead-dark">
		<tr>
		<th>Month</th>
		<th>WW#</th>
		<th>Occurrence Date</th>
		<th>DRB Date</th>
		<th>DRB Number</th>
		<th>DRB Issue</th>
		<th>RFC #</th>
		<th>Block</th>
		<th>Process Affected</th>
		<th>Product Affected</th>
		<th>5M1E</th>
		<th>Recurrence or New Issue</th>
		<th># of Affected Lots</th>
		<th># lotouts lots</th>
		<th>Rank</th>
		<th>Issue Status</th>
		<th>Close Validation Date plan</th>
		<th>Close validation Date actual</th>
		<th>Close Validation and Approval By</th>
		<th>Action</th>
		</tr>
		</thead>
		<tbody>';

		// Count search
		$count_search = pg_num_rows($sql);

		if ($count_search >= 1) {
			while ($row = pg_fetch_array($sql)) {
				$status = $row['drb_status'];
				$rank = $row['rank'];
				$date_plan = $row['closing_validation_plan_date'];
				if (empty($date_plan)) {
					$date_plan = "-";
				}
				else{
					$date_plan = date('F d, Y', strtotime($row['closing_validation_plan_date']));
				}
				if ($status == 1) {
					$stats = "Open";
					$date_actual = "-";
				}
				else{
					$stats ="Closed";
					$date_actual = date('F d, Y', strtotime($row['closing_validation_date']));
				}
				$get_approval = pg_query($this->con,"SELECT * FROM tbl_closing_approval WHERE rank = $rank");
				while ($get = pg_fetch_array($get_approval)) {
					$name_of_approval = $get['name_of_approval'];
				}
				$this->output .='<tr>
				<td>'.$row['occur_month'].'</td>
				<td>'.$row['work_week'].'</td>
				<td>'.date('F d, Y', strtotime($row['occur_date'])).'</td>
				<td>'.date('F d, Y', strtotime($row['drb_date'])).'</td>
				<td>'.strtoupper($row['drb_number']).'</td>
				<td>'.$row['drb_issue'].'</td>
				<td>'.$row['rfc_no'].'</td>
				<td>'.strtoupper($row['block']).'</td>
				<td>'.strtoupper($row['process']).'</td>
				<td>'.$row['product'].'</td>
				<td>'.$row['m5e1'].'</td>
				<td>'.$row['issue_type'].'</td>
				<td>'.$row['affected_lots'].'</td>
				<td>'.$row['lot_out'].'</td>
				<td>'.$rank.'</td>
				<td>'.$stats.'</td>
				<td>'.$date_plan.'</td>
				<td>'.$date_actual.'</td>
				<td>'.$name_of_approval.'</td>
				<td><a class="btn btn-primary text-white view_drb" data-id="'.$row['drb_number'].'"><span class="oi oi-eye"></span>View</a></td>
				</tr>';
			}
		}
		else{
			if ($key == 'date_search_ipi') {
				$this->output .= '<tr>
				<td colspan="20" class="text-center">No Data Found for date <strong>'.$date1.' - '.$date2.'</strong></td>
				</tr>';
			}
			elseif ($key == 'issue_search_ipi') {
				if ($columname == 'rank') {
					$this->output .= '<tr>
					<td colspan="20" class="text-center">No Data Found for Rank Level <strong>'.$date1.'</strong></td>
					</tr>';
				}
				else{
					if ($date1 == 0) {
						$status = 'Closed';
					}
					else{
						$status = 'Open';
					}
					$this->output .= '<tr>
					<td colspan="20" class="text-center">No Data Found for DRB Status <strong>'.$status.'</strong></td>
					</tr>';
				}
			}
			else {
				$this->output .= '<tr>
				<td colspan="20" class="text-center">No Data Found for keyword <strong>'.$key.'</strong></td>
				</tr>';
			}
		}
		$this->output .='
		</tbody>
		</table>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".view_drb").on("click",function() {
				var id = $(this).data("id");
				var url = "DRBMinutes";
					$.ajax({
						method: "post",
						url:url,
						data:{id:id},
						success:function(data){
							window.location.href=url+"?drb="+id;
						}
					})
			});
		});
	</script>
		';
		echo $this->output;
	}
	// Tracking List By Block/Section
	// List of ledger
	public function ledgerblock($block){
		$record_per_page = 10;
		$page = '';

		if (isset($_POST["page"])) {
			$page = $_POST["page"];
		}
		else {
			$page = 1;
		}
		$start_from = ($page - 1) * $record_per_page;
		$sql = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger WHERE block = '$block' ORDER BY occur_date DESC LIMIT $record_per_page OFFSET $start_from");

		$this->output .= '
		<table class="drblist table table-hover table-bordered table-responsive">
		<thead class="thead-dark">
		<tr>
		<th>Month</th>
		<th>WW#</th>
		<th>Occurrence Date</th>
		<th>DRB Date</th>
		<th>DRB Number</th>
		<th>DRB Issue</th>
		<th>RFC #</th>
		<th>Block</th>
		<th>Process Affected</th>
		<th>Product Affected</th>
		<th>5M1E</th>
		<th>Recurrence or New Issue</th>
		<th># of Affected Lots</th>
		<th># lotouts lots</th>
		<th>Rank</th>
		<th>Issue Status</th>
		<th>Close Validation Date plan</th>
		<th>Close validation Date actual</th>
		<th>Close Validation and Approval By</th>
		<th>Action</th>
		</tr>
		</thead>
		<tbody>';

		while ($row = pg_fetch_array($sql)) {
			$status = $row['drb_status'];
			$rank = $row['rank'];
			$date_plan = $row['closing_validation_plan_date'];
			if (empty($date_plan)) {
				$date_plan = "-";
			}
			else{
				$date_plan = date('F d, Y', strtotime($row['closing_validation_plan_date']));
			}
			if ($status == 1) {
				$stats = "Open";
				$date_actual = "-";
			}
			else{
				$stats ="Closed";
				$date_actual = date('F d, Y', strtotime($row['closing_validation_date']));
			}
			$get_approval = pg_query($this->con,"SELECT * FROM tbl_closing_approval WHERE rank = $rank");
			while ($get = pg_fetch_array($get_approval)) {
				$name_of_approval = $get['name_of_approval'];
			}
			$this->output .='<tr>
			<td>'.$row['occur_month'].'</td>
			<td>'.$row['work_week'].'</td>
			<td>'.date('F d, Y', strtotime($row['occur_date'])).'</td>
			<td>'.date('F d, Y', strtotime($row['drb_date'])).'</td>
			<td>'.strtoupper($row['drb_number']).'</td>
			<td>'.$row['drb_issue'].'</td>
			<td>'.$row['rfc_no'].'</td>
			<td>'.strtoupper($row['block']).'</td>
			<td>'.strtoupper($row['process']).'</td>
			<td>'.$row['product'].'</td>
			<td>'.$row['m5e1'].'</td>
			<td>'.$row['issue_type'].'</td>
			<td>'.$row['affected_lots'].'</td>
			<td>'.$row['lot_out'].'</td>
			<td>'.$rank.'</td>
			<td>'.$stats.'</td>
			<td>'.$date_plan.'</td>
			<td>'.$date_actual.'</td>
			<td>'.$name_of_approval.'</td>
			<td><a class="btn btn-primary text-white view_drb" data-id="'.$row['drb_number'].'"><span class="oi oi-eye"></span>View</a></td>
			</tr>';
		}
		$this->output .='
		</tbody>
		</table>
		<nav aria-label="Page navigation example">
		<ul class="pagination justify-content-center">';
		$pagi = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger WHERE block = '$block'");
		$total_records = pg_num_rows($pagi);
		$total_pages = ceil($total_records/$record_per_page);
		for ($i=1; $i <= $total_pages; $i++) { 
			$this->output .=
			'<li class="page-item"><a class="ledger_link page-link" id='.$i.'>'.$i.'</a></li>'; 
		}
		$this->output .= '</ul>
		</nav>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".view_drb").on("click",function() {
				var id = $(this).data("id");
				var url = "DRBMinutes";
					$.ajax({
						method: "post",
						url:url,
						data:{id:id},
						success:function(data){
							window.location.href=url+"?drb="+id;
						}
					})
			});
		});
	</script>';
		echo $this->output;
	}
	// --Search Ledger By Section
	public function search_ledger_section_list($key,$block){
		$sql = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger WHERE block = '$block' AND (lower(drb_number) LIKE lower('%$key%') OR lower(drb_issue) LIKE lower('%$key%'))");

		$this->output .= '
		<table class="drblist table table-hover table-bordered table-responsive">
		<thead class="thead-dark">
		<tr>
		<th>Month</th>
		<th>WW#</th>
		<th>Occurrence Date</th>
		<th>DRB Date</th>
		<th>DRB Number</th>
		<th>DRB Issue</th>
		<th>RFC #</th>
		<th>Block</th>
		<th>Process Affected</th>
		<th>Product Affected</th>
		<th>5M1E</th>
		<th>Recurrence or New Issue</th>
		<th># of Affected Lots</th>
		<th># lotouts lots</th>
		<th>Rank</th>
		<th>Issue Status</th>
		<th>Close Validation Date plan</th>
		<th>Close validation Date actual</th>
		<th>Close Validation and Approval By</th>
		<th>Action</th>
		</tr>
		</thead>
		<tbody>';

		while ($row = pg_fetch_array($sql)) {
			$status = $row['drb_status'];
			$rank = $row['rank'];
			$date_plan = $row['closing_validation_plan_date'];
			if (empty($date_plan)) {
				$date_plan = "-";
			}
			else{
				$date_plan = date('F d, Y', strtotime($row['closing_validation_plan_date']));
			}
			if ($status == 1) {
				$stats = "Open";
				$date_actual = "-";
			}
			else{
				$stats ="Closed";
				$date_actual = date('F d, Y', strtotime($row['closing_validation_date']));
			}
			$get_approval = pg_query($this->con,"SELECT * FROM tbl_closing_approval WHERE rank = $rank");
			while ($get = pg_fetch_array($get_approval)) {
				$name_of_approval = $get['name_of_approval'];
			}
			$this->output .='<tr>
			<td>'.$row['occur_month'].'</td>
			<td>'.$row['work_week'].'</td>
			<td>'.date('F d, Y', strtotime($row['occur_date'])).'</td>
			<td>'.date('F d, Y', strtotime($row['drb_date'])).'</td>
			<td>'.strtoupper($row['drb_number']).'</td>
			<td>'.$row['drb_issue'].'</td>
			<td>'.$row['rfc_no'].'</td>
			<td>'.strtoupper($row['block']).'</td>
			<td>'.strtoupper($row['process']).'</td>
			<td>'.$row['product'].'</td>
			<td>'.$row['m5e1'].'</td>
			<td>'.$row['issue_type'].'</td>
			<td>'.$row['affected_lots'].'</td>
			<td>'.$row['lot_out'].'</td>
			<td>'.$rank.'</td>
			<td>'.$stats.'</td>
			<td>'.$date_plan.'</td>
			<td>'.$date_actual.'</td>
			<td>'.$name_of_approval.'</td>
			<td><a class="btn btn-primary text-white view_drb" data-id="'.$row['drb_number'].'"><span class="oi oi-eye"></span>View</a></td>
			</tr>';
		}
		$this->output .='
		</tbody>
		</table>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".view_drb").on("click",function() {
				var id = $(this).data("id");
				var url = "DRBMinutes";
					$.ajax({
						method: "post",
						url:url,
						data:{id:id},
						success:function(data){
							window.location.href=url+"?drb="+id;
						}
					})
			});
		});
	</script>
		';
		echo $this->output;
	}
	// Add Tracking ledger
	public function add_tracking_ledger($occur_month,$work_week,$occur_date,$drb_date,$drb_number,$rfc_no,$drb_issue,$block,$process,$product,$m5e1,$issue_type,$lot_out,$sheet_out,$total_affected,$rank,$login,$timestamp,$machine_no){

		$check = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE drb_number = '$drb_number'");
		$count = pg_num_rows($check);
		$check2 = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE rfc_no = '$rfc_no'");
		$count2 = pg_num_rows($check2);

		$month_check = date('n',strtotime($drb_date));
		$year_check = date('Y',strtotime($drb_date));
		$get_last_series = pg_query($this->con,"SELECT * from tbl_drb_tracking_ledger where block = '$block' AND date_part('month',drb_date) = $month_check and date_part('year',drb_date) = $year_check order by last_series_no desc limit 1");
		$count_series = pg_num_rows($get_last_series);

		if ($count_series == 0) {
			$last_series_no = 1;
		}
		else{
			while ($get_series = pg_fetch_array($get_last_series)) {
				$last_series_no = $get_series['last_series_no'] + 1;
			}
		}

		if ($count == 0 AND $count2 == 0) {
			try {
				pg_query("BEGIN");
				$sql = pg_query($this->con,"INSERT INTO tbl_drb_tracking_ledger(occur_month,work_week,occur_date,drb_date,drb_number,rfc_no,drb_issue,block,process,product,m5e1,issue_type,affected_lots,sheet_out,rank,drb_status,created_by,created_at,meeting_status,last_series_no,machine_no,lot_out)
					VALUES('$occur_month','$work_week','$occur_date','$drb_date','$drb_number','$rfc_no','$drb_issue','$block','$process','$product','$m5e1','$issue_type','$total_affected','$sheet_out','$rank','1','$login','$timestamp', 0, $last_series_no,'$machine_no',$lot_out)");
				$exe = pg_query("COMMIT");
				if ($exe) {
					$sql2 = pg_query($this->con,"SELECT currval('tbl_drb_tracking_ledger_id_seq')");
					while ($row = pg_fetch_row($sql2)) {
						$id = $row[0];
						$this->add_drb_minute($id,$drb_number);
					}
					$this->get_affected_lots($rfc_no);
					$task = "Added DRB Number ".$drb_number;
					$this->SaveLogs($task);
					echo "success";
				}
			} catch (Exception $e) {
				pg_query("ROLLBACK");
				$task = "Execution Error System malfunction(Add Tracking Ledger)";
				$this->SaveLogs($task);
				echo "Error: ".$e->getMessage();
			}
		}
		elseif($count >= 1){
			echo "RegisteredDRB";
		}
		elseif($count2 >= 1){
			echo "RegisteredRFC";
		}
		else{
			echo "Both";
		}
		
	}
	// Add DRB minutes
	public function add_drb_minute($get_last_id,$drb_num){
		try {
			$sql = pg_query($this->con,"INSERT INTO tbl_drb_minutes(drb_number,drb_number_upload,id_drb)VALUES('$drb_num',0,$get_last_id)");
		} catch (Exception $e) {
			echo "Error: ".$e->getMessage();
		}
	}
	//Verify RFC
	public function verify_RFC($rfc)
	{

		$rfc = "'".$rfc."'";
		$query = 'SELECT DISTINCT(a.rfc_control_no)	From dblink('.$this->link_connection.'::text,'. "'" .'SELECT rfc_control_no FROM public."tblRFC_details"'. "'" .'::text) a(rfc_control_no varchar) WHERE a.rfc_control_no = '.$rfc;
		$link = pg_query($this->con, $query);
		$check = pg_num_rows($link);

		if ($check  == 1) {
			$query2 = 'SELECT *	From dblink('.$this->link_connection.'::text,'. "'" .'SELECT rfc_control_no, lot_no FROM public."tblRFC_details"'. "'" .'::text) a(rfc_control_no varchar, lot_no varchar) WHERE rfc_control_no = '.$rfc;
			$get_lot = pg_query($this->con, $query2);
			while ($set = pg_fetch_array($get_lot)) {
				$data[] = '<option class="lot_list">'.$set['lot_no'].'</option>';
			}
			$return["count"] = pg_num_rows($get_lot);
			$return["json"] = json_encode($return);
			$return["data"] = array('data' => $data);
			echo json_encode($return);
		}
		else{
			echo json_encode("invalid");
		}

	}
			//update RFC
	public function update_RFC($rfc)
	{
		$rfc_no = $rfc;
		$rfc = "'".$rfc."'";
		$query = 'SELECT a.rfc_control_no From dblink('.$this->link_connection.'::text,'. "'" .'SELECT rfc_control_no FROM public."tblRFC_details"'. "'" .'::text) a(rfc_control_no varchar) WHERE a.rfc_control_no = '.$rfc;
		$link = pg_query($this->con, $query);
		$check_update_olams = pg_num_rows($link);

		$query2 = pg_query($this->con,"SELECT * FROM tbl_drb_affected_lots where rfc_no = '$rfc_no'");
		$get_data = pg_fetch_row($query2);

		if ($check_update_olams == $get_data['0']) {
			$data['trigger'] = 'success';
			$data['time'] = '2500';
			$data['update'] = false;
			$data['message'] = 'No Updates';
			$data['desc'] = 'This RFC #<b>'.$rfc_no.'<b/> is up-to-date';
			// $data['json'] = json_encode($data);
			echo json_encode($data);
			$task = "Update RFC(No update found)";
			$this->SaveLogs($task);
		}
		else{
			try {
				pg_query("BEGIN");
				pg_query($this->con,"DELETE FROM tbl_drb_affected_lots WHERE rfc_no = '$rfc_no'");
				pg_query($this->con,"UPDATE tbl_drb_tracking_ledger SET affected_lots = '$check_update_olams' WHERE rfc_no = '$rfc_no'");

				$subquery = 'SELECT a.rfc_control_no, a.lot_no, a.product_name FROM dblink('.$this->link_connection.'::text,'. "'" .'SELECT a.rfc_control_no, a.lot_no, b.product_name FROM public."tblRFC_details" a RIGHT JOIN public."tblProduct" b ON a.product_name::Int = b.no'. "'" .'::text) a(rfc_control_no varchar, lot_no varchar, product_name varchar) WHERE rfc_control_no = '.$rfc;

				$mainquery = 'INSERT INTO tbl_drb_affected_lots(rfc_no,affected_lot,product_name)'.$subquery;

				pg_query($this->con,$mainquery);

				$success = pg_query("COMMIT");
				if ($success) {
					$data['trigger'] = 'success';
					$data['time'] = '2500';
					$data['update'] = true;
					$data['message'] = 'Found new affected lots';
					$data['desc'] = 'Successfully added the new affected lots';
					echo json_encode($data);
					$task = "Update RFC(Found new affected lot and save)";
					$this->SaveLogs($task);
				}
			} catch (Exception $e) {
				pg_query("ROLLBACK");
				$data['trigger'] = 'error';
				$data['time'] = '3500';
				$data['update'] = false;
				$data['message'] = 'Something went wrong';
				$data['desc'] = 'Please contact the IT';
				echo json_encode($data);
				$task = "Update RFC(Failed to update something went wrong)";
				$this->SaveLogs($task);
			}		
		}

	}
	public function get_affected_lots($rfc)
	{
		$rfc = "'".$rfc."'";
		$subquery = 'SELECT a.rfc_control_no, a.lot_no, a.product_name FROM dblink('.$this->link_connection.'::text,'. "'" .'SELECT a.rfc_control_no, a.lot_no, b.product_name FROM public."tblRFC_details" a RIGHT JOIN public."tblProduct" b ON a.product_name::Int = b.no'. "'" .'::text) a(rfc_control_no varchar, lot_no varchar, product_name varchar) WHERE rfc_control_no = '.$rfc;

		$mainquery = 'INSERT INTO tbl_drb_affected_lots(rfc_no,affected_lot,product_name)'.$subquery;
		try {
			pg_query($this->con,$mainquery);
		} catch (Exception $e) {
			echo "Error: ".$e->getMessage();
		}
	}
	// list affected lot on DRB Minutes
	public function list_affected_lot($rfc)
	{
		$query = pg_query($this->con,"SELECT DISTINCT rfc_no,affected_lot,ledger_no,product_name,affected_panel,risk,affected_no_panel FROM tbl_drb_affected_lots where rfc_no = '$rfc'");
		$check = pg_num_rows($query);
		if ($check >= 1) {
			while ($row = pg_fetch_array($query)) {
				echo '<tr>
				<td>'.$row['affected_lot'].'</td>
				<td>'.$row['product_name'].'</td>
				<td><a href="#" class="btn btn-info view_lots" data-func="Generate_lot_data_from_other_DB" data-toggle="modal" data-target="#View_lots" id="'.$row['affected_lot'].'">View Details</a></td>
				</tr>';
			}
		}
		else{
			echo '<tr>
					<td colspan="6" class="text-center">No Data Found</td>
				</tr>';
		}
	}
	// List all date of meeting and time
	public function list_of_meeting($drb_number)
	{
		$query = pg_query($this->con,"SELECT DISTINCT drb_num, drb_date, drb_start_time, drb_end_time, status, 
       drb_total_time, id_drb FROM tbl_drb_minutes_meeting_logs where drb_num = '$drb_number' order by drb_start_time DESC");
		$check = pg_num_rows($query);
		if ($check >= 1) {
			$i = 1;
			while ($row = pg_fetch_array($query)) {

				if (empty($row['drb_end_time'])) {
					$end = '<td>Meeting is On going</td>';
				}
				else{
					$end = '<td>'.date('h:i a',strtotime($row['drb_end_time'])).'</td>';
				}
				echo '<tr>
						<th>'.$i.'</th>
						<td>'.date('F d, Y',strtotime($row['drb_date'])).'</td>
						<td>'.date('h:i a',strtotime($row['drb_start_time'])).'</td>
						'.$end.'
						<td>'.$row['drb_total_time'].'</td>
					</tr>';
					$i++;
			}
		}
		else{
			echo "<tr>
					<td colspan='5' class='text-center'><b>They Didn't yet start the first meeting.</b></td>
				</tr>";
		}
	}
	// Start, End, Close Function
		// Start Meeting
	public function Start_meeting($id,$drb_number)
	{
		$date = date('Y-m-d');
		$time = date('Y-m-d H:i:s');
		try {
			$start_meeting = pg_query($this->con,"UPDATE tbl_drb_tracking_ledger SET meeting_status = 1 WHERE drb_number = '$drb_number'");
			if ($start_meeting) {
				$record_time = pg_query($this->con,"INSERT INTO tbl_drb_minutes_meeting_logs(drb_num,drb_date,drb_start_time,status, id_drb) VALUES('$drb_number','$date','$time',1,$id)");
				$task = "Start The DRB Meeting (".$drb_number.")";
				$this->SaveLogs($task);
				echo "success";
			}
		} catch (Exception $e) {
			$task = "Execution Error System malfunction(Start Meeting)";
			$this->SaveLogs($task);
			echo "Error: ".$e->getMessage();
		}
	}
		// end meeting
	public function End_meeting($drb_number)
	{
		$endtime = date('Y-m-d H:i:s');
		try {
			$end_meeting = pg_query($this->con,"UPDATE tbl_drb_tracking_ledger SET meeting_status = 0 WHERE drb_number = '$drb_number'");
			if ($end_meeting) {
				$get_time = pg_query($this->con,"SELECT * FROM tbl_drb_minutes_meeting_logs where drb_num = '$drb_number' and status = 1");

				while ($row = pg_fetch_array($get_time)) {
					$starttime = $row['drb_start_time'];

					$total = (strtotime($endtime) - strtotime($starttime))/60;
					$total = round($total, 2)." Minutes";
					$record_time = pg_query($this->con,"UPDATE tbl_drb_minutes_meeting_logs set drb_end_time = '$endtime',drb_total_time = '$total' ,status = 0 where drb_num = '$drb_number' and status = 1");
					$task = "End the DRB Meeting(".$drb_number.")";
					$this->SaveLogs($task);
				echo "success";
				}
			}
		} catch (Exception $e) {
			$task = "Execution Error System malfunction(End Meeting)";
			$this->SaveLogs($task);
			echo "Error: ".$e->getMessage();
		}
	}
		// Close issue
	public function Close_Issue($drb_number)
	{
		try {
			$date = date("Y-m-d");
			$close_issue = pg_query($this->con,"UPDATE tbl_drb_tracking_ledger SET drb_status = 0, closing_validation_date = '$date' WHERE drb_number = '$drb_number'");
			if ($close_issue) {
				$task = "Close the DRB Issue (".$drb_number.")";
				$this->SaveLogs($task);
				echo "success";
			}
		} catch (Exception $e) {
			$task = "Execution Error System malfunction(Close Issue)";
			$this->SaveLogs($task);
			echo "Error: ".$e->getMessage();
		}
	}
			// reopen issue
	public function reopen_Issue($drb_number)
	{
		try {
			$null = 'NULL';
			$close_issue = pg_query($this->con,"UPDATE tbl_drb_tracking_ledger SET closing_validation_date = $null, drb_status = 1 WHERE drb_number = '$drb_number'");
			if ($close_issue) {
				$task = "Reopen the issue (".$drb_number.")";
				$this->SaveLogs($task);
				echo "success";
			}
		} catch (Exception $e) {
			$task = "Execution Error System malfunction(Reopen Issue)";
			$this->SaveLogs($task);
			echo "Error: ".$e->getMessage();
		}
	}
		// Upload file
	public function file_upload($drb_number,$file_path)
	{
		$check_data = pg_query($this->con,"SELECT * FROM tbl_drb_minutes where drb_number = '$drb_number'");
		$datetime = date('Y-m-d H:i:s');
		while ($row = pg_fetch_array($check_data)) {
			$no_upload = $row['drb_number_upload'] + 1;
			$first = $row['drb_first_upload'];
			$task = "Upload DRB Minutes File File name (".$file_path.") for DRB #(".$drb_number.")";
			$file_name = $file_path;
			$file_path = "/assets/upload/".$file_path;

			if (empty($first)) {
				$save = pg_query($this->con,"UPDATE tbl_drb_minutes set drb_first_upload = '$datetime', drb_path = '$file_path', drb_number_upload = '$no_upload',drb_file_name = '$file_name' where drb_number = '$drb_number'");
				if ($save) {
					$this->SaveLogs($task);
					$this->result = "success";
				}
			}
			else{
				unlink("..".$row['drb_path']);
				$save = pg_query($this->con,"UPDATE tbl_drb_minutes set drb_last_upload = '$datetime', drb_path = '$file_path', drb_number_upload = '$no_upload', drb_file_name = '$file_name' where drb_number = '$drb_number'");
				if ($save) {
					$this->SaveLogs($task);
					$this->result = "success";
				}
			}


		}
	}
	// Read Data
	public function read($drb){
		$drb = strtolower($drb);

		$validateDRB = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE lower(drb_number) = '$drb'");
		$check = pg_num_rows($validateDRB);

		if($check == 1){
			try {
				$sql = pg_query($this->con,"SELECT a.*, b.name_of_approval,c.drb_path,c.drb_number_upload,c.drb_first_upload,c.drb_last_upload FROM tbl_drb_tracking_ledger a LEFT JOIN tbl_closing_approval b ON a.rank = b.rank LEFT JOIN tbl_drb_minutes c on a.drb_number = c.drb_number WHERE upper(a.drb_number) = upper('$drb')");
				while ($read = pg_fetch_array($sql)) {
					$this->id = $read['id'];
					$this->occur_month = $read['occur_month'];
					$this->work_week = $read['work_week'];
					$this->occur_date = $read['occur_date'];
					$this->drb_date = $read['drb_date'];
					$this->drb_number = $read['drb_number'];
					$this->rfc_no = $read['rfc_no'];
					$this->drb_issue = $read['drb_issue'];
					$this->block = $read['block'];
					$this->process = $read['process'];
					$this->product = $read['product'];
					$this->issue_type = $read['issue_type'];
					$this->m5e1 = $read['m5e1'];
					$this->affected_lot  = $read['affected_lots'];
					$this->lot_out  = $read['lot_out'];
					$this->sheet_out = $read['sheet_out'];
					$this->rank = $read['rank'];
					$this->closing_validation_plan_date = $read['closing_validation_plan_date'];
					$this->closing_validation_date = $read['closing_validation_date'];
					$this->meeting_status = $read['meeting_status'];
					$this->machine_no = $read['machine_no'];
					$this->name_of_approval = $read['name_of_approval'];
					$this->drb_path = $read['drb_path'];
					$this->drb_number_upload = $read['drb_number_upload'];
					$this->drb_first_upload = $read['drb_first_upload'];
					$this->drb_last_upload = $read['drb_last_upload'];

					if ($read['drb_status'] == 1) {
						$this->drb_status = "Open";
					}
					else{
						$this->drb_status = "Closed";
					}
				}
			} catch (Exception $e) {
				echo "Error: ".$e->getMessage();

			}
		}
		else{
			header("Location: DRBTracking");
		}

	}

	// Update tracking Ledger Module
	public function update_ledger($drb_number,$drb_number_previous,$drb_issue,$drb_date,$month,$ww,$occur_date,$block,$process,$product,$m5e1,$issue_type,$lot_out,$sheet_out,$rank,$closing_validation_plan_date,$machine_no)
	{
		if (empty($closing_validation_plan_date)) {
			$closing_validation_plan_date = 'NULL';
		}
		else{
			$closing_validation_plan_date = "'".$closing_validation_plan_date."'";
		}
		if ($drb_number == $drb_number_previous) {
			try {
				pg_query("BEGIN");
				pg_query($this->con,"UPDATE tbl_drb_tracking_ledger set occur_month = '$month', work_week = '$ww', occur_date = '$occur_date',drb_issue  = '$drb_issue',block = '$block',process = '$process',product = '$product',issue_type = '$issue_type',m5e1 = '$m5e1',lot_out  = $lot_out,sheet_out  = '$sheet_out',rank = '$rank',closing_validation_plan_date = $closing_validation_plan_date, machine_no = '$machine_no' WHERE drb_number = '$drb_number_previous' ");
				$update = pg_query("COMMIT");
				if ($update) {
					$task = "Update the Tracking ledger before(".$drb_number_previous.") after(".$drb_number.")";
					$this->SaveLogs($task);
					echo "success";
				}
			} catch (Exception $e) {
				pg_query("ROLLBACK");
				$task = "Execution Error System malfunction(Update Ledger)";
				$this->SaveLogs($task);
				echo "Error: ".$e->getMessage();
			}
		}
		else{
			try {
				$month = date('n',strtotime($drb_date));
				$year = date('Y',strtotime($drb_date));
				$get_last_series = pg_query($this->con,"SELECT * from tbl_drb_tracking_ledger where block = '$block' AND date_part('month',drb_date) = $month and date_part('year',drb_date) = $year order by last_series_no desc limit 1");

				$count = pg_num_rows($get_last_series);
				if ($count == 0) {
					$last_series_no = 1;
				}
				else{
					while ($generate = pg_fetch_array($get_last_series)) {
						$last_series_no = $generate['last_series_no'] + 1;
					}	
				}
				pg_query("BEGIN");
				$update = pg_query($this->con,"UPDATE tbl_drb_tracking_ledger set drb_number = '$drb_number',occur_month = '$month', work_week = '$ww', occur_date = '$occur_date', drb_issue  = '$drb_issue', block = '$block',process = '$process',product = '$product', issue_type = '$issue_type',m5e1 = '$m5e1',lot_out  = '$lot_out',sheet_out  = '$sheet_out',rank = '$rank',closing_validation_plan_date = $closing_validation_plan_date, last_series_no = '$last_series_no', machine_no = '$machine_no' WHERE drb_number = '$drb_number_previous' ");
				if ($update) {
					$this->update_drb_minutes($drb_number, $drb_number_previous);
					$this->update_drb_minutes_logs($drb_number, $drb_number_previous);
					$this->update_customer_dashboard_list($drb_number, $drb_number_previous);
					$exe = pg_query("COMMIT");
					if ($exe) {
						$task = "Update the Tracking ledger before(".$drb_number_previous.") after(".$drb_number.")";
						$this->SaveLogs($task);
						echo "success";
					}
				}
			} catch (Exception $e) {
				pg_query("ROLLBACK");
				$task = "Execution Error System malfunction(Update Ledger)";
				$this->SaveLogs($task);
				echo "Error: ".$e->getMessage();
			}
		}
	}
	public function update_drb_minutes($drb_number, $drb_number_previous)
	{
		try {
			$update = pg_query($this->con,"UPDATE tbl_drb_minutes set drb_number = '$drb_number' where drb_number = '$drb_number_previous'");
		} catch (Exception $e) {
			echo "Error: ".$e->getMessage();
		}
	}
	public function update_drb_minutes_logs($drb_number, $drb_number_previous)
	{
		try {
			$update = pg_query($this->con,"UPDATE tbl_drb_minutes_meeting_logs set drb_num = '$drb_number' where drb_num = '$drb_number_previous'");
		} catch (Exception $e) {
			echo "Error: ".$e->getMessage();
		}
	}
	public function update_customer_dashboard_list($drb_number, $drb_number_previous)
	{
		try {
			$update = pg_query($this->con,"UPDATE tbl_customer_dashboard_list set drb_number = '$drb_number' where drb_number = '$drb_number_previous'");
		} catch (Exception $e) {
			echo "Error: ".$e->getMessage();
		}
	}




	// View Affected Lots
	public function Generate_View_Lots($lot_no)
	{
		$this->output .= '
		<h3>IPP Data Report</h3>
		<div class="row">
		<div class="offset-md-8 col-md-4 text-right">
		<input type="hidden" id="lot" data-lot="'.$lot_no.'">
		<select id="ptrn_code" name="ptrn_code" class="form-control">
		<option selected disabled>Please Select Pattern Code</option> 
		<option value="FZ1CZT">FZ1CZT</option>
		<option value="FZ1RGH">FZ1RGH</option>
		<option value="FZ1LPS">FZ1LPS</option>
		<option value="FZ1COC">FZ1COC</option>
		</select>		
		</div>
		</div>
		<div class="row">
		<div class="col-md-12 text-center" id="default_row_ipp">
		<h6>Please select Pattern Code to generate the Data</h6>
		</div>
		<div class="col-md-12" id="show_details_ipp" style="max-height:350px;"></div>
		</div>
		

		<h3>Consolidated Shipment Report</h3>
		<table class="table table-hover table-bordered">
		<thead class="thead-custom">
		<tr>
		<th>Lot No.</th>
		<th>SLI No.</th>
		<th>Shipment Date</th>
		<th>Shipment Site</th>
		</tr>
		</thead>
		<tbody>';
		$SLI = sqlsrv_query($this->shipment,"SELECT CONVERT(varchar,b.LOT_NO) as LOT_NO, CONVERT(varchar,a.SLI_No) as SLI_No, CONVERT(varchar, a.SDate) as ship_date, CONVERT(varchar,a.Consignee) as Consignee from [dbo].[SHIP_DETAILS] a right join FROM_PACKING b on a.SLI_No COLLATE SQL_Latin1_General_CP1_CI_AS = b.SLI_NUMBER WHERE b.LOT_NO = '$lot_no'");
		$data_count_SLI = sqlsrv_has_rows($SLI);
		if ($data_count_SLI == 1) {
			while ($row = sqlsrv_fetch_array($SLI)) {
				if (empty($row['ship_date'])) {
					$shipdate = "";
				}
				else{
					$shipdate = date('l m/d/Y h:i:s a',strtotime($row['ship_date']));
				}
				$this->output .='
				<tr>
				<td>'.$row['LOT_NO'].'</td>
				<td>'.$row['SLI_No'].'</td>
				<td>'.$shipdate.'</td>
				<td>'.$row['Consignee'].'</td>
				</tr>
				';
			}							
		}
		else{
			$this->output .='
			<tr><td class="text-center" colspan="4">No Shipment Data Found</td></tr>
			';
		}
		$this->output .= '
		</tbody>
		</table>';
		echo $this->output;
	}
	// Generate IPP Data Report
	public function generate_ipp($ptrn_code,$lot_no)
	{
		$this->output .='
		<div class="">
		<table id="dtVerticalScrollExample" class="table table-hover table-bordered fixed-table table-responsive" style="overflow:auto;max-height:350px;">
		<thead class="thead-custom">
		<tr>
		<th class="th-sm">Measure Time</th>
		<th class="th-sm">Measure Logic Date</th>
		<th class="th-sm">Process time by product article</th>
		<th class="th-sm">Measure 10 Digit code</th>
		<th class="th-sm">Measure 10 Digit Name</th>
		<th class="th-sm">Measure History SEQ</th>
		<th class="th-sm">Measure Target</th>
		<th class="th-sm">Upper Spec Value</th>
		<th class="th-sm">Lower Spec Value</th>
		<th class="th-sm">Average</th>
		<th class="th-sm">UCL</th>
		<th class="th-sm">LCL</th>';

		$prepare_query = pg_query($this->con_prod,"SELECT mnfc_lot_no,mesure_10_digit_cd,msr_dt,msr_logic_dt,prod_artcl_item_opr_num,count(mesure_10_digit_cd) as code_digit FROM trn_qua_lot_mesure_msr WHERE mnfc_lot_no = '$lot_no' AND opr_ptrn_cd = '$ptrn_code' AND msr_value_numc IS NOT NULL Group by mesure_10_digit_cd,mnfc_lot_no,msr_dt,msr_logic_dt,prod_artcl_item_opr_num ORDER BY code_digit DESC limit 1");
		$get_single_data = pg_fetch_row($prepare_query);
		$num_column = $get_single_data['5'];
		$i = 1;
		while ($i <= $num_column) {
			$this->output .= '<th class="th-sm">Sample '.$i.'</th>';
			$i++;
		}

		$this->output .='</tr>
		</thead>
		<tbody>';
		$validate = pg_num_rows($prepare_query);
		if ($validate == 1) {
			$get_dtl= pg_query($this->con_prod,"SELECT a.mnfc_lot_no,a.mesure_10_digit_cd,a.prod_artcl_item_opr_num, a.msr_hist_sq,a.msr_dt,a.msr_logic_dt,b.mesure_10_digit_cd_nm  FROM (
						SELECT mnfc_lot_no,mesure_10_digit_cd,msr_hist_sq,msr_hist_dt as msr_dt, msr_hist_logic_dt as msr_logic_dt, prod_artcl_item_opr_num from trn_qua_lot_mesure_msr_hist where msr_hist_dt IS NOT NULL
						UNION ALL
						select mnfc_lot_no,mesure_10_digit_cd, null as msr_hist_sq, msr_dt, msr_logic_dt, prod_artcl_item_opr_num from trn_qua_lot_mesure_msr where msr_value_numc IS NOT NULL
					) 
			a LEFT JOIN mst_qua_mesure_10_digit b ON a.mesure_10_digit_cd = b.mesure_10_digit_cd
			WHERE a.mnfc_lot_no = '$lot_no' AND opr_ptrn_cd = '$ptrn_code' 
			-- and b.cntrl_group_cd != '000000'
			-- and b.proc_cd IS NULL
			AND lower(b.mesure_10_digit_cd_nm) NOT LIKE lower('condition%')
			Group by a.mesure_10_digit_cd, a.mnfc_lot_no,a.msr_hist_sq, a.msr_dt, a.msr_logic_dt, a.prod_artcl_item_opr_num, b.mesure_10_digit_cd_nm 
			ORDER BY  a.prod_artcl_item_opr_num ASC, a.mesure_10_digit_cd DESC,a.msr_dt DESC");

			while ($row = pg_fetch_array($get_dtl)) {
				$measureCode = $row["mesure_10_digit_cd"];
				$msr_dt = $row["msr_dt"];
				$apply_start_date = date('Y',strtotime($msr_dt)).date('m',strtotime($msr_dt)).'01';
				$this->output .= '
				<tr>
				<td>'.$row["msr_dt"].'</td>
				<td>'.$row["msr_logic_dt"].'</td>
				<td>'.$row["prod_artcl_item_opr_num"].'</td>
				<td>'.$row["mesure_10_digit_cd"].'</td>
				<td>'.$row["mesure_10_digit_cd_nm"].'</td>
				<td>'.$row['msr_hist_sq'].'</td>';

				$get_specs_val1 = pg_query($this->con_prod,"SELECT DISTINCT target_value,upper_spec_value,lower_spec_value,avg,cntrl_limit_stdev_value_upper,cntrl_limit_stdev_value_lower FROM mst_qua_cntrl_limit WHERE qua_10_digit_cd = '$measureCode' and apply_start_dt = '$apply_start_date' and (cntrl_limit_kbn = '1')");
				if (pg_num_rows($get_specs_val1) == 0) {
					$get_specs_val_0 = pg_query($this->con_prod,"SELECT DISTINCT target_value,upper_spec_value,lower_spec_value,avg,cntrl_limit_stdev_value_upper,cntrl_limit_stdev_value_lower FROM mst_qua_cntrl_limit WHERE qua_10_digit_cd = '$measureCode' and apply_start_dt = '$apply_start_date' and (cntrl_limit_kbn = '0')");
					if (pg_num_rows($get_specs_val_0) == 0) {
						$get_specs_val_3 = pg_query($this->con_prod,"SELECT DISTINCT target_value,upper_spec_value,lower_spec_value,avg,cntrl_limit_stdev_value_upper,cntrl_limit_stdev_value_lower FROM mst_qua_cntrl_limit WHERE qua_10_digit_cd = '$measureCode' and apply_start_dt = '$apply_start_date' and (cntrl_limit_kbn = '3')");
						if (pg_num_rows($get_specs_val_3) == 0) {
							$get_specs_val_4 = pg_query($this->con_prod,"SELECT DISTINCT target_value,upper_spec_value,lower_spec_value,avg,cntrl_limit_stdev_value_upper,cntrl_limit_stdev_value_lower FROM mst_qua_cntrl_limit WHERE qua_10_digit_cd = '$measureCode' and apply_start_dt = '$apply_start_date' and (cntrl_limit_kbn = '2')");
							if (pg_num_rows($get_specs_val_4) == 0) {
								$upper_specs = '';
								$lower_specs = '';
								$this->output .='
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>';
							}
							else{
								$specs4 = pg_query($this->con_prod,"SELECT DISTINCT target_value,upper_spec_value,lower_spec_value,avg,cntrl_limit_stdev_value_upper,cntrl_limit_stdev_value_lower FROM mst_qua_cntrl_limit WHERE qua_10_digit_cd = '$measureCode' and apply_start_dt = '$apply_start_date' and (cntrl_limit_kbn = '2')");
								$get_specs = pg_fetch_row($specs4);
								$upper_specs = $get_specs['1'];
								$lower_specs = $get_specs['2'];
								while ($dis_specs = pg_fetch_array($get_specs_val_4)) {
									$ucl = $dis_specs["cntrl_limit_stdev_value_upper"];
									$lcl = $dis_specs["cntrl_limit_stdev_value_lower"];
									$avg_avg = $dis_specs["avg"];
									if ($avg_avg > $ucl OR $avg_avg < $lcl) {
										$class = '"bg-danger"';
									}
									else{
										$class = '""';
									}
									$this->output .='
									<td>'.$dis_specs["target_value"].'</td>
									<td>'.$dis_specs["upper_spec_value"].'</td>
									<td>'.$dis_specs["lower_spec_value"].'</td>
									<td class='.$class.'>'.$dis_specs["avg"].'</td>
									<td>'.$dis_specs["cntrl_limit_stdev_value_upper"].'</td>
									<td>'.$dis_specs["cntrl_limit_stdev_value_lower"].'</td>';
								}
							}
						}
						else{
							$specs3 = pg_query($this->con_prod,"SELECT DISTINCT target_value,upper_spec_value,lower_spec_value,avg,cntrl_limit_stdev_value_upper,cntrl_limit_stdev_value_lower FROM mst_qua_cntrl_limit WHERE qua_10_digit_cd = '$measureCode' and apply_start_dt = '$apply_start_date' and (cntrl_limit_kbn = '3')");
							$get_specs = pg_fetch_row($specs3);
							$upper_specs = $get_specs['1'];
							$lower_specs = $get_specs['2'];
							while ($dis_specs = pg_fetch_array($get_specs_val_3)) {
								$ucl = $dis_specs["cntrl_limit_stdev_value_upper"];
								$lcl = $dis_specs["cntrl_limit_stdev_value_lower"];
								$avg_avg = $dis_specs["avg"];
								if ($avg_avg > $ucl OR $avg_avg < $lcl) {
									$class = '"bg-danger"';
								}
								else{
									$class = '""';
								}
								$this->output .='
								<td>'.$dis_specs["target_value"].'</td>
								<td>'.$dis_specs["upper_spec_value"].'</td>
								<td>'.$dis_specs["lower_spec_value"].'</td>
								<td class='.$class.'>'.$dis_specs["avg"].'</td>
								<td>'.$dis_specs["cntrl_limit_stdev_value_upper"].'</td>
								<td>'.$dis_specs["cntrl_limit_stdev_value_lower"].'</td>';
							}
						}
					}
					else{
						$specs2 = pg_query($this->con_prod,"SELECT DISTINCT target_value,upper_spec_value,lower_spec_value,avg,cntrl_limit_stdev_value_upper,cntrl_limit_stdev_value_lower FROM mst_qua_cntrl_limit WHERE qua_10_digit_cd = '$measureCode' and apply_start_dt = '$apply_start_date' and (cntrl_limit_kbn = '0')");
						$get_specs = pg_fetch_row($specs2);
						$upper_specs = $get_specs['1'];
						$lower_specs = $get_specs['2'];
						while ($dis_specs = pg_fetch_array($get_specs_val_0)) {
							$ucl = $dis_specs["cntrl_limit_stdev_value_upper"];
							$lcl = $dis_specs["cntrl_limit_stdev_value_lower"];
							$avg_avg = $dis_specs["avg"];
							if ($avg_avg > $ucl OR $avg_avg < $lcl) {
								$class = '"bg-danger"';
							}
							else{
								$class = '""';
							}
							$this->output .='
							<td>'.$dis_specs["target_value"].'</td>
							<td>'.$dis_specs["upper_spec_value"].'</td>
							<td>'.$dis_specs["lower_spec_value"].'</td>
							<td class='.$class.'>'.$dis_specs["avg"].'</td>
							<td>'.$dis_specs["cntrl_limit_stdev_value_upper"].'</td>
							<td>'.$dis_specs["cntrl_limit_stdev_value_lower"].'</td>';
						}
					}
					
				}
				else{
					$specs1 = pg_query($this->con_prod,"SELECT DISTINCT target_value,upper_spec_value,lower_spec_value,avg,cntrl_limit_stdev_value_upper,cntrl_limit_stdev_value_lower FROM mst_qua_cntrl_limit WHERE qua_10_digit_cd = '$measureCode' and apply_start_dt = '$apply_start_date' and (cntrl_limit_kbn = '1')");
					$get_specs = pg_fetch_row($specs1);
					$upper_specs = $get_specs['1'];
					$lower_specs = $get_specs['2'];
					while ($dis_specs = pg_fetch_array($get_specs_val1)) {
						$ucl = $dis_specs["cntrl_limit_stdev_value_upper"];
						$lcl = $dis_specs["cntrl_limit_stdev_value_lower"];
						$avg_avg = $dis_specs["avg"];
						if (!empty($ucl) AND !empty($lcl)) {
							if ($avg_avg > $ucl OR $avg_avg < $lcl) {
								$class = '"bg-danger"';
							}
							else{
								$class = '';
							}
						}
						elseif ($avg_avg > $ucl AND !empty($ucl)) {
							$class = '"bg-danger"';
						}
						elseif ($avg_avg < $lcl AND !empty($lcl)) {
							$class = '"bg-danger"';
						}
						else{
							$class = '';
						}
						$this->output .='
						<td>'.$dis_specs["target_value"].'</td>
						<td>'.$dis_specs["upper_spec_value"].'</td>
						<td>'.$dis_specs["lower_spec_value"].'</td>
						<td class='.$class.'>'.$dis_specs["avg"].'</td>
						<td>'.$dis_specs["cntrl_limit_stdev_value_upper"].'</td>
						<td>'.$dis_specs["cntrl_limit_stdev_value_lower"].'</td>';
					}
				}
				

				$get_sample = pg_query($this->con_prod,"SELECT msr_value_numc FROM 
						(
						SELECT mesure_10_digit_cd,mnfc_lot_no,msr_hist_value_numc AS msr_value_numc, msr_hist_dt AS msr_dt,msr_n_qty FROM trn_qua_lot_mesure_msr_hist
						UNION ALL
						SELECT mesure_10_digit_cd,mnfc_lot_no,msr_value_numc, msr_dt,msr_n_qty FROM trn_qua_lot_mesure_msr
						) a 
				WHERE a.mesure_10_digit_cd = '$measureCode' AND a.mnfc_lot_no = '$lot_no'
				AND a.msr_dt = '$msr_dt' ORDER BY a.msr_n_qty ASC");

				while ($sample_data = pg_fetch_array($get_sample)) {
					$data_sample = $sample_data['msr_value_numc'];
					if (!empty($upper_specs) AND !empty($lower_specs)) {
						if ($data_sample > $upper_specs OR $data_sample < $lower_specs) {
							$class = '" bg-danger "';
						}
						else{
							$class = '""';
						}
					}
					elseif (!empty($upper_specs)) {
						if ($data_sample > $upper_specs) {
							$class = '" bg-danger "';
						}
						else{
							$class = '""';
						}
					}
					elseif (!empty($lower_specs)) {
						if ($data_sample < $lower_specs) {
							$class = '" bg-danger "';
						}
						else{
							$class = '""';
						}
					}
					else{
						$class='""';
					}
					$this->output .= '<td class='.$class.'>'.$sample_data['msr_value_numc'].'</td>';
				}
				$this->output .= '
				<tr>';
			}
		}
		else{
			$this->output .='<tr>
			<td colspan="10" class="text-center">No Data Found for pattern code <b>'.$ptrn_code.'</b></td>
			</tr>';
		}
		
		$this->output .= "
		</tbody>
		</table>
		</div>
		";

		echo $this->output;
	}
// Archive and Retrive Funtion
	public function archive_data($id,$drb){
		$query = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger where id = $id");

		while ($rows = pg_fetch_array($query)) {
			$rfc = $rows['rfc_no'];
			$drb_date = $rows['drb_date'];
			$block = $rows['block'];
			$last_series_no = $rows['last_series_no'];

			// Get date
			$month = date("m", strtotime($drb_date));
			$year = date("Y", strtotime($drb_date));
			$start = $month."/1/".$year;
			$end = date("m/t/Y",strtotime($start));
			$start = str_replace("/", "-", $start);
			$end = str_replace("/", "-", $end);
			try {
				pg_query("BEGIN");

				$get_data_query = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE last_series_no > $last_series_no AND block = '$block' AND drb_date BETWEEN '$start' AND '$end' order by last_series_no asc");
				$check_series = pg_num_rows($get_data_query);

				pg_query($this->con,"INSERT INTO tbl_drb_affected_lots_delete(drb_number, rfc_no, affected_lot, ledger_no, product_name, affected_panel, risk, affected_no_panel) SELECT drb_number, rfc_no, affected_lot, ledger_no, product_name, affected_panel, risk, affected_no_panel FROM tbl_drb_affected_lots WHERE rfc_no = '$rfc'");

				pg_query($this->con,"INSERT INTO tbl_drb_minutes_delete(drb_number,drb_path,drb_number_upload,drb_first_upload,drb_last_upload,drb_file_name,id_drb) SELECT drb_number,drb_path,drb_number_upload,drb_first_upload,drb_last_upload,drb_file_name,id_drb FROM tbl_drb_minutes WHERE id_drb = $id");

				pg_query($this->con,"INSERT INTO tbl_drb_minutes_meeting_logs_delete(drb_num,drb_date,drb_start_time,drb_end_time,status,drb_total_time,id_drb) SELECT drb_num,drb_date,drb_start_time,drb_end_time,status,drb_total_time,id_drb FROM tbl_drb_minutes_meeting_logs WHERE id_drb = $id");

				pg_query($this->con,"INSERT INTO tbl_drb_tracking_ledger_delete(id,occur_month,work_week,occur_date,drb_date,drb_number,rfc_no,drb_issue,block,process,product,issue_type,m5e1,affected_lots,sheet_out,rank,drb_status,closing_validation_plan_date,closing_validation_date,created_by,created_at,meeting_status,last_series_no,machine_no,lot_out) SELECT id,occur_month,work_week,occur_date,drb_date,drb_number,rfc_no,drb_issue,block,process,product,issue_type,m5e1,affected_lots,sheet_out,rank,drb_status,closing_validation_plan_date,closing_validation_date,created_by,created_at,meeting_status,last_series_no,machine_no,lot_out FROM tbl_drb_tracking_ledger WHERE id = $id");


				// Transfer File
				$archive_file = pg_query($this->con,"SELECT * FROM tbl_drb_minutes WHERE id_drb = $id");
				while ($get_file = pg_fetch_array($archive_file)) {
					if (!empty($get_file['drb_path'])) {
						$path = str_replace("/assets", "../assets", $get_file['drb_path']);
						$new_path = "../assets/archive_files/archive-".$get_file['drb_file_name'];
						$new_file_name = "archive-".$get_file['drb_file_name'];
						rename($path, $new_path);
						$drb_path = str_replace("../assets", "/assets", $new_path);
						pg_query($this->con,"UPDATE tbl_drb_minutes_delete SET drb_path = '$drb_path', drb_file_name = '$new_file_name' WHERE id_drb = $id");
					}
				}

				pg_query($this->con,"DELETE FROM tbl_drb_affected_lots WHERE rfc_no = '$rfc'");
				pg_query($this->con,"DELETE FROM tbl_drb_minutes WHERE id_drb = $id");
				pg_query($this->con,"DELETE FROM tbl_drb_minutes_meeting_logs WHERE id_drb = $id");
				pg_query($this->con,"DELETE FROM tbl_drb_tracking_ledger WHERE id = $id");
				pg_query($this->con,"DELETE FROM tbl_customer_dashboard_list WHERE drb_number = '$drb'");

				// Auto Update DRB by block and series
				if ($check_series > 0) {
					while ($get = pg_fetch_array($get_data_query)) {
						$get_current_drb_date = $get['drb_date'];
						$updated_series = $get['last_series_no'] - 1;
						$current_drb = $get['drb_number'];
						$new_drb = "D-".strtoupper($block)."-".date('y',strtotime($get_current_drb_date))."-".date('m',strtotime($get_current_drb_date))."-".str_pad($updated_series, 3, 0, STR_PAD_LEFT);

						pg_query($this->con,"UPDATE tbl_drb_tracking_ledger SET drb_number = '$new_drb',last_series_no = $updated_series WHERE drb_number = '$current_drb'");
						$this->update_drb_minutes($new_drb, $current_drb);
						$this->update_drb_minutes_logs($new_drb, $current_drb);
						$this->update_customer_dashboard_list($new_drb, $current_drb);
					}
				}
				$exe = pg_query("COMMIT");
				if ($exe) {
					$task = "Archive Data DRB Number(".$drb.")";
					$this->SaveLogs($task);
					echo "success";
				}
			} catch (Exception $e) {
				pg_query("Rollback");
				$task = "Execution Error System malfunction(Archive Data)";
				$this->SaveLogs($task);
				echo "Error: ".$e->getMessage();
				
			}
		}
	}
public function retrieve_data($id,$new_drb){
		$query = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger_delete where id = $id");

		while ($rows = pg_fetch_array($query)) {
			$rfc = $rows['rfc_no'];
			$drb_date = $rows['drb_date'];
			$block = $rows['block'];

			// Get date
			$month = date("m", strtotime($drb_date));
			$year = date("Y", strtotime($drb_date));
			$start = $month."/1/".$year;
			$end = date("m/t/Y",strtotime($start));
			$start = str_replace("/", "-", $start);
			$end = str_replace("/", "-", $end);
			try {
				pg_query("BEGIN");
				$get = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE block = '$block' AND drb_date BETWEEN '$start' AND '$end' order by last_series_no desc limit 1");
				$check_data = pg_num_rows($get);
				if ($check_data == 1) {
					$get_single_data = pg_fetch_row($get);
					$last_series_no = $get_single_data['22']+1;
				}
				else{
					$last_series_no = 1;
				}

				pg_query($this->con,"UPDATE tbl_drb_tracking_ledger_delete SET drb_number = '$new_drb', last_series_no = $last_series_no WHERE id = $id");
				pg_query($this->con,"UPDATE tbl_drb_minutes_meeting_logs_delete SET drb_num = '$new_drb' WHERE id_drb = $id");

				// Transfer File
				$archive_file = pg_query($this->con,"SELECT * FROM tbl_drb_minutes_delete WHERE id_drb = $id");
				while ($get_file = pg_fetch_array($archive_file)) {
					if (!empty($get_file['drb_path'])) {
						$path = str_replace("/assets", "../assets", $get_file['drb_path']);
						$new_path = "../assets/upload/retrieve-".$get_file['drb_file_name'];
						$new_file_name = "retrieve-".$get_file['drb_file_name'];
						// $new_file_name = str_replace("archive-D", "retrieve-D", $get_file['drb_file_name']);
						rename($path, $new_path);
						$drb_path = str_replace("../assets", "/assets", $new_path);
						pg_query($this->con,"UPDATE tbl_drb_minutes_delete SET drb_path = '$drb_path', drb_file_name = '$new_file_name',drb_number = '$new_drb' WHERE id_drb = $id");
					}
					else{
						pg_query($this->con,"UPDATE tbl_drb_minutes_delete SET drb_number = '$new_drb' WHERE id_drb = $id");
					}
				}

				pg_query($this->con,"INSERT INTO tbl_drb_affected_lots(drb_number, rfc_no, affected_lot, ledger_no, product_name, affected_panel, risk, affected_no_panel) SELECT drb_number, rfc_no, affected_lot, ledger_no, product_name, affected_panel, risk, affected_no_panel FROM tbl_drb_affected_lots_delete WHERE rfc_no = '$rfc'");

				pg_query($this->con,"INSERT INTO tbl_drb_minutes(drb_number,drb_path,drb_number_upload,drb_first_upload,drb_last_upload,drb_file_name,id_drb) SELECT drb_number,drb_path,drb_number_upload,drb_first_upload,drb_last_upload,drb_file_name,id_drb FROM tbl_drb_minutes_delete WHERE id_drb = $id");

				pg_query($this->con,"INSERT INTO tbl_drb_minutes_meeting_logs(drb_num,drb_date,drb_start_time,drb_end_time,status,drb_total_time,id_drb) SELECT drb_num,drb_date,drb_start_time,drb_end_time,status,drb_total_time,id_drb FROM tbl_drb_minutes_meeting_logs_delete WHERE id_drb = $id");

				pg_query($this->con,"INSERT INTO tbl_drb_tracking_ledger(occur_month,work_week,occur_date,drb_date,drb_number,rfc_no,drb_issue,block,process,product,issue_type,m5e1,affected_lots,sheet_out,rank,drb_status,closing_validation_plan_date,closing_validation_date,created_by,created_at,meeting_status,last_series_no,machine_no,lot_out) SELECT occur_month,work_week,occur_date,drb_date,drb_number,rfc_no,drb_issue,block,process,product,issue_type,m5e1,affected_lots,sheet_out,rank,drb_status,closing_validation_plan_date,closing_validation_date,created_by,created_at,meeting_status,last_series_no,machine_no,lot_out FROM tbl_drb_tracking_ledger_delete WHERE id = $id");


				$sql = pg_query($this->con,"SELECT currval('tbl_drb_tracking_ledger_id_seq')");
				while ($row = pg_fetch_row($sql)) {
					$updated_id = $row[0];
					pg_query($this->con,"UPDATE tbl_drb_minutes set id_drb = $updated_id where drb_number = '$new_drb'");
					pg_query($this->con,"UPDATE tbl_drb_minutes_meeting_logs set id_drb = $updated_id where drb_num = '$new_drb'");
				}

				pg_query($this->con,"DELETE FROM tbl_drb_affected_lots_delete WHERE rfc_no = '$rfc'");
				pg_query($this->con,"DELETE FROM tbl_drb_minutes_delete WHERE id_drb = $id");
				pg_query($this->con,"DELETE FROM tbl_drb_minutes_meeting_logs_delete WHERE id_drb = $id");
				pg_query($this->con,"DELETE FROM tbl_drb_tracking_ledger_delete WHERE id = $id");

				$exe = pg_query("COMMIT");
				if ($exe) {
					$task = "Retrieve Data DRB Number(".$new_drb.")";
					$this->SaveLogs($task);
					echo "success";
				}
			} catch (Exception $e) {
				pg_query("Rollback");
				$task = "Execution Error System malfunction(Retrieve Data)";
				$this->SaveLogs($task);
				echo "Error: ".$e->getMessage();
				
			}
		}
	}
		// List of Archive
	public function archivelist(){
		$record_per_page = 10;
		$page = '';

		if (isset($_POST["page"])) {
			$page = $_POST["page"];
			
		}
		else {
			$page = 1;
		}
		$start_from = ($page - 1) * $record_per_page;
		$sql = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger_delete ORDER BY occur_date DESC LIMIT $record_per_page OFFSET $start_from");

		$this->output .= '
		<table class="drblist table table-hover table-bordered table-responsive">
		<thead class="thead-dark">
		<tr>
		<th>Month</th>
		<th>WW#</th>
		<th>Occurrence Date</th>
		<th>DRB Date</th>
		<th>DRB Number</th>
		<th>DRB Issue</th>
		<th>RFC #</th>
		<th>Block</th>
		<th>Process Affected</th>
		<th>Product Affected</th>
		<th>5M1E</th>
		<th>Recurrence or New Issue</th>
		<th>Rank</th>
		<th>Issue Status</th>
		<th>Close Validation and Approval By</th>
		<th>Action</th>
		</tr>
		</thead>
		<tbody>';

		while ($row = pg_fetch_array($sql)) {
			$status = $row['drb_status'];
			$rank = $row['rank'];
			$date_plan = $row['closing_validation_plan_date'];
			if (empty($date_plan)) {
				$date_plan = "-";
			}
			else{
				$date_plan = date('F d, Y', strtotime($row['closing_validation_plan_date']));
			}

			if ($status == 1) {
				$stats = "Open";
				$date_actual = "-";
			}
			else{
				$stats ="Closed";
				$date_actual = date('F d, Y', strtotime($row['closing_validation_date']));
			}
			$get_approval = pg_query($this->con,"SELECT * FROM tbl_closing_approval WHERE rank = $rank");
			while ($get = pg_fetch_array($get_approval)) {
				$name_of_approval = $get['name_of_approval'];
			}
			$this->output .='<tr>
			<td>'.$row['occur_month'].'</td>
			<td>'.$row['work_week'].'</td>
			<td>'.date('F d, Y', strtotime($row['occur_date'])).'</td>
			<td>'.date('F d, Y', strtotime($row['drb_date'])).'</td>
			<td>'.strtoupper($row['drb_number']).'</td>
			<td>'.$row['drb_issue'].'</td>
			<td>'.$row['rfc_no'].'</td>
			<td>'.strtoupper($row['block']).'</td>
			<td>'.strtoupper($row['process']).'</td>
			<td>'.$row['product'].'</td>
			<td>'.$row['m5e1'].'</td>
			<td>'.$row['issue_type'].'</td>
			<td>'.$rank.'</td>
			<td>'.$stats.'</td>
			<td>'.$name_of_approval.'</td>
			<td><a class="btn btn-primary text-white" id="prepare_notif" data-block="'.$row['block'].'" data-drbdate="'.$row['drb_date'].'" data-fun="prepare_retrieve_drb" data-iddrb="'.$row['id'].'" data-id="'.$row['drb_number'].'" data-toggle="modal" data-target="#retrieve_popup"><span class="oi oi-external-link"></span> Retrieve</a></td>
			</tr>';
		}
		$this->output .='
		</tbody>
		</table>
		<nav aria-label="Page navigation example">
		<ul class="pagination justify-content-center">';
		$pagi = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger_delete");
		$total_records = pg_num_rows($pagi);
		$total_pages = ceil($total_records/$record_per_page);
		for ($i=1; $i <= $total_pages; $i++) { 
			$this->output .=
			'<li class="page-item tracking_class"><a class="archive_link page-link" id='.$i.'>'.$i.'</a></li>'; 
		}
		$this->output .= '</ul>
		</nav>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".retrieve_drb").on("click",function() {
				var id = $(this).data("id");
				var func = $(this).data("fun");
				var id_drb = $(this).data("iddrb");
				$.ajax({
					method: "post",
					url: "Controller/execute.php",
					data:{id_drb:id_drb,id:id,func:func},
					beforeSend:function() {
						$("body").css("overflow","hidden");
						$(".containers").css("display","flex");
					},
					success:function (response) {
						if (response == "success") {
							$.toast({
								heading: "Retrieve Data",
								text: "Data is successfully Retrieve",
								showHideTransition: "slide",
								hideAfter : 3000,
								position: "top-right",
								icon: "success"
							});
							setTimeout(function(){window.location.href="DRBArchive"} , 3000);

						}else{
							$.toast({
								heading: "Retrieve Data",
								text: response,
								showHideTransition: "slide",
								hideAfter : 3500,
								position: "top-right",
								icon: "warning"
							});
							console.log(response);
						}
					},
					complete:function(){
						$("body").css("overflow","auto");
						$(".containers").css("display","none");
					}
				})
			});
		});
	</script>
		';
		echo $this->output;
	}

		// --Search archive
	public function search_archive_list($columname,$key,$date1,$date2){

		if ($key == 'date_search_ipi') {
			$condition = "WHERE ".$columname." BETWEEN '".$date1."' AND '".$date2."'";
		}
		elseif($key == 'issue_search_ipi'){
			$condition = "WHERE ".$columname." = ".$date1;
		}
		else{
			$condition = "WHERE lower(".$columname.") LIKE lower('%".$key."%')";
		}

		$sql = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger_delete ".$condition." ORDER BY occur_date desc");

		$this->output .= '
		<table class="drblist table table-hover table-bordered table-responsive">
		<thead class="thead-dark">
		<tr>
		<th>Month</th>
		<th>WW#</th>
		<th>Occurrence Date</th>
		<th>DRB Date</th>
		<th>DRB Number</th>
		<th>DRB Issue</th>
		<th>RFC #</th>
		<th>Block</th>
		<th>Process Affected</th>
		<th>Product Affected</th>
		<th>5M1E</th>
		<th>Recurrence or New Issue</th>
		<th>Rank</th>
		<th>Issue Status</th>
		<th>Close Validation and Approval By</th>
		<th>Action</th>
		</tr>
		</thead>
		<tbody>';
		// Count search
		$count_search = pg_num_rows($sql);

		if ($count_search >= 1) {
			while ($row = pg_fetch_array($sql)) {
				$status = $row['drb_status'];
				$rank = $row['rank'];
				$date_plan = $row['closing_validation_plan_date'];
				if (empty($date_plan)) {
					$date_plan = "-";
				}
				else{
					$date_plan = date('F d, Y', strtotime($row['closing_validation_plan_date']));
				}
				if ($status == 1) {
					$stats = "Open";
					$date_actual = "-";
				}
				else{
					$stats ="Closed";
					$date_actual = date('F d, Y', strtotime($row['closing_validation_date']));
				}
				$get_approval = pg_query($this->con,"SELECT * FROM tbl_closing_approval WHERE rank = $rank");
				while ($get = pg_fetch_array($get_approval)) {
					$name_of_approval = $get['name_of_approval'];
				}
				$this->output .='<tr>
				<td>'.$row['occur_month'].'</td>
				<td>'.$row['work_week'].'</td>
				<td>'.date('F d, Y', strtotime($row['occur_date'])).'</td>
				<td>'.date('F d, Y', strtotime($row['drb_date'])).'</td>
				<td>'.strtoupper($row['drb_number']).'</td>
				<td>'.$row['drb_issue'].'</td>
				<td>'.$row['rfc_no'].'</td>
				<td>'.strtoupper($row['block']).'</td>
				<td>'.strtoupper($row['process']).'</td>
				<td>'.$row['product'].'</td>
				<td>'.$row['m5e1'].'</td>
				<td>'.$row['issue_type'].'</td>
				<td>'.$rank.'</td>
				<td>'.$stats.'</td>
				<td>'.$name_of_approval.'</td>
				<td><a class="btn btn-primary text-white" id="prepare_notif" data-block="'.$row['block'].'" data-drbdate="'.$row['drb_date'].'"  data-fun="prepare_retrieve_drb" data-iddrb="'.$row['id'].'" data-id="'.$row['drb_number'].'"><span class="oi oi-external-link"></span> Retrieve</a></td>
				</tr>';
			}
		}
		else{
			if ($key == 'date_search_ipi') {
				$this->output .= '<tr>
				<td colspan="20" class="text-center">No Data Found for date <strong>'.$date1.' - '.$date2.'</strong></td>
				</tr>';
			}
			elseif ($key == 'issue_search_ipi') {
				if ($columname == 'rank') {
					$this->output .= '<tr>
					<td colspan="20" class="text-center">No Data Found for Rank Level <strong>'.$date1.'</strong></td>
					</tr>';
				}
				else{
					if ($date1 == 0) {
						$status = 'Closed';
					}
					else{
						$status = 'Open';
					}
					$this->output .= '<tr>
					<td colspan="20" class="text-center">No Data Found for DRB Status <strong>'.$status.'</strong></td>
					</tr>';
				}
			}
			else {
				$this->output .= '<tr>
				<td colspan="20" class="text-center">No Data Found for keyword <strong>'.$key.'</strong></td>
				</tr>';
			}
		}
		
		$this->output .='
		</tbody>
		</table>
		<script type="text/javascript">
		$(document).ready(function() {
			$(".retrieve_drb").on("click",function() {
				var id = $(this).data("id");
				var func = $(this).data("fun");
				var id_drb = $(this).data("iddrb");
				$.ajax({
					method: "post",
					url: "Controller/execute.php",
					data:{id_drb:id_drb,id:id,func:func},
					beforeSend:function() {
						$("body").css("overflow","hidden");
						$(".containers").css("display","flex");
					},
					success:function (response) {
						if (response == "success") {
							$.toast({
								heading: "Retrieve Data",
								text: "Data is successfully Retrieve",
								showHideTransition: "slide",
								hideAfter : 3000,
								position: "top-right",
								icon: "success"
							});
							setTimeout(function(){window.location.href="DRBArchive"} , 3000);

						}else{
							$.toast({
								heading: "Retrieve Data",
								text: response,
								showHideTransition: "slide",
								hideAfter : 3500,
								position: "top-right",
								icon: "warning"
							});
							console.log(response);
						}
					},
					complete:function(){
						$("body").css("overflow","auto");
						$(".containers").css("display","none");
					}
				})
			});
		});
	</script>';
		echo $this->output;
	}
}

?>