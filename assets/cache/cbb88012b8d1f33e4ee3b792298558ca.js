
var pengajuan = {};
var jadwal = {};
function formOpen() {
	var response = response_edit;
	$('#additional-file').html('');
	$('.jadwal').each(function(){
		$(this).val($(this).attr('data-value'));
	});
	$('.zona').val('WIB').trigger('change');
	if(typeof response.id != 'undefined') {
		$('#nomor_pengajuan').html('<option value="'+response.nomor_pengajuan+'">'+response.nomor_pengajuan+' | '+response.nama_pengadaan+'</option>').trigger('change');
		$.each(response.detail,function(n,z){
			$('[name="lokasi['+z.id_m_penjadwalan+']"]').val(z.lokasi);
			$('[name="tanggal_awal['+z.id_m_penjadwalan+']"]').val(cDate(z.tanggal_awal,true));
			$('[name="tanggal_akhir['+z.id_m_penjadwalan+']"]').val(cDate(z.tanggal_akhir,true));
			$('[name="zona_waktu['+z.id_m_penjadwalan+']"]').val(z.zona_waktu).trigger('change');
		});
		$.each(response.file,function(n,z){
			var konten = '<div class="form-group row">'
				+ '<div class="col-sm-3 col-4 offset-sm-3">'
				+ '<input type="text" class="form-control" autocomplete="off" value="'+n+'" name="keterangan_file[]" placeholder="'+lang.keterangan+'" data-validation="required" aria-label="'+lang.keterangan+'">'
				+ '</div>'
				+ '<div class="col-sm-4 col-5">'
				+ '<input type="hidden" class="form-control" name="file[]" autocomplete="off" value="exist:'+z+'">'
				+ '<div class="input-group">'
				+ '<input type="text" class="form-control" autocomplete="off" disabled value="'+z+'">'
				+ '<div class="input-group-append">'
				+ '<a href="'+base_url+'assets/uploads/rks/'+z+'" target="_blank" class="btn btn-info btn-icon-only"><i class="fa-download"></i></a>'
				+ '</div>'
				+ '</div>'
				+ '</div>'
				+ '<div class="col-sm-2 col-3">'
				+ '<button type="button" class="btn btn-danger btn-remove btn-block btn-icon-only"><i class="fa-times"></i></button>'
				+ '</div>'
				+ '</div>';
			$('#additional-file').append(konten);
		});
	} else {
		view_combo();
	}
}
$(document).on('click','.btn-remove',function(){
	$(this).closest('.form-group').remove();
});
function view_combo() {
	$.ajax({
		url : base_url + 'pengadaan/rks/get_combo',
		dataType : 'json',
		success : function(response){
			pengajuan 	= response.pengajuan;
			jadwal 		= response.jadwal;
			var konten 	= '<option value=""></option>';
			$.each(pengajuan,function(k,v){
				konten += '<option value="'+v.nomor_pengajuan+'">'+v.nomor_pengajuan+' | '+v.nama_pengadaan+'</option>';
			});
			$('#nomor_pengajuan').html(konten).trigger('change');
		}
	});
}
$('#nomor_pengajuan').change(function(){
	if(typeof pengajuan[$(this).val()] !== 'undefined') {
		var p = pengajuan[$(this).val()];
		$('#nama_pengadaan').val(p.nama_pengadaan);
		$('#pemberi_tugas').val(p.pemberi_tugas);
		$('#mata_anggaran').val(p.mata_anggaran);
		$('#usulan_hps').val(customFormat(p.usulan_hps));
		$('#hps_panitia').val(customFormat(p.hps_panitia));
		$('#nama_divisi').val(p.nama_divisi);
		$('#metode_pengadaan').val(p.metode_pengadaan);
		$('#jenis_pengadaan').val(p.jenis_pengadaan);

		var tor = ['latar_belakang','spesifikasi','jumlah_kebutuhan','distribusi_kebutuhan','ruang_lingkup','jangka_waktu','lain_lain'];
		$.each(tor,function(k,v){
			$('#'+v).val(p[v]);
			CKEDITOR.instances[v].setData(decodeEntities(p[v]));
		});
	}
	$('.lokasi, .tanggal_awal, .tanggal_akhir').val('');
	if(typeof jadwal[$(this).val()] !== 'undefined') {
		$.each(jadwal[$(this).val()],function(n,z){
			$('[name="lokasi['+z.id_m_penjadwalan+']"]').val(z.lokasi);
			$('[name="tanggal_awal['+z.id_m_penjadwalan+']"]').val(cDate(z.tanggal_awal,true));
			$('[name="tanggal_akhir['+z.id_m_penjadwalan+']"]').val(cDate(z.tanggal_akhir,true));
			$('[name="zona_waktu['+z.id_m_penjadwalan+']"]').val(z.zona_waktu).trigger('change');
		});
	}
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
$(document).on('click','.detail-pengajuan',function(){
	$.get(base_url+'pengadaan/pengajuan/detail?no_pengajuan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});
$(document).on('click','.btn-print',function(){
	var id = encodeId($(this).attr('data-id'));
	$.redirect(base_url + 'pengadaan/rks/cetak/' + id, {} , 'get', '_blank');
});
function checkTanggal() {
	var res = true;
	$('.tanggal_akhir').each(function(){
		var tanggal_akhir 	= $(this).val();
		var tanggal_awal	= $(this).closest('.row').find('.tanggal_awal').val();
		if(tanggal_akhir) {
			var x_akhir = tanggal_akhir.split(' ');
			var x_awal 	= tanggal_awal.split(' ');
			if(x_akhir.length == 2 && x_awal.length == 2) {
				var d_akhir = x_akhir[0].split('/');
				var t_akhir = x_akhir[1].split(':');
				var akhir 	= d_akhir[2] + '-' + d_akhir[1] + '-' + d_akhir[0] + ' ' + t_akhir[0] + ':';
				akhir 		+= typeof t_akhir[1] !== 'undefined' && t_akhir[1] ? t_akhir[1]+':00' : '00:00';
				var time_akhir = new Date(akhir).getTime();

				var d_awal 	= x_awal[0].split('/');
				var t_awal 	= x_awal[1].split(':');
				var awal 	= d_awal[2] + '-' + d_awal[1] + '-' + d_awal[0] + ' ' + t_awal[0] + ':';
				awal 		+= typeof t_awal[1] !== 'undefined' && t_awal[1] ? t_awal[1]+':00' : '00:00';
				var time_awal = new Date(awal).getTime();

				if(parseInt(time_awal) >= parseInt(time_akhir)) {
					res = false;
					if($(this).parent().find('span.error').length == 0) {
						$(this).addClass('is-invalid');
						$(this).parent().append('<span class="error">' + lang.tidak_boleh_lebih_awal_dari_tanggal_mulai + '</span>');
					}
				}
			} else {
				$(this).val('');
				$(this).closest('.row').find('.tanggal_awal').val('');
				$(this).closest('.row').find('.lokasi').val('');
			}
		}
	});
	return res;
}
$('.tanggal_awal').on('apply.daterangepicker', function(ev, picker) {
	var tgl = $(this).closest('.row').find('.tanggal_akhir');
	tgl.removeClass('is-invalid');
	tgl.parent().find('span.error').remove();
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
