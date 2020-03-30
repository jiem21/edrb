	<footer>
		<div class="footer">
			<h5>S Y S D E V</h5>
			<label>INFORMATION TECHNOLOGY</label>
		</div>
	</footer>


	<div class="modal fade" id="ChangePassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle"><span class="oi oi-key"></span> Change Password</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
					<form id="ChangePasswordUser">
						<div class="modal-body">
							<div class="form-group">
								<input type="hidden" name="id_num" value="<?php echo $_SESSION['user_id'] ?>">
								<input type="hidden" name="func" value="changepassword">
								<label for="old_password">Old Password</label>
								<input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password">
							</div>
							<div class="form-group">
								<label for="new_pass">New Password</label>
								<input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="New Password">
							</div>
							<div class="form-group">
								<label for="new_pass2">Confirm New Password</label>
								<input type="password" class="form-control" id="new_pass2" name="new_pass2" placeholder="Confirm New Password">
							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Save password</button>
						</div>
				</form>
			</div>
		</div>
	</div>

	<!-- <script src="assets/script/custom.js"></script> -->
	<script src="assets/script/jquery/jquery-3.4.1.min.js"></script>
	<script src="assets/bootstraps/js/bootstrap.min.js"></script>
	<script src="assets/plugins/jquerytoast/jquery.toast.js"></script>
	<script src="assets/script/ajax/ajax.js"></script>
	<script src="assets/plugins/chart/chart.min.js"></script>
	<script src="assets/plugins/chart/utils.js"></script>
	<script src="assets/plugins/redirectJS/jquery.redirect.js"></script>
	<script src="assets/script/jquery-datatables.js"></script>
	<!-- <script src="assets/plugins/datepicker/jquery-1.12.4.js"></script> -->
	<script src="assets/plugins/datepicker/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#ChangePasswordUser').on('submit',function(e) {
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
						if (data == "success") {
							$.toast({
								heading: "Change Password",
								text: "Successfully Change password",
								showHideTransition: "slide",
								hideAfter : 2500,
								position: "top-right",
								icon: "success"
							});
							setTimeout(function(){window.location.reload()} , 2600);
						}
						else if(data =="Wrong Password"){
							$.toast({
								heading: "Change Password",
								text: "Old Password is incorrect",
								showHideTransition: "slide",
								hideAfter : 3500,
								position: "top-right",
								icon: "error"
							});
						}
						else if(data =="Password Not Match"){
							$.toast({
								heading: "Change Password",
								text: "Password does not match",
								showHideTransition: "slide",
								hideAfter : 3500,
								position: "top-right",
								icon: "error"
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
		});
	</script>
</body>
</html>