<?php 
$page_title = "Archive Files";
include_once 'header.php';
include_once 'Controller/database.php';
$db = new dbh();
?>
<div class="main-container">

	<?php 
		$check_data = pg_query($db->con,"SELECT * FROM tbl_drb_tracking_ledger_delete");
		$count = pg_num_rows($check_data);

		if ($count >= 1) { 
	?>
	<section class="archive-list">
		<div class="header_list">
			<div class="row">
				<div class="col-md-8">
					<h4 style="color:#007bff;">DRB Archive List</h4>
					<label class="sr-only" for="inlineFormInputGroup">Search DRB Tracking Ledger</label>
					<div class="input-group mb-2">
						<select class="form-control" name="column_name" id="column_name">
							<option selected disabled>Select Column Data to search</option>
							<option value="occur_month" data-fields="text">Month</option>
							<option value="work_week" data-fields="text">Work Week</option>
							<option value="drb_date" data-fields="date">DRB Date</option>
							<option value="drb_number" data-fields="text">DRB Number</option>
							<option value="rfc_no" data-fields="text">RFC Number</option>
							<option value="drb_issue" data-fields="text">DRB Issue</option>
							<option value="block" data-fields="text">Block</option>
							<option value="process" data-fields="text">Process Affected</option>
							<option value="rank" data-fields="rank_level">Rank</option>
							<option value="drb_status" data-fields="drop">Issue Status</option>
							<option value="m5e1" data-fields="text">5M1E</option>
						</select>
						<select class="form-control drop" name="status_issue" id="status_issue" style="display:none;">
							<option selected disabled>Select DRB Status Issue</option>
							<option value="1">Open Issue</option>
							<option value="0">Closed Issue</option>
						</select>
						<select class="form-control ranklvl" name="rank_level" id="rank_level" style="display:none;">
							<option selected disabled>Select Rank Level</option>
							<option value="1">Rank Level 1</option>
							<option value="2">Rank Level 2</option>
							<option value="3">Rank Level 3</option>
							<option value="4">Rank Level 4</option>
							<option value="5">Rank Level 5</option>
						</select>
						<input class="form-control date_picker_field" style="display: none;" type="text" id="date_search" placeholder="Pick Date">
						<input class="form-control date_picker_field" style="display: none;" type="text" id="date_search2" placeholder="Pick Date" disabled>
						<input type="text" class="form-control text" id="archive_key" placeholder="Search" disabled>
						<div class="input-group-prepend">
							<div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="Archive_drb">
			<div id="default_load_archive"></div>
			<div id="search_load_archive"></div>
		</div>
	</section>
	<?php 
		}
		else{ 
	?>
	<section class="no_data">
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="img_style"></div>
				<h2 style="color:#007bff;">Archive Files is Empty</h2>
			</div>
		</div>
	</section>
	<?php	}

	?>
</div>


<!-- Notification for archive -->
<div class="modal fade" id="retrieve_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle"><span class="oi oi-trash"></span>Retrieve Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<h6>Are you sure you want to retrieve this DRB#? <label id="drb_num_display"></label></h6>
				<h6>This will be the new DRB# of the data. <label id="new_drb_num_display"></label></h6>
				<form id="Retrieve">
					<div id="data_gathered">
						<input type="hidden" name="new_drb" id="new_drb">
						<input type="hidden" name="id_drb" id="id_drb">
						<input type="hidden" name="func" id="function" value="retrieve_issue">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
					<button type="submit" class="btn btn-danger">Yes</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include_once 'footer.php';  ?>
