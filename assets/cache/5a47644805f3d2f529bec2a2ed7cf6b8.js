
function toList() {
	window.location = base_url + 'pengadaan_v/undangan_pengadaan';
}
function batalDaftar() {
	var id_remove = $('#btn-batal').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan_v/undangan_pengadaan/unreg',
		data : {id: id_remove},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','toList');
		}
	});
}
$('#setuju').click(function(){
	if($(this).is(':checked')) {
		$('button[type="submit"]').removeAttr('disabled');
	} else {
		$('button[type="submit"]').attr('disabled',true);
	}
});
$('#btn-batal').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','batalDaftar');
});
function checkSetuju() {
	var res = true;
	if(!$('#setuju').is(':checked')) {
		res = false;
		cAlert.open(lang.anda_harus_menyetujui_syarat_dan_ketentuan_proses_lelang)
	}

	return res;
}
