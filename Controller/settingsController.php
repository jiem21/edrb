<?php 
include_once 'database.php';
/**
 * 
 */
class settings extends dbh
{
	public $output = " ";
// User Management
	// User Pagination List
	public function userlist()
	{
		$record_per_page = 5;
		$page = '';

		if (isset($_POST["page"])) {
			$page = $_POST["page"];
			
		}
		else {
			$page = 1;
		}
		$start_from = ($page - 1) * $record_per_page;
		$sql = pg_query($this->con, "SELECT * FROM tbl_accounts where id_no != 'admin' AND account_type != '3' ORDER BY status desc, date_created desc  LIMIT $record_per_page OFFSET $start_from");

		$this->output .= '
		<table class="table table-hover table-bordered">
		<thead class="thead-custom">
		<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Id No.</th>
		<th>Password</th>
		<th>Account Type</th>
		<th>Block</th>
		<th>Status</th>
		<th class="text-center" colspan="2">Action</th>
		</tr>
		</thead>
		<tbody>';

		while ($row = pg_fetch_array($sql)) {
			$acc_type = $row['account_type'];
			$status = $row['status'];

			if ($acc_type == 2) {
				$type = "Admin";
			}elseif($acc_type == 1){
				$type = "User";
			}
			else{
				$type = "Super Admin";
			}
			$action_updated = "<a class='btn btn-info text-white view_user' data-toggle='modal' data-target='#UpdateUser' id='".$row['id']."' data-id='".$row['id_no']."' data-action='view'><span class='oi oi-eye'></span> View</a>";
			if ($status == 1) {
				$stats = "Active";
				$action = "<a class='btn btn-danger text-white action_user' id='".$row['id']."' data-id='".$row['id_no']."' data-action='deactivate'><span class='oi oi-x'></span> Deactivate</a>";
			}
			else{
				$stats ="Inactive";
				$action = "<a class='btn btn-success text-white action_user' id='".$row['id']."' data-id='".$row['id_no']."' data-action='activate'><span class='oi oi-check'></span> Activate</a>";
			}
			$this->output .= "<tr>
			<td>".$row['first_name']." ".$row['last_name']."</td>
			<td>".$row['email_address']."</td>
			<td>".$row['id_no']."</td>
			<td>".$row['password']."</td>
			<td>".strtoupper($type)."</td>
			<td>".strtoupper($row['section'])."</td>
			<td>".$stats."</td>
			<td class='text-center'>".$action_updated."</td>
			<td class='text-center'>".$action."</td>
			</tr>";
		}
		$this->output .='
		</tbody>
		</table>
		<nav aria-label="Page navigation example">
		<ul class="pagination justify-content-center">';
		$pagi = pg_query($this->con, "SELECT * FROM tbl_accounts where id_no != 'admin'");
		$total_records = pg_num_rows($pagi);
		$total_pages = ceil($total_records/$record_per_page);
		for ($i=1; $i <= $total_pages; $i++) { 
			$this->output .=
			'<li class="page-item"><a class="user_link page-link" id='.$i.'>'.$i.'</a></li>'; 
		}
		$this->output .= '</ul>
		</nav>';


		echo $this->output;
	}