<script src="assets/script/ajax/archive_pagi.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#default_load_archive, #search_load_archive').on("click",'#prepare_notif',function() {
			var drb_no = $(this).attr('data-id');
			var iddrb = $(this).attr('data-iddrb');
			var label = "<bold class='display_drb'>"+drb_no+"</bold>"
			var func = $(this).attr('data-fun');
			var block = $(this).attr('data-block');
			var drb_date = $(this).attr('data-drbdate');
			$.ajax({
				method:"post",
				url:"Controller/generateDRB.php",
				data:{drb_no:drb_no, iddrb:iddrb, func:func, block:block, drb_date:drb_date},
				success:function(response){
					$('#drb_num_display .display_drb').remove();
					$('#new_drb_num_display .display_new').remove();
					$('#drb_num_display').append(label);
					$('#new_drb_num_display').append("<bold class='display_new'>"+response+"</bold>");
					$('#new_drb').val(response);
					$('#id_drb').val(iddrb);
				}
			});
		});

		$('#Retrieve').submit(function(e) {
			e.preventDefault();
			var new_drb = $('#new_drb').val();
			var id_drb = $('#id_drb').val();
			var func = $('#function').val();
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{new_drb:new_drb, id_drb:id_drb, func:func},
				success:function(response){
					if (response == "success") {
						$.toast({
							heading: "Retrieve Data",
							text: "Data is successfully retrieve",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.href="DRBArchive"} , 3500);

					}else{
						$.toast({
							heading: "Retrieve Data",
							text: response,
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "warning"
						});
						console.log(response);
					}
				}
			});
		});

		$('#column_name').on('change',function() {
			var column = $(this).val();
			var a = $(this).find('option:selected').attr('data-fields');
			$('#archive_key').prop('disabled',false)
			$('#archive_key').val('');
			$('input').val('');
			$('#date_search2').prop('disabled',true);
			var search_archive = $('#archive_key').val();
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{search_archive:search_archive},
				success:function(response){
					if (response == "default") {
						$('#default_load_archive').css('display','block');
						$('#search_load_archive').css('display','none');
						if (a == 'text') {
							$('.text').css('display','block');
							$('.drop').css('display','none');
							$('.date_picker_field').css('display','none');
							$('.ranklvl').css('display','none');
						}
						else if(a == 'drop'){
							$('.text').css('display','none');
							$('.drop').css('display','block');
							$('.date_picker_field').css('display','none');
							$('.ranklvl').css('display','none');
						}
						else if(a == 'rank_level'){
							$('.text').css('display','none');
							$('.drop').css('display','none');
							$('.date_picker_field').css('display','none');
							$('.ranklvl').css('display','block');
						}
						else{
							$('.text').css('display','none');
							$('.drop').css('display','none');
							$('.date_picker_field').css('display','block');
							$('.ranklvl').css('display','none');
						}
					}
				}
			});
		});

		$('#date_search').on("change",function() {
			var date1 = $(this).val();
			$('#date_search2').prop('disabled',false);
			$("#date_search2").datepicker("option", "minDate", date1);
		});

		$('#date_search2').on("change",function() {
			var column = $('#column_name').find('option:selected').val();
			var date1 = $('#date_search').val();
			var date2 = $(this).val();
			var search_archive = 'date_search_ipi';
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{search_archive:search_archive, date1:date1, date2:date2, column:column},
				success:function(response){
					if (response == "default") {
						$('#default_load_archive').css('display','block');
						$('#search_load_archive').css('display','none');
					}
					else{
						$('#default_load_archive').css('display','none');
						$('#search_load_archive').css('display','block');
						$('#search_load_archive').html(response);
					}
				}
			});
		});
		$('#status_issue').on("change",function() {
			var column = $('#column_name').find('option:selected').val();
			var status = $(this).find('option:selected').val();
			var search_archive = 'issue_search_ipi';
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{search_archive:search_archive, status:status, column:column},
				success:function(response){
					if (response == "default") {
						$('#default_load_archive').css('display','block');
						$('#search_load_archive').css('display','none');
					}
					else{
						$('#default_load_archive').css('display','none');
						$('#search_load_archive').css('display','block');
						$('#search_load_archive').html(response);
					}
				}
			});
		});
		$('#rank_level').on("change",function() {
			var column = $('#column_name').find('option:selected').val();
			var status = $(this).find('option:selected').val();
			var search_archive = 'issue_search_ipi';
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{search_archive:search_archive, status:status, column:column},
				success:function(response){
					if (response == "default") {
						$('#default_load_archive').css('display','block');
						$('#search_load_archive').css('display','none');
					}
					else{
						$('#default_load_archive').css('display','none');
						$('#search_load_archive').css('display','block');
						$('#search_load_archive').html(response);
					}
				}
			});
		});

		// Search date picker
		$( "#date_search" ).datepicker({
			
		});
		$( "#date_search2" ).datepicker({
			
		});
	});
		$('#archive_key').on('keyup',function() {
			var column = $('#column_name').find('option:selected').val();
			var search_archive = $(this).val();
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{search_archive:search_archive, column:column},
				success:function(response) {
					if (response == "default") {
						$('#default_load_archive').css('display','block');
						$('#search_load_archive').css('display','none');
					}
					else{
						$('#default_load_archive').css('display','none');
						$('#search_load_archive').css('display','block');
						$('#search_load_archive').html(response);
					}
				}
			})
		});
</script>