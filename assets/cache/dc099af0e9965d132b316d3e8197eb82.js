
function formOpen() {
	var response = response_edit;
	$('#nama_rekanan').val(response.nm_vendor);
	$('#id_vendor').val(response.i_vendor);
	$('#alamat').val(response.alamat_vendor + ', ' + response.nama_kelurahan + ', ' + response.nama_kecamatan + ', ' + response.nama_kota + ', ' + response.nama_provinsi + ' - ' + response.kode_pos);
}
