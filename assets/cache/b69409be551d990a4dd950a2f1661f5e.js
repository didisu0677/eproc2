
$('#filter').change(function(){
	var url = base_url + 'pengadaan/aanwijzing/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
