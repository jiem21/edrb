<?php 
include_once 'dashboardController.php';
include_once 'DRBController.php';
include_once 'settingsController.php';
include_once 'CustomerDashboardController.php';
include_once 'mailerController.php';
include_once 'maintenanceController.php';
include_once 'DRBMinutesExcelController.php';
$dash = new dashboard();
$drb = new DRBFunc();
$settings = new settings();
$customermgnt = new customDashboard();
$mailer = new mail();
$maintenance = new maintenance();
$excel_validator = new DRB_Generate();
// Dashboard
if (isset($_POST["function_login"]) == "login") {
	$user = $_POST["username"];
	$pass = $_POST["password"];
	$dash->Login($user,$pass);
}
elseif (isset($_POST['func']) AND $_POST['func'] == "logout") {
	$dash->logout();
}
// DRB
// --Add DRB Tracking ledger
elseif (isset($_POST['func']) AND $_POST['func'] == "opt_proc"){
	$block =  $_POST['block'];
	$drb->get_proc_opt($block);
}
elseif (isset($_POST['func']) AND $_POST['func'] == "opt_rank"){
	$rank =  $_POST['rank'];
	$drb->get_rank_opt($rank);
}
// Check RFC from Olams
elseif(isset($_POST['func']) AND $_POST['func'] == "verified_RFC"){
	$rfc = $_POST['RFC'];
	$drb->verify_RFC($rfc);
}
// Check for update affected lots on RFC from Olams
elseif(isset($_POST['func']) AND $_POST['func'] == "check_rfc_updates"){
	$rfc = $_POST['rfc_no'];
	$drb->update_RFC($rfc);
}
// Add Tracking Ledger
elseif (isset($_POST['func']) AND $_POST['func'] == "TrackLedgerAdd"){
	session_start();
	$login = $_SESSION['name'];
	$timestamp = date('Y-m-d H:i:s');
	$occur_month = $_POST['DRBmonth'];
	$work_week = $_POST['ww'];
	$occur_date = $_POST['occur_date'];
	$drb_date = $_POST['DRB_date'];
	$drb_number = $_POST['DRB_Number'];
	$rfc_no = $_POST['RFC_Number'];
	$drb_issue = $_POST['DRB_Issue'];
	$block = $_POST['block'];
	$process = (isset($_POST['process'])) ? $_POST['process'] : '';
	$product = $_POST['products'];
	$m5e1 = $_POST['5m1e'];
	$issue_type = $_POST['issue_type'];
	$lot_out = $_POST['lot_out'];
	$sheet_out = $_POST['sheet_out'];
	$rank = (isset($_POST['Rank'])) ? $_POST['Rank'] : '';
	$machine_no = $_POST['machine_no'];
	$total_affected = $_POST['total_affected'];

	if (empty($lot_out)) {
		$lot_out = 0;
	}
	else{
		$lot_out = $lot_out;
	}
	if (!empty(trim($_POST['DRBmonth'])) && !empty(trim($_POST['ww'])) && !empty(trim($_POST['occur_date'])) && !empty(trim($_POST['DRB_date'])) && !empty(trim($_POST['DRB_Number'])) && !empty(trim($_POST['RFC_Number'])) && !empty(trim($_POST['DRB_Issue'])) && !empty(trim($_POST['block'])) && !empty(trim($_POST['products'])) && !empty(trim($_POST['issue_type'])) && !empty(trim($_POST['process'])) && !empty(trim($_POST['5m1e'])) && !empty(trim($_POST['Rank'])) ) {
		$drb->add_tracking_ledger($occur_month,$work_week,$occur_date,$drb_date,$drb_number,$rfc_no,$drb_issue,$block,$process,$product,$m5e1,$issue_type,$lot_out,$sheet_out,$total_affected,$rank,$login,$timestamp,$machine_no);
	}
	else{
		echo "empty";
	}
	
}
// Update Tracking Ledger
elseif(isset($_POST['func']) AND $_POST['func'] == "TrackLedgerUpdate"){
	$drb_number = $_POST['DRB_Number'];
	$DRB_Number_previous = $_POST['DRB_Number_previous'];
	$drb_issue = $_POST['DRB_Issue'];
	$drb_date = $_POST['DRB_date'];
	$month = $_POST['DRBmonth'];
	$ww = $_POST['ww'];
	$occur_date = $_POST['occur_date'];
	$block = $_POST['block'];
	$process = $_POST['process'];
	$product = $_POST['products'];
	$m5e1 = $_POST['5m1e'];
	$issue_type = $_POST['issue_type'];
	$lot_out = $_POST['lot_out'];
	$sheet_out = $_POST['sheet_out'];
	$rank = $_POST['Rank'];
	$machine_no = $_POST['machine_no'];
	$closing_validation_plan_date = $_POST['closing_plan_date'];

	$drb->update_ledger($drb_number,$DRB_Number_previous,$drb_issue,$drb_date,$month,$ww,$occur_date,$block,$process,$product,$m5e1,$issue_type,$lot_out,$sheet_out,$rank,$closing_validation_plan_date,$machine_no);
}
// DRB Minutes Function
	// DRB Start Meeting
