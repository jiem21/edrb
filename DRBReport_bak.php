<?php 
$page_title = "DRB Analysis Report";
include 'header.php';
include 'Controller/CustomerDashboardController.php';

$settings = new customDashboard();
$settings->get_SQA_setting();
?>
<style type="text/css">
	fieldset{
		background-repeat:no-repeat;
		background-position:center;
		background-size:cover;
	}
	fieldset.analysis{
		border: unset !important;
	}
	fieldset.analysis .row{
		border-bottom: 1px solid #007bff;
	}
	fieldset.border{
		border: 1px solid #007bff !important;
	}
	.report_form fieldset div{
		padding: 20px;
	}
	legend{
		width:300px;
		padding:10px 10px;
		text-align: center;
		color: #007bff;
	}
	.chart{
		padding-bottom: 50px;
	}
	.btn-report{
		color: #ffffff !important;
	}
</style>
<section>
	<div class="container-fluid">
		<div class="report_generation_title text-left">
			<h3>DRB Tracking Ledger Analyzation and Generation of Report</h3>
		</div>
	</div>
	<div class="container-fluid">
		<fieldset class="analysis">
			<legend class="text-center">Analysis Report</legend>
			<div class="row">
				<div class="col-md-6">
					<div class="chart">
						<canvas id="canvas"></canvas>
					</div>
				</div>
				<div class="col-md-6">
					<div class="chart">
						<canvas id="canvas2"></canvas>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="chart">
						<canvas id="canvas3"></canvas>
					</div>
				</div>
				<div class="col-md-6">
					<div class="chart">
						<canvas id="canvas4"></canvas>
					</div>
				</div>
			</div>
			<?php if ($settings->status == '0') { ?>
				<div class="row">
					<div class="col-md-6">
						<div class="chart">
							<canvas id="canvas5"></canvas>
						</div>
					</div>
					<div class="col-md-6">
						<div class="chart">
							<canvas id="canvas6"></canvas>
						</div>
					</div>
				</div>
			<?php } ?>
		</fieldset>
	</div>
	<div class="report_form">
		<fieldset class="border">
			<legend class="text-center">Generate Report Data</legend>
			<div>
				<div class="form-group">
					<select class="form-control" name="column_name" id="column_name">
						<option selected disabled>Select Column Date for generation</option>
						<option value="occur_date">Occurence Date</option>
						<option value="drb_date">DRB Date</option>
					</select>
				</div>
				<div class="form-group">
					<label for="Date_start_report">Date of Start</label>
					<input type="text" class="form-control" disabled id="Date_start_report" name="Date_start_report" aria-describedby="emailHelp" placeholder="Pick Date for the start of report">
				</div>
				<div class="form-group">
					<label for="Date_end_report">Date of End</label>
					<input type="text" class="form-control" disabled id="Date_end_report" name="Date_end_report" placeholder="Pick Date for the end of report">
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<a id="generate_Tracking" class="form-control btn btn-success btn-report disabled" data-action="Tracking"><span class="oi oi-document"></span> Generate Tracking Ledger List</a>
						</div>
						<div class="col-md-6">
							<a id="generate_report_data" class="form-control btn btn-success btn-report disabled" data-action="ReportData"><span class="oi oi-document"></span> Generate Report Data</a>
						</div>
					</div>
					
				</div>
			</div>
		</fieldset>
	</div>
</section>

<?php include_once 'footer.php';?>
<link rel="stylesheet" type="text/css" href="assets/plugins/datepicker/jquery-ui.css">
<script src="assets/plugins/datepicker/jquery-1.12.4.js"></script>
<script src="assets/plugins/datepicker/jquery-ui.js"></script>
<script src="assets/plugins/chart/chart.min.js"></script>
<script src="assets/plugins/chart/utils.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$( "#Date_start_report" ).datepicker({
			
		});
		$( "#Date_end_report" ).datepicker({
			
		});

		$('#column_name').on("change",function() {
			$('#Date_start_report').prop('disabled',false);
			$('#Date_start_report').val('');
			$('#Date_end_report').val('');
			$('#Date_end_report').prop('disabled',true);
			$('#generate_Tracking').removeClass('disabled');
			$('#generate_report_data').removeClass('disabled');
			$('#generate_Tracking').addClass('disabled');
			$('#generate_report_data').addClass('disabled');
		});

		$('#Date_start_report').on("change",function() {
			var date1 = $(this).val();
			$('#Date_end_report').prop('disabled',false);
			$("#Date_end_report").datepicker("option", "minDate", date1);
		});

		$('#Date_end_report').on("change",function() {
			var column_name = $('#column_name').find('option:selected').val();
			var action = $('#generate_Tracking').attr('data-action');
			var action2 = $('#generate_report_data').attr('data-action');
			var date1 = $('#Date_start_report').val();
			var date2 = $(this).val();
			var link = "Controller/executeExcel?action="+action+"&date1="+date1+"&date2="+date2+"&column_name="+column_name;
			var link2 = "Controller/executeExcel?action="+action2+"&date1="+date1+"&date2="+date2+"&column_name="+column_name;
			$('#generate_Tracking').attr('href',link);
			$('#generate_report_data').attr('href',link2);
			$('#generate_Tracking').removeClass('disabled');
			$('#generate_report_data').removeClass('disabled');

		});


	});
