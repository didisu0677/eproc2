
$('#filter').change(function(){
	var url = base_url + 'pengadaan/penetapan_pemenang/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
