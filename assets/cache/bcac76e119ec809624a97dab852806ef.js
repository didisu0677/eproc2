
function add_row_anggota() {
	konten = '<div class="form-group row">'
			+ '<label class="col-form-label col-sm-1"></label>'
			+ '<div class="col-sm-1 col-9">'
			+ '<input type="number" name="level_persetujuan[]" autocomplete="off" class="form-control level_persetujuan" data-validation="required">'
			+ '</div>'
			+ '<div class="col-sm-3 col-9">'
			+ '<input type="text" name="nama_persetujuan[]" autocomplete="off" class="form-control nama_persetujuan" data-validation="required">'
			+ '</div>'
			+ '<div class="col-sm-3 col-9">'
			+ '<input type="text" name="limit_approval[]" autocomplete="off" class="form-control limit_approval money" data-validation="required">'
			+ '</div>'
			+ '<div class="col-sm-3 col-9">'
			+ '<select id="username" class="form-control col-md-9 col-xs-9 username" name="username[]" data-validation="required">'+username+'</select>'
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
			url			: base_url + 'settings/grup_persetujuan/get_data',
			data 		: {'id':$(this).attr('data-id')},
			type		: 'post',
			cache		: false,
			dataType	: 'json',
			success		: function(response) {
				
	          	var z = 1;
            	$.each(response.detail,function(e,d){
            		if(z == 1) {
            			$('.level_persetujuan').val(d.level_persetujuan);
            			$('.nama_persetujuan').val(d.nama_persetujuan);
            			$('.limit_approval').val(d.limit_approval);
            			$('.username').val(d.userid);
            		} else {
            			konten = '<div class="form-group row">'
            				+ '<label class="col-form-label col-sm-1"></label>'
            				+ '<div class="col-sm-1 col-9">'
            				+ '<input type="number" name="level_persetujuan[]" autocomplete="off" class="form-control level_persetujuan idp-'+z+'" data-validation="required">'
            				+ '</div>'
            				+ '<div class="col-sm-3 col-9">'
            				+ '<input type="text" name="nama_persetujuan[]" autocomplete="off" class="form-control nama_persetujuan idw-'+z+'" data-validation="required">'
            				+ '</div>'
            				+ '<div class="col-sm-3 col-9">'
            				+ '<input type="text" name="limit_approval[]" autocomplete="off" class="form-control limit_approval money idy-'+z+'" data-validation="required">'
            				+ '</div>'
            				+ '<div class="col-sm-3 col-9">'
            				+ '<select id="username" class="form-control col-md-9 col-xs-9 select2 username idx-'+z+'" name="username[]" >'+username+'</select> '
            				+ '</div>'
            				+ '<div class="col-sm-1 col-3">'
            				+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
            				+ '</div>'
            				
            				+ '</div>'
            				$('#additional-anggota').append(konten);
                			$('.idx-'+z).val(d.userid);
                			$('.idp-'+z).val(d.level_persetujuan);
                			$('.idw-'+z).val(d.nama_persetujuan);
                			$('.idy-'+z).val(d.limit_approval);
            		}
            		z++;
            	});
			}
		});
	}
});
function view_combo() {
	$.ajax({
		url			: base_url + 'settings/grup_persetujuan/get_combo',
 		dataType	: 'json',
        success     : function(response){
        	$('#username').html(response.username);
            username = response.username; 
         }
    });
}
