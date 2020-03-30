<?php 
include_once 'DRBMinutesExcelController.php';
$minutes = new DRB_Generate();

if (isset($_GET['action'])) {
	$action = $_GET['action'];
}
else{
	$action = "";
}

if ($action == 'Template') {
	$drb = $_GET['drb'];
	$minutes->template($drb);
}
elseif ($action == 'Download') {
	$minutes->template($drb);
}
elseif($action == 'Tracking'){
	$column_name = $_GET['column_name'];
	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];
	// echo $column_name;
	$minutes->generate_tracking_ledger($column_name,$date1,$date2);
}
elseif($action == "ReportData"){
	$column_name = $_GET['column_name'];
	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];
	// echo $column_name;
	$minutes->generate_report_data($column_name,$date1,$date2);
}
?>