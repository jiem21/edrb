$(document).ready(function() {
	load_data();
	function load_data(page) {
		$.ajax({
			url:"Controller/Pagination/CustomerDashledgerPagination.php",
			method:"POST",
			data:{page:page},
			beforeSend:function() {
				$('body').css('overflow','hidden');
				$('.containers').css('display','flex');
			},
			success:function(data){
				$('#default_List_setting').html(data);
			},
			complete:function(){
				$('body').css('overflow','auto');
				$('.containers').css('display','none');
			}
		})
	}

	$(document).on('click','.tracking_link',function() {
		var page = $(this).attr("id");
		load_data(page);
	});
});