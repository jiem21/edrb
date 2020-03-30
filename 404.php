<?php 
$page_title = "Page not found";
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="assets/bootstraps/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/bootstraps/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="assets/plugins/iconic/font/css/open-iconic-bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/style/main.css">
	<title>404 Not Found</title>
	<style type="text/css">
		.notfound{
			text-align: center;
			vertical-align: middle;
			padding: 10% 10vw;
			min-height: 522px;
			font-family: sans-serif;
		}
		.notfound h1{
			font-size: 120px;
		}
	</style>
</head>
<body>
	<div class="container-fluid dashboard">
		<header class="top-affix">
			<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
				<a class="navbar-brand" href="dashboard">Electronic DRB System</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<!-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item active">
							<a class="nav-link" href="dashboard">Dashboard</a>
						</li>
					</ul>
					<a href="" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">LOGIN</a>
				</div> -->
			</nav>
		</header>

		<div class="container-fluid">
			<div class="row">
				<div class="col notfound">
					<h1>404</h1>
					<h3>PAGE NOT FOUND</h3>
				</div>
			</div>
		</div>
		<?php include_once 'footer.php'; ?>