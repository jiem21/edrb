<?php 
session_start();
if (isset($_SESSION['name'])) {
	header("Location: index");
}

include 'Controller/CustomerDashboardController.php';
	$settings = new customDashboard();
	$settings->get_SQA_setting();

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="assets/bootstraps/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/bootstraps/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="assets/plugins/iconic/font/css/open-iconic-bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/plugins/jquerytoast/jquery.toast.css">
	<link rel="stylesheet" type="text/css" href="assets/style/main.css">
	<title>Dashboard</title>
</head>
<body>
	<div class="container-fluid dashboard">
		<header class="top-affix">
			<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
				<a class="navbar-brand" href="dashboard">Electronic DRB System</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item active">
							<a class="nav-link" href="dashboard">Home</a>
						</li>
						<?php if ($settings->status == '0') { ?>
							<li class="nav-item active">
								<a class="nav-link" href="storage/User_Manual.pdf" target="_blank">User Manual</a>
							</li>
						<?php } ?>
					</ul>
					<a href="" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">LOGIN</a>
				</div>
			</nav>
		</header>

		<div class="content_dash">
			<!-- Section -->
			<section class="chart_data">
				<div class="chart">
					<canvas id="canvas"></canvas>
				</div>
			</section>
		</div>
		<footer>
			<div class="footer">
				<h5>S Y S D E V</h5>
				<label>INFORMATION TECHNOLOGY</label>
			</div>
		</footer>
	</div>
	<!-- End of Body Content -->
	<!-- Login Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">LOGIN</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<label class="error">ID no. and Password Does not match.</label>
					<label id="errorfunc"></label>
					<form method="post" id="login">
						<div class="form-group">
							<label for="exampleInputEmail1">ID No.</label>
							<input type="text" required="true" class="form-control" id="username" name="username" placeholder="Enter ID No.">
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Password</label>
							<input type="password" required="true" class="form-control" id="password" name="password" placeholder="Password">
						</div>
						<div class="credits">
							<h6><b>Copyright © 2019 S Y S D E V.</b> All rightsreserved.</h6>
						</div>
						<input type="hidden" name="function_login" value="login">
					</div>
					<div class="modal-footer">
						<div class="text-left">
							<a class="btn btn-info text-white" data-toggle="modal" data-target="#forgot_password_modal">Forgot Password</a>
						</div>
						<div class="prelog">	
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Login</button>
						</div>
						<div class="logproc">
							<button disabled class="btn btn-primary"><span class="oi oi-reload oi-reload-animate"></span> Processing</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Forgot Password Modal -->
	<div class="modal fade" id="forgot_password_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content" style="height: 389px;">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<label id="errorfunc"></label>
					<form method="post" id="forgotPassword">
						<div class="form-group">
							<label for="exampleInputEmail1">Email Address</label>
							<input type="email" required="true" class="form-control" id="email" name="email" placeholder="Email Address">
						</div>
						<input type="hidden" name="function_mail" value="forgotPassword">
					</div>
					<div class="modal-footer">
						<div class="prelog">	
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
						<div class="logproc">
							<button disabled class="btn btn-primary"><span class="oi oi-reload oi-reload-animate"></span> Processing</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
<script src="assets/script/jquery/jquery-3.4.1.min.js"></script>
<script src="assets/bootstraps/js/bootstrap.min.js"></script>
<script src="assets/script/ajax/ajax.js"></script>
<script src="assets/plugins/jquerytoast/jquery.toast.js"></script>
<script src="assets/plugins/chart/chart.min.js"></script>
<script src="assets/plugins/chart/utils.js"></script>
<script src="assets/script/custom.js"></script>
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
	$generate_label = pg_query($db->con,"SELECT distinct occur_month, DATE_PART('month',occur_date) occur_mnth, DATE_PART('year',occur_date) occur_year FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY occur_year,occur_mnth DESC");

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

			$generate_month = pg_query($db->con,"SELECT distinct DATE_PART('year',occur_date) occur_year ,DATE_PART('month',occur_date) occur_mnth,occur_month FROM tbl_drb_tracking_ledger WHERE occur_date between '$FY1' AND '$FY2' ORDER BY occur_year ,occur_mnth DESC");
			?>

			{
				label: '<?php echo ($m5e1['method_type']) ?>',
				backgroundColor: '<?php echo $get_color; ?>',
				data: [

				<?php
				while ($get_month = pg_fetch_array($generate_month)) {
					$use_month = $get_month['occur_month'];
					$get_data = pg_query($db->con,"SELECT count(b.method_type) data_count, b.method_type,a.occur_month, DATE_PART('year',a.occur_date) occur_year
						FROM tbl_m5e1 b
						right Join  tbl_drb_tracking_ledger a on a.m5e1 = b.method_type
						where a.m5e1 = '$get_m5e1' AND a.occur_month = '$use_month'  AND a.occur_date between '$FY1' AND '$FY2'  GROUP BY b.method_type, a.occur_month, DATE_PART('year',a.occur_date) order by  occur_year;");
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
</script>
<script type="text/javascript">
	$(document).ready(function() {
			$('#forgotPassword').on('submit',function(e) {
				e.preventDefault();
				var formData = new FormData($(this)[0]);
				$.ajax({
					method: "post",
					url: "Controller/execute.php",
					data: formData,
					cache:false,
					processData: false,
					contentType: false,
					success:function (data) {
						if (data == "Sent") {
							$.toast({
								heading: "Forgot Password",
								text: "Please wait for the email from the system.",
								showHideTransition: "slide",
								hideAfter : 2500,
								position: "top-right",
								icon: "success"
							});
							setTimeout(function(){window.location.reload()} , 2600);
						}
						else if(data =="No Email"){
							$.toast({
								heading: "Forgot Password",
								text: "Email invalid",
								showHideTransition: "slide",
								hideAfter : 3500,
								position: "top-right",
								icon: "error"
							});
						}
						else{
							$.toast({
								heading: "Forgot Password",
								text: data,
								showHideTransition: "slide",
								hideAfter : 3500,
								position: "top-right",
								icon: "error"
							});
							console.log(data);
						}
					},
					complete:function(){
						$('#update_rank').prop('disabled',false);
					}
				});
			});
		});
</script>
</html>