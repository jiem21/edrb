<?php 

include_once 'Controller/database.php';
$db = new dbh();

require '../assets/plugins/phpspreadsheet/vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// $rfc = '47-0917-011';
// $rfc = "'".$rfc."'";

// $link_connection = "'host=192.168.53.221 port=5432 dbname=OLAMS user=postgres password=conPG'";

// $query = 'SELECT * From dblink('.$link_connection.'::text,'. "'" .'SELECT rfc_control_no, lot_no FROM public."tblRFC_details"'. "'" .'::text) a (rfc_control_no varchar, lot_no varchar) WHERE rfc_control_no = '.$rfc;

// // echo $query;

// $link = pg_query($db->con, $query);

// while ($get_link_data = pg_fetch_array($link)) {
// 	echo $get_link_data['rfc_control_no']." ";
// 	echo $get_link_data['lot_no']."<br> ";
// } 




// $a = strtotime('09:00:01');
// $b = strtotime('12:00:00');

// $c = ($b-$a)/60;
// echo round($c,2);

// $link = "assets/upload/D-CORE-19-06-001.xlsx";


// if (unlink($link)) {
// 	echo "success";
// }
// else{
// 	echo "failed";
// }



?>
<!-- <a href="/edrb/assets/templates/DRB_Minutes_Template.xlsx" download>Download</a> -->


<!-- <select>
	<?php
	$test = pg_query($db->con,"SELECT * FROM tbl_affected_process where block = 'core'");
	while ($row = pg_fetch_array($test)) {
		echo '<option value="'.$row['process'].'">'.$row['process'].'</option>';
	}
	?>
</select> -->


<?php 

// SQL Server Connection String
// $servername = "192.168.53.248\IPI_MFG";
// $connectionInfo = array("Database" => "SLI_NUMBERING", "UID" => "conSLI", "PWD" => "conSLI");
// $shipment = sqlsrv_connect($servername,$connectionInfo);

// // if ($shipment) {
// // 	echo("connection is working");
// // }
// // else{
// // 	echo "Failed";
// // 	die(print_r( sqlsrv_errors(),true ));
// // }

// $SLI = sqlsrv_query($shipment,"SELECT top 10 b.LOT_NO, a.SLI_No, CONVERT(varchar, a.SDate) as ship_date, a.Consignee from [dbo].[SHIP_DETAILS] a right join FROM_PACKING b on a.SLI_No COLLATE SQL_Latin1_General_CP1_CI_AS = b.SLI_NUMBER WHERE b.LOT_NO = 'P7448530'");
// $count = sqlsrv_has_rows($SLI);
// 	// echo $count;
// while ($rrow = sqlsrv_fetch_array($SLI)) {
// 	// echo $rrow['ship_date'];
// 	$shipdate = date('m/d/Y h:i:s a',strtotime($rrow['ship_date']));
// 	echo $rrow['LOT_NO']." | ".$rrow['SLI_No']." | ".$shipdate." | ".$rrow['Consignee']."<br>";

// }

// 		// date('m/d/Y H:i:s a',strtotime($rrow['SDate']))


				// $prepare_FY1 = "04/01/".date("Y");
				// $prepare_FY2 = "03/31/".date("Y");
				// $current_date = date("m/d/Y");
				// if (strtotime($current_date) > strtotime($prepare_FY2)) {
				// 	$FY1 = str_replace("/", "-", $prepare_FY1);
				// 	$FY2 = date("m-d-Y", strtotime(date("m/d/Y", strtotime($prepare_FY2)) . " + 1 year"));
				// 	// echo $FY1." - ".$FY2;
				// }
				// else{
				// 	$FY2 = str_replace("/", "-", $prepare_FY2);
				// 	$FY1 = date("m-d-Y", strtotime(date("m/d/Y", strtotime($prepare_FY1)) . " - 1 year"));
				// 	// echo $FY1."  ".$FY2;
				// }
				// echo strtotime($prepare_FY2)."<br>";
				// echo $prepare_FY2."<br>";
				// echo strtotime($current_date) ." > ". strtotime($prepare_FY2);

?>

<?php  
// PHP program for implementation  
// of Bubble Sort 

// function bubbleSort(&$arr) 
// { 
//     $n = sizeof($arr); 

//     // Traverse through all array elements 
//     for($i = 0; $i < $n; $i++)  
//     { 
//         // Last i elements are already in place 
//         for ($j = 0; $j < $n - $i - 1; $j++)  
//         { 
//             // traverse the array from 0 to n-i-1 
//             // Swap if the element found is greater 
//             // than the next element 
//             if ($arr[$j] > $arr[$j+1]) 
//             { 
//                 $t = $arr[$j]; 
//                 $arr[$j] = $arr[$j+1]; 
//                 $arr[$j+1] = $t; 
//             } 
//         } 
//     } 
// } 

// // Driver code to test above 
// $arr = array(64, 34, 25, 12, 22, 11, 90); 

// $len = sizeof($arr); 
// for ($i = 0; $i < $len; $i++){
//     echo $arr[$i]." ";  
// }
// echo "<br>";
// bubbleSort($arr); 

// echo "Sorted array : \n"; 

// for ($i = 0; $i < $len; $i++) 
//     echo $arr[$i]." ";  

// This code is contributed by ChitraNayal. 

// $check =  
// $upload = "D-CORE-19-08-001 (1) - Copy.xlsx";
// $file_path = "/assets/upload/";

