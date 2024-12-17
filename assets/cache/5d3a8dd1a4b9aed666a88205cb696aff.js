
$('#filter').change(function(){
	var url = base_url + 'pengadaan/klarifikasi/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
