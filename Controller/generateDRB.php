<?php 
include_once 'database.php';
$db = new dbh();

if (isset($_POST['block']) && isset($_POST['drb_date'])) {
	$block = $_POST['block'];
	$drb_date = $_POST['drb_date'];
	$month = date('n',strtotime($drb_date));
	$year = date('Y',strtotime($drb_date));
	$series = 000;

	$get_last_series = pg_query($db->con,"SELECT * from tbl_drb_tracking_ledger
		where block = '$block' AND date_part('month',drb_date) = $month and date_part('year',drb_date) = $year order by last_series_no desc limit 1");
	$count = pg_num_rows($get_last_series);

	if ($count == 0) {
		echo "D-".strtoupper($block)."-".date('y',strtotime($drb_date))."-".date('m',strtotime($drb_date))."-"."001";
	}
	else{
		while ($generate = pg_fetch_array($get_last_series)) {
			$series = str_pad($generate['last_series_no'] + 1, 3, 0, STR_PAD_LEFT);
			echo "D-".strtoupper($block)."-".date('y',strtotime($drb_date))."-".date('m',strtotime($drb_date))."-".$series;
		}	
	}
}
elseif(isset($_POST['block']) && isset($_POST['drb_no'])){
	$block = $_POST['block'];
	$drb_no = $_POST['drb_no'];
	$series = 000;

	$get_previous = pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger where drb_number = '$drb_no'");
	while ($row = pg_fetch_array($get_previous)) {

		$drb_date = $row['drb_date'];
		$month = date('n',strtotime($drb_date));
		$year = date('Y',strtotime($drb_date));

		$get_last_series = pg_query($db->con,"SELECT * from tbl_drb_tracking_ledger
			where block = '$block' AND date_part('month',drb_date) = $month and date_part('year',drb_date) = $year order by last_series_no desc limit 1");

		$count = pg_num_rows($get_last_series);
		if ($count == 0) {
			echo "D-".strtoupper($block)."-".date('y',strtotime($drb_date))."-".date('m',strtotime($drb_date))."-"."001";
		}
		else{
			while ($generate = pg_fetch_array($get_last_series)) {
				$series = str_pad($generate['last_series_no'] + 1, 3, 0, STR_PAD_LEFT);
				echo "D-".strtoupper($block)."-".date('y',strtotime($drb_date))."-".date('m',strtotime($drb_date))."-".$series;
			}	
		}

	}
}

?>