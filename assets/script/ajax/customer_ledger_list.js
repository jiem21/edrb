$(document).ready(function() {
	load_data();
	function load_data(page) {
		$.ajax({
			url:"Controller/Pagination/CustomerShowLedgerPagination.php",
			method:"POST",
			data:{page:page},
			success:function(data){
				$('#default_customer_List_setting').html(data);
			}
		})
	}

	$(document).on('click','.tracking_customer_link',function() {
		var page = $(this).attr("id");
		load_data(page);
	});
});