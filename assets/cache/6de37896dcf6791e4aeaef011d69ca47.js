
$('#filter').change(function(){
	var url = base_url + 'pengadaan/pendaftaran_pengadaan/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
