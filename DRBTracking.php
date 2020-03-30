<?php 
$page_title = "DRB Tracking Ledger List";
include_once 'header.php'; 
?>
<div class="content_list">
	<section class="DRB_list_ledger">
		<div class="header_list">
			<div class="row">
				<div class="col-md-8">
					<h4>DRB Tracking Ledger List</h4>
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
						<select class="form-control ranklvl" name="rank_level" id="rank_level" style="display:none;" required>
							<option selected disabled>Select Rank Level</option>
							<option value="1">Rank Level 1</option>
							<option value="2">Rank Level 2</option>
							<option value="3">Rank Level 3</option>
							<option value="4">Rank Level 4</option>
							<option value="5">Rank Level 5</option>
						</select>
						<input class="form-control date_picker_field" style="display: none;" type="text" id="date_search" placeholder="Pick Date">
						<input class="form-control date_picker_field" style="display: none;" type="text" id="date_search2" placeholder="Pick Date" disabled>
						<input type="text" class="form-control text" id="ledger_key" placeholder="Search" disabled>
						<div class="input-group-prepend">
							<div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
						</div>
					</div>
				</div>
				<?php if ($acc_type == 3 or $acc_type == 2): ?>
					<div class="col-md-4 text-right"><a href="#" class="btn btn-custom" data-toggle="modal" data-target="#AddDRBLedger"><span class="oi oi-plus"></span> Add DRB Ledger</a></div>
				<?php endif ?>

			</div>
		</div>
		<div class="list_all_drb">
			<div id="default_load_ledger"></div>
			<div id="search_load_ledger"></div>
		</div>
	</section>
</div>

<?php include_once 'footer.php';  ?>
<!-- <link rel="stylesheet" type="text/css" href="assets/plugins/datepicker/jquery-ui.css">
<script src="assets/plugins/datepicker/jquery-1.12.4.js"></script>
<script src="assets/plugins/datepicker/jquery-ui.js"></script> -->
<script src="assets/script/ajax/ledger_pagi.js"></script>

