
function openForm() {
	$('#nama_pengadaan').val('');
	$('#tanggal_pengadaan').val('');
	$('#mata_anggaran').val('');
	$('#unit_kerja').val('');
	$('#divisi').val('');
	$('#besar_anggaran').val('');
	$('#usulan_hps').val('');
	$('#id_user').html('');

	var response = response_edit;
	if(typeof response.id != 'undefined') {
		$('#nomor_pengajuan').html(response.nomor_pengajuan).trigger('change');
    	$('#id_user').val(response.id_user).trigger('change');
	} else {
		view_combo();
	}
}
function view_combo() {
	$('#nomor_pengajuan').html('').trigger('change');
	$.ajax({
		url			: base_url + 'pengadaan/disposisi/get_combo',
 		dataType	: 'json',
        success     : function(response){
        	$('#nomor_pengajuan').html(response.nomor_pengajuan).trigger('change');
         }
    });
}
$('#nomor_pengajuan').change(function(){
	$('#nama_pengadaan').val($(this).find(':selected').attr('data-nama_pengadaan'));
	$('#tanggal_pengadaan').val($(this).find(':selected').attr('data-tanggal_pengadaan'));
	$('#mata_anggaran').val($(this).find(':selected').attr('data-mata_anggaran'));
	$('#unit_kerja').val($(this).find(':selected').attr('data-unit_kerja'));
	$('#divisi').val($(this).find(':selected').attr('data-divisi'));
	$('#besar_anggaran').val($(this).find(':selected').attr('data-besar_anggaran'));
	$('#usulan_hps').val($(this).find(':selected').attr('data-usulan_hps'));

	$('#id_user').html('<option value=""></option>');
	$('#user-delegator [data-unit="'+$(this).find(':selected').attr('data-unit')+'"]').each(function(){
		$('#id_user').append('<option value="'+$(this).attr('value')+'">'+$(this).text()+'</option>');
	});
	$('#id_user').trigger('change');
});
function detail_callback(id){
	$.get(base_url+'pengadaan/disposisi/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}
$(document).on('click','.detail-pengajuan',function(){
	$.get(base_url+'pengadaan/pengajuan/detail?no_pengajuan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});
