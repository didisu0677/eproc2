
var id_pengajuan = 0;
var value_persetujuan = 0;
function detail_callback(e) {
	$.ajax({
		url 	: base_url + 'pengadaan/approval_permintaan/get_data',
		data 	: {id:e},
		type 	: 'post',
		dataType : 'json',
		success : function(response) {
			id_pengajuan = response.id;
			$.each(response,function(k,v){
				if(k.indexOf('tanggal') != -1) v = cDate(v);
				else if(k == 'besar_anggaran' || k == 'usulan_hps') v = numberFormat(v,0,',','.');
				if($('#'+k).length == 1) $('#' + k).text(v);
			});
			$('#alasan').val('');
			$('#modal-approve').modal();
			$('#persetujuan').val('1').trigger('change');
			setTimeout(function(){
				$('#persetujuan').focus().select2('open');
			},700);
		}
	});
}
function save_persetujuan() {
	$.ajax({
		url 	: base_url + 'pengadaan/approval_permintaan/save_persetujuan',
		data 	: {
			id : id_pengajuan,
			value : value_persetujuan,
			alasan : $('#alasan').val()
		},
		type	: 'post',
		success : function(response) {
			cAlert.open(response,'success','refreshData');
		}
	});
}
function openApproval() {
	if( $('.btn-act-view[data-id="'+$('[data-openid]').attr('data-openid')+'"]').length == 1 ) {
		$('.btn-act-view[data-id="'+$('[data-openid]').attr('data-openid')+'"]').trigger('click');
		$('[data-openid]').removeAttr('data-openid');
	}
}
$('#persetujuan').change(function(){
	if($(this).val() == '1') {
		$('#row-alasan').hide();
		$('#alasan').val('');
	} else if($(this).val() == '8') {
		$('#row-alasan').show();
		$('#row-alasan th').text($('#row-alasan th').attr('data-dikembalikan'));
	} else {
		$('#row-alasan').show();
		$('#row-alasan th').text($('#row-alasan th').attr('data-ditolak'));		
	}
});
$(document).on('click','.btn-approve',function(){
	if($('#alasan').val() != '' || $('#persetujuan').val() == '1' ) {
		value_persetujuan 	= $('#persetujuan').val();
		var msg 			= lang.anda_yakin_menyetujui;
		if(value_persetujuan == '9') msg = lang.anda_yakin_menolak;
		else if(value_persetujuan == '8') msg = lang.anda_yakin_mengembalikan;
		cConfirm.open(msg,'save_persetujuan');
	} else {
		$('#alasan').focus();
	}
});
$(document).on('click','.detail-pengajuan',function(){
	$.get(base_url+'pengadaan/pengajuan/detail?no_pengajuan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});
$(document).on('click','.btn-print',function(){
	var id = encodeId($(this).attr('data-id'));
	$.redirect(base_url + 'pengadaan/pengajuan/cetak_tor/' + id, {} , 'get', '_blank');
});
