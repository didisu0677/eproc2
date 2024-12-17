
function add_row_anggota() {
	konten = '<div class="form-group row">'
			+ '<label class="col-form-label col-sm-1"></label>'
			+ '<div class="col-sm-3 col-9">'
			+ '<input type="text" name="nama_panitia[]" autocomplete="off" class="form-control nama_panitia" data-validation="required">'
			+ '</div>'
			+ '<div class="col-sm-3 col-9">'
			+ '<input type="text" name="jabatan[]" autocomplete="off" class="form-control jabatan" data-validation="required">'
			+ '</div>'
			+ '<div class="col-sm-2 col-9">'
			+ '<input type="text" name="posisi_panitia[]" autocomplete="off" class="form-control posisi_panitia" data-validation="required">'
			+ '</div>'
			+ '<div class="col-sm-2 col-9">'
			+ '<select id="username" class="form-control col-md-9 col-xs-9 username" name="username[]" data-validation="required">'+username+'</select> '
			+ '</div>'
			+ '<div class="col-sm-1 col-3">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
			+ '</div>'
			
			+ '</div>'
			$('#additional-anggota').append(konten);
}
$('.btn-add-anggota').click(function(){
	add_row_anggota();
});
$(document).on('click','.btn-remove-anggota',function(){
	$(this).closest('.form-group').remove();
});

$(document).ready(function(){
	view_combo();
	    
});

$(document).on('click','.btn-input',function(){
	$('#modal-form form')[0].reset();
	$('#additional-anggota').html('');
	if($(this).data('id') == 0) {
		mtitle = typeof $(this).attr('aria-label') != 'undefined' ? $(this).attr('aria-label') : lang.tambah;
		$('#modal-form [type="submit"]').text(lang.simpan);
	} else {
		mtitle = lang.ubah;
		$('#modal-form [type="submit"]').text(lang.perbaharui);
	}
	$('#modal-form form .is-invalid').each(function(){
		$(this).removeClass('is-invalid');
		$(this).closest('.form-group').find('.error').remove();
	});
	if($(this).attr('data-id') == '0') {
		$('#modal-form .modal-title').html(mtitle);
		$('#modal-form').modal();
		$('#modal-form [name="id"]').val(0);
		$('#modal-form .modal-footer').addClass('hidden');
		proccess = true;
	} else {
		$.ajax({
			url			: base_url + 'settings/panitia_pengadaan/get_data',
			data 		: {'id':$(this).attr('data-id')},
			type		: 'post',
			cache		: false,
			dataType	: 'json',
			success		: function(response) {
				
	          	var z = 1;
            	$.each(response.detail,function(e,d){
            		if(z == 1) {
            			$('.nama_panitia').val(d.nama_panitia);
            			$('.jabatan').val(d.jabatan);
            			$('.posisi_panitia').val(d.posisi_panitia);
            			$('.username').val(d.username);
            		} else {
            			konten = '<div class="form-group row">'
            				+ '<label class="col-form-label col-sm-1"></label>'
            				+ '<div class="col-sm-3 col-9">'
            				+ '<input type="text" name="nama_panitia[]" autocomplete="off" class="form-control nama_panitia idp-'+z+'" data-validation="required">'
            				+ '</div>'
            				+ '<div class="col-sm-3 col-9">'
            				+ '<input type="text" name="jabatan[]" autocomplete="off" class="form-control jabatan idw-'+z+'" data-validation="required">'
            				+ '</div>'
            				+ '<div class="col-sm-2 col-9">'
            				+ '<input type="text" name="posisi_panitia[]" autocomplete="off" class="form-control posisi_panitia idy-'+z+'" data-validation="required">'
            				+ '</div>'
            				+ '<div class="col-sm-2 col-9">'
            				+ '<select id="username" class="form-control col-md-9 col-xs-9 select2 idx-'+z+'" name="username[]" placeholder = "username" >'+username+'</select> '
            				+ '</div>'
            				+ '<div class="col-sm-1 col-3">'
            				+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
            				+ '</div>'
            				
            				+ '</div>'
            				$('#additional-anggota').append(konten);
                			$('.idx-'+z).val(d.username);
                			$('.idp-'+z).val(d.nama_panitia);
                			$('.idw-'+z).val(d.jabatan);
                			$('.idy-'+z).val(d.posisi_panitia);
            		}
            		z++;
            	});
			}
		});
	}
});
function view_combo() {
	$.ajax({
		url			: base_url + 'settings/panitia_pengadaan/get_combo',
 		dataType	: 'json',
        success     : function(response){
        	$('#username').html(response.username);
            username = response.username; 
         }
    });
}
