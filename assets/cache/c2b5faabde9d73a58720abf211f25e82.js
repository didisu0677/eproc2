
function openForm() {
	$('#nama_pengadaan').val('');
	$('#tanggal_pengadaan').val('');
	$('#mata_anggaran').val('');
	$('#unit_kerja').val('');
	$('#divisi').val('');
	$('#besar_anggaran').val('');
	$('#usulan_hps').val('');

	$('#id_m_panitia').html('');
	var response = response_edit;
	if(typeof response.id != 'undefined') {
		$('#nomor_pengajuan').html(response.nomor_pengajuan1).trigger('change');
    	$('#id_m_panitia').val(response.id_m_panitia).trigger('change');
	} else {
		view_combo();
	}
}
function view_combo() {
	$.ajax({
		url			: base_url + 'pengadaan/delegasi/get_combo',
 		dataType	: 'json',
        success     : function(response){
        	$('#nomor_pengajuan').html(response.nomor_pengajuan);
         }
    });
}
$('#nomor_pengajuan').change(function(){
	$('#nama_pengadaan').val($(this).find(':selected').attr('data-nama_pengadaan'));
	$('#tanggal_pengadaan').val($(this).find(':selected').attr('data-tanggal_pengadaan'));
	$('#mata_anggaran').val($(this).find(':selected').attr('data-mata_anggaran'));
	$('#divisi').val($(this).find(':selected').attr('data-divisi'));
	$('#unit_kerja').val($(this).find(':selected').attr('data-unit_kerja'));
	$('#besar_anggaran').val($(this).find(':selected').attr('data-besar_anggaran'));
	$('#usulan_hps').val($(this).find(':selected').attr('data-usulan_hps'));

	$('#id_m_panitia').html('<option value=""></option>');
	$('#list-panitia [data-unit="'+$(this).find(':selected').attr('data-unit')+'"]').each(function(){
		$('#id_m_panitia').append('<option value="'+$(this).attr('value')+'">'+$(this).text()+'</option>');
	});
	$('#id_m_panitia').trigger('change');
});
$(document).on('click','.detail-pengajuan',function(){
	$.get(base_url+'pengadaan/pengajuan/detail?no_pengajuan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});
function detail_callback(id){
	$.get(base_url+'pengadaan/delegasi/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}
