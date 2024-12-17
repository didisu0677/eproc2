
$('#create-berita-acara').click(function(e){
	e.preventDefault();
	$('#modal-berita-acara').modal();
});
$('#create-rks').click(function(e){
	e.preventDefault();
	$('#modal-rks .modal-body.wizard a').removeClass('active').attr('aria-selected','false');
	$('#modal-rks .modal-body.wizard li:first-child a').addClass('active').attr('aria-selected','true');
	$('#modal-rks .wizard .tab-content .tab-pane').removeClass('show').removeClass('active');
	$('#modal-rks .wizard .tab-content .tab-pane:first-child').addClass('show').addClass('active');
	if($('#id').val() == '0') {
		$('#modal-rks .modal-body.wizard .nav-tabs li a').removeAttr('data-toggle');
		$('#modal-rks .modal-body.wizard .nav-tabs li:first-child a').attr('data-toggle','tab');
	}
	$('#modal-rks').modal();
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('.form-group').remove();
});
$('#add-file').click(function(){
	$('#upl-file').click();
});
var accept 	= Base64.decode(upl_alw);
var regex 	= "(\.|\/)("+accept+")$";
var re 		= accept == '*' ? '*' : new RegExp(regex,"i");
$('#upl-file').fileupload({
	maxFileSize: upl_flsz,
	autoUpload: false,
	dataType: 'text',
	acceptFileTypes: re
}).on('fileuploadadd', function(e, data) {
	$('#add-file').attr('disabled',true);
	data.process();
	is_autocomplete = true;
}).on('fileuploadprocessalways', function (e, data) {
	if (data.files.error) {
		var explode = accept.split('|');
		var acc 	= '';
		$.each(explode,function(i){
			if(i == 0) {
				acc += '*.' + explode[i];
			} else if (i == explode.length - 1) {
				acc += ', ' + lang.atau + ' *.' + explode[i];
			} else {
				acc += ', *.' + explode[i];
			}
		});
		cAlert.open(lang.file_yang_diizinkan + ' ' + acc + '. ' + lang.ukuran_file_maks + ' : ' + (upl_flsz / 1024 / 1024) + 'MB');
		$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
	} else {
		data.submit();
	}
	is_autocomplete = false;
}).on('fileuploadprogressall', function (e, data) {
	var progress = parseInt(data.loaded / data.total * 100, 10);
	$('#add-file').text(progress + '%');
}).on('fileuploaddone', function (e, data) {
	if(data.result == 'invalid' || data.result == '') {
		cAlert.open(lang.gagal_menunggah_file,'error');
	} else {
		var spl_result = data.result.split('/');
		if(spl_result.length == 1) spl_result = data.result.split('\\');
		if(spl_result.length > 1) {
			var spl_last_str = spl_result[spl_result.length - 1].split('.');
			if(spl_last_str.length == 2) {
				var filename = data.result;
				var f = filename.split('/');
				var fl = filename.split('temp');
				var fl_link = base_url + 'assets/uploads/temp' + fl[1];
				var konten = '<div class="form-group row">'
							+ '<div class="col-sm-3 col-4 offset-sm-3">'
							+ '<input type="text" class="form-control" autocomplete="off" value="" name="keterangan_file[]" placeholder="'+lang.keterangan+'" data-validation="required" aria-label="'+lang.keterangan+'">'
							+ '</div>'
							+ '<div class="col-sm-4 col-5">'
							+ '<input type="hidden" class="form-control" name="file[]" autocomplete="off" value="'+data.result+'">'
							+ '<div class="input-group">'
							+ '<input type="text" class="form-control" autocomplete="off" disabled value="'+f[f.length - 1]+'">'
							+ '<div class="input-group-append">'
							+ '<a href="'+fl_link+'" target="_blank" class="btn btn-info btn-icon-only"><i class="fa-download"></i></a>'
							+ '</div>'
							+ '</div>'
							+ '</div>'
							+ '<div class="col-sm-2 col-3">'
							+ '<button type="button" class="btn btn-danger btn-remove btn-block btn-icon-only"><i class="fa-times"></i></button>'
							+ '</div>'
							+ '</div>';
				$('#additional-file').append(konten);
			} else {
				cAlert.open(lang.file_gagal_diunggah,'error');
			}
		} else {
			cAlert.open(lang.file_gagal_diunggah,'error');						
		}
	}
	$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
	is_autocomplete = false;
}).on('fileuploadfail', function (e, data) {
	cAlert.open(lang.gagal_menunggah_file,'error');
	$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
	is_autocomplete = false;
}).on('fileuploadalways', function() {
});
$('#btn-transkrip').click(function(e){
	e.preventDefault();
	var params = {
		'id_export' 		: $(this).attr('data-id_chat'),
		'periode' 			: '',
		'csrf_token' 		: $(this).attr('data-key')
	};
	var url = base_url + 'settings/obrolan/export';
	$.redirect(url, params, "POST", "_blank"); 
});
$('#tutup_negosiasi').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','tutupNegosiasi');
});
function tutupNegosiasi() {
	var id = $('#id_klarifikasi').val();
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/tutup_negosiasi',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-peninjauan').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','kembaliPeninjauan');
});
function kembaliPeninjauan() {
	var id = $('#btn-peninjauan').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/kembali_peninjauan',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-next-negosiasi').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','nextNegosiasi');
});
function nextNegosiasi() {
	var id = $('#btn-next-negosiasi').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/negosiasi_kandidat_lain',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-batal').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','batalPengadaan');
});
function batalPengadaan() {
	var id = $('#btn-batal').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/batal_pengadaan',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-mulai-sesi').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','mulaiSesi');
});
function mulaiSesi() {
	var id = $('#btn-mulai-sesi').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/mulai_sesi',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-tutup-lelang').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','tutupLelang');
});
function tutupLelang() {
	var id = $('#btn-tutup-lelang').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/tutup_lelang',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-proses').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','proses');
});
function proses() {
	var id = $('#btn-proses').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/proses',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-reinisiasi').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','inisiasiUlang');
});
function inisiasiUlang() {
	var nomor_pengadaan = $('#btn-reinisiasi').attr('data-pengadaan');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/inisiasi_ulang',
		data : {nomor_pengadaan : nomor_pengadaan},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$(document).ready(function(){
	if($('#counter').length == 1) {
		var counter = toNumber($('#counter').attr('data-value'));
		setInterval(function(){
			counter--;
			var menit = counter / 60;
			var minute = Math.floor(menit);
			var detik = counter - (minute * 60);
			var string_menit = minute < 10 ? '0' + minute : minute;
			var string_detik = detik < 10 ? '0' + detik : detik;
			$('#counter').text(string_menit+' : '+string_detik);
			if(counter <= 0) {
				reload();
			}
		},1000);
	}
});
function checkBatasHps() {
	var res 	= true;
	if( toNumber($('#batas_hps_atas').val()) < toNumber($('#batas_hps_bawah').val())) {
		if($('#batas_hps_atas').parent().find('span.error').length == 0) {
			$('#batas_hps_atas').addClass('is-invalid');
			$('#batas_hps_atas').parent().append('<span class="error">' + lang.tidak_boleh_lebih_kecil_dari_batas_hps_minimal + '</span>');
		}
		res = false;
	}
	return res;
}
$('.price_unit').keyup(function(){
	var j = moneyToNumber($(this).closest('tr').find('.quantity').text());
	var h = moneyToNumber($(this).val());
	var t = j * h;
	$(this).closest('tr').find('.total_value').text(customFormat(t));
	var t_bef_ppn = 0;
	$('.total_value').each(function(){
		t_bef_ppn += moneyToNumber($(this).text());
	});
	$('#total_sebelum_ppn').text(customFormat(t_bef_ppn));
});
function checkTotal() {
	var p_akhir = moneyToNumber($('[href="#collapseTwo"]').text());
	var p_awal = moneyToNumber($('#penawaran_awal').val());
	var ttl = moneyToNumber($('#total_hps_pembulatan').text());
	var batas = p_akhir;
	if(p_akhir == 0) {
		batas = p_awal;
	}
	if(ttl > batas) {
		cAlert.open(lang.maksimal_penawaran + ' = ' + customFormat(batas));
		return false;
	} else return true;
}
