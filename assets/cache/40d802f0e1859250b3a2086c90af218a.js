
var wi, timer;
$('#id_mata_anggaran').change(function(){
	$('#besar_anggaran').val($(this).find(':selected').attr('data-anggaran'));
});
function formOpen() {
	var response = response_edit;
	$('#additional-file').html('');
	$('#ajukan').prop('checked',true);
	$("#ajukan-lagi").hide();
	$('#browse-req').removeAttr('disabled');
	if(typeof response.id != 'undefined' && parseInt(response.id) > 0) {
		$('#browse-req').attr('disabled',true);
		$('#besar_anggaran').val(numberFormat(response.besar_anggaran,0,',','.'));
		if(response.approve_user == '8') {
			$('#ajukan-lagi').show();
		}
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
				+ '<a href="'+base_url+'assets/uploads/pengajuan/'+z+'" target="_blank" class="btn btn-info btn-icon-only"><i class="fa-download"></i></a>'
				+ '</div>'
				+ '</div>'
				+ '</div>'
				+ '<div class="col-sm-2 col-3">'
				+ '<button type="button" class="btn btn-danger btn-remove btn-block btn-icon-only"><i class="fa-times"></i></button>'
				+ '</div>'
				+ '</div>';
			$('#additional-file').append(konten);
		});
	}
}

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
function detail_callback(id){
	$.get(base_url+'pengadaan/pengajuan/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}
$(document).on('click','.btn-print',function(){
	var id = encodeId($(this).attr('data-id'));
	$.redirect(base_url + 'pengadaan/pengajuan/cetak_tor/' + id, {} , 'get', '_blank');
});
function openView() {
	if($('.btn-act-view[data-id="'+$('[data-openid]').attr('data-openid')+'"]').length == 1 ) {
		$('.btn-act-view[data-id="'+$('[data-openid]').attr('data-openid')+'"]').trigger('click');
		$('[data-openid]').removeAttr('data-openid');
	}
}
function checkAnggaran() {
	var ret = true;
	if(moneyToNumber($('#besar_anggaran').val()) < moneyToNumber($('#usulan_hps').val())) {
		cAlert.open(lang.usulan_hps_lebih_besar_anggaran);
		ret = false;
	}
	return ret;
}
$('#purchase_req_item').click(function(){
	$('#browse-req').trigger('click');
});
$('#browse-req').click(function(){
	if(!wi) {
		wi = popupWindow(base_url + 'pengadaan/pengajuan/browse_req', '_blank', window, ($(window).width() * 0.8), ($(window).height() * 0.8));
		timer = setInterval(checkChild, 500);
	} else wi.focus();
});
function popupWindow(url, title, win, w, h) {
	const y = win.top.outerHeight / 2 + win.top.screenY - ( h / 2);
	const x = win.top.outerWidth / 2 + win.top.screenX - ( w / 2);
	return win.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+y+', left='+x);
}
function checkChild() {
    if (wi.closed) {
        wi = null;
        clearInterval(timer);
    }
}
$(window).bind('beforeunload', function(){
	if (wi) {
		wi.close();
	}
});
$('#modal-form').on('hidden.bs.modal', function () {
	if (wi) {
		wi.close();
	}
});
function setValue(v1, v2, v3, v4) {
	$('#purchase_req_item').val(v1).trigger('keyup');
	$('#jabatan_pemberi_tugas').val(v2).trigger('keyup');
	$('#nama_pengadaan').val(v3).trigger('keyup');
	$('#usulan_hps').val(v4).trigger('keyup');
}
$(document).on('click','.sap-detail',function(){
	var v = $(this).attr('data-value');
	$.get(base_url+'pengadaan/pengajuan/detail_sap?req_no='+v,function(result){
		cInfo.open(lang.detil,result);
	});
});
$('#preview-detail').click(function(){
	if($('#purchase_req_item').val() != '') {
		var v = $('#purchase_req_item').val();
		$.get(base_url+'pengadaan/pengajuan/detail_sap?req_no='+v,function(result){
			cInfo.open(lang.detil,result);
		});
	}
});
$(document).on('click','.cetak-hps',function(){
	var id = encodeId($(this).attr('data-id'));
	window.open(base_url + 'pengadaan/pengajuan/hps_usulan/' + id,'_blank');
});