elseif(isset($_POST['func']) AND $_POST['func'] == "StartMeeting"){
	$drb_num = $_POST['drb'];
	$id = $_POST['id'];
	$drb->Start_meeting($id,$drb_num);
}	
	// DRB End Meeting
elseif(isset($_POST['func']) AND $_POST['func'] == "EndMeeting"){
	$drb_num = $_POST['drb'];
	$drb->End_meeting($drb_num);
}
	// DRB Issue close
elseif(isset($_POST['func']) AND $_POST['func'] == "CloseIssue"){
	$drb_num = $_POST['drb'];
	$drb->Close_Issue($drb_num);
}
	// DRB Issue reopen
elseif(isset($_POST['func']) AND $_POST['func'] == "reopenIssue"){
	$drb_num = $_POST['drb'];
	$drb->reopen_Issue($drb_num);
}

	// Save DRB Minutes
elseif(isset($_POST['func']) AND $_POST['func'] == "upload_file"){
	// define data
	$drb_num = $_POST['drb_number'];
	$validator = $_POST['validator'];
	$file_name = $_FILES['drb_upload']['name'];

	// move to tempo file
	move_uploaded_file($_FILES['drb_upload']['tmp_name'],"../assets/tmp_files/" . $file_name);
	// Check excel file if valid for indicate DRB
	$excel_validator->validate_excel($file_name,$validator);
	if ($excel_validator->result) {
		$drb->file_upload($drb_num,$file_name);
		if ($drb->result == "success") {
			rename("../assets/tmp_files/".$file_name, "../assets/upload/".$file_name);
			echo $drb->result;
		}
	}
	else{
		echo "Incorrect";
	}
	
}
	// Archive Issue
elseif (isset($_POST['func']) AND $_POST['func'] == "archiveissue") {
	$drb_number = $_POST['id'];
	$id = $_POST['id_drb'];
	$drb->archive_data($id,$drb_number);
}
	// Retrieve Issue
elseif (isset($_POST['func']) AND $_POST['func'] == "retrieve_issue") {
	$drb_number = $_POST['new_drb'];
	$id = $_POST['id_drb'];
	$drb->retrieve_data($id,$drb_number);
}
// --Search Ledger
elseif (isset($_POST['search_ledger'])){
	if (empty($_POST['search_ledger'])) {
		echo "default";
	}
	else{
		$key = $_POST['search_ledger'];
		if ($key == 'date_search_ipi') {
			$date1 = $_POST['date1'];
			$date2 = $_POST['date2'];
			$column = $_POST['column'];
			$drb->search_ledger_list($column,$key,$date1,$date2);
		}
		elseif($key == 'issue_search_ipi'){
			$date1 = '';
			$date2 = '';
			$status = $_POST['status'];
			$column = $_POST['column'];
			$drb->search_ledger_list($column,$key,$status,$date2);
		}
		else{
			$date1 = '';
			$date2 = '';
			$column = $_POST['column'];
			$drb->search_ledger_list($column,$key,$date1,$date2);
		}
	}
}
// --Search Ledger by Section
elseif (isset($_POST['ledger_section_key'])){
	if (empty($_POST['ledger_section_key'])) {
		echo "default";
	}
	else{
		session_start();
		$block = $_SESSION['block'];
		$key = $_POST['ledger_section_key'];
		$drb->search_ledger_section_list($key,$block);
	}
}
// --Search Ledger on Customer Management
elseif (isset($_POST['search_ledger_settings'])){
	if (empty($_POST['search_ledger_settings'])) {
		echo "default";
	}
	else{
		session_start();
		$key = $_POST['search_ledger_settings'];
		$customermgnt->search_ledger_list_settings($key);
	}
}
// --Search archive
elseif (isset($_POST['search_archive'])){
	if (empty($_POST['search_archive'])) {
		echo "default";
	}
	else{
		$key = $_POST['search_archive'];
		if ($key == 'date_search_ipi') {
			$date1 = $_POST['date1'];
			$date2 = $_POST['date2'];
			$column = $_POST['column'];
			$drb->search_archive_list($column,$key,$date1,$date2);
		}
		elseif($key == 'issue_search_ipi'){
			$date1 = '';
			$date2 = '';
			$status = $_POST['status'];
			$column = $_POST['column'];
			$drb->search_archive_list($column,$key,$status,$date2);
		}
		else{
			$date1 = '';
			$date2 = '';
			$column = $_POST['column'];
			$drb->search_archive_list($column,$key,$date1,$date2);
		}
	}
}
// Settings module
	// --User Management
		// Add Account
