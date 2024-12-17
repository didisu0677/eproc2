
$('#id_mata_anggaran').change(function(){
	$('#besar_anggaran').val($(this).find(':selected').attr('data-anggaran'));
});
$(document).on('click','.btn-input',function(e){
	e.preventDefault();
	proccess = false;
	$('#additional-file').html('');
	$('select[data-child]').each(function(){
		var data_child = $(this).attr('data-child');
		var spl_child = data_child.split('|');
		$.each(spl_child,function(k,v){
			$('select[name="'+v+'"]').html('').trigger('change');
		});
	});
	var mtitle = '';
	$('#modal-form input[type=hidden]').each(function(){
		if($(this).attr('name').indexOf("field") == -1 && $(this).attr('name').indexOf("validation") == -1 && $(this).attr('id') == 'undefined') {
			$(this).val('');
		}
	});
	$('#modal-form form')[0].reset();
	$('#modal-form .modal-footer').html('').addClass('hidden');
	if($(this).data('id') == 0) {
		mtitle = typeof $(this).attr('aria-label') != 'undefined' ? $(this).attr('aria-label') : lang.tambah;
	} else {
		mtitle = lang.ubah;
	}
	$('#modal-form form .is-invalid').each(function(){
		$(this).removeClass('is-invalid');
		$(this).closest('.form-group').find('.error').remove();
	});
	$('.modal-body.wizard a').removeClass('active').attr('aria-selected','false');
	$('.modal-body.wizard li:first-child a').addClass('active').attr('aria-selected','true');
	$('.tab-content .tab-pane').removeClass('show').removeClass('active');
	$('.tab-content .tab-pane:first-child').addClass('show').addClass('active');
	if($(this).attr('data-id') == '0') {
		$('#modal-form .modal-title').html(mtitle);
		$('#modal-form').modal();
		$('#modal-form [name="id"]').val(0);
		if($('#modal-form select').length > 0) {
			$('#modal-form select').trigger('change');
		}
		$('[data-validation="image"]').each(function(){
			$(this).parent().find('img').attr('src', $(this).parent().find('img').attr('data-origin') );
			$(this).val('');
		});
		proccess = true;
		if($('[data-editor]').length > 0 && typeof window.CKEDITOR != 'undefined') {
			$('[data-editor]').each(function(){
				$(this).val('');
				CKEDITOR.instances[$(this).attr('id')].setData('');
			});
		}
		$('.modal-body.wizard .nav-tabs li a').removeAttr('data-toggle');
		$('.modal-body.wizard .nav-tabs li:first-child a').attr('data-toggle','tab');
	} else {
		$.ajax({
			url			: base_url + 'pengadaan/tor/get_data',
			data 		: {'id':$(this).attr('data-id')},
			type		: 'post',
			cache		: false,
			dataType	: 'json',
			success		: function(response) {
				$('.modal-body.wizard .nav-tabs li a').attr('data-toggle','tab');

				$('#id_mata_anggaran').val(response.id_mata_anggaran);
				$('#id_divisi').val(response.id_divisi);
				if($('#modal-form select').length > 0 && proccess == false) {
					$('#modal-form select').trigger('change');
				}

				$('#besar_anggaran').val(response.besar_anggaran);
				if(typeof response['status'] == 'undefined' || typeof response['message'] == 'undefined') {
					$('#modal-form form :input').each(function(){
						var res_value = response[$(this).attr('name')] == null ? '' : response[$(this).attr('name')];
						if(typeof $(this).attr('name') != 'undefined' && ($(this).attr('name').indexOf("field") == 0 || $(this).attr('name').indexOf("validation") == 0) && res_value == '') {
							res_value = $(this).val();
						}
						if( $(this).attr('type') == 'checkbox') {
							if(res_value == $(this).attr('value'))
							$(this).prop('checked',true);
							else
							$(this).prop('checked',false);
						} else if($(this).attr('type') == 'hidden' && typeof $(this).attr('data-validation') != 'undefined' && $(this).attr('data-validation') == 'image') {
							$(this).val(res_value);
							if(typeof response['dir_upload'] != 'undefined' && res_value != '') {
								$(this).parent().children('img').attr('src', response['dir_upload'] + res_value);
							}
						} else if(typeof $(this).attr('data-editor') != 'undefined' && typeof window.CKEDITOR != 'undefined') {
							$(this).val(res_value);
							CKEDITOR.instances[$(this).attr('id')].setData(decodeEntities(res_value));
						} else {
							if(typeof res_value != 'undefined') {
								var c_date = res_value.split('-');
								var c_datetime = res_value.split(' ');
								var is_datetime = false;
								if(res_value.length == 19 && c_datetime.length == 2) {
									var dt_date = c_datetime[0].split('-');
									var dt_time = c_datetime[1].split(':');
									if(dt_date.length == 3 && dt_time.length == 3) {
										if(res_value == '0000-00-00 00:00:00') {
											$(this).val('');
										} else {
											$(this).val(dt_date[2]+'/'+dt_date[1]+'/'+dt_date[0]+' '+dt_time[0]+':'+dt_time[1]).trigger('change');
										}
										is_datetime = true;
									}
								}
								if(!is_datetime) {
									if(c_date.length == 3 && c_date[0].length == 4 && c_date[1].length == 2 && c_date[2].length == 2) {
										if(res_value == '0000-00-00') {
											$(this).val('');
										} else {
											$(this).val(c_date[2]+'/'+c_date[1]+'/'+c_date[0]);
										}
									} else {
										if(typeof response['opt_' + $(this).attr('name')] != 'undefined') {
											$(this).html(response['opt_' + $(this).attr('name')]).val(res_value);
											if($(this).val() == null && $(this).is('select')) {
												var vl_dt = res_value;
												var el_vl = $(this);
												$(this).children().each(function(){
													var vl = $(this).attr('value').toUpperCase();
													if(vl == vl_dt) {
														el_vl.val($(this).attr('value'));
													}
												});
											}
										}  else {
											if($(this).hasClass('money')) {
												$(this).val(numberFormat(res_value,0,',','.','negatif'));
											} else {
												$(this).val(res_value);
												if($(this).val() == null && $(this).is('select')) {
													var vl_dt = res_value;
													var el_vl = $(this);
													$(this).children().each(function(){
														var vl = $(this).attr('value').toUpperCase();
														if(vl == vl_dt) {
															el_vl.val($(this).attr('value'));
														}
													});
												}
											}
										}
									}
								}
							} else if($(this).prop('multiple') === true) {
								var $t = $(this);
								$.each(response[$(this).attr('id')],function(i){
									$t.find('[value="'+response[$t.attr('id')][i]+'"]').prop('selected',true);
								});
							}
						}
					});

					$('#modal-form .modal-title').html(mtitle);
					$('#modal-form').modal();
					if($('#password').length > 0) {
						$('#password').attr('data-validation','min-length:6').attr('placeholder',lang.kosongkan_jika_tidak_diubah).val('');
					}
					if ($('#modal-form form .icp').length > 0) {
						$('#modal-form form .icp').each(function(){
							$(this).closest('.input-group').find('.input-group-text').html('<i class="'+$(this).val()+'"></i>');
						});
					}

				//	if($('#modal-form select').length > 0 && proccess == false) {
				//		$('#modal-form select').trigger('change');
				//	}

					$('#modal-form .modal-footer').html('');
					var footer_text = '';
					var create_info = '';
					var update_info = '';

					if(typeof response['create_by'] != 'undefined' && typeof response['create_at'] != 'undefined') {
						if(response['create_at'] != '0000-00-00 00:00:00') {
							var create_by = response['create_by'] == '' ? 'Unknown' : response['create_by'];
							var create_at = response['create_at'].split(' ');
							var tanggal_c = create_at[0].split('-');
							var waktu_c = create_at[1].split(':');
							var date_c = tanggal_c[2]+'/'+tanggal_c[1]+'/'+tanggal_c[0]+' '+waktu_c[0]+':'+waktu_c[1];
							create_info += '<small>' + lang.dibuat_oleh + ' <strong>' + create_by + ' </strong> @ ' + date_c + '</small>';
						}
					}


					if(typeof response['update_by'] != 'undefined' && typeof response['update_at'] != 'undefined') {
						if(response['update_at'] != '0000-00-00 00:00:00') {
							var update_by = response['update_by'] == '' ? 'Unknown' : response['update_by'];
							var update_at = response['update_at'].split(' ');
							var tanggal_u = update_at[0].split('-');
							var waktu_u = update_at[1].split(':');
							var date_u = tanggal_u[2]+'/'+tanggal_u[1]+'/'+tanggal_u[0]+' '+waktu_u[0]+':'+waktu_u[1];
							update_info += '<small>' + lang.diperbaharui_oleh + ' <strong>' + update_by + ' </strong> @ ' + date_u + '</small>';
						}
					}
					if(create_info || update_info) {
						footer_text += '<div class="w-100">';
						footer_text += create_info;
						footer_text += update_info;
						footer_text += '</div>';
					}
					if(footer_text) {
						$('#modal-form .modal-footer').html(footer_text).removeClass('hidden');
					}
					$.each(response.file,function(n,z){
						var konten = '<div class="form-group row">'
							+ '<div class="col-sm-9 offset-sm-3">'
							+ '<input type="text" class="form-control" autocomplete="off" disabled value="'+z+'">'
							+ '</div>'
							+ '</div>';
						$('#additional-file').append(konten);
					});
				} else {
					cAlert.open(response['message'],response['status']);
				}

				proccess = true;
			}
		});
	}
});
$('.modal-body.wizard .nav-tabs li a').click(function(e){
	e.preventDefault();
});
$(document).on('click','.btn-next',function(e){
	e.preventDefault();
	var pane = $(this).closest('.tab-pane').attr('id');
	if(validation(pane)) {
		$('.modal-body.wizard a').removeClass('active').attr('aria-selected','false');
		$('#' + $(this).attr('data-target') + '-tab').addClass('active').attr('aria-selected','true').attr('data-toggle','tab');
		$('.tab-content .tab-pane').removeClass('show').removeClass('active');
		$('#' + $(this).attr('data-target')).addClass('show').addClass('active');
	}
});
$(document).on('click','.btn-prev',function(e){
	e.preventDefault();
	$('.modal-body.wizard a').removeClass('active').attr('aria-selected','false');
	$('#' + $(this).attr('data-target') + '-tab').addClass('active').attr('aria-selected','true');
	$('.tab-content .tab-pane').removeClass('show').removeClass('active');
	$('#' + $(this).attr('data-target')).addClass('show').addClass('active');
});

