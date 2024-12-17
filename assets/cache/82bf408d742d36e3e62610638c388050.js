
$('#filter').change(function(){
	var url = base_url + 'pengadaan_v/penawaran_v/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
