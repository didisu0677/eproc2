<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php echo access_button(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body" data-openid="<?php echo $id; ?>">
	<?php
	table_open('',true,base_url('pengadaan/pengajuan/data'),'tbl_pengajuan','data-callback="openView"');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nama_divisi'),'','data-content="nama_divisi"');
				th(lang('nomor_pengajuan'),'','data-content="nomor_pengajuan"');
				th(lang('tanggal_pengadaan'),'','data-content="tanggal_pengadaan" data-type="daterange"');
				th(lang('nama_pemberi_tugas'),'','data-content="pemberi_tugas"');
				th(lang('nama_pengadaan'),'','data-content="nama_pengadaan"');
				th(lang('nomor_pengajuan_pembelian'),'','data-content="purchase_req_item" data-link="sap-detail"');
				th(lang('usulan_hps'),'text-right','data-content="usulan_hps" data-type="currency" data-link="cetak-hps"');
				th(lang('mata_anggaran'),'','data-content="mata_anggaran"');
				th(lang('besar_anggaran'),'text-right','data-content="besar_anggaran" data-type="currency"');
				th(lang('status'),'','width="150" data-content="approve_user" data-replace="0:'.lang('diproses').'|1:'.lang('disetujui').'|8:'.lang('dikembalikan').'|9:'.lang('ditolak').'"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php
modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
	modal_body('wizard');
		form_open(base_url('pengadaan/pengajuan/save'),'post','form');
		?>
			<ul class="nav nav-tabs" id="tab-wizard" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="step1-tab" data-toggle="tab" href="#step1" role="tab" aria-controls="step1" aria-selected="true"><?php echo lang('informasi_permintaan'); ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="step2-tab" data-toggle="tab" href="#step2" role="tab" aria-controls="step2" aria-selected="off"><?php echo lang('tor'); ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="step3-tab" data-toggle="tab" href="#step3" role="tab" aria-controls="step3" aria-selected="off"><?php echo lang('unggah_dokumen'); ?></a>
				</li>
			</ul>
			<div class="tab-content" id="tab-wizardContent">
				<div class="tab-pane show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
					<?php
					col_init(3,9);
					input('hidden','id','id');
					input('text',lang('nomor_pengajuan'),'nomor_pengajuan','required|unique|max-length:30');
					?>
					<div class="form-group row">
						<label class="col-form-label col-sm-3 required" for="purchase_req_item"><?php echo lang('nomor_pengajuan_pembelian'); ?></label>
						<div class="col-sm-9">
							<div class="input-group">
								<input type="text" name="purchase_req_item" id="purchase_req_item" class="form-control" autocomplete="off" data-validation="required" readonly data-readonly="true">
								<div class="input-group-append">
									<button type="button" class="btn btn-success btn-icon-only" id="browse-req"><i class="fa-list"></i></button>
								</div>
							</div>
						</div>
					</div>
					<?php
					input('text',lang('nama_pemberi_tugas'),'pemberi_tugas','required|max-length:100');
					input('text',lang('jabatan_pemberi_tugas'),'jabatan_pemberi_tugas','required|max-length:100','','readonly data-readonly="true"');
					textarea(lang('nama_pengadaan'),'nama_pengadaan','required','','readonly data-readonly="true"');
					input('date',lang('tanggal_pengadaan'),'tanggal_pengadaan','required');
					?>
					<div class="form-group row">
						<label class="col-form-label col-sm-3 required" for="usulan_hps"><?php echo lang('usulan_hps'); ?></label>
						<div class="col-sm-9">
							<div class="input-group">
								<input type="text" name="usulan_hps" id="usulan_hps" autocomplete="off" class="form-control money" data-validation="required|max-length:25" value="" readonly data-readonly="true">
								<div class="input-group-append">
									<button type="button" class="btn btn-sm btn-success" id="preview-detail"><i class="fa-search"></i> <?php echo lang('detil'); ?></button>
								</div>
							</div>
						</div>
					</div>
					<?php
					select2(lang('program_kerja'),'id_proker','required',$proker,'id','nama_program_kerja');
					col_init(3,9);
					?>
					<div class="form-group row">
						<label class="col-form-label col-sm-3 required" for="nomor_pengadaan"><?php echo lang('mata_anggaran'); ?></label>
						<div class="col-sm-4">
							<select name="id_mata_anggaran" id="id_mata_anggaran" class="form-control select2" data-validation="required">
								<option value=""></option>
								<?php foreach ($mata_anggaran as $ma){ ?>
									<option value="<?php echo $ma['id'] ?>" data-anggaran="<?php echo custom_format($ma['besaran_anggaran']) ?>"><?php echo $ma['nama_anggaran']; ?></option>
								<?php } ?>

							</select>
						</div>
						<label class="col-form-label col-sm-2" for="besar_anggaran"><?php echo lang('besar_anggaran'); ?></label>
						<div class="col-sm-3">
							<input type="text" name="besar_anggaran" value="" id="besar_anggaran" class="form-control money" readonly data-readonly="true">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-9 offset-sm-3">
							<button type="reset" class="btn btn-secondary"><?php echo lang('batal'); ?></button>
							<button type="button" class="btn btn-success btn-next" data-target="step2" data-trigger="checkAnggaran"><?php echo lang('selanjutnya'); ?></button>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="step2" role="tabpanel" aria-labelledby="step2-tab">
					<?php
					input('date',lang('tanggal_tor'),'tanggal_tor','required');
					input('text',lang('nomor_tor'),'nomor_tor','required|unique');
					textarea(lang('latar_belakang'),'latar_belakang','required','','data-editor="inline"');
					textarea(lang('spesifikasi'),'spesifikasi','required','','data-editor="inline"');
					textarea(lang('jumlah_kebutuhan'),'jumlah_kebutuhan','required','','data-editor="inline"');
					textarea(lang('distribusi_kebutuhan'),'distribusi_kebutuhan','required','','data-editor="inline"');
					textarea(lang('jangka_waktu'),'jangka_waktu','required','','data-editor="inline"');
					textarea(lang('ruang_lingkup'),'ruang_lingkup','required','','data-editor="inline"');
					textarea(lang('lain_lain'),'lain_lain','required','','data-editor="inline"');
					?>
					<div class="form-group row">
						<div class="col-sm-9 offset-sm-3">
							<button type="button" class="btn btn-danger btn-prev" data-target="step1"><?php echo lang('sebelumnya'); ?></button>
							<button type="button" class="btn btn-success btn-next" data-target="step3"><?php echo lang('selanjutnya'); ?></button>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="step3" role="tabpanel" aria-labelledby="step3-tab">
					<?php
					?>
					<div class="form-group row">
						<label class="col-form-label col-sm-3"><?php echo lang('dokumen_pendukung') ?><small><?php echo lang('maksimal'); ?> 5MB</small></label>
						<div class="col-sm-9">
							<button type="button" class="btn btn-info" id="add-file" title="<?php echo lang('tambah_dokumen'); ?>"><?php echo lang('tambah_dokumen'); ?></button>
						</div>
					</div>
					<div id="additional-file" class="mb-2"></div>
					<div class="form-group row" id="ajukan-lagi">
						<div class="col-sm-9 offset-sm-3">
							<div class="custom-checkbox custom-control custom-control-inline">
								<input class="custom-control-input" type="checkbox" id="ajukan" name="ajukan">
								<label class="custom-control-label" for="ajukan"><?php echo lang('ajukan_kembali'); ?></label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-9 offset-sm-3">
							<button type="button" class="btn btn-danger btn-prev" data-target="step2"><?php echo lang('sebelumnya'); ?></button>
							<button type="submit" class="btn btn-success"><?php echo lang('simpan'); ?></button>
						</div>
					</div>
				</div>
			</div>
			<?php
		form_close();
	modal_footer();
modal_close();
?>
<form action="<?php echo base_url('upload/file/datetime'); ?>" class="hidden">
	<input type="hidden" name="name" value="field_document">
	<input type="hidden" name="token" value="<?php echo encode_id([user('id'),(time() + 900)]); ?>">
	<input type="file" name="document" id="upl-file">
</form>

<script type="text/javascript" src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js') ?>"></script>
<script>
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
</script>
