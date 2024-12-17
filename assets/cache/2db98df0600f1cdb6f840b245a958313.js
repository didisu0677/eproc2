
$('#filter').change(function(){
	var url = base_url + 'pengadaan/laporan_peninjauan/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
