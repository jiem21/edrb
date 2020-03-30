<?php 

include 'Controller/database.php';
include 'Controller/DRBMinutesExcelController.php';
$db = new dbh();
$excel_validator = new DRB_Generate();

// require 'assets/plugins/phpspreadsheet/vendor/autoload.php';


// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['submit'])) {
	// define file
	$get_cell_data = $_FILES['drb_upload']['name'];
	// move to tempo file
	move_uploaded_file($_FILES['drb_upload']['tmp_name'],"assets/tmp_files/" . $get_cell_data);
	$excel_validator->validate_excel($get_cell_data);

}

?>




<form method="post" accept="test2.php" enctype="multipart/form-data">
<input type="file" name="drb_upload" required>
<input type="submit" name="submit" value="submit">
</form>



<!-- Execute file_upload -->
<?php
	// Save DRB Minutes
elseif(isset($_POST['func']) AND $_POST['func'] == "upload_file"){
	$drb_num = $_POST['drb_number'];
	$file_name = $_FILES['drb_upload']['name'];
	$drb->file_upload($drb_num,$file_name);
	if ($drb->result == "success") {
		move_uploaded_file($_FILES["drb_upload"]["tmp_name"],"../assets/upload/" . $file_name);
		echo $drb->result;
	}
}