// $file_path = str_replace("/assets", "assets", $file_path);

// // move_uploaded_file($_FILES['']['name'], 'assets/archive_file/D-CORE-19-08-001 (1) - Copy.xlsx');

// // Move the file from another file
// rename($file_path.$upload, "assets/archive_files/D-CORE-19-08-001 (1) - Copy Archive File.xlsx");
// if ($check) {
// 	echo "success";
// }
// else{
// 	echo "Error";
// }

// $date = "2019-08-22";
// $month = date("m", strtotime($date));
// $year = date("Y", strtotime($date));
// $start = $month."/1/".$year;
// $end = date("m/t/Y",strtotime($start));

// $start = str_replace("/", "-", $start);
// $end = str_replace("/", "-", $end);

// echo "Date Range ".$start." AND ".$end."<br>";
// echo str_pad(10, 3, 0, STR_PAD_LEFT);;

// $get = pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE block = 'core' AND drb_date BETWEEN '08/01/2019' AND '08/31/2019' order by last_series_no desc limit 1");

// $row = pg_fetch_row($get);
// echo $row['23']+1;


// // Retrieve
// if (!empty($get_file['drb_path'])) {
// 	$path = str_replace("/assets", "../assets", $get_file['drb_path']);
// 	$new_path = "../assets/upload/retrieve-".$get_file['drb_file_name'];
// 	$new_file_name = str_replace("archive-D", "retrieve-D", $get_file['drb_file_name']);
// 	rename($path, $new_path);
// 	$drb_path = str_replace("../assets", "/assets", $new_path);
// 	pg_query($this->con,"UPDATE tbl_drb_minutes_delete SET drb_path = '$drb_path', drb_file_name = '$new_file_name',drb_number = '$new_drb' WHERE id_drb = $id");
// }
// // Archive
// if (!empty($get_file['drb_path'])) {
// 	$path = str_replace("/assets", "../assets", $get_file['drb_path']);
// 	$new_path = "../assets/archive_files/archive-".$get_file['drb_file_name'];
// 	$new_file_name = "archive-".$get_file['drb_file_name'];
// 	rename($path, $new_path);
// 	$drb_path = str_replace("../assets", "/assets", $new_path);
// 	pg_query($this->con,"UPDATE tbl_drb_minutes_delete SET drb_path = '$drb_path', drb_file_name = '$new_file_name' WHERE id_drb = $id");
// }
// $sql2 = pg_query($this->con,"SELECT currval('tbl_drb_tracking_ledger_id_seq')");
// $id = $row[0];


// pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger a right join tbl_customer_dashboard_list b on a.drb_number = b.drb_number WHERE");


// $get = pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger
//  WHERE block = 'core' AND drb_date BETWEEN '08/01/2019' AND '08/31/2019' order by last_series_no desc");
// 				while ($row = pg_fetch_array($get)) {
// 					$drb_date = $row['drb_date'];
// 					$p = $row['process'];
// 					// $data = "'".$row['process']."'";
// 					// $block = strtoupper($row['block']);
// 					// $series = str_pad($row['last_sereis_no'] + 1, 3, 0, STR_PAD_LEFT);
// 					// echo "D-".$block."-".date('y',strtotime($drb_date))."-".date('m',strtotime($drb_date))."-".$series."<br>";
// 					echo "<label class='used' value='".$p."'>".$p."</label>";
// 				}
// $foo = 104.5678;
//  echo number_format((float)$foo, 2, '.', '');

?>
<!-- 
<table>
	<thead>
		<th>Measeure Time</th>
		<th>Measure Logic</th>
		<th>Lot No.</th>
		<?php 
		$query2 = pg_query($db->con_prod,"SELECT mnfc_lot_no,mesure_10_digit_cd,msr_dt,msr_logic_dt,prod_artcl_item_opr_num,count(mesure_10_digit_cd) as code_digit FROM trn_qua_lot_mesure_msr WHERE mnfc_lot_no = 'P7717580' AND opr_ptrn_cd = 'FZ1CZT' AND msr_value_numc > 0 Group by mesure_10_digit_cd,mnfc_lot_no,msr_dt,msr_logic_dt,prod_artcl_item_opr_num ORDER BY code_digit DESC limit 1");
		$get_single_data = pg_fetch_row($query2);
		$num_column = $get_single_data['5'];
		$a = 1;
		// echo $num_column;
		while ($a <= $num_column) {
			echo "<th>Sample ".$a."</th>";
		$a++;
		}
		?>
	</thead>
	<tbody>
		<tr>
			<?php 
			$query = pg_query($db->con,"SELECT * FROM tbl_drb_affected_lots WHERE affected_lot = 'P7682180'");
			while ($k = pg_fetch_array($query)) {
				echo "<td>".$k['rfc_no']."</td>
					  <td>".$k['ledger_no']."</td>
					  <td>".$k['affected_lot']."</td>";
			}
			$j = 1;
			while ($j <= 7) {
				echo "<td>".$j."</td>";
				$j++;
			}
			 ?>
		</tr>
	</tbody>
</table> 
 -->

<?php
// $danger = "class='red'";
// $danger = true;

// if ($danger) {

// 	echo $danger;
// }
// else{
// 	echo $danger;
// }
// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="D-CORE-19-04-001.xlsx"'); 
// header('Cache-Control: max-age=0');
// header('Cache-Control: max-age=1');
// echo readfile('/edrb/assets/upload/D-CORE-19-04-001.xlsx');
?>