
$('#filter').change(function(){
	var url = base_url + 'pengadaan_v/klarifikasi_negosiasi_v/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
