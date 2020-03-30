<?php 
include_once 'database.php';
require '../assets/plugins/phpspreadsheet/vendor/autoload.php';
// require 'assets/plugins/phpspreadsheet/vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
/**
 * 
 */
class DRB_Generate extends dbh
{
	public $result ="";
	public function validate_excel($file_name,$validator)
	{
	// get file path
		$path = "../assets/tmp_files/".$file_name;
	// load excel file
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
	// get details
		$rfc = $spreadsheet->getActiveSheet()->getCell('AB3')->getValue();
	// conditional statement
		if ($validator == $rfc) {
			$this->result = true;
		}
		else{
			unlink("../assets/tmp_files/" . $file_name);
			$this->result = false;
		}
	}
	
	public function template($drb_number)
	{
		$template = new Spreadsheet();
		$spreadsheet = $template->getActiveSheet();
		// Get Template and paste for new file
		$inputFileName = '../assets/templates/DRB_Minutes_Template.xlsx';

		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
		$filename = $drb_number.'.xlsx';

		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('IPI DRB Minutes');
		$prepare_query = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE drb_number = '$drb_number'");
		while ($generate_data = pg_fetch_array($prepare_query)) {
		// Data
			$rfc = $generate_data['rfc_no'];
			$rank = $generate_data['rank'];

		// Top right Header
			$sheet->setCellValue('AB2', strtoupper($generate_data['drb_number']));
			$sheet->setCellValue('AB3', strtoupper($generate_data['rfc_no']));

		// Other Header Details
			$sheet->setCellValue('F5', $generate_data['drb_issue']);
			$sheet->setCellValue('F6', date('d-M-y',strtotime($generate_data['drb_date'])));
			$sheet->setCellValue('F10', strtoupper($generate_data['block']));

		// Query for Affected Lots
			$lots = pg_query($this->con,"SELECT DISTINCT product_name,rfc_no,affected_lot,ledger_no FROM tbl_drb_affected_lots where rfc_no = '$rfc'");
			$lot_no = pg_query($this->con,"SELECT DISTINCT rfc_no,affected_lot,ledger_no FROM tbl_drb_affected_lots WHERE rfc_no = '$rfc'");
			$count_total = pg_num_rows($lot_no);

		// Problem Statement
			$sheet->setCellValue('F18', date('M-d-y',strtotime($generate_data['occur_date'])));
			$sheet->setCellValue('F19', $generate_data['process']);
			$sheet->setCellValue('F20', $generate_data['process']);
			$sheet->setCellValue('F21', $count_total);

			$i = 18;
			while ($get_lots = pg_fetch_array($lots)) {
				$sheet->setCellValue('I'.$i, $get_lots['product_name']);
				$sheet->setCellValue('Q'.$i, $get_lots['ledger_no']);
				$sheet->setCellValue('V'.$i, $get_lots['affected_lot']);
				// $sheet->setCellValue('AA'.$i, $get_lots['affected_panel']);
				$i++;
			}

		// THE AGENDA ITEM IS RECURRENCE?
			if ($generate_data['issue_type'] == "New") {
				$sheet->getStyle('K54')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}
			else{
				$sheet->getStyle('G54')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}

		// Problem Category
			if ($generate_data['m5e1'] == 'Man'){
				$sheet->getStyle('F56')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}
			elseif($generate_data['m5e1'] == 'Machine'){
				$sheet->getStyle('H56')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}
			elseif($generate_data['m5e1'] == 'Method'){
				$sheet->getStyle('L56')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}
			elseif($generate_data['m5e1'] == 'Material'){
				$sheet->getStyle('P56')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}
			elseif($generate_data['m5e1'] == 'Measurement'){
				$sheet->getStyle('T56')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}
			elseif($generate_data['m5e1'] == 'Environment'){
				$sheet->getStyle('X56')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}
			else{
				$sheet->getStyle('AB56')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}

		// DRB Closing Rank
			if ($generate_data['rank'] == 1) {
				$sheet->getStyle('F57')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}
			elseif ($generate_data['rank'] == 2) {
				$sheet->getStyle('H57')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}
			elseif ($generate_data['rank'] == 3) {
				$sheet->getStyle('L57')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}
			elseif ($generate_data['rank'] == 4) {
				$sheet->getStyle('P57')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}
			else {
				$sheet->getStyle('T57')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffff00');
			}


		// Closing Approval
			$get_approver = pg_query($this->con,"SELECT * FROM tbl_closing_approval where rank = $rank");
			while ($get_name = pg_fetch_array($get_approver)) {
				$sheet->setCellValue('W142', $get_name['name_of_approval']);
			}

		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"'); 
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
	}

	public function Download($drb_number)
	{
		$get_data = pg_query($this->con,"SELECT * FROM tbl_drb_minutes where drb_number = '$drb_number'");
		while ($get = pg_fetch_array($get_data)) {

			$template = new Spreadsheet();
			$spreadsheet = $template->getActiveSheet();

			$inputFileName = '..'.$get['drb_path'];
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
			$filename = $drb_number.'.xlsx';

			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('AB2', strtoupper($get['drb_number']));

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'"'); 
			header('Cache-Control: max-age=0');
			header('Cache-Control: max-age=1');

			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save('php://output');
		}
	}