elseif (isset($_POST['func']) AND $_POST['func'] == "add_user"){
	session_start();
	$id_num = $_POST['ID_num'];
	$fname = $_POST['first_name'];
	$mname = $_POST['middle_name'];
	$lname = $_POST['last_name'];
	$email = $_POST['email_add'];
	$account_type = $_POST['acc_type'];
	if (!empty(trim($_POST['ID_num'])) && !empty(trim($_POST['first_name'])) && !empty(trim($_POST['last_name'])) && !empty(trim($_POST['email_add'])) && !empty(trim($_POST['acc_type']))) {
		if ($account_type == 1) {
			$block = $_POST['block'];
		}
		elseif($account_type == 3){
			$block = "Admin";
		}
		else{
			$block = "QC";
		}
		$date = date('Y-m-d');
		$login = $_SESSION['name'];
		$settings->addUser($id_num,$fname,$mname,$lname,$email,$block,$account_type,$login,$date);
	}
	else{
		echo "empty";
	}
}
		// Get View Account
elseif(isset($_POST['data_action']) AND $_POST['data_action'] == 'view'){
	$id_num = $_POST['id_number'];
	$settings->get_user($id_num);
}
		// Update Function Account
elseif(isset($_POST['func']) AND $_POST['func'] == 'update_user'){
	$id_num = $_POST['id_num'];
	$account_type = $_POST['acc_type_update'];
	if ($account_type == 1) {
		$block = $_POST['block_update'];
	}
	elseif($account_type == 3){
		$block = "Admin";
	}
	else{
		$block = "QC";
	}
	$settings->update_account($id_num,$account_type,$block);
}
		// --Search User
elseif (isset($_POST['search_user'])){
	if (empty($_POST['search_user'])) {
		echo "default";
	}
	else{
		$key = $_POST['search_user'];
		$settings->search_user($key);
	}

}
	// --Activate Deactivate User Account
elseif(isset($_POST['action_user']) AND $_POST['action_user'] == 'activate' || $_POST['action_user'] == 'deactivate'){
	$id_num = $_POST['id_number'];
	$action_user = $_POST['action_user'];
	$settings->change_status($id_num,$action_user);
}
	// --Rank Management
elseif(isset($_POST['func']) AND $_POST['func'] == "update_rank"){
	$rank = $_POST['ranklevel'];
	$settings->read($rank);
}
   // -- Save rank update
elseif (isset($_POST['func']) AND $_POST['func'] == "save_rank_update") {
	$rank = $_POST['rank_level'];
	$name = $_POST['approver_name'];
	if (empty(trim($_POST['approver_name']))) {
		echo "empty";
	}
	else{
		$settings->UpdateRank($rank,$name);
	}
}
	// Change Password
elseif(isset($_POST['func']) AND $_POST['func'] == "changepassword"){
	$id_num = $_POST['id_num'];
	$old = $_POST['old_password'];
	$new = $_POST['new_pass'];
	$confirm = $_POST['new_pass2'];

	if ($new != $confirm) {
		echo "Password Not Match";
	}
	else{
		$settings->verify_password($id_num,$old,$new);
	}

}

// Add DRB and Remove
elseif(isset($_POST['func']) AND $_POST['func'] == "add_drb"){
	$drb = $_POST['drb'];
	$customermgnt->Add_To_Customer_Dashboard($drb);
}
elseif(isset($_POST['func']) AND $_POST['func'] == "remove_drb"){
	$drb = $_POST['drb'];
	$customermgnt->Remove_To_Customer_Dashboard($drb);
}
// Generate View affected lot Data
elseif(isset($_POST['func']) AND $_POST['func'] == "Generate_lot_data_from_other_DB"){
	$lot = $_POST['id'];
	$drb->Generate_View_Lots($lot);
}
// Generate IPP Data
elseif(isset($_POST['func']) AND $_POST['func'] == "generate_ipp"){
	$ptrn_code = $_POST['ptrn_code'];
	$lot_no = $_POST['lot_no'];
	$drb->generate_ipp($ptrn_code,$lot_no);
}
// Mail Fucntion trigger
elseif(isset($_POST['function_mail']) AND $_POST['function_mail'] == "forgotPassword"){
	$email = $_POST['email'];
	$function = $_POST['function_mail'];
	$mailer->verify($email,$function);
}
elseif(isset($_POST['func']) AND $_POST['func'] == "SQA_settings"){
	$id = $_POST['id'];
	$customermgnt->SQA_update($id);
}
elseif(isset($_POST['func']) AND $_POST['func'] == "addblock"){
	$block = $_POST['block_name'];
	$color = "rgba(".$_POST['red'].",".$_POST['green'].",".$_POST['blue'].",0.5)";
	$maintenance->AddBlock($block,$color);
}
elseif(isset($_POST['block_action']) AND $_POST['block_action'] == "delblock"){
	$block = $_POST['id'];
	$maintenance->delete_block($block);
}
elseif(isset($_POST['func']) AND $_POST['func'] == "addprocess"){
	$block = $_POST['block_name'];
	$process = $_POST['proc_name'];
	$maintenance->add_process($block,$process);
}
elseif(isset($_POST['block_action']) AND $_POST['block_action'] == "delproc"){
	$block = $_POST['id'];
	$maintenance->del_proc($block);
}
else{
	echo "Please Call An IT Personnel to fix this bug";
}



?>