$(document).on('click','.btn-remove-row',function(){
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
		cAlert.open('Tidak dapat mengupload file ini');
		$('#add-file').text('Tambah File').removeAttr('disabled');
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
	var konten = '<div class="form-group row">'
				+ '<div class="col-sm-7 col-9 offset-sm-3">'
				+ '<input type="hidden" class="form-control" name="file[]" autocomplete="off" value="'+data.result+'">'
				+ '<input type="text" class="form-control" autocomplete="off" disabled value="'+f[f.length - 1]+'">'
				+ '</div>'
				+ '<div class="col-sm-2 col-3">'
				+ '<button type="button" class="btn btn-danger btn-remove-row btn-block btn-icon-only"><i class="fa-times"></i></button>'
				+ '</div>'
				+ '</div>';
	$('#additional-file').append(konten);
	$('#add-file').text('Tambah File').text('Upload').removeAttr('disabled');
	is_autocomplete = false;
}).on('fileuploadfail', function (e, data) {
	cAlert.open('File gagal diupload','error');
	$('#add-file').text('Tambah File').removeAttr('disabled');
	is_autocomplete = false;
}).on('fileuploadalways', function() {
});
// function detail_callback(id){
// 	$.get(base_url+'pengadaan/tor/detail/'+id,function(result){
// 		cInfo.open('Detail',result);
// 	});
// }

	/*
	$(document).on('click','.btn-print',function(){
		proccess = false;
		$.ajax({
			url :  base_url + 'pengadaan/tor/cetak_tor',
			data : {'id' : $(this).attr('data-id')},
			type : 'post',
			dataType : 'json',
			success : function(response) {

					proccess = true;
			}
		});
	});
	*/

	$(document).on('click','.btn-print',function(){
		proccess = false;
		$.ajax({
			url :  base_url + 'procurement/tor/detail?i=',
			data : {'id' : $(this).attr('data-id')},
			type : 'post',
			dataType : 'json',
			success : function(response) {

					proccess = true;
			}
		});
	});
