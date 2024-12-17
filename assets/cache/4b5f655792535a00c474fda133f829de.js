
$(document).on('click','.btn-jawab',function(){
	var id = $(this).attr('data-id');
	$.ajax({
		url 	: base_url + 'pengadaan/sanggah/get_data',
		data 	: {id:id},
		type 	: 'post',
		dataType: 'json',
		success : function(r){
			$('#id').val(id);
			var konten = '<tr><th width="200">' + lang.pesan + '</th><td>'+r.pesan+'</td></tr>';
			konten += '<tr><th>' + lang.file_pendukung + '</th><td>';
			if(r.file_pendukung == '') konten += '-';
			else {
				konten += '[ <a href="'+base_url+'assets/uploads/sanggah/'+r.file_pendukung+'" target="_blank">'+lang.unduh+'</a> ]';
			}
			konten += '</td></tr>';
			if(r.jawaban != '') {
				konten += '<tr><th>' + lang.jawaban + '</th><td>'+r.jawaban+'</td></tr>';
				konten += '<tr><th>' + lang.file_jawaban + '</th><td>';
				if(r.file_jawaban == '') konten += '-';
				else {
					konten += '[ <a href="'+base_url+'assets/uploads/sanggah/'+r.file_jawaban+'" target="_blank">'+lang.unduh+'</a> ]';
				}
			}
			$('#table-info').html(konten);
			$('#modal-jawab').modal();
		}
	});
});
$('#btn-batal').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','prosesBatal');
});
function prosesBatal() {
	var id = $('#btn-proses').attr('data-id');
	$.ajax({
		url 	: base_url + 'pengadaan/sanggah/pembatalan',
		data 	: {id:id},
		type 	: 'post',
		dataType: 'json',
		success : function(r){
			cAlert.open(r.message,r.status,'reload');
		}
	});
}
$('#btn-proses').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','proses');
});
function proses() {
	var id = $('#btn-proses').attr('data-id');
	$.ajax({
		url 	: base_url + 'pengadaan/sanggah/proses',
		data 	: {id:id},
		type 	: 'post',
		dataType: 'json',
		success : function(r){
			cAlert.open(r.message,r.status,'reload');
		}
	});
}
$('#btn-reinisiasi').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','inisiasiUlang');
});
function inisiasiUlang() {
	var nomor_pengadaan = $('#btn-reinisiasi').attr('data-pengadaan');
	$.ajax({
		url : base_url + 'pengadaan/sanggah/inisiasi_ulang',
		data : {nomor_pengadaan : nomor_pengadaan},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