<!-- Modal -->
<div class="modal fade" id="AddDRBLedger" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Add DRB Tracking Ledger</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<label id="errorfunc"></label>
				<form id="AddDRBTrackingLedger">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label for="RFC_Number">RFC Number</label>
									<input class="form-control"  data-inputmask="'mask': '00-0000-0000'" type="text" id="RFC_Number" name="RFC_Number" data-func="verified_RFC">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="datepicker">Occurrence Date</label>
									<input class="form-control" type="text" id="datepicker" name="occur_date">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="month">Month</label>
									<select class="form-control" name="DRBmonth" id="month" required>
										<option selected disabled>Select Month</option>
										<option value="January">January</option>
										<option value="February">February</option>
										<option value="March">March</option>
										<option value="April">April</option>
										<option value="May">May</option>
										<option value="June">June</option>
										<option value="July">July</option>
										<option value="August">August</option>
										<option value="September">September</option>
										<option value="October">October</option>
										<option value="November">November</option>
										<option value="December">December</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="month">Work Week</label>
									<input class="form-control" type="number" name="ww" min="1" max="52"/>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="datepicker2">DRB Date</label>
									<input class="form-control" type="text" id="datepicker2" name="DRB_date">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="DRB_Number">DRB Number</label>
									<input class="form-control drb_num" type="text" disabled>
									<input class="form-control drb_num" type="hidden" id="DRB_Number" name="DRB_Number">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="DRB_Issue">DRB Issue</label>
									<textarea class="form-control" name="DRB_Issue" placeholder="DRB ISSUE" rows="12" id="DRB_Issue"></textarea>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="block">Affected Block</label>
									<select class="form-control" name="block" id="block" data-func="opt_proc" required>
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
								<div class="form-group">
									<label for="products">Affected Products</label>
									<select class="form-control" name="products" id="products" required>
										<option selected disabled>Select Products</option>
										<option value="Chipset">Chipset</option>
										<option value="CPU">CPU</option>
										<option value="UTC">UTC</option>
										<option value="Chipset/CPU">Chipset/CPU</option>
										<option value="Chipset/UTC">Chipset/UTC</option>
										<option value="CPU/UTC">CPU/UTC</option>
									</select>
								</div>
								<div class="form-group">
									<label for="issue_type">Issue Type</label>
									<select class="form-control" name="issue_type" id="issue_type" required>
										<option selected disabled>Select Issue Type</option>
										<option value="New">New</option>
										<option value="Recurrence">Recurrence</option>
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<div class="row">
									<div class="col-md-7">
										<div class="form-group">
											<label for="process">Affected Process</label>
											<select class="form-control" name="process" id="process" required>
												<option selected disabled>Select Process</option>
											</select>
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<label for="machine_no">Machine No.</label>
											<input type="text" class="form-control" name="machine_no" id="machine_no">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="5m1e">5M1E</label>
									<select class="form-control" name="5m1e" id="5m1e" required>
										<option selected disabled>Select</option>
										<option value="Man">Man</option>
										<option value="Machine">Machine</option>
										<option value="Method">Method</option>
										<option value="Material">Material</option>
										<option value="Measurement">Measurement</option>
										<option value="Environment">Environment</option>
										<option value="Others">Others</option>
									</select>
								</div>
								<div class="form-group">
									<div class="form-group">
										<label for="lot">Affected Lots <span id="affect_count"></span></label>
										<input type="hidden" id="total_affected" name="total_affected" value=''>
										<select multiple class="form-control" id="lot">
											
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="lot_out">Disposition: Lot Out</label>
									<input type="text" class="form-control" name="lot_out" id="lot_out">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="sheet_out">Disposition: Sheet Out</label>
									<input type="text" class="form-control" name="sheet_out" id="sheet_out">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="Rank">Rank</label>
									<select class="form-control" name="Rank" id="Rank" data-func="opt_rank" required>
										<option selected disabled>Select Rank</option>
										<option value="1">Rank 1</option>
										<option value="2">Rank 2</option>
										<option value="3">Rank 3</option>
										<option value="4">Rank 4</option>
										<option value="5">Rank 5</option>
									</select>
								</div>
							</div>
