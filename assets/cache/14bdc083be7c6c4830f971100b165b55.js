
$('#filter').change(function(){
	var url = base_url + 'pengadaan/peninjauan_lapangan/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
