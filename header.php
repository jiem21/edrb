<?php 
session_start();
if (!isset($_SESSION['name'])) {
	header("Location: dashboard");
}
$name = $_SESSION['name'];
$acc_type = $_SESSION['type'];
$block = $_SESSION['block'];
$user_id = $_SESSION['user_id'];


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
	<link rel="stylesheet" type="text/css" href="assets/plugins/datepicker/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/style/main.css">
	<title><?php echo ($page_title == null ? "Dashboard" : $page_title); ?></title>
</head>
<body>
	<div class="main_loader">
		<div class="containers">
			<div></div>
			<div></div>
			<div></div>  
			<div></div>  
			<div></div>
			<div></div>  
		</div>
	</div>
	<header class="top-affix">
		<?php if ($acc_type == 3) { ?>
			<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
				<a class="navbar-brand" href="index">Electronic DRB System</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item ">
							<a class="nav-link <?php if ($page_title == 'Dashboard') : echo 'active'; endif ?>" href="index">Dashboard</a>
						</li>
						<li class="nav-item ">
							<a class="nav-link <?php if ($page_title == 'DRB Tracking Ledger List') : echo 'active'; endif ?>" href="DRBTracking">DRB Tracking Ledger</a>
						</li>
						<li class="nav-item ">
							<a class="nav-link <?php if ($page_title == 'DRB Analysis Report') : echo 'active'; endif ?>" href="DRBReport">Report</a>
						</li>
						<li class="nav-item <?php if ($page_title != 'Dashboard' AND $page_title != 'DRB Tracking Ledger List' AND $page_title != 'DRB Analysis Report') : echo 'active'; else : echo ''; endif ?> dropdown">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Settings</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="Users"><span class="oi oi-person"></span> User and Rank Management</a>
								<a class="dropdown-item" href="CustomerDashboardSettings"><span class="oi oi-pie-chart"></span> Customer Dashboard Settings</a>
								<a class="dropdown-item" href="DRBArchive"><span class="oi oi-box"></span> Archive Data</a>
								<a class="dropdown-item" href="Maintenance"><span class="oi oi-cog"></span> Maintenance</a>
								<a class="dropdown-item" data-toggle="modal" data-target="#ChangePassword"><span class="oi oi-key"></span> Change Password</a>
								<a class="dropdown-item" id="logout" data-func="logout"><span class="oi oi-power-standby"></span> LOGOUT</a>
							</div>
						</li>
					</ul>
					<label class="nav_name">Account User: <?php echo $name; ?></label>
					<label class="nav_name">Block: <?php echo strtoupper($block); ?></label>
				</div>
			</nav>
		<?php } elseif($acc_type == 2){ ?>
			<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
				<a class="navbar-brand" href="index">Electronic DRB System</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item <?php if ($page_title == 'Dashboard') : echo 'active'; endif ?>">
							<a class="nav-link" href="index">Dashboard</a>
						</li>
						<li class="nav-item <?php if ($page_title == 'DRB Tracking Ledger List') : echo 'active'; endif ?>">
							<a class="nav-link" href="DRBTracking">DRB Tracking Ledger</a>
						</li>
						<li class="nav-item <?php if ($page_title == 'DRB Analysis Report') : echo 'active'; endif ?>">
							<a class="nav-link" href="DRBReport">Report</a>
						</li>
						<li class="nav-item <?php if ($page_title != 'Dashboard' AND $page_title != 'DRB Tracking Ledger List' AND $page_title != 'DRB Analysis Report') : echo 'active'; else : echo ''; endif ?> dropdown">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Settings</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="Users"><span class="oi oi-person"></span> User and Rank Management</a>
								<!-- <a class="dropdown-item" href="CustomerDashboardSettings"><span class="oi oi-pie-chart"></span> Customer Dashboard Settings</a> -->
								<a class="dropdown-item" data-toggle="modal" data-target="#ChangePassword"><span class="oi oi-key"></span> Change Password</a>
								<a class="dropdown-item" id="logout" data-func="logout"><span class="oi oi-power-standby"></span> LOGOUT</a>
							</div>
						</li>
					</ul>
					<label class="nav_name">Account User: <?php echo $name; ?></label>
					<label class="nav_name">Block: <?php echo strtoupper($block); ?></label>
				</div>
			</nav>
		<?php } else{ ?>
			<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
				<a class="navbar-brand" href="index">Electronic DRB System</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item active">
							<a class="nav-link" href="index">Dashboard</a>
						</li>
						<li class="nav-item active dropdown">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">DRB Tracking Ledger</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="DRBTracking">All Tracking List</a>
								<a class="dropdown-item" href="DRBTrackingSection"><?php echo ucfirst($block);?> Tracking List</a>
							</div>
						</li>
						<li class="nav-item active">
							<a class="nav-link" href="DRBReport">Report</a>
						</li>
						<li class="nav-item active dropdown">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Settings</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" data-toggle="modal" data-target="#ChangePassword"><span class="oi oi-key"></span> Change Password</a>
								<a class="dropdown-item" id="logout" data-func="logout"><span class="oi oi-power-standby"></span> LOGOUT</a>
							</div>
						</li>
					</ul>
					<label class="nav_name">Account User: <?php echo $name; ?></label>
					<label class="nav_name">Block: <?php echo strtoupper($block); ?></label>
				</div>
			</nav>
		<?php } ?>

	</header>