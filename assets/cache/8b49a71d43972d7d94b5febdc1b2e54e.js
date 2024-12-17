
$('#filter').change(function(){
	var url = base_url + 'pengadaan/klarifikasi_negosiasi/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