	public function generate_tracking_ledger($column_name,$date1,$date2)
	{
		$date1 = str_replace("/", "-", $date1);
		$date2 = str_replace("/", "-", $date2);
		$generate = new Spreadsheet();
		$spreadsheet = $generate->getActiveSheet();
		// Get Template and paste for new file
		$inputFileName = '../assets/templates/DRB_Tracking_Ledger_Template.xlsx';

		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
		$filename = 'DRB Tracking Ledger Report.xlsx';

		$sheet = $spreadsheet->getActiveSheet();
		$rowCount = 5;
		$prepare_data = pg_query($this->con,"SELECT a.*, b.name_of_approval FROM tbl_drb_tracking_ledger a left join tbl_closing_approval b on a.rank = b.rank WHERE a.$column_name BETWEEN '$date1' AND '$date2' order by occur_date");
		while ($generate_data = pg_fetch_array($prepare_data)) {
			if ($generate_data['drb_status'] == 1) {
				$status = 'Open';
			}
			else{
				$status = 'Closed';
				// Fill color of row
				// $sheet->getStyle('A'.$rowCount.':U'.$rowCount)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff66ff');
			}

			if (empty($generate_data['closing_validation_plan_date'])) {
				$date_plan = "";
			}
			else{
				$string_date = "WW";
				$date_plan = date('y', strtotime($generate_data['closing_validation_plan_date'])).$string_date.date('W', strtotime($generate_data['closing_validation_plan_date']));
			}

			if (empty($generate_data['closing_validation_date'])) {
				$date = "";
			}
			else{
				$date = date('d-M-y', strtotime($generate_data['closing_validation_date']));
			}

			$sheet->setCellValue('A'.$rowCount, $generate_data['occur_month']);
			$sheet->setCellValue('B'.$rowCount, 'ww'.$generate_data['work_week']);
			$sheet->setCellValue('C'.$rowCount, date('d-M-y', strtotime($generate_data['occur_date'])));
			$sheet->setCellValue('D'.$rowCount, date('d-M-y', strtotime($generate_data['drb_date'])));
			$sheet->setCellValue('E'.$rowCount, $generate_data['drb_number']);
			$sheet->setCellValue('F'.$rowCount, $generate_data['drb_issue']);
			$sheet->setCellValue('G'.$rowCount, $generate_data['block']);
			$sheet->setCellValue('H'.$rowCount, $generate_data['process'].' '.$generate_data['machine_no']);
			$sheet->setCellValue('I'.$rowCount, $generate_data['product']);
			$sheet->setCellValue('J'.$rowCount, $generate_data['m5e1']);
			$sheet->setCellValue('K'.$rowCount, $generate_data['issue_type']);
			$sheet->setCellValue('M'.$rowCount, $generate_data['affected_lots']);
			$sheet->setCellValue('N'.$rowCount, $generate_data['lot_out']);
			$sheet->setCellValue('O'.$rowCount, 'Rank '.$generate_data['rank']);
			$sheet->setCellValue('P'.$rowCount, $status);
			$sheet->setCellValue('R'.$rowCount, $date_plan);
			$sheet->setCellValue('S'.$rowCount, $date);
			$sheet->setCellValue('T'.$rowCount, $generate_data['name_of_approval']);

			$rowCount++;
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"'); 
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');

	}
	// end of Generation of Tracking list
	public function generate_report_data($column_name,$date1,$date2)
	{
		$date1 = str_replace("/", "-", $date1);
		$date2 = str_replace("/", "-", $date2);
		$generate = new Spreadsheet();
		$spreadsheet = $generate->getActiveSheet();
		// Get Template and paste for new file
		$inputFileName = '../assets/templates/DRB_Report_Data_Template.xlsx';

		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
		$filename = 'DRB Report Data.xlsx';

		$sheet = $spreadsheet->getActiveSheet();
		$columnBlock = 3;
		// DRB BY Block
		$generate_month_block = pg_query($this->con,"SELECT distinct occur_month,DATE_PART('month',occur_date) occur_mnth, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE $column_name between '$date1' AND '$date2' ORDER BY occur_year,occur_mnth ASC");

		while ($get_month_block = pg_fetch_array($generate_month_block)) {
			$use_month = $get_month_block['occur_month'];
			$sheet->setCellValueByColumnAndRow($columnBlock,2,date('m',strtotime($get_month_block['occur_month'])).'/'.date('y',strtotime($get_month_block['occur_year'])));

			$rowCount = 3;
			$get_block = pg_query($this->con,"SELECT * FROM tbl_blocks");
			while ($get_block_data = pg_fetch_array($get_block)) {
				$block = $get_block_data['blocks'];

				$get_data = pg_query($this->con,"SELECT count(b.blocks) data_count, b.blocks,a.occur_month, DATE_PART('year',a.occur_date) occur_year
					FROM tbl_blocks b
					left Join  tbl_drb_tracking_ledger a on a.block = b.blocks
					where a.block = '$block' AND a.occur_month = '$use_month' AND a.$column_name between '$date1' AND '$date2' GROUP BY b.blocks, a.occur_month, DATE_PART('year',a.occur_date) order by occur_year;");
				$count_blocks = pg_num_rows($get_data);
				if ($count_blocks >= 1) {
					while ($get_total = pg_fetch_array($get_data)) {
						$sheet->setCellValueByColumnAndRow($columnBlock,$rowCount,$get_total['data_count']);
					}
				}
				else{
					$sheet->setCellValueByColumnAndRow($columnBlock,$rowCount,$count_blocks);
				}
				$rowCount++;
			}
			$actual = pg_query($this->con,"SELECT count(a.occur_month) data_count, a.occur_month, DATE_PART('year',a.occur_date) occur_year
				FROM tbl_blocks b
				left Join  tbl_drb_tracking_ledger a on a.block = b.blocks
				where a.occur_month = '$use_month' AND a.$column_name between '$date1' AND '$date2' GROUP BY a.occur_month, DATE_PART('year',a.occur_date) order by occur_year");
			while ($actual_count = pg_fetch_array($actual)) {
				$sheet->setCellValueByColumnAndRow($columnBlock,10,$actual_count['data_count']);
			}
			
			$sheet->setCellValueByColumnAndRow($columnBlock,11,4);
			$columnBlock++;
		}
		// End By Block

		// DRB by 5M1E
		$generate_month_5M1E = pg_query($this->con,"SELECT distinct occur_month, DATE_PART('month',occur_date) occur_mnth,DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE $column_name between '$date1' AND '$date2' ORDER BY occur_year,occur_mnth ASC");

		$column5m1e = 3;
		while ($get_month_5M1E = pg_fetch_array($generate_month_5M1E)) {
			$use_month = $get_month_5M1E['occur_month'];
			$sheet->setCellValueByColumnAndRow($column5m1e,14,date('m',strtotime($get_month_5M1E['occur_month'])).'/'.date('y',strtotime($get_month_5M1E['occur_year'])));

			$rowCount = 15;
			$get_m5e1 = pg_query($this->con,"SELECT * FROM tbl_m5e1");
			while ($get_m5e1_data = pg_fetch_array($get_m5e1)) {
				$m5e1 = $get_m5e1_data['method_type'];

				$get_data = pg_query($this->con,"SELECT count(b.method_type) data_count, b.method_type,a.occur_month, DATE_PART('year',a.occur_date) occur_year
					FROM tbl_m5e1 b
					right Join  tbl_drb_tracking_ledger a on a.m5e1 = b.method_type
					where a.m5e1 = '$m5e1' AND a.occur_month = '$use_month'  AND a.occur_date between '$date1' AND '$date2'  GROUP BY b.method_type, a.occur_month, DATE_PART('year',a.occur_date) order by occur_year;");
				$count_blocks = pg_num_rows($get_data);
				if ($count_blocks >= 1) {
					while ($get_total = pg_fetch_array($get_data)) {
						$sheet->setCellValueByColumnAndRow($column5m1e,$rowCount,$get_total['data_count']);
					}
				}
				else{
					$sheet->setCellValueByColumnAndRow($column5m1e,$rowCount,$count_blocks);
				}
				$rowCount++;
			}
			$column5m1e++;
		}
		// End 5M1E

		// DRB by RANK LEVEL
		$generate_month_rank = pg_query($this->con,"SELECT distinct occur_month, DATE_PART('month',occur_date) occur_mnth, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE $column_name between '$date1' AND '$date2' ORDER BY occur_year,occur_mnth ASC");

		$columnRank = 3;
		while ($get_month_rank = pg_fetch_array($generate_month_rank)) {
			$use_month = $get_month_rank['occur_month'];
			$sheet->setCellValueByColumnAndRow($columnRank,27,date('m',strtotime($get_month_rank['occur_month'])).'/'.date('y',strtotime($get_month_rank['occur_year'])));

			$rowCount = 28;
			$get_rank = pg_query($this->con,"SELECT * FROM tbl_closing_approval order by rank asc");
			while ($get_rank_data = pg_fetch_array($get_rank)) {
				$rank = $get_rank_data['rank'];

				$get_data = pg_query($this->con,"SELECT count(b.rank) data_count, b.rank,a.occur_month, DATE_PART('year',a.occur_date) occur_year
					FROM tbl_closing_approval b
					right Join  tbl_drb_tracking_ledger a on a.rank = b.rank
					where a.rank = '$rank' AND a.occur_month = '$use_month'  AND a.$column_name between '$date1' AND '$date2'  GROUP BY b.rank, a.occur_month, DATE_PART('year',a.occur_date) order by occur_year;");
				$count_blocks = pg_num_rows($get_data);
				if ($count_blocks >= 1) {
					while ($get_total = pg_fetch_array($get_data)) {
						$sheet->setCellValueByColumnAndRow($columnRank,$rowCount,$get_total['data_count']);
					}
				}
				else{
					$sheet->setCellValueByColumnAndRow($columnRank,$rowCount,$count_blocks);
				}
				$rowCount++;
			}
			$columnRank++;
		}
		// End RANK LEVEL

		// DRB by Closure
		$generate_month_closure = pg_query($this->con,"SELECT distinct occur_month, DATE_PART('month',occur_date) occur_mnth,DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE $column_name between '$date1' AND '$date2' ORDER BY occur_year,occur_mnth ASC");

		$columnclosure = 3;
		$rowCount = 37;
		while ($get_month_closure = pg_fetch_array($generate_month_closure)) {
			$use_month = $get_month_closure['occur_month'];
			$sheet->setCellValueByColumnAndRow($columnclosure,36,date('m',strtotime($get_month_closure['occur_month'])).'/'.date('y',strtotime($get_month_closure['occur_year'])));

			
			$get_open = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE $column_name between '$date1' AND '$date2' AND drb_status = 1 and occur_month = '$use_month'");
			$get_close = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE $column_name between '$date1' AND '$date2' AND drb_status = 0 and occur_month = '$use_month'");

			$total_open = pg_num_rows($get_open);
			$total_close = pg_num_rows($get_close);
			$total = $total_open + $total_close;
			$percentage = ($total_close / $total) * 100;

			$sheet->setCellValueByColumnAndRow($columnclosure,37,"100%");
			$sheet->setCellValueByColumnAndRow($columnclosure,38,$percentage.'%');
			$sheet->setCellValueByColumnAndRow($columnclosure,39,$total);
			$sheet->setCellValueByColumnAndRow($columnclosure,40,$total_close);

			
			$columnclosure++;
		}
		// End Closure

		// DRB by Reccurence
		$generate_month_reccurence = pg_query($this->con,"SELECT distinct occur_month, DATE_PART('month',occur_date) occur_mnth,DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE $column_name between '$date1' AND '$date2' ORDER BY occur_year,occur_mnth ASC");

		$columnReccurence = 3;
		while ($get_month_reccurence = pg_fetch_array($generate_month_reccurence)) {
			$use_month = $get_month_reccurence['occur_month'];
			$sheet->setCellValueByColumnAndRow($columnReccurence,44,date('m',strtotime($get_month_reccurence['occur_month'])).'/'.date('y',strtotime($get_month_reccurence['occur_year'])));

			$rowCount = 45;
			$get_m5e1 = pg_query($this->con,"SELECT * FROM tbl_m5e1");
			while ($get_m5e1_data = pg_fetch_array($get_m5e1)) {
				$m5e1 = $get_m5e1_data['method_type'];

				$get_data = pg_query($this->con,"SELECT count(b.method_type) data_count, b.method_type,a.occur_month, DATE_PART('year',a.occur_date) occur_year
					FROM tbl_m5e1 b
					right Join  tbl_drb_tracking_ledger a on a.m5e1 = b.method_type
					where a.m5e1 = '$m5e1' AND a.occur_month = '$use_month' AND lower(a.issue_type) = lower('Recurrence') AND a.occur_date between '$date1' AND '$date2'  GROUP BY b.method_type, a.occur_month, DATE_PART('year',a.occur_date) order by occur_year;");
				$count_blocks = pg_num_rows($get_data);
				if ($count_blocks >= 1) {
					while ($get_total = pg_fetch_array($get_data)) {
						$sheet->setCellValueByColumnAndRow($columnReccurence,$rowCount,$get_total['data_count']);
					}
				}
				else{
					$sheet->setCellValueByColumnAndRow($columnReccurence,$rowCount,$count_blocks);
				}
				$prepare_query1 = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger where $column_name between '$date1' AND '$date2' AND issue_type = 'Recurrence' and occur_month = '$use_month';");
				$prepare_query2 = pg_query($this->con,"SELECT * FROM tbl_drb_tracking_ledger where $column_name between '$date1' AND '$date2' AND occur_month = '$use_month';");

				$Recurrence = pg_num_rows($prepare_query1);
				$total = pg_num_rows($prepare_query2);
				$percentage = ($Recurrence / $total) * 100;

				$sheet->setCellValueByColumnAndRow($columnReccurence,52, $Recurrence);
				$sheet->setCellValueByColumnAndRow($columnReccurence,53, $percentage.'%');
				$sheet->setCellValueByColumnAndRow($columnReccurence,54,'');
				$sheet->setCellValueByColumnAndRow($columnReccurence,55,$total);
				$rowCount++;
			}
			$columnReccurence++;
		}
		// End Reccurence

		// DRB Lot out by block
		$columnBlockLotout = 3;
		$generate_month_block = pg_query($this->con,"SELECT distinct occur_month, DATE_PART('month',occur_date) occur_mnth,DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE $column_name between '$date1' AND '$date2' ORDER BY occur_year,occur_mnth ASC");

		while ($get_month_block_lot_out = pg_fetch_array($generate_month_block)) {
			$use_month = $get_month_block_lot_out['occur_month'];
			$sheet->setCellValueByColumnAndRow($columnBlockLotout,60,date('m',strtotime($get_month_block_lot_out['occur_month'])).'/'.date('y',strtotime($get_month_block_lot_out['occur_year'])));

			$rowCount = 61;
			$get_block = pg_query($this->con,"SELECT * FROM tbl_blocks");
			while ($get_block_data = pg_fetch_array($get_block)) {
				$block = $get_block_data['blocks'];

				$get_data = pg_query($this->con,"SELECT sum(a.lot_out) data_count, b.blocks,a.occur_month, DATE_PART('year',a.occur_date) occur_year
					FROM tbl_blocks b
					left Join  tbl_drb_tracking_ledger a on a.block = b.blocks
					where a.block = '$block' AND a.occur_month = '$use_month' AND a.$column_name between '$date1' AND '$date2' GROUP BY b.blocks, a.occur_month, DATE_PART('year',a.occur_date) order by occur_year;");
				$count_blocks = pg_num_rows($get_data);
				if ($count_blocks >= 1) {
					while ($get_total = pg_fetch_array($get_data)) {
						$sheet->setCellValueByColumnAndRow($columnBlockLotout,$rowCount,$get_total['data_count']);
					}
				}
				else{
					$sheet->setCellValueByColumnAndRow($columnBlockLotout,$rowCount,$count_blocks);
				}
				$rowCount++;
			}
			$actual = pg_query($this->con,"SELECT sum(a.lot_out) data_count, a.occur_month, DATE_PART('year',a.occur_date) occur_year
				FROM tbl_blocks b
				left Join  tbl_drb_tracking_ledger a on a.block = b.blocks
				where a.occur_month = '$use_month' AND a.$column_name between '$date1' AND '$date2' GROUP BY a.occur_month, DATE_PART('year',a.occur_date) order by occur_year");
			while ($actual_count = pg_fetch_array($actual)) {
				$sheet->setCellValueByColumnAndRow($columnBlockLotout,68,$actual_count['data_count']);
			}
			$columnBlockLotout++;
		}
		// End Lot out by block

		// DRB Lot out by 5M1E
		$generate_month_5M1E_lot_out = pg_query($this->con,"SELECT distinct occur_month, DATE_PART('month',occur_date) occur_mnth,DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE $column_name between '$date1' AND '$date2' ORDER BY occur_year,occur_mnth ASC");

		$column5m1eLotout = 3;
		while ($get_month_5M1E_lot_out = pg_fetch_array($generate_month_5M1E_lot_out)) {
			$use_month = $get_month_5M1E_lot_out['occur_month'];
			$sheet->setCellValueByColumnAndRow($column5m1eLotout,76,date('m',strtotime($get_month_5M1E_lot_out['occur_month'])).'/'.date('y',strtotime($get_month_5M1E_lot_out['occur_year'])));

			$rowCountLotOut = 77;
			$get_m5e1 = pg_query($this->con,"SELECT * FROM tbl_m5e1");
			while ($get_m5e1_data = pg_fetch_array($get_m5e1)) {
				$m5e1 = $get_m5e1_data['method_type'];

				$get_data = pg_query($this->con,"SELECT sum(a.lot_out) data_count, b.method_type,a.occur_month, DATE_PART('year',a.occur_date) occur_year
					FROM tbl_m5e1 b
					right Join  tbl_drb_tracking_ledger a on a.m5e1 = b.method_type
					where a.m5e1 = '$m5e1' AND a.occur_month = '$use_month'  AND a.occur_date between '$date1' AND '$date2'  GROUP BY b.method_type, a.occur_month, DATE_PART('year',a.occur_date) order by occur_year;");
				$count_blocks = pg_num_rows($get_data);
				if ($count_blocks >= 1) {
					while ($get_total = pg_fetch_array($get_data)) {
						$sheet->setCellValueByColumnAndRow($column5m1eLotout,$rowCountLotOut,$get_total['data_count']);
					}
				}
				else{
					$sheet->setCellValueByColumnAndRow($column5m1eLotout,$rowCountLotOut,$count_blocks);
				}
				$rowCountLotOut++;
			}
			$column5m1eLotout++;
		}
		// End Lot out 5M1E

		// Lot out/incident
		$generate_month_incident = pg_query($this->con,"SELECT distinct occur_month,DATE_PART('month',occur_date) occur_mnth, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE $column_name between '$date1' AND '$date2' ORDER BY occur_year,occur_mnth ASC");

		$columnIncident = 3;
		while ($get_month_incident = pg_fetch_array($generate_month_incident)) {
			$use_month = $get_month_incident['occur_month'];
			$sheet->setCellValueByColumnAndRow($columnIncident,91,date('m',strtotime($get_month_incident['occur_month'])).'/'.date('y',strtotime($get_month_incident['occur_year'])));

			// $rowCountIncident = 92;
				// Get lot out count
				$get_lotout = pg_query($this->con,"SELECT sum(lot_out) data_count, occur_month,  DATE_PART('year',occur_date) occur_year
					FROM tbl_drb_tracking_ledger
					where occur_month = '$use_month' AND occur_date between '$date1' AND '$date2'  GROUP BY occur_month, DATE_PART('year',occur_date) order by occur_year;");
				$count_blocks = pg_num_rows($get_lotout);
				if ($count_blocks >= 1) {
					while ($get_total = pg_fetch_array($get_lotout)) {
						$sheet->setCellValueByColumnAndRow($columnIncident,92,$get_total['data_count']);
					}
				}
				else{
					$sheet->setCellValueByColumnAndRow($columnIncident,92,$count_blocks);
				}
				// Get total DRB or Issue or incident
				$get_incident = pg_query($this->con,"SELECT count(drb_number) issue_count, occur_month,  DATE_PART('year',occur_date) occur_year
					FROM tbl_drb_tracking_ledger
					where occur_month = '$use_month' AND occur_date between '$date1' AND '$date2'  GROUP BY occur_month, DATE_PART('year',occur_date) order by occur_year;");
				$count_blocks = pg_num_rows($get_incident);
				if ($count_blocks >= 1) {
					while ($get_total = pg_fetch_array($get_incident)) {
						$sheet->setCellValueByColumnAndRow($columnIncident,93,$get_total['issue_count']);
					}
				}
				else{
					$sheet->setCellValueByColumnAndRow($columnIncident,93,$count_blocks);
				}
				// Get percentage
				$get_lotout_no = pg_query($this->con,"SELECT sum(lot_out) data_count, occur_month,  DATE_PART('year',occur_date) occur_year
					FROM tbl_drb_tracking_ledger
					where occur_month = '$use_month' AND occur_date between '$date1' AND '$date2'  GROUP BY occur_month, DATE_PART('year',occur_date) order by occur_year;");
				$lotout_no = pg_fetch_row($get_lotout_no);
				$get_incident_no = pg_query($this->con,"SELECT count(drb_number) issue_count, occur_month,  DATE_PART('year',occur_date) occur_year
					FROM tbl_drb_tracking_ledger
					where occur_month = '$use_month' AND occur_date between '$date1' AND '$date2'  GROUP BY occur_month, DATE_PART('year',occur_date) order by occur_year;");
				$incident_no = pg_fetch_row($get_incident_no);

				$lotout = $lotout_no['0'];
				$incident = $incident_no['0'];
				$percentage = ($lotout/$incident)*100;
				$percentage = $percentage."%";
				$sheet->setCellValueByColumnAndRow($columnIncident,94,$percentage);

				$columnIncident++;
		}
		// End Lot out/incident

		// affected Lot
		$generate_month_incident = pg_query($this->con,"SELECT distinct occur_month, DATE_PART('month',occur_date) occur_mnth,DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE $column_name between '$date1' AND '$date2' ORDER BY occur_year,occur_mnth ASC");

		$columnIncident = 3;
		while ($get_month_incident = pg_fetch_array($generate_month_incident)) {
			$use_month = $get_month_incident['occur_month'];
			$sheet->setCellValueByColumnAndRow($columnIncident,99,date('m',strtotime($get_month_incident['occur_month'])).'/'.date('y',strtotime($get_month_incident['occur_year'])));

			// $rowCountIncident = 92;
				// Get lot out count
				$get_affected = pg_query($this->con,"SELECT sum(affected_lots::integer) data_count, occur_month,  DATE_PART('year',occur_date) occur_year
					FROM tbl_drb_tracking_ledger
					where occur_month = '$use_month' AND occur_date between '$date1' AND '$date2'  GROUP BY occur_month, DATE_PART('year',occur_date) order by occur_year;");
				$count_blocks = pg_num_rows($get_affected);
				if ($count_blocks >= 1) {
					while ($get_total = pg_fetch_array($get_affected)) {
						$sheet->setCellValueByColumnAndRow($columnIncident,100,$get_total['data_count']);
					}
				}
				else{
					$sheet->setCellValueByColumnAndRow($columnIncident,100,$count_blocks);
				}
				// Get total DRB or Issue or incident
				$get_incident = pg_query($this->con,"SELECT count(drb_number) issue_count, occur_month,  DATE_PART('year',occur_date) occur_year
					FROM tbl_drb_tracking_ledger
					where occur_month = '$use_month' AND occur_date between '$date1' AND '$date2'  GROUP BY occur_month, DATE_PART('year',occur_date) order by occur_year;");
				$count_blocks = pg_num_rows($get_incident);
				if ($count_blocks >= 1) {
					while ($get_total = pg_fetch_array($get_incident)) {
						$sheet->setCellValueByColumnAndRow($columnIncident,101,$get_total['issue_count']);
					}
				}
				else{
					$sheet->setCellValueByColumnAndRow($columnIncident,101,$count_blocks);
				}
				// Get percentage
				$get_affected_no = pg_query($this->con,"SELECT sum(affected_lots::integer) data_count, occur_month,  DATE_PART('year',occur_date) occur_year
					FROM tbl_drb_tracking_ledger
					where occur_month = '$use_month' AND occur_date between '$date1' AND '$date2'  GROUP BY occur_month, DATE_PART('year',occur_date) order by occur_year;");
				$affected_no = pg_fetch_row($get_affected_no);
				$get_incident_no = pg_query($this->con,"SELECT count(drb_number) issue_count, occur_month,  DATE_PART('year',occur_date) occur_year
					FROM tbl_drb_tracking_ledger
					where occur_month = '$use_month' AND occur_date between '$date1' AND '$date2'  GROUP BY occur_month, DATE_PART('year',occur_date) order by occur_year;");
				$incident_no = pg_fetch_row($get_incident_no);

				$total_affected = $affected_no['0'];
				$incident = $incident_no['0'];
				$percentage = ($total_affected/$incident);
				$sheet->setCellValueByColumnAndRow($columnIncident,102,$percentage);

				$columnIncident++;
		}
		// End affected Lot


		// DRB by Man Rootcause
		$generate_month_MR = pg_query($this->con,"SELECT distinct occur_month, DATE_PART('month',occur_date) occur_mnth,DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE $column_name between '$date1' AND '$date2' ORDER BY occur_year,occur_mnth ASC");

		$columnMR = 3;
		while ($get_month_MR = pg_fetch_array($generate_month_MR)) {
			$use_month = $get_month_MR['occur_month'];
			$sheet->setCellValueByColumnAndRow($columnMR,107,date('m',strtotime($get_month_MR['occur_month'])).'/'.date('y',strtotime($get_month_MR['occur_year'])));

			$rowCount = 108;
			$get_blocks = pg_query($this->con,"SELECT * FROM tbl_blocks");
			while ($get_MR_data = pg_fetch_array($get_blocks)) {
				$block = $get_MR_data['blocks'];

				$get_data = pg_query($this->con,"SELECT count(b.blocks) data_count, b.blocks,a.occur_month, DATE_PART('year',a.occur_date) occur_year
					FROM tbl_blocks b
					right Join  tbl_drb_tracking_ledger a on a.block = b.blocks
					where a.block = '$block' AND a.occur_month = '$use_month' AND a.m5e1 = 'Man' AND a.occur_date between '$date1' AND '$date2'  GROUP BY b.blocks, a.occur_month, DATE_PART('year',a.occur_date) order by occur_year;");
				$count_blocks = pg_num_rows($get_data);
				if ($count_blocks >= 1) {
					while ($get_total = pg_fetch_array($get_data)) {
						$sheet->setCellValueByColumnAndRow($columnMR,$rowCount,$get_total['data_count']);
					}
				}
				else{
					$sheet->setCellValueByColumnAndRow($columnMR,$rowCount,$count_blocks);
				}
				$rowCount++;
			}
			$columnMR++;
		}
		// End Man Rootcause


		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"'); 
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
	}
	// End of Generation of Report Data
}
?>