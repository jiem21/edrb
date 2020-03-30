<?php 
$page_title = "DRB Tracking Ledger List";
include_once 'header.php'; 
?>
<div class="content_list">
	<section class="DRB_list_ledger">
		<div class="header_list">
			<div class="row">
				<div class="col-md-8">
					<h4>DRB Tracking List Of <?php echo strtoupper($block); ?></h4>
					<label class="sr-only" for="inlineFormInputGroup">Search DRB Tracking Ledger</label>
					<div class="input-group mb-2">
						<input type="text" class="form-control" id="ledger_section_key" placeholder="Search DRB Issue or by DRB Number">
						<div class="input-group-prepend">
							<div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class="list_all_drb">
			<div id="default_load_ledger_block"></div>
			<div id="search_load_ledger_block"></div>
		</div>
	</section>
</div>
<?php include_once 'footer.php';?>
<script src="assets/script/ajax/ledger_block_list.js"></script>