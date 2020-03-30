<?php 
$page_title = "Dashboard";
include_once 'header.php'; 
?>

<div class="content_dash">
	<section class="chart_data">
		<div class="chart">
			<canvas id="canvas"></canvas>
		</div>
	</section>
</div>


<?php include_once 'footer.php';?>
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
// Graph of Internal DRB by 5M1E
	$generate_label = pg_query($db->con,"SELECT distinct DATE_PART('month',occur_date) ,occur_month, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY DATE_PART('month',occur_date), occur_year ASC");

	$generate_m5e1 = pg_query($db->con,"SELECT * FROM tbl_m5e1");
	?>

	var m5e1 = {
		labels: [
		<?php while ($month = pg_fetch_array($generate_label)) {
			echo "'".$month['occur_month']." ".$month['occur_year']."',";
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
	window.onload = function() {
		var ctx = document.getElementById('canvas').getContext('2d');
		window.myBar = new Chart(ctx, {
			type: 'bar',
			data: m5e1,
			options: {
				title: {
					display: true,
					text: 'DRB by 5M1E'
				},
				animation: {
					duration: 2000,
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
							suggestedMax: 30
						}
					}]
				}
			}
		});
	};

	// function chart() {
	// 	$.ajax({
	// 		success:function(){
	// 			var ctx = document.getElementById('canvas').getContext('2d');
	// 			window.myBar = new Chart(ctx, {
	// 				type: 'bar',
	// 				data: m5e1,
	// 				options: {
	// 					title: {
	// 						display: true,
	// 						text: 'DRB by 5M1E'
	// 					},
	// 					animation: {
	// 						duration: 2000,
	// 					},
	// 					tooltips: {
	// 						mode: 'index',
	// 						intersect: true
	// 					},
	// 					responsive: true,
	// 					scales: {
	// 						xAxes: [{
	// 							stacked: true,
	// 						}],
	// 						yAxes: [{
	// 							stacked: true,
	// 							ticks:{
	// 								beginAtZero: true,
	// 								precision:0,
	// 								suggestedMax: 30
	// 							}
	// 						}]
	// 					}
	// 				}
	// 			});
	// 		}
	// 	});
		
	// };

	// setInterval(function(){chart()} , 1000);
</script>