
var data_metode = {};
var pengajuan = {};
var jadwal = {};
var is_edit = false;
var idx = 999;
function formOpen() {
	is_edit = true;
	var response = response_edit;
	$('#additional-file, #detilBobotTeknis').html('');
	$('.jadwal').each(function(){
		$(this).val($(this).attr('data-value'));
	});
	$('.zona').val('WIB').trigger('change');
	$('#ajukan').prop('checked',true);
	$("#ajukan-lagi").hide();
	$('#step3 tbody').html('');
	if(typeof response.id != 'undefined') {
		if(response.status_proses == '8') {
			$('#ajukan-lagi').show();
		}
		$('#nomor_pengajuan').html('<option value="'+response.nomor_pengajuan+'">'+response.nomor_pengajuan+' | '+response.nama_pengadaan+'</option>').trigger('change');
		var konten = '<option value="" data-type=""></option>';
		$.each(response.metode_pengadaan,function(k,v){
			konten += '<option value="'+v.id+'" data-type="'+v.tipe+'" data-limit="'+v.limit+'">'+v.metode_pengadaan+'</option>';
		});
		$('#id_metode_pengadaan').html(konten).val(response.id_metode_pengadaan).trigger('change');
		$('#bobot_harga').val(cPercent(response.bobot_harga));
		$('#bobot_teknis').val(cPercent(response.bobot_teknis));
		var konten = '';
		$.each(response.vendor,function(k,v){
			konten += '<option value="'+v.id+'">'+v.nama+'</option>';
		});
		$('#id_vendor').html(konten);
		if(response.id_vendor != null && response.id_vendor.length > 0) {
			$.each(response.id_vendor, function(k,v){
				$('#id_vendor').find('[value="'+v+'"]').prop('selected',true);
			});
			$('#id_vendor').trigger('change');
		}
		if(response.id_bidang_usaha.length > 0) {
			$.each(response.id_bidang_usaha, function(k,v){
				$('#id_bidang_usaha').find('[value="'+v+'"]').prop('selected',true);
			});
			$('#id_bidang_usaha').trigger('change');
		}
		if(response.id_kategori_rekanan.length > 0) {
			$.each(response.id_kategori_rekanan, function(k,v){
				$('#id_kategori_rekanan').find('[value="'+v+'"]').prop('selected',true);
			});
			$('#id_kategori_rekanan').trigger('change');
		}
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
				+ '<a href="'+base_url+'assets/uploads/inisiasi_pengadaan/'+z+'" target="_blank" class="btn btn-info btn-icon-only"><i class="fa-download"></i></a>'
				+ '</div>'
				+ '</div>'
				+ '</div>'
				+ '<div class="col-sm-2 col-3">'
				+ '<button type="button" class="btn btn-danger btn-remove btn-block btn-icon-only"><i class="fa-times"></i></button>'
				+ '</div>'
				+ '</div>';
			$('#additional-file').append(konten);
		});
		parsingDokumenPersyaratan(response.dokumen_persyaratan, response.mandatori);
		$.each(response.pembobotan[0],function(k1,v1){
			var p = $('#detilBobotTeknis [data-idx="'+v1.id_persyaratan+'"]');
			p.find('.detil_bobot_keterangan').val(v1.deskripsi);
			p.find('select').val(v1.tipe_rumus).trigger('change');
			p.find('.detail_bobot').val(cPercent(v1.bobot));
			$.each(response.pembobotan[v1.id],function(k2,v2){
				var konten = '<tr>';
				if(v1.tipe_rumus == 'range') {
					konten += '<td colspan="2"><input type="text" name="child_batas_bawah['+v1.id_persyaratan+'][]" class="form-control" data-validation="required|number" autocomplete="off" value="'+v2.batas_bawah+'"></td>';
					konten += '<td colspan="2"><input type="text" name="child_batas_atas['+v1.id_persyaratan+'][]" class="form-control" data-validation="required|number" autocomplete="off" value="'+v2.batas_atas+'"></td>';
				} else {
					konten += '<td colspan="4"><input type="text" name="child_deskripsi['+v1.id_persyaratan+'][]" class="form-control" data-validation="required" autocomplete="off" value="'+v2.deskripsi+'"></td>';
				}
				konten += '<td><input type="text" name="child_bobot['+v1.id_persyaratan+'][]" class="form-control percent child_bobot" data-validation="required" autocomplete="off" maxlength="6" value="'+cPercent(v2.bobot)+'"></td>';
				konten += '<td><button type="button" class="btn btn-sm btn-danger btn-icon-only btn-remove-bobot"><i class="fa-times"></i></button></td>';
				konten += '</tr>';
				p.find('tbody').append(konten);
				$(".percent:not([readonly])").each(function(){
					var placeholder = '';
					if(typeof $(this).attr('placeholder') != 'undefined') placeholder = $(this).attr('placeholder');
					$(this).mask('099,09',{placeholder : placeholder});
				});
			});
		});
	} else {
		view_combo();
	}
	is_edit = false;
}
$(document).on('click','.btn-remove',function(){
	$(this).closest('.form-group').remove();
});
function view_combo() {
	$.ajax({
		url			: base_url + 'pengadaan/inisiasi_pengadaan/get_combo',
		dataType	: 'json',
		success     : function(response){
			pengajuan 	= response.pengajuan;
			jadwal 		= response.jadwal;
			var konten 	= '<option value=""></option>';
			$.each(pengajuan,function(k,v){
				konten += '<option value="'+v.nomor_pengajuan+'">'+v.nomor_pengajuan+' | '+v.nama_pengadaan+'</option>';
			});
			$('#nomor_pengajuan').html(konten).trigger('change');
			data_metode = response.metode_pengadaan;
			parsingDokumenPersyaratan(response.dokumen_persyaratan, response.mandatori);
		}
	});
}
$('#id_kategori_rekanan').change(function(){
	$('#id_vendor').html();
	if(!is_edit && proccess) {
		readonly_ajax = false;
		var last_value = $('#id_vendor').val();
		$.ajax({
			url : base_url + 'pengadaan/inisiasi_pengadaan/get_vendor',
			data : {id_kategori : $(this).val()},
			type : 'post',
			dataType : 'json',
			success : function(response) {
				var konten = '';
				$.each(response,function(k,v){
					konten += '<option value="'+v.id+'">'+v.nama+'</option>';
				});
				$('#id_vendor').html(konten);
				$.each(last_value,function(k,v){
					$('#id_vendor').find('[value="'+v+'"]').prop('selected',true);
				});
				$('#id_vendor').trigger('change');
				readonly_ajax = true;
			}
		});
	}
});
$('#nomor_pengajuan').change(function(){
	if(typeof pengajuan[$(this).val()] !== 'undefined') {
		var p = pengajuan[$(this).val()];
		$('#nama_pengadaan').val(p.nama_pengadaan);
		$('#tanggal_pengadaan').val(cDate(p.tanggal_pengadaan));
		$('#mata_anggaran').val(p.mata_anggaran);
		$('#unit_kerja').val(p.unit_kerja);
		$('#nama_divisi').val(p.nama_divisi);
		$('#besar_anggaran').val(customFormat(p.besar_anggaran));
		$('#usulan_hps').val(customFormat(p.usulan_hps));
		$('#hps_panitia').val(customFormat(p.hps_panitia));
	}
	var konten = '<option value="" data-type=""></option>';
	$.each(data_metode[$(this).val()],function(k,v){
		konten += '<option value="'+v.id+'" data-type="'+v.tipe+'" data-limit="'+v.limit+'">'+v.metode_pengadaan+'</option>';
	});
	$('#id_metode_pengadaan').html(konten).trigger('change');
	$('.lokasi, .tanggal_awal, .tanggal_akhir').val('');
	if(typeof jadwal[$(this).val()] !== 'undefined') {
		$.each(jadwal[$(this).val()],function(n,z){
			$('[name="lokasi['+z.id_m_penjadwalan+']"]').val(z.lokasi);
			$('[name="tanggal_awal['+z.id_m_penjadwalan+']"]').val(cDate(z.tanggal_awal,true));
			$('[name="tanggal_akhir['+z.id_m_penjadwalan+']"]').val(cDate(z.tanggal_akhir,true));
		});
	}
});
$('#id_jenis_pengadaan').change(function(){
	$('#bobot_harga').val($(this).find(':selected').attr('data-bobot_harga'));
	$('#bobot_teknis').val($(this).find(':selected').attr('data-bobot_teknis'));
});
$('#id_metode_pengadaan').change(function() {
	if( $(this).find(':selected').attr('data-type') == 'Pemilihan Langsung' || $(this).find(':selected').attr('data-type') == 'Penunjukan Langsung' || $(this).find(':selected').attr('data-type') == 'Jasa Langsung' ){
		$('#id_vendor').closest('.form-group').show();
		$('#id_vendor').attr('data-validation','required');
	}else{
		$('#id_vendor').closest('.form-group').hide();
		$('#id_vendor').find(':selected').prop('selected',false).trigger('change');
		$('#id_vendor').removeAttr('data-validation');
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
function detail_callback(id){
	$.get(base_url+'pengadaan/inisiasi_pengadaan/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}
function openForm() {
	if( $('[data-openid]').attr('data-openid') != '0' && $('.btn-input[data-id="'+$('[data-openid]').attr('data-openid')+'"]').length == 1 ) {
		$('.btn-input[data-id="'+$('[data-openid]').attr('data-openid')+'"]').trigger('click');
		$('[data-openid]').removeAttr('data-openid');
	}
}
function checkLimitRekanan() {
	var res 	= true;
	var k 		= $('#id_metode_pengadaan').find(':selected');
	if( k.attr('data-type') == 'Pemilihan Langsung' || k.attr('data-type') == 'Penunjukan Langsung') {
		var limit 	= toNumber(k.attr('data-limit'));
		var jumlah 	= $('#id_vendor').find(':selected').length;
		if(jumlah < limit) {
			cAlert.open(lang.minimal_rekanan_yang_diundang + ' : ' + limit);
			res = false;
		}
	}
	return res;
}
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
$(document).on('click','.btn-add-kelengkapan',function(){
	var konten = '';
	if($(this).hasClass('add-sub')) {
		konten += '<tr data-idx="'+idx+'" data-parent="'+$(this).attr('data-idx')+'">';
		konten += '<td width="30">&nbsp;</td>';
		konten += '<td colspan="2"><input type="text" name="deskripsi['+$(this).attr('data-grup')+']['+$(this).attr('data-idx')+']['+idx+']" class="form-control deskripsi_kelengkapan" autocomplete="off" data-validation="required"></td>';
		konten += '<td>&nbsp;</td>';
		konten += '<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove-kelengkapan" data-idx="'+idx+'"><i class="fa-times"></i></button></td>';
	} else {
		konten += '<tr data-idx="'+idx+'">';
		konten += '<td colspan="3"><input type="text" name="deskripsi['+$(this).attr('data-grup')+'][0]['+idx+']" class="form-control deskripsi_kelengkapan" autocomplete="off" data-validation="required"></td>';
		konten += '<td><button type="button" class="btn btn-success btn-icon-only btn-sm btn-add-kelengkapan add-sub" data-idx="'+idx+'" data-grup="'+$(this).attr('data-grup')+'"><i class="fa-plus"></i></button></td>';
		konten += '<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove-kelengkapan" data-idx="'+idx+'"><i class="fa-times"></i></button></td>';
	}
	konten += '</tr>';
	$('#grup-'+$(this).attr('data-grup')).append(konten);
	detilBobotTeknis();
	idx++;
});
$(document).on('keyup','#grup-dokumen_teknis .deskripsi_kelengkapan',function(){
	detilBobotTeknis();
});
$(document).on('click','.btn-remove-kelengkapan',function(){
	$(this).closest('tr').remove();
	$('#detilBobotTeknis .table-responsive[data-idx="'+$(this).attr('data-idx')+'"]').remove();
	$('tr[data-parent="'+$(this).attr('data-idx')+'"]').remove();
});
function parsingDokumenPersyaratan(d,m) {
	$.each(d,function(x,y){
		if(m[x] == "1") {
			$('#mandatori_'+x).prop('checked',true);
		} else {
			$('#mandatori_'+x).prop('checked',false);
		}

		$.each(d[x][0],function(j,k){
			var konten = '<tr data-idx="'+k.id+'">'
				+ '<td colspan="3"><input type="text" name="deskripsi['+k.grup+'][0]['+k.id+']" class="form-control deskripsi_kelengkapan" autocomplete="off" data-validation="required|max-length:150" value="'+k.deskripsi+'"></td>'
				+ '<td><button type="button" class="btn btn-success btn-icon-only btn-sm btn-add-kelengkapan add-sub" data-idx="'+k.id+'" data-grup="'+k.grup+'"><i class="fa-plus"></i></button></td>'
				+ '<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove-kelengkapan" data-idx="'+k.id+'"><i class="fa-times"></i></button></td>'
			+ '</tr>';
			$('#grup-'+k.grup).append(konten);
			$.each(d[x][k.id],function(e,f){
				var konten = '<tr data-idx="'+f.id+'" data-parent="'+k.id+'"><td width="30">&nbsp;</td>'
					+ '<td colspan="2"><input type="text" name="deskripsi['+k.grup+']['+k.id+']['+f.id+']" class="form-control deskripsi_kelengkapan" autocomplete="off" data-validation="required|max-length:150" value="'+f.deskripsi+'"></td>'
					+ '<td>&nbsp;</td>'
					+ '<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove-kelengkapan" data-idx="'+f.id+'"><i class="fa-times"></i></button></td>'
				+ '</tr>';
				$('#grup-'+k.grup).append(konten);
			});
		});
	});
	detilBobotTeknis();
}

function detilBobotTeknis() {
	$('#grup-dokumen_teknis tr').each(function(){
		if(typeof $(this).attr('data-parent') == 'undefined') {
			if($('#detilBobotTeknis .table-responsive[data-idx="'+$(this).attr('data-idx')+'"]').length == 1) {
				$('#detilBobotTeknis .table-responsive[data-idx="'+$(this).attr('data-idx')+'"]').find('.detil_bobot_keterangan').val($(this).find('input').val());
			} else {
				var konten = '<div class="table-responsive" data-idx="'+$(this).attr('data-idx')+'">' +
					'<table class="table table-bordered table-detail">' +
						'<thead>' +
							'<tr>' +
								'<th colspan="3"><input type="hidden" name="idx[]" value="'+$(this).attr('data-idx')+'" /><input type="text" name="detil_bobot_keterangan[]" value="'+$(this).find('input').val()+'" autocomplete="off" class="form-control detil_bobot_keterangan" data-validation="required|max-length:150" /></th>' +
								'<th width="250">' +
									'<select class="form-control cara-hitung" name="cara_perhitungan[]" data-validation="required">' +
										'<option value="terbanyak">' + lang.berdasarkan_poin_terbanyak + '</option>' +
										'<option value="terendah">' + lang.berdasarkan_poin_terendah + '</option>' +
										'<option value="acuan">' + lang.berdasarkan_acuan + '</option>' +
										'<option value="range">' + lang.berdasarkan_range_angka + '</option>' +
									'</select>' +
								'</th>' +
								'<th width="150">' +
									'<div class="input-group">' +
										'<div class="input-group-prepend"><span class="input-group-text">'+lang.bobot+'</span></div>' +
										'<input type="text" name="detail_bobot[]" class="form-control percent detail_bobot" maxlength="6" autocomplete="off" />' +
									'</div>' +
								'</th>' +
								'<th width="50">&nbsp;</th>' +
							'</tr>' +
							'<tr class="header">' +
								'<th colspan="4">' + lang.poin_yang_dinilai + '</th>' +
								'<th>' + lang.bobot + '</th>' +
								'<th><button type="button" class="btn btn-success btn-sm btn-icon-only btn-add-bobot"><i class="fa-plus"></i></button></th>' +
							'</tr>' +
						'</thead>' +
						'<tbody></tbody>' +
					'</table>' +
				'</div>';
				$('#detilBobotTeknis').append(konten);
				$('#detilBobotTeknis .table-responsive').last().find('select').select2({
					minimumResultsForSearch: Infinity,
					dropdownParent : $('#detilBobotTeknis .table-responsive').last().find('select').parent(),
					width: '100%'
				});
				$(".percent:not([readonly])").each(function(){
					var placeholder = '';
					if(typeof $(this).attr('placeholder') != 'undefined') placeholder = $(this).attr('placeholder');
					$(this).mask('099,09',{placeholder : placeholder});
				});
			}
		}
	});
}
$(document).on('change','.cara-hitung',function(){
	$(this).closest('table').find('tbody').html('');
	var konten = '';
	if($(this).val() == 'acuan') {
		konten += '<th colspan="4">' + lang.acuan_nilai + '</th>' +
			'<th>' + lang.bobot + '</th>' +
			'<th><button type="button" class="btn btn-success btn-sm btn-icon-only btn-add-bobot"><i class="fa-plus"></i></th>';
	} else if($(this).val() == 'range') {
		konten += '<th colspan="2">' + lang.batas_bawah + '</th>' +
			'<th colspan="2" width="300">' + lang.batas_atas + '</th>' +
			'<th>' + lang.bobot + '</th>' +
			'<th><button type="button" class="btn btn-success btn-sm btn-icon-only btn-add-bobot"><i class="fa-plus"></i></th>';
	} else {
		konten += '<th colspan="4">' + lang.poin_yang_dinilai + '</th>' +
			'<th>' + lang.bobot + '</th>' +
			'<th><button type="button" class="btn btn-success btn-sm btn-icon-only btn-add-bobot"><i class="fa-plus"></i></th>';
	}
	$(this).closest('table').find('.header').html(konten);
});
function checkBobot() {
	var res 		= true;
	var b_harga 	= toNumber($('#bobot_harga').val());
	var b_teknis 	= toNumber($('#bobot_teknis').val());
	if(b_harga + b_teknis != 100) {
		res = false;
		$('#bobot_teknis,#bobot_harga').addClass('is-invalid');
		$('#bobot_teknis,#bobot_harga').parent().parent().find('.error').remove();
		$('#bobot_teknis,#bobot_harga').parent().parent().append('<span class="error">' + lang.jumlah_bobot_harus_100 + '</span>');
	}
	var jml_detail	= 0;
	$('.detail_bobot').each(function(){
		jml_detail += toNumber($(this).val());
	});
	if($('#detilBobotTeknis .table-responsive').length > 0 && jml_detail != 100) {
		res = false;
		$('.detail_bobot').addClass('is-invalid');
		$('.detail_bobot').parent().parent().find('.error').remove();
		$('.detail_bobot').parent().parent().append('<span class="error">' + lang.jumlah_sub_bobot_teknis_harus_100 + '</span>');
	}
	var sum_child = 0;
	$('#detilBobotTeknis .table-responsive').each(function(){
		if($(this).find('tbody').find('tr').length > 0) {
			sum_child++;
		}
		var p = $(this).find('table');
		var t = p.find('thead').find('select').val();
		var c = p.find('thead').find('.detail_bobot').val();

		if(t == 'terendah' || t == 'terbanyak') {
			var jml_child = 0;
			p.find('.child_bobot').each(function(){
				jml_child += toNumber($(this).val());
			});
			if(jml_child != toNumber(c)) {
				res = false;
				p.find('.child_bobot').addClass('is-invalid');
				p.find('.child_bobot').parent().find('.error').remove();
				p.find('.child_bobot').parent().append('<span class="error">' + lang.jumlah_harus + ' ' + c + '</span>');
			}
		} else {
			p.find('.child_bobot').each(function(){
				if(toNumber($(this).val()) > toNumber(c)) {
					res = false;
					$(this).addClass('is-invalid');
					$(this).parent().find('.error').remove();
					$(this).parent().append('<span class="error">' + lang.maksimal + ' ' + c + '</span>');
				}
			});
		}
	});
	if(sum_child != $('#detilBobotTeknis .table-responsive').length) {
		cAlert.open(lang.semua_detil_bobot_teknis_harus_dijabarkan);
		res = false;
	}
	return res;
}
$('#bobot_teknis,#bobot_harga').keyup(function(){
	$('#bobot_teknis,#bobot_harga').parent().parent().find('.error').remove();
	$('#bobot_teknis,#bobot_harga').removeClass('is-invalid');
});
$(document).on('keyup','.detail_bobot',function(){
	$('.detail_bobot').parent().parent().find('.error').remove();
	$('.detail_bobot').removeClass('is-invalid');
});
$(document).on('keyup','.child_bobot',function(){
	$(this).closest('tbody').find('.child_bobot').each(function(){
		$(this).removeClass('is-invalid');
		$(this).parent().find('.error').remove();
	});
});
$(document).on('click','.btn-add-bobot',function(){
	var p = $(this).closest('table');
	var i = p.find('thead').find('input[type="hidden"]').val();
	var t = p.find('thead').find('select').val();
	var konten = '<tr>';
	if(t == 'range') {
		konten += '<td colspan="2"><input type="text" name="child_batas_bawah['+i+'][]" class="form-control" data-validation="required|number" autocomplete="off"></td>';
		konten += '<td colspan="2"><input type="text" name="child_batas_atas['+i+'][]" class="form-control" data-validation="required|number" autocomplete="off"></td>';
	} else {
		konten += '<td colspan="4"><input type="text" name="child_deskripsi['+i+'][]" class="form-control" data-validation="required" autocomplete="off"></td>';
	}
	konten += '<td><input type="text" name="child_bobot['+i+'][]" class="form-control percent child_bobot" data-validation="required" autocomplete="off" maxlength="6"></td>';
	konten += '<td><button type="button" class="btn btn-sm btn-danger btn-icon-only btn-remove-bobot"><i class="fa-times"></i></button></td>';
	konten += '</tr>';
	p.find('tbody').append(konten);
	$(".percent:not([readonly])").each(function(){
		var placeholder = '';
		if(typeof $(this).attr('placeholder') != 'undefined') placeholder = $(this).attr('placeholder');
		$(this).mask('099,09',{placeholder : placeholder});
	});
});
$(document).on('click','.btn-remove-bobot',function(){
	$(this).closest('tr').remove();
});
