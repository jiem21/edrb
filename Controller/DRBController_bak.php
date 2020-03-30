<?php 
include_once 'database.php';
/**
 * 
 */
class DRBFunc extends dbh
{
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
	public $lot_out;
	public $sheet_out;
	public $rank;
	public $drb_status;
	public $closing_validation_plan_date;
	public $closing_validation_date;
	public $meeting_status;
	public $name_of_approval;
	public $drb_path;
	public $drb_number_upload;
	public $drb_first_upload;
	public $drb_last_upload;
	public $output ="";
	public function get_proc_opt($block)
	{
		try {
			$sql = pg_query($this->con,"SELECT * FROM tbl_affected_process WHERE block = '$block'");

			while ($rows = pg_fetch_array($sql)) {
				echo "<option class='used' value=".$rows['process'].">".$rows['process']."</option>";
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
		$sql = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger ORDER BY created_at ASC LIMIT $record_per_page OFFSET $start_from");

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
			if ($status == 1) {
				$stats = "Open";
			}
			else{
				$stats ="Closed";
			}
			$get_approval = pg_query($this->con,"SELECT * FROM tbl_closing_approval WHERE rank = $rank");
			while ($get = pg_fetch_array($get_approval)) {
				$name_of_approval = $get['name_of_approval'];
			}
			$this->output .='<tr>
			<td>'.$row['occur_month'].'</td>
			<td>WW'.$row['work_week'].'</td>
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
			<td>'.$row['lot_out'].'</td>
			<td>'.$row['sheet_out'].'</td>
			<td>'.$rank.'</td>
			<td>'.$stats.'</td>
			<td>'.date('F d, Y', strtotime($row['closing_validation_plan_date'])).'</td>
			<td>-</td>
			<td>'.$name_of_approval.'</td>
			<td><a class="btn btn-primary text-white view_drb" data-id="'.$row['drb_number'].'"><span class="oi oi-eye"></span>View</a></td>
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
			'<li class="page-item"><a class="tracking_link page-link" id='.$i.'>'.$i.'</a></li>'; 
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
							window.open(url+"?drb="+id);
						}
					})
			});
		});
	</script>';
		echo $this->output;
	}
	// --Search Ledger
	public function search_ledger_list($key){
		$sql = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger WHERE (lower(drb_number) LIKE lower('%$key%') OR lower(drb_issue) LIKE lower('%$key%'))");

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
			if ($status == 1) {
				$stats = "Open";
			}
			else{
				$stats ="Closed";
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
			<td>'.$row['lot_out'].'</td>
			<td>'.$row['sheet_out'].'</td>
			<td>'.$rank.'</td>
			<td>'.$stats.'</td>
			<td>'.date('F d, Y', strtotime($row['closing_validation_plan_date'])).'</td>
			<td>-</td>
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
							window.open(url+"?drb="+id);
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
		$sql = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger WHERE block = '$block' ORDER BY created_at ASC LIMIT $record_per_page OFFSET $start_from");

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
			if ($status == 1) {
				$stats = "Open";
			}
			else{
				$stats ="Closed";
			}
			$get_approval = pg_query($this->con,"SELECT * FROM tbl_closing_approval WHERE rank = $rank");
			while ($get = pg_fetch_array($get_approval)) {
				$name_of_approval = $get['name_of_approval'];
			}
			$this->output .='<tr>
			<td>'.$row['occur_month'].'</td>
			<td>WW'.$row['work_week'].'</td>
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
			<td>'.$row['lot_out'].'</td>
			<td>'.$row['sheet_out'].'</td>
			<td>'.$rank.'</td>
			<td>'.$stats.'</td>
			<td>'.date('F d, Y', strtotime($row['closing_validation_plan_date'])).'</td>
			<td>-</td>
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
							window.open(url+"?drb="+id);
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
			if ($status == 1) {
				$stats = "Open";
			}
			else{
				$stats ="Closed";
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
			<td>'.$row['lot_out'].'</td>
			<td>'.$row['sheet_out'].'</td>
			<td>'.$rank.'</td>
			<td>'.$stats.'</td>
			<td>'.date('F d, Y', strtotime($row['closing_validation_plan_date'])).'</td>
			<td>-</td>
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
							window.open(url+"?drb="+id);
						}
					})
			});
		});
	</script>
		';
		echo $this->output;
	}
	// Add Tracking ledger
	public function add_tracking_ledger($occur_month,$work_week,$occur_date,$drb_date,$drb_number,$rfc_no,$drb_issue,$block,$process,$product,$m5e1,$issue_type,$lot_out,$sheet_out,$rank,$closing_validation_plan_date,$login,$timestamp){

		$check = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE drb_number = '$drb_number'");
		$count = pg_num_rows($check);
		$check2 = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE rfc_no = '$rfc_no'");
		$count2 = pg_num_rows($check2);

		if ($count == 0 AND $count2 == 0) {
			try {
				$sql = pg_query($this->con,"INSERT INTO tbl_drb_tracking_ledger(occur_month,work_week,occur_date,drb_date,drb_number,rfc_no,drb_issue,block,process,product,m5e1,issue_type,lot_out,sheet_out,rank,drb_status,closing_validation_plan_date,created_by,created_at,meeting_status)
					VALUES('$occur_month','$work_week','$occur_date','$drb_date','$drb_number','$rfc_no','$drb_issue','$block','$process','$product','$m5e1','$issue_type','$lot_out','$sheet_out','$rank','1','$closing_validation_plan_date','$login','$timestamp',0)");
				$this->add_drb_minute($drb_number);
				echo "success";
			} catch (Exception $e) {
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
	public function add_drb_minute($drb_num){
		try {
			$sql = pg_query($this->con,"INSERT INTO tbl_drb_minutes(drb_number,drb_number_upload)VALUES('$drb_num',0)");
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
				echo '<li>'.$set['lot_no'].'</li>';
			}
		}
		else{
			echo "invalid";
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
					$this->lot_out  = $read['lot_out'];
					$this->sheet_out = $read['sheet_out'];
					$this->rank = $read['rank'];
					$this->closing_validation_plan_date = $read['closing_validation_plan_date'];
					$this->closing_validation_date = $read['closing_validation_date'];
					$this->meeting_status = $read['meeting_status'];
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
}

?>