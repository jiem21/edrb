<?php 
$page_title = "Maintenance";
include_once 'header.php';
include_once 'Controller/maintenanceController.php';

$main = new maintenance();
?>
<link rel="stylesheet" type="text/css" href="assets/style/datatable.css">
<style type="text/css">
	h3{
		color: #007bff;
	}
	.title-customer{
		padding: 20px 20px;
	}
</style>

<div class="content_settings">
	<div class="container-fluid">
		<div class="title-customer text-left">
			<div class="row">
				<div class="col-md-6">
					<h3>Block Maintenance</h3>
					<button class="btn btn-primary" data-toggle="modal" data-target="#addBlock">Add Block</button>
					<div class="row">
						<div class="col-md-10">
							<table class="table table-bordered" id="tbl_block">
								<thead>
									<th>#</th>
									<th>Blocks</th>
									<th>Colors</th>
									<th>Action</th>
								</thead>
								<tbody>
									<?php
									$main->Get_Block();
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<h3>Process Maintenance</h3>
					<button class="btn btn-primary" data-toggle="modal" data-target="#addProcess">Add Process</button>
					<div class="row">
						<div class="col-md-10">
							<table class="table table-bordered" id="tbl_process">
								<thead>
									<th>#</th>
									<th>Block Name</th>
									<th>Prorcess</th>
									<th>Action</th>
								</thead>
								<tbody>
									<?php
									$main->Get_Process();
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Add Process Modal -->
<div class="modal fade" id="addProcess" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Add Process</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
					<form id="AddProcess">
						<div class="modal-body">
							<div class="form-group">
								<input type="hidden" name="func" value="addprocess">
								<label for="block_name">Block Name</label>
								<select class="form-control" name="block_name">
									<option selected disabled>Select Block</option>
									<?php 
										$main->select_proc();
									 ?>
								</select>
							</div>
							<div class="form-group">
								<label>Process Name</label>
								<input type="text" class="form-control" id="proc_name" name="proc_name" placeholder="Process Name">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary" id="btn-addproc">Add Process</button>
						</div>
				</form>
			</div>
		</div>
	</div>

<!-- Add Block Modal -->
	<div class="modal fade" id="addBlock" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Add Block</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
					<form id="AddBlock">
						<div class="modal-body">
							<div class="form-group">
								<input type="hidden" name="func" value="addblock">
								<label for="block_name">Block Name</label>
								<input type="text" class="form-control" id="block_name" name="block_name" placeholder="Block Name">
							</div>
							<div class="form-group">
									<label>Color</label>
								<div class="row">
									<div class="col-md-4">
										<label for="new_pass">Red</label>
										<input type="text" class="form-control" id="red" name="red" placeholder="0 - 255">
									</div>
									<div class="col-md-4">
										<label for="new_pass">Green</label>
										<input type="text" class="form-control" id="green" name="green" placeholder="0 - 255">
									</div>
									<div class="col-md-4">
										<label for="new_pass">Blue</label>
										<input type="text" class="form-control" id="blue" name="blue" placeholder="0 - 255">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary" id="btn-addblock">Add Block</button>
						</div>
				</form>
			</div>
		</div>
	</div>

