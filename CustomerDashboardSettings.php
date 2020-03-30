<?php 
$page_title = "Customer Dashboard Settings";
include_once 'header.php';
include_once 'Controller/CustomerDashboardController.php';

$settings = new customDashboard();
$settings->get_SQA_setting();
?>
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
			<h3>Customer Dashboard Settings</h3>
		</div>

		<div class="customer-settings">
			<div class="row">
				<div class="col-md-4">
					<table class="table table-hover table-bordered">
						<thead class="thead-custom">
							<tr>
								<th>Number of Filtered DRB Number</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php $settings->count_list(); ?></td>
							</tr>
						</tbody>
					</table>
					<table class="table table-hover table-bordered">
						<thead class="thead-custom">
							<tr>
								<th>Settings Name</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $settings->desc; ?></td>
								<td><?php echo $settings->show_stat; ?></td>
								<td>
									<button type="button" id="toggle_settings" class="btn btn-toggle <?php echo $settings->class_active; ?>" data-toggle="button" data-function="SQA_settings" data-status="<?php echo $settings->show_stat;?>" aria-pressed="false" autocomplete="off" data-id="<?php echo $settings->id_set; ?>">
										<div class="handle"></div>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-8">
					<div id="default_customer_List_setting"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<label class="sr-only" for="inlineFormInputGroup">Search DRB Tracking Ledger</label>
					<div class="input-group mb-2">
						<input type="text" class="form-control" id="setting_ledger_key" placeholder="Search DRB Issue or by DRB Number">
						<div class="input-group-prepend">
							<div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
						</div>
					</div>
				</div>
				<div class="col-md">
					<h3>List of Tracking ledger</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div id="default_List_setting"></div>
					<div id="search_List_setting"></div>
				</div>
			</div>

		</div>
	</div>
</div>

<?php include_once 'footer.php';?>
<script src="assets/script/ajax/setting_dashboard.js"></script>
<script src="assets/script/ajax/customer_ledger_list.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
			// Search Module DRB Tracking Ledger
			$('#setting_ledger_key').on('keyup',function() {
				var search_ledger_settings = $(this).val();
				$.ajax({
					method:"post",
					url:"Controller/execute.php",
					data:{search_ledger_settings:search_ledger_settings},
					success:function(response) {
						if (response == "default") {
							$('#default_List_setting').css('display','block');
							$('#search_List_setting').css('display','none');
						}
						else{
							$('#default_List_setting').css('display','none');
							$('#search_List_setting').css('display','block');
							$('#search_List_setting').html(response);
						}
					}
				})
			});
			$('#toggle_settings').on('click',function() {
				var id = $(this).attr('data-id');
				var func = $(this).attr('data-function');
				var status = $(this).attr('data-status');
				$.ajax({
					method:"post",
					url:"Controller/execute.php",
					data:{id:id,func:func,status:status},
					dataType: "json",
					beforeSend:function() {
						if (status == 'OFF') {
							$('#toggle_settings').addClass('active');
						}
						else{
							$('#toggle_settings').removeClass('active');
						}
						
						$('#toggle_settings').prop('disabled',true);
					},
					success:function(response) {
						if (response.message == "success") {
							$.toast({
								heading: "Customer Settings",
								text: "<b>"+response.desc+"</b>",
								showHideTransition: "slide",
								hideAfter : 3500,
								position: "top-right",
								icon: "success"
							});
							setTimeout(function(){window.location.reload()} , 3500);
						}
						else{
							$.toast({
								heading: "Customer Setting",
								text: response,
								showHideTransition: "slide",
								hideAfter : 3500,
								position: "top-right",
								icon: "error"
							});
						}
					}
				})
			});
		});
	</script>