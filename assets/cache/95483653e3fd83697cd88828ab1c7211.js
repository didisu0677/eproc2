
$('#btn-reinisiasi').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','inisiasiUlang');
});
function inisiasiUlang() {
	var nomor_pengadaan = $('#btn-reinisiasi').attr('data-pengadaan');
	$.ajax({
		url : base_url + 'pengadaan/penetapan_pemenang/inisiasi_ulang',
		data : {nomor_pengadaan : nomor_pengadaan},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#is_kontrak').click(function(){
	if($(this).is(':checked')) {
		$('#tanggal_po').closest('.row').addClass('hidden');
		$('#tanggal_po').val('');
		$('#tanggal_po').removeAttr('data-validation');
	} else {
		$('#tanggal_po').closest('.row').removeClass('hidden');
		$('#tanggal_po').addClass('data-validation','required');		
	}
});

