
$(document).on('click','.btn-pengambilan',function(){
	var id = $(this).attr('data-id');
	$('#form')[0].reset();
	$('#id').val(id);
	$('#detail--jaminan').html('');
	$.get(base_url+'manajemen_kontrak/penerimaan_jaminan/detail/'+id,function(result){
		$('#detail--jaminan').html(result);
	});
	$('#modal-pengambilan').modal();
});
$(document).on('click','.detail-jaminan',function(){
	var id = $(this).attr('data-id');
	$.get(base_url+'manajemen_kontrak/penerimaan_jaminan/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
});
