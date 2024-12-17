
$('#filter').change(function(){
	var url = base_url + 'pengadaan/penawaran/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
