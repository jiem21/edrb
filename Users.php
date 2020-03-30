<?php 
$page_title = "User Management";
include_once 'header.php';
include_once 'Controller/settingsController.php';

$settings = new settings();
?>


<div class="container-fluid user_UI">
	<!-- User Management -->
	<div class="row">
		<div class="col text-left">
			<h3>User Management</h3>
			<label class="sr-only" for="inlineFormInputGroup">Username</label>
			<div class="input-group mb-2">
				<input type="text" class="form-control" id="user_key" placeholder="Search Name or by ID Number">
				<div class="input-group-prepend">
					<div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
				</div>
			</div>
		</div>
		<div class="col text-right">
			<a class="btn btn-primary add_user" data-toggle="modal" data-target="#AddUser"><span class="oi oi-plus"></span> Add User</a>
		</div>
	</div>
	<div id="userlist table-responsive">
		<div id="default_load"></div>
		<div id="search_load"></div>
	</div>

	<!-- Rank Management -->
	<div class="row">
		<div class="col text-left">
			<h3>Rank Management</h3>
		</div>
	</div>
	<div id="ranklist">
		<table class="table table-hover table-bordered">
			<thead class="thead-custom">
				<tr>
					<th>Approver</th>
					<th>Rank Level</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody class="text-center">
				<?php 
					$settings->Rank_level();
				?>
			</tbody>
		</table>
	</div>
</div>

<?php include_once 'footer.php';?>

<script src="assets/script/ajax/user_pagi.js"></script>
<!-- Add user -->
<div class="modal fade" id="AddUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Add User Account</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="errorfunc"></div>
				<form id="AddUserForm" method="post">
					<div class="container-fluid">
						<div class="row">
							<div class="col">
								<div id="errorfunc"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="ID_num">ID Number</label>
									<input class="form-control" type="text" name="ID_num" id="ID_num" required />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="first_name">First Name</label>
									<input class="form-control" type="text" name="first_name" id="first_name" required/>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="middle_name">Middle Name</label>
									<input class="form-control" type="text" name="middle_name" id="middle_name"/>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="last_name">Last Name</label>
									<input class="form-control" type="text" name="last_name" id="last_name" required/>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="email_add">Email Address</label>
									<input class="form-control" type="email" name="email_add" id="email_add" required/>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="acc_type">Account Type</label>
									<select class="form-control" name="acc_type" id="acc_type" required>
										<option selected disabled>Select Account Type</option>
										<option value="1">MFG</option>
										<option value="2">Admin</option>
										<option value="3">Super Admin</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="block">Block</label>
									<select class="form-control" name="block" id="block" disabled>
										<option selected disabled>Select Block</option>
										<option value="core">Core</option>
										<option value="vf">VF</option>
										<option value="sap">SAP</option>
										<option value="sf">SF</option>
										<option value="be">BE</option>
										<option value="fvi">FVI</option>
										<option value="others">Others</option>
									</select>
								</div>
								<input type="hidden" name="func" value="add_user">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<input type="submit" class="btn btn-primary" id="save_user" value="Save">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Update User -->
<div class="modal fade" id="UpdateUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Update Account</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="UpdateUserForm" method="post">
				<div class="modal-body" id="display_output"></div>
				<input type="hidden" name="func" value="update_user">
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<input type="submit" class="btn btn-primary" id="update_user" value="Update">
				</div>
			</form>
		</div>
	</div>
</div>


<!-- Update Rank -->

<div class="modal fade" id="updateRank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Update Rank</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="errorfunc"></div>
				<form id="updaterankForm" method="post">
					<div id="rankdata"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<input type="submit" class="btn btn-primary" id="update_rank" value="Save">
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#acc_type').on('change',function() {
			var type = $(this).val();
			if (type == 2 || type == 3) {
				$('#block').prop('disabled',true);
				$('#block').prop('selectedIndex',0);
			}
			else{
				$('#block').prop('disabled',false);
			}
		});
		// Get Rank data
		$('.updaterank').on('click',function() {
			var ranklevel = $(this).attr('id');
			var func = $(this).attr('data-func');
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{ranklevel:ranklevel, func:func},
				success:function(response) {
					$('#rankdata').html(response);
				}
			})
		})
		// Update Rank data
		$('#updaterankForm').submit(function(e) {
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
					$('#update_rank').prop('disabled',true);
				},
				success:function (data) {
					if (data == "success") {
						$.toast({
							heading: "Rank Maintenance",
							text: "Rank is successfully Update",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.href="Users"} , 2600);
					}
					else if(data =="empty"){
						$.toast({
							heading: "Rank Maintenance",
							text: "Please Fill up the field",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "warning"
						});
					}
					else{
						$("#errorfunc").css("display","block");
						$("#errorfunc").html(data);
						console.log(data);
					}
				},
				complete:function(){
					$('#update_rank').prop('disabled',false);
				}
			});
		});
		// Active Deactive User
		$('#default_load,#search_load').on('click','a.action_user',function() {
			var id_number = $(this).attr('data-id');
			var action_user = $(this).attr('data-action');
			console.log(action_user);
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{id_number:id_number, action_user:action_user},
				beforeSend:function() {
					$('body').css('overflow','hidden');
					$('.containers').css('display','flex');
				},
				success:function(response) {
					if (response == "activated") {
						$.toast({
							heading: "User Maintenance",
							text: "User is successfully Activated",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.href="Users"} , 1500);
					}
					else if(response =="deactivated"){
						$.toast({
							heading: "User Maintenance",
							text: "User is successfully Deactivated",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.href="Users"} , 1500);
					}
					else{
						$.toast({
							heading: "User Maintenance",
							text: "System Error please contact the SYSTEM DEVELOPER TO FIX THE ISSUE",
							showHideTransition: "slide",
							hideAfter : 5000,
							position: "top-right",
							icon: "error"
						});
						console.log(response);
					}
				},
				complete:function(){
					$('body').css('overflow','auto');
					$('.containers').css('display','none');
				}
			})
		});
		// Update User function
			// Show Modal view
		$('#default_load,#search_load').on('click','a.view_user',function() {
			var id_number = $(this).attr('data-id');
			var data_action = $(this).attr('data-action');
			// console.log(id_number);
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{id_number:id_number, data_action:data_action},
				success:function(response) {
					$('#display_output').html(response);
				}
			})
		});
			// Drop down function for update account
		$('#display_output').on('change','select#acc_type_update',function() {
			var type = $(this).val();
			if (type == 2 || type == 3) {
				$('#block_update').prop('disabled',true);
				$('#block_update').prop('selectedIndex',0);
			}
			else{
				$('#block_update').prop('disabled',false);
			}
		});
			// Submit Form
		$('#UpdateUserForm').on('submit',function(e) {
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
				$('body').css('overflow','hidden');
				$('.containers').css('display','flex');
			},
			success:function (data) {
				if (data == "success") {
					$.toast({
							heading: "Update Account",
							text: "Account is successfully update",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.href="Users"} , 2500);
				}
				else{
					$.toast({
							heading: "Update Account",
							text: "SOMETHING Went Wrong",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "waning"
						});
					console.log(data);
				}
			},
			complete:function(){
				$('body').css('overflow','auto');
				$('.containers').css('display','none');
			}
		});
		});
	});
</script>