<?php include_once 'footer.php';?>
<script type="text/javascript">
	$('#tbl_block').DataTable();
	$('#tbl_process').DataTable();

	$('#AddBlock').submit(function(e) {
		e.preventDefault();
		var formData = new FormData($(this)[0]);
		$.ajax({
			method: "post",
			url: "Controller/execute.php",
			data: formData,
			cache:false,
			processData: false,
			contentType: false,
			beforeSend:function() {
				$('#btn-addblock').prop('disabled',true);
			},
			success:function (data) {
				if (data =='success') {
					$.toast({
						heading: "Blocks",
						text: "<b>Added A new block</b>",
						showHideTransition: "slide",
						hideAfter : 3500,
						position: "top-right",
						icon: "success"
					});
					setTimeout(function(){window.location.href="Maintenance"} , 3500);
				}
				else if(data =='failed'){
					$.toast({
						heading: "Block",
						text: "<b>Error Upon Saving the data.</b>",
						showHideTransition: "slide",
						hideAfter : 3500,
						position: "top-right",
						icon: "error"
					});
				}
				else{
					$.toast({
						heading: "RFC Number",
						text: "<b>"+ data +"</b>",
						showHideTransition: "slide",
						hideAfter : 3500,
						position: "top-right",
						icon: "error"
					});
					console.log(data);
				}
			},
			complete:function(){
				$('#btn-addblock').prop('disabled',false);
			}
		});
	});

	$('#AddProcess').submit(function(e) {
		e.preventDefault();
		var formData = new FormData($(this)[0]);
		$.ajax({
			method: "post",
			url: "Controller/execute.php",
			data: formData,
			cache:false,
			processData: false,
			contentType: false,
			beforeSend:function() {
				$('#btn-addproc').prop('disabled',true);
			},
			success:function (data) {
				if (data =='success') {
					$.toast({
						heading: "Process",
						text: "<b>Added A new process</b>",
						showHideTransition: "slide",
						hideAfter : 3500,
						position: "top-right",
						icon: "success"
					});
					setTimeout(function(){window.location.href="Maintenance"} , 3500);
				}
				else if(data =='failed'){
					$.toast({
						heading: "Block",
						text: "<b>Error Upon Saving the data.</b>",
						showHideTransition: "slide",
						hideAfter : 3500,
						position: "top-right",
						icon: "error"
					});
				}
				else{
					$.toast({
						heading: "RFC Number",
						text: "<b>"+ data +"</b>",
						showHideTransition: "slide",
						hideAfter : 3500,
						position: "top-right",
						icon: "error"
					});
					console.log(data);
				}
			},
			complete:function(){
				$('#btn-addproc').prop('disabled',false);
			}
		});
	});

	$('#tbl_block').on('click','button.btn-del-block',function() {
		var id = $(this).data('id');
		var block_action = $(this).attr('data-func');
		console.log(block_action);
		$.ajax({
			method: "post",
			url: "Controller/execute.php",
			data: {id:id, block_action:block_action},
			beforeSend:function() {
				$('button').prop('disabled',true);
			},
			success:function (data) {
				if (data =='success') {
					$.toast({
						heading: "Blocks",
						text: "<b>Deleted a Block</b>",
						showHideTransition: "slide",
						hideAfter : 3500,
						position: "top-right",
						icon: "success"
					});
					setTimeout(function(){window.location.href="Maintenance"} , 3500);
				}
				else if(data =='failed'){
					$.toast({
						heading: "Block",
						text: "<b>Error Upon deleting the data.</b>",
						showHideTransition: "slide",
						hideAfter : 3500,
						position: "top-right",
						icon: "error"
					});
				}
				else{
					$.toast({
						heading: "Block",
						text: "<b>"+ data +"</b>",
						showHideTransition: "slide",
						hideAfter : 3500,
						position: "top-right",
						icon: "error"
					});
					console.log(data);
				}
			},
			complete:function(){
				// $('.btn-del-block').prop('disabled',false);
			}
		});
	});

	$('#tbl_process').on('click','button.btn-del-process',function() {
		var id = $(this).data('id');
		var block_action = $(this).attr('data-func');
		console.log(block_action);
		$.ajax({
			method: "post",
			url: "Controller/execute.php",
			data: {id:id, block_action:block_action},
			beforeSend:function() {
				$('button').prop('disabled',true);
			},
			success:function (data) {
				if (data =='success') {
					$.toast({
						heading: "Process",
						text: "<b>Deleted a Process</b>",
						showHideTransition: "slide",
						hideAfter : 3500,
						position: "top-right",
						icon: "success"
					});
					setTimeout(function(){window.location.href="Maintenance"} , 3500);
				}
				else if(data =='failed'){
					$.toast({
						heading: "Process",
						text: "<b>Error Upon deleting the data.</b>",
						showHideTransition: "slide",
						hideAfter : 3500,
						position: "top-right",
						icon: "error"
					});
				}
				else{
					$.toast({
						heading: "Process",
						text: "<b>"+ data +"</b>",
						showHideTransition: "slide",
						hideAfter : 3500,
						position: "top-right",
						icon: "error"
					});
					console.log(data);
				}
			},
			complete:function(){
				// $('.btn-del-block').prop('disabled',false);
			}
		});
	});

</script>