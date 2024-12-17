
$(document).on('click','.btn-penugasan',function(){
	$('#form-penugasan')[0].reset();
	$('#additional-anggota').html('');
	var id = $(this).attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/peninjauan_lapangan/init_penugasan',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			$('#id').val(id);
			$('#nomor_surat_tugas').val(response.nomor_surat_tugas);
			$('#tanggal_peninjauan').val(response.tanggal_peninjauan);
			$('#nama_vendor').val(response.nama_rekanan);
			$('#alamat_vendor').val(response.alamat_rekanan);
			$('#nama_pemberi_tugas').val(response.nama_pemberi_tugas);
			$('#jabatan_pemberi_tugas').val(response.jabatan_pemberi_tugas);
			$('#modal-penugasan').modal();
			$.each(response.detail,function(k,v){
				if(k == '0') {
					$('.anggota').val(v.nama_user);
					$('.id_anggota').val(v.id_user);
				} else {
					konten = '<div class="form-group row">'
							+ '<div class="offset-sm-3 col-sm-5 col-9">'
							+ '<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota" data-validation="required" value="'+v.nama_user+'">'
							+ '<input type="hidden" name="id_anggota[]" class="id_anggota" value="'+v.id_user+'">'
							+ '</div>'
							+ '<div class="col-sm-2 col-3">'
							+ '<input type="text" name="posisi[]" autocomplete="off" class="form-control posisi" disabled value="Anggota">'
							+ '</div>'
							+ '<div class="col-sm-2 col-3">'
							+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
							+ '</div>'
							+ '</div>';
					$('#additional-anggota').append(konten);
				}
			});
		}
	});
});
$(document).ready(function(){
	cAutocomplete();
});
function add_row_anggota() {
	konten = '<div class="form-group row">'
			+ '<div class="offset-sm-3 col-sm-5 col-9">'
			+ '<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota" data-validation="required">'
			+ '<input type="hidden" name="id_anggota[]" class="id_anggota">'
			+ '</div>'
			+ '<div class="col-sm-2 col-3">'
			+ '<input type="text" name="posisi[]" autocomplete="off" class="form-control posisi" disabled value="Anggota">'
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
		serviceUrl: base_url + 'pengadaan/peninjauan_lapangan/get_tim_peninjauan/' + $('#form-penugasan').attr('data-id'),
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
