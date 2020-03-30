$(document).ready(function() {
	// Dashboard Function
	$("#login").submit(function(e){
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
				$('.prelog').css('display','none');
				$('.logproc').css('display','block');
			},
			success:function (data) {
				if (data == "valid") {
					$.toast({
							heading: "Login Status",
							text: "Username and password is verified please wait...",
							showHideTransition: "slide",
							hideAfter : 1500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.href="index"} , 1500);
				}
				else if(data =="deactivate"){
					$.toast({
							heading: "Login Status",
							text: "This Account is deactivate Please contact the Administrator of the system reactive the account",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "error"
						});
				}
				else if(data =="invalid"){
					$.toast({
							heading: "Login Status",
							text: "Username and password is invalid",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "warning"
						});
				}
				else{
					$("#errorfunc").css("display","block");
					$("#errorfunc").html(data);
				}
			},
			complete:function(){
				$('.prelog').css('display','block');
				$('.logproc').css('display','none');
			}
		});
	});
	$('#logout').click(function() {
		var func = $(this).attr('data-func');
		$.ajax({
			method:"post",
			url: "Controller/execute.php",
			data:{func:func},
			success:function (data) {
				if (data == "Success") {
					$.toast({
							heading: "Logout Status",
							text: "Initiating Logout sequence...",
							showHideTransition: "slide",
							hideAfter : 1500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.href="dashboard"} , 1500);
					
				}
				else{
					console.log(data);
				}
			}
		});
	});
	// DRB Module
		// Add Tracking Ledger
		$('#AddDRBTrackingLedger').submit(function(e) {
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
					$('#add_tracking_ledger').prop('disabled',true);
				},
				success:function (data) {
					if (data == "success") {
						$.toast({
							heading: "DRB Tracking Ledger",
							text: "DRB Tracking Ledger is Successfully Added to the list",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.href="DRBTracking"} , 3500);
					}
					else if(data =="empty"){
						$.toast({
							heading: "DRB Tracking Ledger",
							text: "Please Fill up all the fields",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "warning"
						});
					}
					else if(data =="RegisteredRFC"){
						$.toast({
							heading: "DRB Tracking Ledger",
							text: "RFC number is already used on this system",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "warning"
						});
					}
					else if(data =="RegisteredDRB"){
						$.toast({
							heading: "DRB Tracking Ledger",
							text: "DRB number is already used on this system",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "warning"
						});
					}
					else if(data =="Both"){
						$.toast({
							heading: "DRB Tracking Ledger",
							text: "DRB number and RFC Number are already used on this system",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "warning"
						});
					}
					else{
						$.toast({
							heading: "DRB Tracking Ledger",
							text: "Something went wrong please contact IT SysDev ("+data+")",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "erroe"
						});
						console.log(data);
					}
				},
				complete:function(){
					$('#add_tracking_ledger').prop('disabled',false);
				}
			});
		});
			// Update Tracking Ledger
			$('#UpdateDRBTrackingLedger').submit(function(e) {
				e.preventDefault();
				var formData = new FormData($(this)[0]);
				var new_url = $('#DRB_Number').val();
				$.ajax({
					method: "post",
					url: "Controller/execute.php",
					data: formData,
					cache:false,
					processData: false,
					contentType: false,
					beforeSend:function() {
						$('#update_tracking_ledger').prop('disabled',true);
					},
					success:function (data) {
						if (data == "success") {
							$.toast({
							heading: "DRB Tracking Ledger",
							text: "DRB Tracking Ledger is Successfully Update",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.href="DRBMinutes?drb="+new_url} , 3500);
						}
						else{
							$("#errorfunc").css("display","block");
							$("#errorfunc").html(data);
						}
					},
					complete:function(){
						$('#update_tracking_ledger').prop('disabled',false);
					}
				});
			});
		// Search Module DRB Tracking Ledger
		$('#ledger_key').on('keyup',function() {
			var column = $('#column_name').find('option:selected').val();
			var search_ledger = $(this).val();
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{search_ledger:search_ledger, column:column},
				success:function(response) {
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
			})
		});
		// Search Module DRB Tracking Ledger by Section
		$('#ledger_section_key').on('keyup',function() {
			var ledger_section_key = $(this).val();
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{ledger_section_key:ledger_section_key},
				success:function(response) {
					if (response == "default") {
						$('#default_load_ledger_block').css('display','block');
						$('#search_load_ledger_block').css('display','none');
					}
					else{
						$('#default_load_ledger_block').css('display','none');
						$('#search_load_ledger_block').css('display','block');
						$('#search_load_ledger_block').html(response);
					}
				}
			})
		});
		// Start DRB Meeting
		$('#start_meeting').on('click',function() {
			var id = $(this).attr('data-iddrb');
			var drb = $(this).attr('data-id');
			var func = $(this).attr('data-func');
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{id:id, drb:drb, func:func},
				beforeSend:function() {
					$('#start_meeting').addClass('disabled');
				},
				success:function(response) {
					if (response == "success") {
						$.toast({
							heading: "DRB Meeting",
							text: "Meeting is now Starting",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.reload()} , 3500);
					}
					else{
						$.toast({
							heading: "DRB Meeting",
							text: "Something Went Wrong",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "success"
						});
						$(this).prop('disabled',false);
					}
				}
			});
		});
		// End DRB Meeting
		$('#end_meeting').on('click',function() {
			var drb = $(this).attr('data-id');
			var func = $(this).attr('data-func');
			// get file
			var excel = new FormData($('#upload_file')[0]);
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{drb:drb, func:func},
				beforeSend:function() {
					$('#end_meeting').addClass('disabled');
				},
				success:function(response) {
					if (response == "success") {
						$.toast({
							heading: "DRB Meeting",
							text: "DRB Meeting is now end",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.reload()} , 2600);
					}
					else{
						$.toast({
							heading: "DRB Meeting",
							text: "Something Went Wrong",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "success"
						});
						$(this).prop('disabled',false);
					}
				}
			});
		});
		// Upload file
		$('#drb_upload').change(function(){
			if($('#drb_upload').val()==''){
				$('#submit_file').attr('disabled',true)
			} 
			else{
				$('#submit_file').attr('disabled',false);
			}
		});
		// Upload file to web
		$('#upload_file').submit(function(e) {
			e.preventDefault();
			var formData = new FormData($(this)[0]);
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:formData,
				cache:false,
				processData: false,
				contentType: false,
				beforeSend:function() {
					$('#submit_file').prop('disabled',true);
				},
				success:function(response) {
					if (response == "success") {
						$.toast({
							heading: "<b>File is successfully save</b>",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.reload()} , 2600);
					}
					else if(response == "Incorrect"){
						$.toast({
							heading: "<b>Incorrect file upload</b>",
							showHideTransition: "slide",
							hideAfter : 3500,
							position: "top-right",
							icon: "error"
						});
					}
					else{
						$.toast({
							heading: "Something went wrong",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "error"
						});
						console.log(response);
					}
				},
				complete:function(){
					$('#submit_file').prop('disabled',false);
				}
			});
		})
		// Close Issue
		$('#close_issue').on('click',function() {
			var drb = $(this).attr('data-id');
			var func = $(this).attr('data-func');
			// get file
			var excel = new FormData($('#upload_file')[0]);
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{drb:drb, func:func},
				beforeSend:function() {
					$('.btn').addClass('disabled');
				},
				success:function(response) {
					if (response == "success") {
						$.toast({
							heading: "DRB Issue status",
							text: "<b>is now close</b>",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.reload()} , 2600);
					}
					else{
						$.toast({
							heading: "DRB Issue status",
							text: "Cannot close the issue due to some issue",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "error"
						});
						console.log(response);
					}
				},
				complete:function(){
					$(this).prop('disabled',false);
				}
			});
		});
		// reopen Issue
		$('#reopen_issue').on('click',function() {
			var drb = $(this).attr('data-id');
			var func = $(this).attr('data-func');
			// get file
			var excel = new FormData($('#upload_file')[0]);
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{drb:drb, func:func},
				beforeSend:function() {
					$('.btn').addClass('disabled');
				},
				success:function(response) {
					if (response == "success") {
						$.toast({
							heading: "DRB Issue status",
							text: "<b>DRB Issue Change status to open</b>",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.reload()} , 2600);
					}
					else{
						$.toast({
							heading: "DRB Issue status",
							text: "Cannot reopen the issue due to some issue",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "error"
						});
						console.log(response);
					}
				},
				complete:function(){
					$(this).prop('disabled',false);
				}
			});
		});
	// Setting Module
		// Add User Module
		$('#AddUserForm').submit(function(e) {
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
					$('#save_user').prop('disabled',true);
				},
				success:function (data) {
					if (data == "valid") {
						$.toast({
							heading: "User Maintenance",
							text: "User Account is Successfully Added",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "success"
						});
						setTimeout(function(){window.location.href="Users"} , 2600);
					}
					else if(data =="empty"){
						$.toast({
							heading: "User Maintenance",
							text: "Please Fill up all the fields",
							showHideTransition: "slide",
							hideAfter : 2500,
							position: "top-right",
							icon: "warning"
						});
					}
					else if(data =="Registered"){
						$.toast({
							heading: "User Maintenance",
							text: "This ID Number is already Registered on this system",
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
					$('#save_user').prop('disabled',false);
				}
			});
		});
		// User Search Settings Module
		$('#user_key').on('keyup',function() {
			var search_user = $(this).val();
			$.ajax({
				method:"post",
				url:"Controller/execute.php",
				data:{search_user:search_user},
				success:function(response) {
					if (response == "default") {
						$('#default_load').css('display','block');
						$('#search_load').css('display','none');
					}
					else{
						$('#default_load').css('display','none');
						$('#search_load').css('display','block');
						$('#search_load').html(response);
					}
				}
			})
		});
	})