	// Add User
	public function addUser($id_num,$fname,$mname,$lname,$email,$block,$account_type,$login,$date){

		$check = pg_query($this->con,"SELECT * FROM tbl_accounts WHERE id_no = '$id_num'");
		$count = pg_num_rows($check);

		if ($count == 0) {
			try {
				$sql = pg_query($this->con,"INSERT INTO tbl_accounts(id_no,first_name,middle_name,last_name,email_address,section,status,account_type,created_by,date_created,password) 
					VALUES('$id_num','$fname','$mname','$lname','$email','$block','1','$account_type','$login','$date','$id_num')");
				$task = "Add an account (".$id_num.")";
				$this->SaveLogs($task);
				echo "valid";
			} catch (Exception $e) {
				$task = "Execution Error System Malfunction Add Account(".$id_num.")";
				$this->SaveLogs($task);
				echo "Error: ".$e->getMessage();
			}
		}
		else{
			echo "Registered";
		}
		
	}

	public function search_user($key){
		$sql = pg_query($this->con, "SELECT * FROM tbl_accounts WHERE id_no != 'admin' AND (lower(first_name) LIKE lower('%$key%') OR lower(last_name) LIKE lower('%$key%') OR id_no LIKE '$key%') ORDER BY date_created");

		$this->output .= '
		<table class="table table-hover table-bordered table-responsive">
		<thead class="thead-custom">
		<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Id No.</th>
		<th>Password</th>
		<th>Account Type</th>
		<th>Block</th>
		<th>Status</th>
		<th colspan="2" class="text-center">Action</th>
		</tr>
		</thead>
		<tbody>';

		$count = pg_num_rows($sql);
		if ($count == 0) {
			$this->output .= '<tr>
			<td class="text-center" colspan="8">No recored found for keyword <b>'.$key.'</b></td>
			</tr>';
		}
		else{
			while ($row = pg_fetch_array($sql)) {
				$acc_type = $row['account_type'];
				$status = $row['status'];

				if ($acc_type == 2) {
					$type = "Admin";
				}elseif($acc_type == 1){
					$type = "User";
				}
				else{
					$type = "Super Admin";
				}

				if ($status == 1) {
					$stats = "Active";
				}
				else{
					$stats ="Inactive";
				}

				$action_updated = "<a class='btn btn-info text-white view_user' data-toggle='modal' data-target='#UpdateUser' id='".$row['id']."' data-id='".$row['id_no']."' data-action='view'><span class='oi oi-eye'></span> View</a>";
				if ($status == 1) {
					$stats = "Active";
					$action = "<a class='btn btn-danger text-white action_user' id='".$row['id']."' data-id='".$row['id_no']."'data-action='deactivate'><span class='oi oi-x'></span> Deactivate</a>";
				}
				else{
					$stats ="Inactive";
					$action = "<a class='btn btn-success text-white action_user' id='".$row['id']."' data-id='".$row['id_no']."'data-action='activate'><span class='oi oi-check'></span> Activate</a>";
				}
				$this->output .= "<tr>
				<td>".$row['first_name']." ".$row['last_name']."</td>
				<td>".$row['email_address']."</td>
				<td>".$row['id_no']."</td>
				<td>".$row['password']."</td>
				<td>".strtoupper($type)."</td>
				<td>".strtoupper($row['section'])."</td>
				<td>".$stats."</td>
				<td>".$action_updated."</td>
				<td>".$action."</td>
				</tr>";
			}
		}
		$this->output .='
		</tbody>
		</table>';
		echo $this->output;
	}

	public function change_status($id_num,$action)
	{
		switch ($action) {
			case "activate":
			try {
				pg_query("BEGIN");
				pg_query($this->con,"UPDATE tbl_accounts set status = '1' where id_no = '$id_num'");
				$execute = pg_query("COMMIT");
				if ($execute) {
					$task = "ID Number is activated(".$id_num.")";
					$this->SaveLogs($task);
					echo "activated";
				}
			} catch (Exception $e) {
				pg_query("ROLLBACK");
				$task = "Execution Error System Malfunction Change Status id number(".$id_num.")";
				$this->SaveLogs($task);
				echo "Error: ".$e->getMessage();	
			}
			break;
			case "deactivate":
			try {
				pg_query("BEGIN");
				pg_query($this->con,"UPDATE tbl_accounts set status = '0' where id_no = '$id_num'");
				$execute = pg_query("COMMIT");
				if ($execute) {
					$task = "ID Number is deactivated(".$id_num.")";
					$this->SaveLogs($task);
					echo "deactivated";
				}
			} catch (Exception $e) {
				pg_query("ROLLBACK");
				$task = "Execution Error System Malfunction Change Status id number(".$id_num.")";
				$this->SaveLogs($task);
				echo "Error: ".$e->getMessage();	
			}
			break;
			default:
			echo "failed";
			break;
		}
	}
	// Get View Account
	public function get_user($id_no)
	{
		$get_data = pg_query($this->con,"SELECT * FROM tbl_accounts where id_no = '$id_no'");
		$this->output .= '
		<div id="errorfunc"></div>
		<div class="container-fluid">';
		while ($use = pg_fetch_array($get_data)) {
			if ($use['account_type'] == 1) {
				$account_type = "MFG";
			}elseif($use['account_type'] == 2){
				$account_type = "ADMIN";
			}
			else{
				$account_type = "SUPER ADMIN";
			}

			$this->output .= '<div class="row">
			<div class="col">
			<div id="errorfunc"></div>
			</div>
			</div>
			<div class="row">
			<div class="col">
			<div class="form-group">
			<label for="ID_num">ID Number</label>
			<input class="form-control" type="text" disabled value="'.$use['id_no'].'"/>
			<input type="hidden" name="id_num" value="'.$use['id_no'].'"/>
			</div>
			</div>
			</div>
			<div class="row">
			<div class="col">
			<div class="form-group">
			<label for="first_name">First Name</label>
			<input class="form-control" type="text" disabled value="'.$use['first_name'].'"/>
			</div>
			</div>
			<div class="col">
			<div class="form-group">
			<label for="middle_name">Middle Name</label>
			<input class="form-control" type="text" disabled value="'.$use['middle_name'].'"/>
			</div>
			</div>
			<div class="col">
			<div class="form-group">
			<label for="last_name">Last Name</label>
			<input class="form-control" type="text" disabled value="'.$use['last_name'].'"/>
			</div>
			</div>
			</div>
			<div class="row">
			<div class="col">
			<div class="form-group">
			<label for="email_add">Email Address</label>
			<input class="form-control" type="email" disabled value="'.$use['email_address'].'"/>
			</div>
			</div>
			<div class="col">
			<div class="form-group">
			<label for="acc_type_update">Account Type</label>
			<select class="form-control" name="acc_type_update" id="acc_type_update" required>
			<option selected value="'.$account_type.'">'.$account_type.'</option>
			<option value="1">MFG</option>
			<option value="2">Admin</option>
			<option value="3">Super Admin</option>
			</select>
			</div>
			</div>
			<div class="col">
			<div class="form-group">
			<label for="block_update">Block</label>
			<select class="form-control" name="block_update" id="block_update" disabled>
			<option selected value="'.$use['account_type'].'">'.strtoupper($use['section']).'</option>
			<option value="core">Core</option>
			<option value="vf">VF</option>
			<option value="sap">SAP</option>
			<option value="sf">SF</option>
			<option value="be">BE</option>
			<option value="fvi">FVI</option>
			<option value="others">Others</option>
			</select>
			</div>';
		}
		$this->output .='';

		echo $this->output;
	}
	// Update Account
	public function update_account($id_num,$account_type,$block)
	{
		try {
			pg_query("BEGIN");
			pg_query($this->con,"UPDATE tbl_accounts set section = '$block', account_type = $account_type where id_no = '$id_num'");
			$execute = pg_query("COMMIT");
			if ($execute) {
				$task = "Update an account account no. (".$id_num.")";
				$this->SaveLogs($task);
				echo "success";
			}
		} catch (Exception $e) {
			pg_query("ROLLBACK");
			$task = "Execution Error System Malfunction Update Account";
			$this->SaveLogs($task);
			echo "Error: ".$e->getMessage();
		}
	}
	// Change Password
	public function ChangePassword($id_num, $new_password)
	{
		try {
			pg_query("BEGIN");
			pg_query($this->con,"UPDATE tbl_accounts set password = '$new_password' where id_no = '$id_num'");
			$execute = pg_query("COMMIT");
			if ($execute) {
				$task = "Account has change password id no.(".$id_num.")";
				$this->SaveLogs($task);
				echo "success";
			}
		} catch (Exception $e) {
			pg_query("ROLLBACK");
			$task = "Execution Error System Malfunction Change Password";
			$this->SaveLogs($task);
			echo "Error: ".$e->getMessage();
			
		}
	}
	public function verify_password($id_num,$old_password,$new_password)
	{
		$check_password = pg_query($this->con,"SELECT * FROM tbl_accounts where id_no = '$id_num' AND password = '$old_password'");
		$count = pg_num_rows($check_password);

		if ($count == 1) {
			$this->ChangePassword($id_num,$new_password);
		}
		else{
			echo "Wrong Password";
		}
	}
//Rank Management
	// List of Rank
	public function Rank_level()
	{
		try {
			$get_list = pg_query($this->con,"SELECT * FROM tbl_closing_approval order by rank asc");

			while ($get = pg_fetch_array($get_list)) {
				echo '<tr>
				<td>'.$get['name_of_approval'].'</td>
				<td>Rank '.$get['rank'].'</td>
				<td><a class="btn btn-info text-white updaterank" data-func="update_rank" data-toggle="modal" data-target="#updateRank" id="'.$get['rank'].'">Update</a></td>
				</tr>';
			}
		} catch (Exception $e) {
			echo "Error: ".$e->getMessage();
		}
	}
	// Get Rank Data
	public function read($rank)
	{
		$read_data = pg_query($this->con,"SELECT * FROM tbl_closing_approval where rank = $rank");
		while ($get = pg_fetch_array($read_data)) {
			echo '
			<div class="row">
			<div class="col-md-12">
			<label>Rank Level:<br> <b>Rank '.$get['rank'].'</b></label>
			</div>
			<div class="col-md-12">
			<div class="form-group">
			<label for="approver_name">Approver Name</label>
			<input type="hidden" name="rank_level" value="'.$get['rank'].'">
			<input type="hidden" name="func" value="save_rank_update">
			<input class="form-control" type="text" name="approver_name" id="approver_name" required value="'.$get['name_of_approval'].'"/>
			</div>
			</div>
			</div>
			';
		}
	}
	// Update rank
	public function UpdateRank($rank,$name)
	{
		try {
			$update = pg_query($this->con,"UPDATE tbl_closing_approval SET name_of_approval = '$name' WHERE rank = $rank");
			
			if ($update) {
				$task = "Update Rank Level(".$rank.")";
				$this->SaveLogs($task);
				echo "success";
			}
		} catch (Exception $e) {
			$task = "Execution Error System Malfunction Update Rank Level(".$rank.")";
			$this->SaveLogs($task);
			echo "Error: ".$e->getMessage();
		}
	}
}

?>