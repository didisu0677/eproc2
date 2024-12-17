
$('#create-berita-acara').click(function(e){
	e.preventDefault();
	$('#modal-berita-acara').modal();
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
$(document).on('click','.btn-penilaian',function(){
	$('#form-penilaian')[0].reset();
	var id_vendor = $(this).attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/evaluasi/get_penilaian',
		data : {
			id_vendor : id_vendor,
			nomor_pengajuan : $(this).attr('data-pengajuan')
		},
		type : 'post',
		dataType : 'json',
		success : function(response){
			$('#id_vendor').val(id_vendor);
			$('#nama-rekanan').text(response.nama_vendor);
			$('#alamat-rekanan').html(response.alamat_vendor);
			$('#download-dokumen-persyaratan').attr('href',response.dok_persyaratan);
			$('#download-dokumen-administrasi').attr('href',response.dok_administrasi);
			$('#download-dokumen-teknis').attr('href',response.dok_teknis);
			$('#download-dokumen-penawaran').attr('href',response.dok_penawaran);
			$('#password-dokumen-persyaratan').text(response.pass_persyaratan);
			$('#password-dokumen-administrasi').text(response.pass_administrasi);
			$('#password-dokumen-teknis').text(response.pass_teknis);
			$('#password-dokumen-penawaran').text(response.pass_penawaran);
			$('#penawaran_harga').val(response.nilai_total_penawaran);
			$.each(response.bobot,function(k,v){
				$('#v_bobot'+v.id_pembobotan).val(v._value);
				if($('#v_bobot'+v.id_pembobotan).hasClass('select2')) {
					$('#v_bobot'+v.id_pembobotan).trigger('change');
				}
			});
			$('#modal-penilaian').modal();
		}
	});
});
$(document).ready(function(){
	cAutocomplete();
});
function add_row_anggota() {
	konten = '<div class="form-group row">'
			+ '<div class="offset-sm-3 col-sm-7 col-9">'
			+ '<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota">'
			+ '<input type="hidden" name="id_anggota[]" class="id_anggota">'
			+ '</div>'
			+ '<div class="col-sm-2 col-3">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>';
	$('#additional-anggota').append(konten);
	cAutocomplete();
}
$('.btn-add-anggota').click(function(){
	add_row_anggota();
});
$(document).on('click','.btn-remove-anggota',function(){
	$(this).closest('.form-group').remove();
});
$(document).on('blur','.anggota',function(){
	if($(this).parent().find('.id_anggota').val() == '0' || $(this).parent().find('.id_anggota').val() == '') {
		$(this).val('');
	}
});
function cAutocomplete() {
	$('.anggota').autocomplete({
		serviceUrl: base_url + 'pengadaan/penawaran/get_user/' + $('#form-penawaran').attr('data-id'),
		showNoSuggestionNotice: true,
		noSuggestionNotice: lang.data_tidak_ditemukan,
        onSearchStart: function(query) {
            readonly_ajax = false;
            is_autocomplete = true;
            if($(this).parent().find('.autocomplete-spinner').length == 0) {
                $(this).parent().append('<i class="fa-spinner spin autocomplete-spinner"></i>');
            }
        }, onSearchComplete: function (query, suggestions) {
            is_autocomplete = false;
            $(this).parent().find('.autocomplete-spinner').remove();
        }, onSearchError: function (query, jqXHR, textStatus, errorThrown) {
            is_autocomplete = false;
            $(this).parent().find('.autocomplete-spinner').remove();
        }, onSelect: function (suggestion) {
			$(this).parent().find('.id_anggota').val(suggestion.data);
			var n = 0;
			$('.id_anggota').each(function(){
				if($(this).val() == suggestion.data) n++;
			});
			if(n > 1) {
				$(this).parent().find('.id_anggota').val('');
				$(this).val('');
			}
		}
	});
}
$('#btn-batal').click(function(){
	cConfirm.open(lang.anda_yakin_membatalkan_pengadaan_ini,'batalPengadaan');
});
function batalPengadaan() {
	var nomor_pengajuan = $('#btn-batal').attr('data-pengajuan');
	$.ajax({
		url : base_url + 'pengadaan/penawaran/pembatalan',
		data : {nomor_pengajuan : nomor_pengajuan},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-pembobotan').click(function(){
	$('#modal-pembobotan').modal();
});
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
	if(jml_detail != 100) {
		res = false;
		$('.detail_bobot').addClass('is-invalid');
		$('.detail_bobot').parent().parent().find('.error').remove();
		$('.detail_bobot').parent().parent().append('<span class="error">' + lang.jumlah_bobot_harus_100 + '</span>');
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
		res = false;
		cAlert.open(lang.semua_detil_bobot_teknis_harus_dijabarkan);
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
