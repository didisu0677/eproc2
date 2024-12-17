
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
		url : base_url + 'pengadaan/penawaran/get_penilaian',
		data : {
			id_vendor : id_vendor,
			nomor_pengadaan : $(this).attr('data-pengadaan')
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
			$('#nilai_total_penawaran').val(response.nilai_total_penawaran);
			$('#nilai_jaminan_penawaran').val(response.nilai_jaminan_penawaran);
			$.each(response.persyaratan,function(k1,v1){
				if(v1.sah == '1') {
					$('#sah'+v1.id_persyaratan).prop('checked',true);
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
	var nomor_pengadaan = $('#btn-batal').attr('data-pengadaan');
	$.ajax({
		url : base_url + 'pengadaan/penawaran/pembatalan',
		data : {nomor_pengadaan : nomor_pengadaan},
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
		url : base_url + 'pengadaan/penawaran/inisiasi_ulang',
		data : {nomor_pengadaan : nomor_pengadaan},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}

