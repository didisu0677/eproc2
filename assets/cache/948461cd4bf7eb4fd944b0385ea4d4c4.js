
function toList() {
	window.location = base_url + 'pengadaan_v/daftar_pengadaan_v';
}
function batalDaftar() {
	var id_remove = $('#btn-batal').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan_v/daftar_pengadaan_v/delete',
		data : {id: id_remove},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','toList');
		}
	});
}
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
