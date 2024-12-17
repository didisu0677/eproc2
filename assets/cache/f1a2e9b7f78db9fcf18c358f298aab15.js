
$('#filter').change(function(){
	var url = base_url + 'manajemen_rekanan/laporan_kunjungan/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
