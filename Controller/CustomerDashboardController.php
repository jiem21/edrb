<?php 
include_once 'database.php';

/**
 * 
 */
class customDashboard extends dbh
{
	
	public $output = "";
	public $id_set;
	public $desc;
	public $status;
	public $show_stat;
	public $class_active;
	// List of ledger
	public function CustomerSettingsLedgerlist(){
		$record_per_page = 10;
		$page = '';

		if (isset($_POST["page"])) {
			$page = $_POST["page"];
			
		}
		else {
			$page = 1;
		}
		$start_from = ($page - 1) * $record_per_page;
		$sql = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger a WHERE NOT EXISTS(SELECT b.drb_number,b.last_sereis_no from tbl_customer_dashboard_list b where a.drb_number = b.drb_number ORDER BY b.last_sereis_no ASC) ORDER BY a.block ASC LIMIT $record_per_page OFFSET $start_from");

		$this->output .= '
		<table class="table table-hover table-bordered">
		<thead class="thead-custom">
		<tr>
		<th>DRB Number</th>
		<th>DRB Issue</th>
		<th>RFC #</th>
		<th>Block</th>
		<th>Process Affected</th>
		<th>Product Affected</th>
		<th>5M1E</th>
		<th>Rank</th>
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
			<td>'.strtoupper($row['drb_number']).'</td>
			<td>'.$row['drb_issue'].'</td>
			<td>'.$row['rfc_no'].'</td>
			<td>'.strtoupper($row['block']).'</td>
			<td>'.strtoupper($row['process']).'</td>
			<td>'.$row['product'].'</td>
			<td>'.$row['m5e1'].'</td>
			<td>'.$rank.'</td>
			<td><a class="btn btn-success text-white add_drb" data-func="add_drb" data-id="'.$row['drb_number'].'"><span class="oi oi-plus"></span> Add to the Customer Dashboard</a></td>
			</tr>';
		}
		$this->output .='
		</tbody>
		</table>
		<nav aria-label="Page navigation example">
		<ul class="pagination justify-content-center">';
		$pagi = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger a WHERE NOT EXISTS(SELECT b.drb_number from tbl_customer_dashboard_list b where a.drb_number = b.drb_number)");
		$total_records = pg_num_rows($pagi);
		$total_pages = ceil($total_records/$record_per_page);
		for ($i=1; $i <= $total_pages; $i++) { 
			$this->output .=
			'<li class="page-item"><a class="tracking_link page-link" id='.$i.'>'.$i.'</a></li>'; 
		}
		$this->output .= '</ul>
		</nav><script type="text/javascript">
			$(document).ready(function() {
				$(".add_drb").on("click",function() {
					var drb = $(this).attr("data-id");
						var func = $(this).attr("data-func");
						$.ajax({
							method: "post",
							url: "Controller/execute.php",
							data: {drb:drb, func:func},
							beforeSend:function() {
								$(".add_drb").prop("disabled",true);
							},
							success:function (data) {
								if (data == "success") {
									$.toast({
										heading: "Customer Dashboard",
										text: "Successfully Add to Customer Dashboard",
										showHideTransition: "slide",
										hideAfter : 2500,
										position: "top-right",
										icon: "success"
									});
									setTimeout(function(){window.location.href="CustomerDashboardSettings"} , 2600);
								}
								else{
									$.toast({
										heading: "Customer Dashboard",
										text: data,
										showHideTransition: "slide",
										hideAfter : 2500,
										position: "top-right",
										icon: "warning"
									});
									console.log(data);
								}
							},
							complete:function(){
								$(".add_drb").prop("disabled",false);
							}
						});
					
				});
			});
		</script>';
		echo $this->output;
	}
	// --Search Ledger
	public function search_ledger_list_settings($key){
		$sql = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger a WHERE (lower(a.drb_number) LIKE lower('%$key%') OR lower(a.drb_issue) LIKE lower('%$key%')) AND NOT EXISTS(SELECT b.drb_number from tbl_customer_dashboard_list b where a.drb_number = b.drb_number) order by a.block asc, a.last_series_no asc, a.drb_date desc");

		$this->output .= '
		<table class="table table-hover table-bordered">
		<thead class="thead-custom">
		<tr>
		<th>DRB Number</th>
		<th>DRB Issue</th>
		<th>RFC #</th>
		<th>Block</th>
		<th>Process Affected</th>
		<th>Product Affected</th>
		<th>5M1E</th>
		<th>Rank</th>
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
			<td>'.strtoupper($row['drb_number']).'</td>
			<td>'.$row['drb_issue'].'</td>
			<td>'.$row['rfc_no'].'</td>
			<td>'.strtoupper($row['block']).'</td>
			<td>'.strtoupper($row['process']).'</td>
			<td>'.$row['product'].'</td>
			<td>'.$row['m5e1'].'</td>
			<td>'.$rank.'</td>
			<td><a class="btn btn-success text-white add_drb" data-func="add_drb" data-id="'.$row['drb_number'].'"><span class="oi oi-plus"></span> Add to the Customer Dashboard</a></td>
			</tr>';
		}
		$this->output .='
		</tbody>
		</table>
		<script type="text/javascript">
			$(document).ready(function() {
				$(".add_drb").on("click",function() {
					var drb = $(this).attr("data-id");
						var func = $(this).attr("data-func");
						$.ajax({
							method: "post",
							url: "Controller/execute.php",
							data: {drb:drb, func:func},
							beforeSend:function() {
								$(".add_drb").prop("disabled",true);
							},
							success:function (data) {
								if (data == "success") {
									$.toast({
										heading: "Customer Dashboard",
										text: "Successfully Add to Customer Dashboard",
										showHideTransition: "slide",
										hideAfter : 2500,
										position: "top-right",
										icon: "success"
									});
									setTimeout(function(){window.location.href="CustomerDashboardSettings"} , 2600);
								}
								else{
									$.toast({
										heading: "Customer Dashboard",
										text: data,
										showHideTransition: "slide",
										hideAfter : 2500,
										position: "top-right",
										icon: "warning"
									});
								console.log(data);
								}
							},
							complete:function(){
								$(".add_drb").prop("disabled",false);
							}
						});
					
				});
			});
		</script>';
		echo $this->output;
	}
	// Customer List
		// 
	public function count_list()
	{
		$get_data = pg_query($this->con,"SELECT * FROM tbl_customer_dashboard_list");

		$count = pg_num_rows($get_data);
		echo $count; 
	}
		// List of ledger
	public function CustomerLedgerlist(){
		$record_per_page = 5;
		$page = '';

		if (isset($_POST["page"])) {
			$page = $_POST["page"];
			
		}
		else {
			$page = 1;
		}
		$start_from = ($page - 1) * $record_per_page;
		$sql = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger a right join tbl_customer_dashboard_list b on a.drb_number = b.drb_number ORDER BY created_at ASC LIMIT $record_per_page OFFSET $start_from");
		$check = pg_num_rows($sql);
			$this->output .= '
			<table class="table table-hover table-bordered">
			<thead class="thead-custom">
			<tr>
			<th>Customer DRB</th>
			<th>DRB Number</th>
			<th>DRB Issue</th>
			<th>RFC #</th>
			<th>5M1E</th>
			<th>Rank</th>
			<th>Action</th>
			</tr>
			</thead>
			<tbody>';
		if ($check == 0) {
			$this->output .= '<tr><td class="text-center" colspan="7">No Record Found For Customer Dashboard</td></tr>';
		}
		else{
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
				<td>'.strtoupper($row['drb_customer_number']).'</td>
				<td>'.strtoupper($row['drb_number']).'</td>
				<td>'.$row['drb_issue'].'</td>
				<td>'.$row['rfc_no'].'</td>
				<td>'.$row['m5e1'].'</td>
				<td>'.$rank.'</td>
				<td><a class="btn btn-danger text-white remove_drb" data-func="remove_drb" data-id="'.$row['drb_number'].'"><span class="oi oi-trash"></span> Remove From the Customer Dashboard</a></td>
				</tr>';
			}
			$this->output .='
			</tbody>
			</table>
			<nav aria-label="Page navigation example">
			<ul class="pagination justify-content-center">';
			$pagi = pg_query($this->con, "SELECT * FROM tbl_drb_tracking_ledger a right join tbl_customer_dashboard_list b on a.drb_number = b.drb_number");
			$total_records = pg_num_rows($pagi);
			$total_pages = ceil($total_records/$record_per_page);
			for ($i=1; $i <= $total_pages; $i++) { 
				$this->output .=
				'<li class="page-item"><a class="tracking_customer_link page-link" id='.$i.'>'.$i.'</a></li>'; 
			}
			$this->output .= '</ul>
			</nav>
			<script type="text/javascript">
				$(document).ready(function() {
					$(".remove_drb").on("click",function() {
						var drb = $(this).attr("data-id");
						var func = $(this).attr("data-func");
						$.ajax({
							method: "post",
							url: "Controller/execute.php",
							data: {drb:drb, func:func},
							beforeSend:function() {
								$(".remove_drb").prop("disabled",true);
							},
							success:function (data) {
								if (data == "success") {
									$.toast({
										heading: "Customer Dashboard",
										text: "Successfully Remove From Customer Dashboard",
										showHideTransition: "slide",
										hideAfter : 2500,
										position: "top-right",
										icon: "success"
									});
									setTimeout(function(){window.location.href="CustomerDashboardSettings"} , 2600);
								}
								else{
									$.toast({
										heading: "Customer Dashboard",
										text: data,
										showHideTransition: "slide",
										hideAfter : 2500,
										position: "top-right",
										icon: "warning"
									});
								}
							},
							complete:function(){
								$(".remove_drb").prop("disabled",false);
							}
						});
						
					});
				});
			</script>';
		}
			echo $this->output;
	}
	// Add DRB From Customer Dashboard
	public function Add_To_Customer_Dashboard($drb)
	{
		try {
			pg_query("BEGIN");
			$fetch_data = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger where drb_number = '$drb'");
			while ($row = pg_fetch_array($fetch_data)) {
				$id = $row['id'];
				$rfc = $row['rfc_no'];
				$drb_date = $row['drb_date'];
				$block = $row['block'];

			// Get date
				$month = date("m", strtotime($drb_date));
				$year = date("Y", strtotime($drb_date));
				$start = $month."/1/".$year;
				$end = date("m/t/Y",strtotime($start));
				$start = str_replace("/", "-", $start);
				$end = str_replace("/", "-", $end);
				// Get Series
				$get = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger a right join tbl_customer_dashboard_list b on a.drb_number = b.drb_number
					WHERE a.block = '$block' AND a.drb_date BETWEEN '$start' AND '$end' order by b.last_sereis_no desc limit 1");
				$check = pg_num_rows($get);
				// Create a new drb for customer
				if ($check == 0) {
					$last_series_no = 1;
					$customer_drb = "D-".strtoupper($block)."-".date('y',strtotime($drb_date))."-".date('m',strtotime($drb_date))."-"."001";
				}
				else{
					$generate = pg_fetch_row($get);
					$last_series_no = $generate['28'] + 1;
					$series = str_pad($generate['28'] + 1, 3, 0, STR_PAD_LEFT);
					$customer_drb = "D-".strtoupper($block)."-".date('y',strtotime($drb_date))."-".date('m',strtotime($drb_date))."-".$series;
				}

			}
			pg_query($this->con,"INSERT INTO tbl_customer_dashboard_list(drb_number,drb_customer_number,last_sereis_no,id_drb) 
					VALUES('$drb','$customer_drb',$last_series_no,$id)");
			$transact = pg_query("COMMIT");
			if ($transact) {
				$task = "Add to customer dashboard (".$drb.")";
				$this->SaveLogs($task);
				echo "success";
			}
		} catch (Exception $e) {
			pg_query("ROLLBACK");
			$task = "Execution Error System malfunction(Add Tracking ledger Customer Dashboard)";
			$this->SaveLogs($task);
			echo "Error: ".$e->getMessage();
		}
	}
	public function Remove_To_Customer_Dashboard($drb)
	{
		try {
			pg_query("BEGIN");
			$fetch_data = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger a right join tbl_customer_dashboard_list b on a.drb_number = b.drb_number where a.drb_number = '$drb'");
			while ($row = pg_fetch_array($fetch_data)) {
				$id = $row['id'];
				$rfc = $row['rfc_no'];
				$drb_date = $row['drb_date'];
				$block = $row['block'];
				$last_sereis_no = $row['last_sereis_no'];
				// Get date
				$month = date("m", strtotime($drb_date));
				$year = date("Y", strtotime($drb_date));
				$start = $month."/1/".$year;
				$end = date("m/t/Y",strtotime($start));
				$start = str_replace("/", "-", $start);
				$end = str_replace("/", "-", $end);
				// DELETE
				pg_query($this->con,"DELETE FROM tbl_customer_dashboard_list WHERE drb_number = '$drb'");
				// Update Data
				$get = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger a right join tbl_customer_dashboard_list b on a.drb_number = b.drb_number
					WHERE a.block = '$block' AND b.last_sereis_no > $last_sereis_no AND a.drb_date BETWEEN '$start' AND '$end' order by b.last_sereis_no desc");
				// Execute
				$check = pg_num_rows($get);
				if ($check >= 1) {
					while ($row2 = pg_fetch_array($get)) {
						$id = $row2['id_drb'];
						$update_series = $row2['last_sereis_no']-1;
						$series = str_pad($update_series, 3, 0, STR_PAD_LEFT);
						$update_drb = "D-".strtoupper($block)."-".date('y',strtotime($drb_date))."-".date('m',strtotime($drb_date))."-".$series;
						pg_query($this->con,"UPDATE tbl_customer_dashboard_list SET drb_customer_number = '$update_drb',last_sereis_no = $update_series WHERE id_drb = $id");
					}
				}
				$transact = pg_query("COMMIT");
				if ($transact) {
					$task = "Remove from customer dashboard (".$drb.")";
					$this->SaveLogs($task);
					echo "success";
				}
			}
		} catch (Exception $e) {
			$task = "Execution Error System malfunction(Remove Tracking ledger Customer Dashboard)";
			$this->SaveLogs($task);
			echo "Error: ".$e->getMessage();
		}
	}

	public function SQA_update($id)
	{
		$query = pg_query($this->con,"SELECT * FROM tbl_dashboard_settings WHERE id = $id");
		while ($row = pg_fetch_array($query)) {
			$stat = $row['filter_data'];
			if ($stat == '1') {
				$change = '0';
				$mess = "Off";
			}
			else{
				$change = '1';
				$mess = "On";
			}
			try {
				pg_query("BEGIN");
				pg_query($this->con,"UPDATE tbl_dashboard_settings SET filter_data = '$change' WHERE id = $id");
				$commit=pg_query("COMMIT");
				if ($commit) {
					$data['message'] = "success";
					$data['desc'] = "Filtering of data for customer is ".$mess;
					echo json_encode($data);
				}
			} catch (Exception $e) {
				pg_query("ROLLBACK");
				echo "Error: ".$e->getMessage();
				$data['message'] = "failed";
				$data['desc'] = "Error: ".$e->getMessage();
				echo json_encode($data);
			}
		}
	}
	public function get_SQA_setting()
	{
		$query = pg_query($this->con,"SELECT * FROM tbl_dashboard_settings LIMIT 1");
		while ($row = pg_fetch_array($query)) {
			$this->id_set = $row['id'];
			$this->desc = $row['status_show'];
			$this->status = $row['filter_data'];

			if ($this->status == '1') {
				$this->show_stat = "ON";
				$this->class_active = "active";
			}
			else{
				$this->show_stat = "OFF";
				$this->class_active = "";
			}
			
		}
	}

}




?>