<!-- 							<div class="col-md-3">
								<div class="form-group">
									<label for="closing_plan_date">Closing Validation Date Plan</label>
									<input class="form-control" type="text" id="closing_plan_date" name="closing_plan_date">
								</div>
							</div> -->
							<div class="col-md-3">
								<div class="form-group">
									<label for="approval_name">Approval By:</label>
									<input class="form-control" type="text" id="approval_name" disabled>
									<input type="hidden" name="func" value="TrackLedgerAdd">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="add_tracking_ledger" disabled="true">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#column_name').on('change',function() {
			var column = $(this).val();
			var a = $(this).find('option:selected').attr('data-fields');
			$('#ledger_key').prop('disabled',false)
			$('#ledger_key').val('');
			$('input').val('');
			$('#date_search2').prop('disabled',true);
			var search_ledger = $('#ledger_key').val();
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{search_ledger:search_ledger},
				success:function(response){
					if (response == "default") {
						$('#default_load_ledger').css('display','block');
						$('#search_load_ledger').css('display','none');
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
			var search_ledger = 'date_search_ipi';
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{search_ledger:search_ledger, date1:date1, date2:date2, column:column},
				success:function(response){
					if (response == "default") {
						$('#default_load_ledger').css('display','block');
						$('#search_load_ledger').css('display','none');
					}
					else{
						$('#default_load_ledger').css('display','none');
						$('#search_load_ledger').css('display','block');
						$('#search_load_ledger').html(response);
					}
				}
			});
		});
		$('#status_issue').on("change",function() {
			var column = $('#column_name').find('option:selected').val();
			var status = $(this).find('option:selected').val();
			var search_ledger = 'issue_search_ipi';
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{search_ledger:search_ledger, status:status, column:column},
				success:function(response){
					if (response == "default") {
						$('#default_load_ledger').css('display','block');
						$('#search_load_ledger').css('display','none');
					}
					else{
						$('#default_load_ledger').css('display','none');
						$('#search_load_ledger').css('display','block');
						$('#search_load_ledger').html(response);
					}
				}
			});
		});
		$('#rank_level').on("change",function() {
			var column = $('#column_name').find('option:selected').val();
			var status = $(this).find('option:selected').val();
			var search_ledger = 'issue_search_ipi';
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{search_ledger:search_ledger, status:status, column:column},
				success:function(response){
					if (response == "default") {
						$('#default_load_ledger').css('display','block');
						$('#search_load_ledger').css('display','none');
					}
					else{
						$('#default_load_ledger').css('display','none');
						$('#search_load_ledger').css('display','block');
						$('#search_load_ledger').html(response);
					}
				}
			});
		});

		// Search date picker
		$( "#date_search" ).datepicker({
			
		});
		$( "#date_search2" ).datepicker({
			
		});
		// Add tracking ledger date picker
		$( "#datepicker" ).datepicker({
			maxDate: "+0d"
		});
		$( "#datepicker2" ).datepicker({
			maxDate: "+0d"
		});
		$( "#closing_plan_date" ).datepicker({
			minDate: "+0d"
		});
		$('#datepicker2').on("change",function() {
			$('#block').prop('selectedIndex',0);
			$('.drb_num').val('');

		});



		$('#RFC_Number').on("blur",function(argument) {
			var RFC = $(this).val();
			var func = $(this).attr('data-func');
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{RFC:RFC, func:func},
				dataType:"json",
				beforeSend:function() {
					$('body').css('overflow','hidden');
					$('.containers').css('display','flex');
				},
				success:function (response) {
					if (response == "invalid") {
						$.toast({
							heading: "RFC Number",
							text: "<b>RFC Number is invalid</b>",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "error"
						});
						$('#RFC_Number').css('background-color','#ff0000');
						$('#add_tracking_ledger').prop('disabled',true);
						$('#lot .lot_list').remove();
						console.log(response);
					}
					else{
						$.toast({
							heading: "RFC Number",
							text: "<b>RFC Number is Valid</b>",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "success"
						});
						$('#RFC_Number').css('background-color','#00ff00');
						$('#add_tracking_ledger').prop('disabled',false);
						$('#lot .lot_list').remove();
						$.each(response.data, function(index, values) {
							$('#lot').append(values);
						});
						$('#affect_count').text(response.count);
						$('#total_affected').val(response.count);
						console.log(response["data"]);
					}
				},
				complete:function(){
					$('body').css('overflow','auto');
					$('.containers').css('display','none');
				}
			});
		});
		$('#block').on("change",function() {
			var block = $(this).val();
			var func = $(this).attr('data-func');
			$.ajax({
				method: "post",
				url: "Controller/execute.php",
				data:{block:block, func:func},
				success:function (response) {
					$('#process .used').remove();
					$('#process').append(response);
				}
			})
		});
		$('#block').on("change",function() {
			var drb_date = $('#datepicker2').val();
			var block = $(this).val();
			$.ajax({
				method: "post",
				url: "Controller/generateDRB.php",
				data:{block:block, drb_date:drb_date},
				success:function (response) {
					$('.drb_num').val(response);
				}
			})
		});
		$('#Rank').on("change",function() {
			var rank = $(this).val();
			var func = $(this).attr('data-func');
			$.ajax({
				method: "post",
				url: "Controller/execute.php",
				data:{rank:rank, func:func},
				success:function (response) {
					$('#approval_name').val(response);
					console.log(response)
				}
			})
		});
	});
</script>