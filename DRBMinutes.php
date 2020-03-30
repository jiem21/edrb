<?php 
$page_title = "DRB Minutes";
include_once 'header.php';
include_once 'Controller/DRBController.php';
include 'Controller/CustomerDashboardController.php';
$drb = $_GET['drb'];

$settings = new customDashboard();
$settings->get_SQA_setting();

$function = new DRBFunc();

$function->read($drb);

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
<style type="text/css">
	.table th {
		padding: .2rem !important;
		width: 150px;
	}
	.table td{
		padding: .35rem !important;
	}
</style>
<section class="section_minutes_details">
	<div class="container-fluid">
		<div class="minutes_details">
			<div class="header_title text-center"><h2>DRB Minutes Details</h2></div>
			<div class="row py-2">
				<div class="col-md-12 text-center">
					<?php if ($function->drb_status == "Closed" AND ($acc_type == '2' OR $acc_type == '3')) { ?>
						<a href="#" class="btn btn-success" id="reopen_issue" data-id="<?php echo $function->drb_number; ?>" data-func="reopenIssue"> Reopen the DRB Issue</a>
						<?php if ($acc_type == '3'): ?>
							<a href="#" class="btn btn-danger" id="prepare_notif" data-id="<?php echo $function->drb_number; ?>" data-func="archiveissue" data-toggle="modal" data-target="#Archive_popup" data-iddrb="<?php echo $function->id; ?>">Archive DRB Issue</a>
						<?php endif ?>
						<!-- <a href="#" class="btn btn-danger" id="archive_issue" data-id="<?php echo $function->drb_number; ?>" data-func="archiveissue">Archive DRB Issue</a> -->
					<?php }elseif ($function->drb_status == "Closed" AND $function->block == $block) { ?>
						<a href="#" class="btn btn-success disabled"> DRB Issue is Closed</a>
					<?php }elseif($function->meeting_status == 0 AND $function->block == $block) { ?>
						<a href="#" class="btn btn-success" id="start_meeting" data-id="<?php echo $function->drb_number; ?>" data-iddrb="<?php echo $function->id; ?>" data-func="StartMeeting">Start DRB Meeting</a>
					<?php }elseif($function->meeting_status == 1 AND $function->block == $block){ ?>
						<button type="submit" class="btn btn-danger" id="end_meeting" data-id="<?php echo $function->drb_number; ?>" data-func="EndMeeting">End DRB Meeting</button>
					<?php }elseif($function->meeting_status == 0 AND ($acc_type == '2' OR $acc_type == '3')){ ?>
						<a href="#" class="btn btn-danger" id="close_issue" data-id="<?php echo $function->drb_number; ?>" data-func="CloseIssue">Close DRB Issue</a>
						<?php if ($acc_type == '3'): ?>
						<a href="#" class="btn btn-danger" id="prepare_notif" data-id="<?php echo $function->drb_number; ?>" data-func="archiveissue" data-toggle="modal" data-target="#Archive_popup" data-iddrb="<?php echo $function->id; ?>">Archive DRB Issue</a>
						<?php endif ?>
						<!-- <a href="#" class="btn btn-danger" id="archive_issue" data-id="<?php echo $function->drb_number; ?>" data-func="archiveissue">Archive DRB Issue</a> -->
					<?php } elseif($function->meeting_status == 1 AND ($acc_type == '2' OR $acc_type == '3')){ ?>
						<a href="#" class="btn btn-success disabled"><span class="oi oi-reload oi-reload-animate"></span> On Going Meeting</a>
					<?php } ?>	
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<table class="table table-hover table-bordered">
						<tbody>
							<tr>
								<th class="thead-custom">Month</th>
								<td><?php echo $function->occur_month; ?></td>
							</tr>
							<tr>
								<th class="thead-custom">Work Week</th>
								<td><?php echo $function->work_week; ?></td>
							</tr>
							<tr>
								<th class="thead-custom">Occurrence Date</th>
								<td><?php echo date('F d,Y',strtotime($function->occur_date)); ?></td>
							</tr>
							<tr>
								<th class="thead-custom">DRB Date</th>
								<td><?php echo date('F d,Y',strtotime($function->drb_date)); ?></td>
							</tr>
							<tr>
								<th class="thead-custom">Block Affected</th>
								<td><?php echo strtoupper($function->block); ?></td>
							</tr>
							<tr>
								<th class="thead-custom">Process Affected</th>
								<td><?php echo $function->process; ?></td>
							</tr>
							<tr>
								<th class="thead-custom">Machine No.</th>
								<td><?php echo $function->machine_no; ?></td>
							</tr>
							<tr>
								<th class="thead-custom">Product Affected</th>
								<td><?php echo $function->product; ?></td>
							</tr>
							<tr>
								<th class="thead-custom">5M1E</th>
								<td><?php echo $function->m5e1; ?></td>
							</tr>
							<tr>
								<th class="thead-custom">Issue Type</th>
								<td><?php echo $function->issue_type; ?></td>
							</tr>
							<tr>
								<th class="thead-custom"># of Affected Lots</th>
								<td><?php echo $function->affected_lot; ?></td>
							</tr>
							<tr>
								<th class="thead-custom"># lotouts lots</th>
								<td><?php echo $function->lot_out; ?></td>
							</tr>
							<tr>
								<th class="thead-custom">Rank</th>
								<td><?php echo $function->rank; ?></td>
							</tr>
							<tr>
								<th class="thead-custom">Close Validation Date plan</th>
								<td><?php 
								if (empty($function->closing_validation_plan_date)) {
									echo "-";
								}
								else{
									echo date('F d,Y',strtotime($function->closing_validation_plan_date));
								} 
								?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-9">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-hover table-bordered">
								<thead  class="thead-custom">
									<tr>
										<th>DRB Status</th>
										<th>DRB Number</th>
										<th>RFC Number</th>
										<th>Closed Validation Date</th>
										<th style="width: 190px;">Close Validation and Approval By</th>
										<?php if ($acc_type == 3 or $acc_type == 2): ?>
											<th>Action</th>
										<?php endif ?>
										
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo $function->drb_status; ?></td>
										<td><?php echo strtoupper($function->drb_number); ?></td>
										<td><?php echo $function->rfc_no; ?></td>
										<td><?php 
										if (empty($function->closing_validation_date)) {
											echo "-";
										}
										else{
											echo date('F d,Y',strtotime($function->closing_validation_date));
										}
										?></td>
										<td><?php echo $function->name_of_approval; ?></td>
										<?php if ($function->drb_status == "Closed" AND ($acc_type == 3 or $acc_type == 2)){ ?>
											<td>
												<a href="#" class="btn btn-info disabled">Update tracking ledger</a>
											</td>
										<?php }elseif ($function->meeting_status == 0 AND ($acc_type == 3 or $acc_type == 2)){ ?>
											<td>
												<a href="#" class="btn btn-info" data-toggle="modal" data-target="#UpdateDRBLedger">Update Ledger</a>
											</td>
										<?php }elseif ($function->meeting_status == 1 AND ($acc_type == 3 or $acc_type == 2)) { ?>
											<td>
												<a href="#" class="btn btn-info disabled"><span class="oi oi-reload oi-reload-animate"></span> Ongoing</a>
											</td>
										<?php } ?>
										
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-12">
							<table class="table table-hover table-bordered">
								<thead  class="thead-custom">
									<tr>
										<th>DRB Issue</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo $function->drb_issue; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-12">
							<table class="table table-hover table-bordered text-center">
								<thead  class="thead-custom">
									<tr>
										<th>Date of First Upload</th>
										<th>Date of Last Upload</th>
										<th>No. of Upload</th>
										<th colspan="2">Action</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo $function->drb_first_upload; ?></td>
										<td><?php echo $function->drb_last_upload; ?></td>
										<td><?php echo $function->drb_number_upload; ?></td>
										
										<?php 
										$link ="";
										if ($function->drb_number_upload == 0) {
											$link = "Controller/executeExcel?action=Template&drb=".$function->drb_number;
										}
										else{
											$link = "/edrb".$function->drb_path;
												// $download = "Controller/executeExcel?action=Download&drb=".$function->drb_number;
										}
										if ($function->meeting_status == 0 AND $function->drb_number_upload > 0 AND $function->block == $block) { ?>
											<td colspan="2">
												<a href="<?php 	echo $link ?>" class="btn btn-info"><span class="oi oi-data-transfer-download"></span> Download Latest File</a>
											</td>
										<?php }elseif($function->meeting_status == 0 AND $function->drb_number_upload == 0 AND $function->block == $block){ ?>
											<td colspan="2">
												<a href="Controller/executeExcel?action=Template&drb=<?php echo $function->drb_number;?>" class="btn btn-info"><span class="oi oi-data-transfer-download"></span> Download Template</a>
											</td>
										<?php } elseif($function->block == $block AND $function->meeting_status == 1){ ?>
											<td colspan="2">
												<form id="upload_file" method="post">
													<input type="hidden" name="drb_number" value="<?php echo $function->drb_number; ?>">
													<input type="hidden" name="validator" value="<?php echo $function->rfc_no;?>">
													<input type="hidden" name="func" value="upload_file">
													<input class="form-control" type="file" name="drb_upload" id="drb_upload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
													<input type="submit" name="submit_file" value="Upload File" class="btn btn-info" disabled="true" id="submit_file">
												</form>
											</td>
										<?php }	elseif(($acc_type == 3 or $acc_type == 2) AND $function->meeting_status == 0 AND $function->drb_status == "Open"){ ?>
											<td>
												<a href="<?php echo $link; ?>" class="btn btn-info" download><span class="oi oi-data-transfer-download"></span> Download</a>
											</td>
											<td>
												<form id="upload_file" method="post">
													<input type="hidden" name="drb_number" value="<?php echo $function->drb_number; ?>">
													<input type="hidden" name="validator" value="<?php echo $function->rfc_no;?>">
													<input type="hidden" name="func" value="upload_file">
													<input class="form-control" type="file" name="drb_upload" id="drb_upload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
													<input type="submit" name="submit_file" value="Upload File" class="btn btn-info" disabled="true" id="submit_file">
												</form>
											</td>
										<?php }else{ ?>
											<td colspan="2">
												<a href="<?php echo $link; ?>" class="btn btn-info" download><span class="oi oi-data-transfer-download"></span> Download</a>
											</td>
										<?php } ?>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<?php if ($acc_type != '1'): ?>
				<div class="row">
					<div class="col-md-12 text-right">
						<button class="btn btn-info py-2 my-1" id="update_rfc" data-rfc="<?php echo $function->rfc_no; ?>">Update Affected Lots</button>
					</div>
				</div>
			<?php endif ?>
			
			<div class="row">
				<div class="col-md-12">
					<table class="table table-hover table-bordered text-center" id="tbl_lots">
						<thead class="thead-custom">
							<tr>
								<th>Affected Lot</th>
								<th>Product Name</th>
								<th>View Details</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$rfc = $function->rfc_no;
							$function->list_affected_lot($rfc);
							?>
						</tbody>
					</table>
				</div>
			</div>
			<?php if ($settings->status == '0') { ?>
			<div class="row">
				<div class="col-md-12 table-responsive">
					<table class="table table-hover table-bordered" id="tbl_minutes">
						<thead  class="thead-custom">
							<tr>
								<th>#</th>
								<th>Date Meeting</th>
								<th>Time Start</th>
								<th>Time End</th>
								<th>Total Time</th>
							</tr>
						</thead>
						<tbody>
							<?php $function->list_of_meeting($drb); ?>
						</tbody>
					</table>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</section>
<?php include_once 'footer.php';?>


<!-- Modal Update Ledger -->
<div class="modal fade" id="UpdateDRBLedger" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Update DRB Tracking Ledger</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<label id="errorfunc"></label>
				<form id="UpdateDRBTrackingLedger" method="post">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label for="RFC_Number">RFC Number</label>
									<input class="form-control" type="text" id="RFC_Number" name="RFC_Number" disabled value="<?php echo $function->rfc_no;?>">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="month">Month</label>
									<select class="form-control" name="DRBmonth" id="month">
										<option <?php echo ($function->occur_month == 'January' ? 'selected' : ''); ?> value="January">January</option>
										<option <?php echo ($function->occur_month == 'February' ? 'selected' : ''); ?> value="February">February</option>
										<option <?php echo ($function->occur_month == 'March' ? 'selected' : ''); ?> value="March">March</option>
										<option <?php echo ($function->occur_month == 'April' ? 'selected' : ''); ?> value="April">April</option>
										<option <?php echo ($function->occur_month == 'May' ? 'selected' : ''); ?> value="May">May</option>
										<option <?php echo ($function->occur_month == 'June' ? 'selected' : ''); ?> value="June">June</option>
										<option <?php echo ($function->occur_month == 'July' ? 'selected' : ''); ?> value="July">July</option>
										<option <?php echo ($function->occur_month == 'August' ? 'selected' : ''); ?> value="August">August</option>
										<option <?php echo ($function->occur_month == 'September' ? 'selected' : ''); ?> value="September">September</option>
										<option <?php echo ($function->occur_month == 'October' ? 'selected' : ''); ?> value="October">October</option>
										<option <?php echo ($function->occur_month == 'November' ? 'selected' : ''); ?> value="November">November</option>
										<option <?php echo ($function->occur_month == 'December' ? 'selected' : ''); ?> value="December">December</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="month">Work Week</label>
									<input class="form-control" type="text" name="ww" value="<?php echo $function->work_week; ?>" />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="datepicker">Occurrence Date</label>
									<input class="form-control" type="text" id="datepicker" name="occur_date" value="<?php echo date('m/d/Y',strtotime($function->occur_date));?>">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="datepicker2">DRB Date</label>
									<input class="form-control" type="text" id="datepicker2" value="<?php echo date('m/d/Y',strtotime($function->drb_date));?>" disabled>
									<input type="hidden" name="DRB_date" value="<?php echo date('m/d/Y',strtotime($function->drb_date));?>">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="DRB_Number">DRB Number</label>
									<input class="form-control drb_num" type="text" disabled value="<?php echo $function->drb_number;?>">
									<input class="form-control drb_num" type="hidden" id="DRB_Number" name="DRB_Number" value="<?php echo $function->drb_number;?>">
									<input class="form-control" type="hidden" id="DRB_Number_previous" name="DRB_Number_previous" value="<?php echo $function->drb_number;?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="DRB_Issue">DRB Issue</label>
									<textarea class="form-control" name="DRB_Issue" placeholder="DRB ISSUE" rows="12" id="DRB_Issue"><?php echo $function->drb_issue; ?></textarea>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="block">Affected Block</label>
									<input type="hidden" value="<?php echo strtolower($function->block);?>" id="current_block">
									<select class="form-control" name="block" id="block" data-func="opt_proc" required>
										<option selected value="<?php echo strtolower($function->block);?>"><?php echo strtoupper($function->block);?></option>
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
										<option selected value="<?php echo $function->product;?>"><?php echo $function->product;?></option>
										<option value="Chipset">Chipset</option>
										<option value="CPU">CPU</option>
										<option value="UTC">UTC</option>
									</select>
								</div>
								<div class="form-group">
									<label for="issue_type">Issue Type</label>
									<select class="form-control" name="issue_type" id="issue_type" required>
										<option selected value="<?php echo $function->issue_type;?>"><?php echo $function->issue_type;?></option>
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
												<option selected value="<?php echo $function->process;?>"><?php echo $function->process;?></option>
											</select>
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<label for="machine_no">Machine No.</label>
											<input type="text" class="form-control" name="machine_no" id="machine_no" value="<?php echo $function->machine_no;?>">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="5m1e">5M1E</label>
									<select class="form-control" name="5m1e" id="5m1e" required>
										<option selected value="<?php echo $function->m5e1;?>"><?php echo $function->m5e1;?></option>
										<option value="Man">Man</option>
										<option value="Machine">Machine</option>
										<option value="Method">Method</option>
										<option value="Material">Material</option>
										<option value="Measurement">Measurement</option>
										<option value="Environment">Environment</option>
										<option value="Others">Others</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label for="lot_out">Disposition: Lot Out</label>
									<input type="text" class="form-control" name="lot_out" id="lot_out" value="<?php echo $function->lot_out; ?>">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="sheet_out">Disposition: Sheet Out</label>
									<input type="text" class="form-control" name="sheet_out" id="sheet_out" value="<?php echo $function->sheet_out; ?>">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="Rank">Rank</label>
									<select class="form-control" name="Rank" id="Rank" data-func="opt_rank" required>
										<option selected value="<?php echo $function->rank;?>">Rank <?php echo $function->rank;?></option>
										<option value="1">Rank 1</option>
										<option value="2">Rank 2</option>
										<option value="3">Rank 3</option>
										<option value="4">Rank 4</option>
										<option value="5">Rank 5</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="closing_plan_date">Closing Validation Date Plan</label>
									<?php 
									if (empty($function->closing_validation_plan_date)) {
										$date_plan = NULL;
									}else{
										$date_plan = date('m/d/Y',strtotime($function->closing_validation_plan_date));
									}
									?>
									<input class="form-control" type="text" id="closing_plan_date" name="closing_plan_date" value="<?php echo $date_plan;?>">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="approval_name">Approval By:</label>
									<input class="form-control" type="text" id="approval_name" disabled value="<?php echo $function->name_of_approval;?>">
									<input type="hidden" name="func" value="TrackLedgerUpdate">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-success">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- View Affected Lot -->
<div class="modal fade" id="View_lots" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Details of Affected Lot</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="generate_lot_data"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Notification for archive -->
<div class="modal fade" id="Archive_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle"><span class="oi oi-trash"></span>Archive Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<h6>Are you sure you want to archive this <div id="drb_num_display"></div></h6>
				<form id="Archive">
					<div id="data_gathered">
						<input type="hidden" name="id" id="drb_number">
						<input type="hidden" name="func" id="function">
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
<!-- <link rel="stylesheet" type="text/css" href="assets/plugins/datepicker/jquery-ui.css"> -->
<!-- <script src="assets/plugins/datepicker/jquery-1.12.4.js"></script>
	<script src="assets/plugins/datepicker/jquery-ui.js"></script> -->
	<script type="text/javascript">
		$('#tbl_lots').DataTable();
		// $('#tbl_minutes').DataTable();
		$(document).ready(function() {
			$( "#datepicker" ).datepicker({
				maxDate: "+0d"
			});
			$( "#datepicker2" ).datepicker({
				maxDate: "+0d"
			});
			$( "#closing_plan_date" ).datepicker({
				minDate: "+0d"
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
				var drb_no = $('#DRB_Number_previous').val();
				var current = $('#current_block').val();
				var block = $(this).val();
				if ($(this).find('option:selected').val() == current) {
					$('.drb_num').val(drb_no);
				}
				else{
					$.ajax({
						method: "post",
						url: "Controller/generateDRB.php",
						data:{block:block, drb_no:drb_no},
						success:function (response) {
							$('.drb_num').val(response);
						}
					})
				}
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
					}
				})
			});

			$('#tbl_lots').on("click",'.view_lots',function() {
				var id = $(this).attr('id');
				var func = $(this).attr('data-func');
				$.ajax({
					method: "post",
					url: "Controller/execute.php",
					data:{id:id, func:func},
					success:function (response) {
						$("#generate_lot_data").html(response);
					}
				})
			});

			$('#prepare_notif').on("click",function() {
				var id = $(this).attr('data-id');
				var label = "<bold class='display_drb'>"+id+"</bold>"
				var func = $(this).attr('data-func');
				$('#drb_num_display .display_drb').remove();
				$('#drb_num_display').append(label);
				$('#drb_number').val(id);
				$('#function').val(func);
			});

			$('#Archive').submit(function(e) {
				e.preventDefault();
				var id = $('#prepare_notif').attr('data-id');
				var func = $('#prepare_notif').attr('data-func');
				var id_drb = $('#prepare_notif').attr('data-iddrb');
				$.ajax({
					method: "post",
					url: "Controller/execute.php",
					data:{id_drb:id_drb,id:id, func:func},
					beforeSend:function() {
						$('body').css('overflow','hidden');
						$('.containers').css('display','flex');
					},
					success:function (response) {
						if (response == "success") {
							$.toast({
								heading: "Archive Data",
								text: "Data is successfully archive",
								showHideTransition: "slide",
								hideAfter : 3500,
								position: "top-right",
								icon: "success"
							});
							setTimeout(function(){window.location.href="DRBTracking"} , 3500);

						}else{
							$.toast({
								heading: "Archive Data",
								text: response,
								showHideTransition: "slide",
								hideAfter : 3500,
								position: "top-right",
								icon: "warning"
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

			$("#generate_lot_data").on('change','#ptrn_code',function() {
				var ptrn_code = $(this).find('option:selected').val();
				var lot_no = $('#lot').attr('data-lot');
				var func = "generate_ipp";
				$.ajax({
					method: "post",
					url: "Controller/execute.php",
					data:{ptrn_code:ptrn_code,lot_no:lot_no, func:func},
					beforeSend:function() {
						$('body').css('overflow','hidden');
						$('.containers').css('display','flex');
					},
					success:function(response) {
						$('#default_row_ipp').css('display','none');
						$('#show_details_ipp').html(response);
					},
					complete:function(){
						$('body').css('overflow','auto');
						$('.containers').css('display','none');
					}
				});
				
			});

			$("#update_rfc").on('click',function() {
				var rfc_no = $(this).attr('data-rfc');
				var func = "check_rfc_updates";
				$.ajax({
					method: "post",
					url: "Controller/execute.php",
					data:{rfc_no:rfc_no, func:func},
					dataType:"json",
					beforeSend:function() {
						$('#update_rfc').prop('disabled',true);
						$('.view_lots').addClass('disabled');
					},
					success:function(response) {
						$.toast({
								heading: response.message,
								text: response.desc,
								showHideTransition: "slide",
								hideAfter : response.time,
								position: "top-right",
								icon: response.trigger
							});
						if (response.update) {
							setTimeout(function(){window.location.reload()} , 2500);
						}
					},
					complete:function(response){
						if (response.update) {

						}
						else{
							$('#update_rfc').prop('disabled',false);
							$('.view_lots').removeClass('disabled');
						}	
					}
				});
				
			});

		});
	</script>