</script>

<script>
	<?php
	include_once 'Controller/database.php';
	$db = new dbh();

	$prepare_FY1 = "04/01/".date("Y");
	$prepare_FY2 = "03/31/".date("Y");
	$current_date = date("m/d/Y");
	if (strtotime($current_date) > strtotime($prepare_FY2)) {
		$FY1 = str_replace("/", "-", $prepare_FY1);
		$FY2 = date("m-d-Y", strtotime(date("m/d/Y", strtotime($prepare_FY2)) . " + 1 year"));
		$year =  date("Y")." - ".date("Y", strtotime(date("Y", strtotime($prepare_FY2)) . " + 1 year"));
	}
	else{
		$FY2 = str_replace("/", "-", $prepare_FY2);
		$FY1 = date("m-d-Y", strtotime(date("m/d/Y", strtotime($prepare_FY1)) . " - 1 year"));
		$year = date("Y", strtotime(date("Y", strtotime($prepare_FY2)) . " - 1 year")) ." - ". date("Y");
	}

	// Graph of Internal DRB by Block
	$generate_label = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date) ,occur_month, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), occur_year ASC");
	$generate_label2 = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date) ,occur_month, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), occur_year ASC");

	$generate_block = pg_query($db->con,"SELECT * FROM tbl_blocks");
	?>

	var DRB_BY_BLOCK = {
		labels: [
		<?php while ($month = pg_fetch_array($generate_label)) {
			echo "'".date('m',strtotime($month['occur_month']))."/".date('y',strtotime($month['occur_year']))."',";
		} ?> ],
		datasets: [
		{
			label: 'Target',
			backgroundColor: 'rgba(255,0,0,1)',
			yAxisID: 'y-axis-2',
			type: 'line',
			fill: false,
			pointRadius: 7,
			pointHoverRadius:7,
			lineTension: 0,
			data: [
			<?php while ($target = pg_fetch_array($generate_label2)) {
				echo "'4',";
			}
			?>
			]
		},

		<?php 
		while ($blocks = pg_fetch_array($generate_block)) {
			$get_block = $blocks['blocks'];
			$get_color = $blocks['color'];

			$generate_month = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date), DATE_PART('year',occur_date) ,occur_month FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), DATE_PART('year',occur_date) ASC");
			?>

			{
				label: '<?php echo strtoupper($blocks['blocks']) ?>',
				backgroundColor: '<?php echo $get_color; ?>',
				data: [

				<?php
				while ($get_month = pg_fetch_array($generate_month)) {
					$use_month = $get_month['occur_month'];
					$get_data = pg_query($db->con,"SELECT count(b.blocks) data_count, b.blocks,a.occur_month, DATE_PART('month',a.occur_date) occur_months, DATE_PART('year',a.occur_date) occur_year
						FROM tbl_blocks b
						left Join  tbl_drb_tracking_ledger a on a.block = b.blocks
						where a.block = '$get_block' AND a.occur_month = '$use_month' AND a.occur_date between '$FY1' AND '$FY2' GROUP BY b.blocks, a.occur_month, DATE_PART('month',a.occur_date), DATE_PART('year',a.occur_date) order by occur_months, occur_year;");
					$count_blocks = pg_num_rows($get_data);
					if ($count_blocks >= 1) {
						while ($get_total = pg_fetch_array($get_data)) {
							echo "'".$get_total['data_count']."',";
						}
					}
					else{
						echo "'".$count_blocks."',";
					}
					
				}
				?>
				]
			},
		<?php } ?>
		]
	};


	<?php
	// Graph of Internal DRB by 5M1E
	$generate_label = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date) ,occur_month, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), occur_year ASC");

	$generate_m5e1 = pg_query($db->con,"SELECT * FROM tbl_m5e1");
	?>

	var m5e1 = {
		labels: [
		<?php while ($month = pg_fetch_array($generate_label)) {
			echo "'".date('m',strtotime($month['occur_month']))."/".date('y',strtotime($month['occur_year']))."',";
		} ?> ],
		datasets: [

		<?php 
		while ($m5e1 = pg_fetch_array($generate_m5e1)) {
			$get_m5e1 = $m5e1['method_type'];
			$get_color = $m5e1['color'];

			$generate_month = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date), DATE_PART('year',occur_date) ,occur_month FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), DATE_PART('year',occur_date) ASC");
			?>

			{
				label: '<?php echo ($m5e1['method_type']) ?>',
				backgroundColor: '<?php echo $get_color; ?>',
				data: [

				<?php
				while ($get_month = pg_fetch_array($generate_month)) {
					$use_month = $get_month['occur_month'];
					$get_data = pg_query($db->con,"SELECT count(b.method_type) data_count, b.method_type,a.occur_month, DATE_PART('month',a.occur_date) occur_months, DATE_PART('year',a.occur_date) occur_year
						FROM tbl_m5e1 b
						right Join  tbl_drb_tracking_ledger a on a.m5e1 = b.method_type
						where a.m5e1 = '$get_m5e1' AND a.occur_month = '$use_month'  AND a.occur_date between '$FY1' AND '$FY2'  GROUP BY b.method_type, a.occur_month, DATE_PART('month',a.occur_date), DATE_PART('year',a.occur_date) order by occur_months, occur_year;");
					$count_blocks = pg_num_rows($get_data);
					if ($count_blocks >= 1) {
						while ($get_total = pg_fetch_array($get_data)) {
							echo "'".$get_total['data_count']."',";
						}
					}
					else{
						echo "'".$count_blocks."',";
					}
					
				}
				?>
				]
			},
		<?php } ?>
		]
	};


	<?php
	// Graph of Internal DRB by Rank Level
	$generate_label = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date) ,occur_month, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), occur_year ASC");

	$generate_rank = pg_query($db->con,"SELECT * FROM tbl_closing_approval order by rank asc");
	?>

	var rank_level = {
		labels: [
		<?php while ($month = pg_fetch_array($generate_label)) {
			echo "'".date('m',strtotime($month['occur_month']))."/".date('y',strtotime($month['occur_year']))."',";
		} ?> ],
		datasets: [
		<?php 
		while ($rank = pg_fetch_array($generate_rank)) {
			$get_rank = $rank['rank'];
			$get_color = $rank['color'];

			$generate_month = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date), DATE_PART('year',occur_date) ,occur_month FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), DATE_PART('year',occur_date) ASC");
			?>

			{
				label: 'Rank <?php echo strtoupper($rank['rank']) ?>',
				backgroundColor: '<?php echo $get_color; ?>',
				data: [

				<?php
				while ($get_month = pg_fetch_array($generate_month)) {
					$use_month = $get_month['occur_month'];
					$get_data = pg_query($db->con,"SELECT count(b.rank) data_count, b.rank,a.occur_month, DATE_PART('month',a.occur_date) occur_months, DATE_PART('year',a.occur_date) occur_year
						FROM tbl_closing_approval b
						left Join  tbl_drb_tracking_ledger a on a.rank = b.rank
						where a.rank = '$get_rank' AND a.occur_month = '$use_month'  AND a.occur_date between '$FY1' AND '$FY2'  GROUP BY b.rank, a.occur_month, DATE_PART('month',a.occur_date), DATE_PART('year',a.occur_date) order by occur_months, occur_year;");
					$count_blocks = pg_num_rows($get_data);
					if ($count_blocks >= 1) {
						while ($get_total = pg_fetch_array($get_data)) {
							echo "'".$get_total['data_count']."',";
						}
					}
					else{
						echo "'".$count_blocks."',";
					}
					
				}
				?>
				]
			},
		<?php } ?>
		]
	};

	<?php
	// Graph of Internal DRB Closure
	$generate_label = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date) ,occur_month, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), occur_year ASC");

	$generate_label2 = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date) ,occur_month, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), occur_year ASC");

	$generate_label3 = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date) ,occur_month, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), occur_year ASC");

	$generate_label4 = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date) ,occur_month, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), occur_year ASC");
	?>

	var closure = {
		labels: [
		<?php while ($month = pg_fetch_array($generate_label)) {
			echo "'".date('m',strtotime($month['occur_month']))."/".date('y',strtotime($month['occur_year']))."',";
		} ?> ],
		datasets: [
		<?php 
		$generate_month = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date), DATE_PART('year',occur_date) ,occur_month FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), DATE_PART('year',occur_date) ASC");
		?>
		{
			label: 'Total DRB',
			backgroundColor: 'rgba(55,127,199,1)',
			type: 'bar',
			fill: false,
			lineTension: 0,
			data: [
			<?php 
			while ($get_data4 = pg_fetch_array($generate_label4)) {
				$use_month = $get_data4['occur_month'];
				$get_open = pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE drb_status = 1 and occur_month = '$use_month'");
				$get_close = pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE drb_status = 0 and occur_month = '$use_month'");

				$total_open = pg_num_rows($get_open);
				$total_close = pg_num_rows($get_close);
				$total = $total_open + $total_close;
				echo "'".$total."',";
			}
			?>
			]
		},
		{
			label: 'Total Open',
			backgroundColor: 'rgba(203,164,59,1)',
			type: 'bar',
			fill: false,
			lineTension: 0,
			data: [
			<?php 
			while ($get_data2 = pg_fetch_array($generate_label2)) {
				$use_month = $get_data2['occur_month'];
				$get_open = pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE drb_status = 1 and occur_month = '$use_month'");
				$total_open = pg_num_rows($get_open);
				echo "'".$total_open."',";
			}
			?>
			]
		},
		// {
		// 	label: 'Total Close',
		// 	backgroundColor: 'rgba(1,164,59,1)',
		// 	type: 'bar',
		// 	fill: false,
		// 	lineTension: 0,
		// 	data: [
		// 	<?php 
		// 	while ($get_data3 = pg_fetch_array($generate_label3)) {
		// 		$use_month = $get_data3['occur_month'];
		// 		$get_close = pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE drb_status = 0 and occur_month = '$use_month'");
		// 		$total_close = pg_num_rows($get_close);
		// 		echo "'".$total_close."',";
		// 	}
		// 	?>
		// 	]
		// },
				{
			label: 'Closure',
			backgroundColor: 'rgba(255,0,0,1)',
			yAxisID: 'y-axis-2',
			type: 'line',
			pointRadius: 7,
			pointHoverRadius:7,
			fill: false,
			lineTension: 0,
			data: [
			<?php 
			while ($get_data = pg_fetch_array($generate_month)) {
				$use_month = $get_data['occur_month'];
				$get_open = pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE drb_status = 1 and occur_month = '$use_month'");
				$get_close = pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger WHERE drb_status = 0 and occur_month = '$use_month'");

				$total_open = pg_num_rows($get_open);
				$total_close = pg_num_rows($get_close);
				$total = $total_open + $total_close;
				$percentage = ($total_close / $total) * 100;
				$percentage = number_format((float)$percentage, 2, '.', '');
				echo "'".$percentage."',";
			}
			?>

			]
		},


		]
	};


	<?php
	// Graph of Internal DRB Recurrence
	$generate_label = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date) ,occur_month, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), occur_year ASC");
	$generate_label2 = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date) ,occur_month, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), occur_year ASC");

	$generate_m5e1 = pg_query($db->con,"SELECT * FROM tbl_m5e1");
	?>

	var DRB_Recurrence = {
		labels: [
		<?php while ($month = pg_fetch_array($generate_label)) {
			echo "'".date('m',strtotime($month['occur_month']))."/".date('y',strtotime($month['occur_year']))."',";
		} ?> ],
		datasets: [
		{
			label: 'Target',
			backgroundColor: 'rgba(255,0,0,1)',
			yAxisID: 'y-axis-2',
			type: 'line',
			pointRadius: 7,
			pointHoverRadius:7,
			fill: false,
			lineTension: 0,
			data: [
			<?php 
			while ($month2 = pg_fetch_array($generate_label2)) {
				$occur_month = $month2['occur_month'];
				$prepare_query1 = pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger where issue_type = 'Recurrence' and occur_month = '$occur_month';");
				$prepare_query2 = pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger where occur_month = '$occur_month';");

				$Recurrence = pg_num_rows($prepare_query1);
				$total = pg_num_rows($prepare_query2);
				$percentage = ($Recurrence / $total) * 100;
				$percentage = number_format((float)$percentage, 2, '.', '');
				echo "'".$percentage."',";
			}
			?>
			]
		},

		<?php 
		while ($m5e1 = pg_fetch_array($generate_m5e1)) {
			$get_m5e1 = $m5e1['method_type'];
			$get_color = $m5e1['color'];

			$generate_month = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date), DATE_PART('year',occur_date) ,occur_month FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), DATE_PART('year',occur_date) ASC");
			?>

			{
				label: '<?php echo ($get_m5e1) ?>',
				backgroundColor: '<?php echo $get_color; ?>',
				data: [

				<?php
				while ($get_month = pg_fetch_array($generate_month)) {
					$use_month = $get_month['occur_month'];
					$get_data = pg_query($db->con,"SELECT count(b.method_type) data_count, b.method_type,a.occur_month, DATE_PART('month',a.occur_date) occur_months, DATE_PART('year',a.occur_date) occur_year
						FROM tbl_m5e1 b
						right Join  tbl_drb_tracking_ledger a on a.m5e1 = b.method_type
						where a.m5e1 = '$get_m5e1' AND a.occur_month = '$use_month' AND lower(a.issue_type) = lower('Recurrence') AND a.occur_date between '$FY1' AND '$FY2'  GROUP BY b.method_type, a.occur_month, DATE_PART('month',a.occur_date), DATE_PART('year',a.occur_date) order by occur_months, occur_year;");
					$count_blocks = pg_num_rows($get_data);
					if ($count_blocks >= 1) {
						while ($get_total = pg_fetch_array($get_data)) {
							echo "'".$get_total['data_count']."',";
						}
					}
					else{
						echo "'".$count_blocks."',";
					}
					
				}
				?>
				]
			},
		<?php } ?>
		]
	};

	<?php
	// Graph of Internal DRB by Man Rootcause
	$generate_label = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date) ,occur_month, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), occur_year ASC");

	$generate_block = pg_query($db->con,"SELECT * FROM tbl_blocks");
	?>

	var Man_rootcause = {
		labels: [
		<?php while ($month = pg_fetch_array($generate_label)) {
			echo "'".date('m',strtotime($month['occur_month']))."/".date('y',strtotime($month['occur_year']))."',";
		} ?> ],
		datasets: [
		<?php 
		while ($blocks = pg_fetch_array($generate_block)) {
			$get_block = $blocks['blocks'];
			$get_color = $blocks['color'];

			$generate_month = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date), DATE_PART('year',occur_date) ,occur_month FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), DATE_PART('year',occur_date) ASC");
			?>

			{
				label: '<?php echo strtoupper($blocks['blocks']) ?>',
				backgroundColor: '<?php echo $get_color; ?>',
				data: [

				<?php
				while ($get_month = pg_fetch_array($generate_month)) {
					$use_month = $get_month['occur_month'];
					$get_data = pg_query($db->con,"SELECT count(b.blocks) data_count, b.blocks,a.occur_month, DATE_PART('month',a.occur_date) occur_months, DATE_PART('year',a.occur_date) occur_year
						FROM tbl_blocks b
						left Join  tbl_drb_tracking_ledger a on a.block = b.blocks
						where a.block = '$get_block' AND a.occur_month = '$use_month' AND a.m5e1 = 'Man' AND a.occur_date between '$FY1' AND '$FY2' GROUP BY b.blocks, a.occur_month, DATE_PART('month',a.occur_date), DATE_PART('year',a.occur_date) order by occur_months, occur_year;");
					$count_blocks = pg_num_rows($get_data);
					if ($count_blocks >= 1) {
						while ($get_total = pg_fetch_array($get_data)) {
							echo "'".$get_total['data_count']."',";
						}
					}
					else{
						echo "'".$count_blocks."',";
					}
					
				}
				?>
				]
			},
		<?php } ?>
		]
	};

	window.onload = function() {
		var ctx = document.getElementById('canvas').getContext('2d');
		window.myBar = new Chart(ctx, {
			type: 'bar',
			data: DRB_BY_BLOCK,
			options: {
				legend: {
					position: 'bottom'
				},
				title: {
					display: true,
					text: 'Internal DRB by Block'
				},
				tooltips: {
					mode: 'index',
					intersect: true
				},
				responsive: true,
				scales: {
					xAxes: [{
						stacked: true,
					}],
					yAxes: [
					{
						stacked: true,
						ticks:{
							beginAtZero: true,
							precision:0,
							suggestedMax: 10
						}
					},
					{
						display: true,
						position: 'right',
						id: 'y-axis-2',
						gridLines: {
							drawOnChartArea: false
						},
						ticks:{
							precision:0,
							beginAtZero: true,
							suggestedMax: 10
						}
					}
					]
				}
			}
		});

		var ctx2 = document.getElementById('canvas2').getContext('2d');
		window.myBar2 = new Chart(ctx2, {
			type: 'bar',
			data: m5e1,
			options: {
				legend: {
					position: 'bottom'
				},
				title: {
					display: true,
					text: 'DRB by 5M1E'
				},
				tooltips: {
					mode: 'index',
					intersect: true
				},
				responsive: true,
				scales: {
					xAxes: [{
						stacked: true,
					}],
					yAxes: [{
						stacked: true,
						ticks:{
							beginAtZero: true,
							precision:0,
							suggestedMax: 10
						}
					}
					]
				}
			}
		});
		var ctx3 = document.getElementById('canvas3').getContext('2d');
		window.myBar3 = new Chart(ctx3, {
			type: 'bar',
			data: rank_level,
			options: {
				legend: {
					position: 'bottom'
				},
				title: {
					display: true,
					text: 'DRB By Rank Level'
				},
				tooltips: {
					mode: 'index',
					intersect: true
				},
				responsive: true,
				scales: {
					xAxes: [{
						stacked: true,
					}],
					yAxes: [
					{
						stacked: true,
						ticks:{
							beginAtZero: true,
							precision:0,
							suggestedMax: 10
						}
					}
					]
				}
			}
		});

		var ctx4 = document.getElementById('canvas4').getContext('2d');
		window.myBar4 = new Chart(ctx4, {
			type: 'bar',
			data: closure,
			options: {
				legend: {
					position: 'bottom'
				},
				title: {
					display: true,
					text: 'DRB Closure'
				},
				tooltips: {
					mode: 'index',
					intersect: true
				},
				responsive: true,
				scales: {
					xAxes: [{
						stacked: false,
					}],
					yAxes: [{
						ticks:{
							beginAtZero: true,
							suggestedMax: 10
						}
					}
					,
					{
						display: true,
						position: 'right',
						id: 'y-axis-2',
						gridLines: {
							drawOnChartArea: false
						},
						ticks:{
							callback: function(tick) {
								return tick.toString() + '%';
							},
							beginAtZero: true,
							suggestedMax: 100
						}
					}
					]
				}
			}
		});

		var ctx5 = document.getElementById('canvas5').getContext('2d');
		window.myBar5 = new Chart(ctx5, {
			type: 'bar',
			data: DRB_Recurrence,
			options: {
				legend: {
					position: 'bottom'
				},
				title: {
					display: true,
					text: 'DRB Re-occurrence'
				},
				tooltips: {
					mode: 'index',
					intersect: true
				},
				responsive: true,
				scales: {
					xAxes: [{
						stacked: true,
					}],
					yAxes: [{
						stacked: true,
						ticks:{
							beginAtZero: true,
							precision:0,
							suggestedMax: 10
						}
					}
					,
					{
						display: true,
						position: 'right',
						id: 'y-axis-2',
						gridLines: {
							drawOnChartArea: false
						},
						ticks:{
							callback: function(tick) {
								return tick.toString() + '%';
							},
							beginAtZero: true,
							suggestedMax: 10
						}
					}
					]
				}
			}
		});

		var ctx6 = document.getElementById('canvas6').getContext('2d');
		window.myBar6 = new Chart(ctx6, {
			type: 'bar',
			data: Man_rootcause,
			options: {
				legend: {
					position: 'bottom'
				},
				title: {
					display: true,
					text: 'No. of MAN DRB by rootcause'
				},
				tooltips: {
					mode: 'index',
					intersect: true
				},
				responsive: true,
				scales: {
					xAxes: [{
						stacked: true,
					}],
					yAxes: [{
						stacked: true,
						ticks:{
							beginAtZero: true,
							precision:0,
							suggestedMax: 10
						}
					}
					]
				}
			}
		});
	};
</script>