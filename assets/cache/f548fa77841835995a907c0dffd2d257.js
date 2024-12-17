
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
$('#upl-file').fileupload({
	autoUpload: false,
	dataType: 'text',
}).on('fileuploadadd', function(e, data) {
	$('#add-file').attr('disabled',true);
	data.process();
	is_autocomplete = true;
}).on('fileuploadprocessalways', function (e, data) {
	if (data.files.error) {
		cAlert.open(lang.tidak_dapat_mengunggah_file_ini);
		$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
	} else {
		data.submit();
	}
	is_autocomplete = false;
}).on('fileuploadprogressall', function (e, data) {
	var progress = parseInt(data.loaded / data.total * 100, 10);
	$('#add-file').text(progress + '%');
}).on('fileuploaddone', function (e, data) {
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
		serviceUrl: base_url + 'pengadaan/klarifikasi/get_user/' + $('#form-klarifikasi').attr('data-id'),
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
