<?php 

// include 'Controller/database.php';
// include 'Controller/DRBMinutesExcelController.php';
// $db = new dbh();
// $excel_validator = new DRB_Generate();

// // require 'assets/plugins/phpspreadsheet/vendor/autoload.php';


// // use PhpOffice\PhpSpreadsheet\Spreadsheet;
// // use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// // use PhpOffice\PhpSpreadsheet\IOFactory;

// if (isset($_POST['submit'])) {
// 	// define file
// 	$get_cell_data = $_FILES['drb_upload']['name'];
// 	// move to tempo file
// 	move_uploaded_file($_FILES['drb_upload']['tmp_name'],"assets/tmp_files/" . $get_cell_data);
// 	$excel_validator->validate_excel($get_cell_data);

// }

?>




<!-- <form method="post" accept="test2.php" enctype="multipart/form-data">
<input type="file" name="drb_upload" required>
<input type="submit" name="submit" value="submit">
</form> -->

<?php 
//Get IP Address
function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    echo $ip;
}

echo getUserIpAddr();
?>