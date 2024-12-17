<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>

		<div class="float-right">
			<select class="select2 infinity custom-select" id="filter">
				<option value="rekanan"><?php echo lang('daftar_sp_rekanan'); ?></option>
				<option value="blacklist"><?php echo lang('daftar_blacklist'); ?></option>
			</select>
			<?php echo access_button(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('manajemen_rekanan/sp_rekanan/data'),'tbl_sp_vendor');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('kode_rekanan'),'','data-content="kode_rekanan"');
				th(lang('nama_rekanan'),'','data-content="nama_rekanan"');
				th(lang('alamat'),'','data-content="alamat"');
				th(lang('sp_terakhir'),'','data-content="sp_terakhir"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
	modal_body('wizard');
		form_open(base_url('manajemen_rekanan/sp_rekanan/save'),'post','form'); ?>
		
			<div class="tab-content" id="tab-wizardContent">
	
			<?php for($i=0; $i < 1; $i++) { ?>
				<?php 
				col_init(3,9);
						input('hidden','id','id');
						card_open(lang('data_rekanan'),'mb-2');
						select2(lang('nama_rekanan'),'id_vendor','required');
						input('hidden','nama_rekanan','nama_rekanan');
						input('text',lang('alamat'),'alamat','','','');
						card_close();
						
						card_open(lang('pejabat_pembuat'),'mb-2');
						input('text',lang('nama_pembuat'),'nama_pembuat');
						input('text',lang('jabatan'),'jabatan');
						card_close();
			     ?>
				<div class="card mb-2">
					<div class="card-header">
						<div class="form-group row">
							<label class="col-form-label col-sm-2" for="nomor<?php echo $i; ?>"><?php echo lang('nomor'); ?></label>
							<div class="col-sm-10">
								<input type="text" name="nomor[]" id="nomor<?php echo $i; ?>" autocomplete="off" class="form-control nomorx" readonly placeholder="Otomatis saat disimpan">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2" for="jenis<?php echo $i; ?>"><?php echo lang('jenis'); ?></label>
							<div class="col-sm-10">
								<input type="text" name="jenis[]" id="jenis<?php echo $i; ?>" autocomplete="off" class="form-control" data-validation="required">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2" for="perihal<?php echo $i; ?>"><?php echo lang('perihal'); ?></label>
							<div class="col-sm-10">
								<input type="text" name="perihal[]" id="perihal<?php echo $i; ?>" autocomplete="off" class="form-control" data-validation="required">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2" for="tanggal_berlaku<?php echo $i; ?>"><?php echo lang('tanggal_berlaku'); ?></label>
							<div class="col-sm-10">
								<input type="date" name="tanggal_berlaku[]" id="tanggal_berlaku<?php echo $i; ?>" autocomplete="off" class="form-control" data-validation="required" >
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2" for="catatan<?php echo $i; ?>"><?php echo lang('catatan'); ?></label>
							<div class="col-sm-10">
								<input name="catatan[]" id="catatan<?php echo $i; ?>" autocomplete="off" class="form-control" data-validation="required">
							</div>
						</div>
 
						<div class="form-group row">
								<label class="col-form-label col-sm-2" for="lampiran<?php echo $i; ?>"><?php echo lang('lampiran'); ?></label>
								<div class="col-sm-9">
									<input type="text" name="file[]" id="file<?php echo $i; ?>" data-validation="required" data-action="<?php echo base_url('upload/file/datetime'); ?>" data-token="<?php echo encode_id([user('id'),(time() + 900)]); ?>" autocomplete="off" class="form-control input-file" value="" placeholder="<?php echo lang('maksimal'); ?> 5MB">
								</div>


								<div class="input-group-append">
									<button class="btn btn-secondary btn-file" type="button"><?php echo lang('unggah'); ?></button>
								</div>
						</div>	

					</div>

					<div class="card-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-2" for="isi_pasal<?php echo $i; ?>"><?php echo strtoupper(lang('isi_surat')); ?></label>		
						<div class="col-sm-10">
							<textarea name="isi_pasal[]" id="isi_pasal<?php echo $i; ?>" class="form-control editor" data-validation="required" rows="4" data-editor="inline"></textarea>
						</div>
					</div>
					</div>
				</div>

					<?php } ?>
					<div id="additional-pasal"></div>
					<div class="form-group row">
						<div class="col-sm-3">
							<button type="button" class="btn btn-success" id="btn-add" data-nomor="<?php echo lang('nomor'); ?>" data-jenis="<?php echo lang('jenis'); ?>" data-perihal="<?php echo lang('perihal'); ?>" data-tanggal_berlaku="<?php echo lang('tanggal_berlaku'); ?>" data-catatan="<?php echo lang('catatan'); ?>" data-lampiran="<?php echo lang('lampiran'); ?>" data-file2="<?php echo lang('file'); ?>" data-isi_pasal="<?php echo strtoupper(lang('isi_surat')); ?>"><i class="fa-plus"></i> <?php echo lang('tambah'); ?></button>
						</div>
						<div class="col-sm-9">
							<button type="submit" class="btn btn-success"><?php echo lang('simpan'); ?></button>
						</div>
					</div>

			</div>

				
		<?php
		form_close();
	modal_footer();
modal_close();
?>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js') ?>"></script>
<script>
function initUploadFile() {
	$('.input-file').each(function(i,j){
		var _token = "<?php echo encode_id([user('id'),(time() + 900)])?>";

		var idx 	= 'upl-file-' + i;
		var konten 	= '<form action="'+base_url+'upload/file/datetime'+'" class="hidden">';
		var accept 	= typeof $(this).attr('data-accept') == 'undefined' ? Base64.decode(upl_alw) : $(this).attr('data-accept');
		var regex 	= "(\.|\/)("+accept+")$";
		var re 		= accept == '*' ? '*' : new RegExp(regex,"i");
		var name 	= $(this).parent().children('input').attr('name');
		var nm_attr	= name.replace('[','_').replace(']','');
		konten += '<input type="file" name="document" class="input-file" id="'+idx+'">';
		konten += '<input type="hidden" name="name" value="'+nm_attr+'">';
		konten += '<input type="hidden" name="token" value="'+_token+'">';
		konten += '</form>';
		$(this).attr('data-file',idx);
		$(this).parent().find('button').attr('data-file',idx);
		$('body').append(konten);

		if(re == '*') {
			$('#' + idx).fileupload({
				maxFileSize: upl_flsz,
				autoUpload: false,
				dataType: 'text',
			}).on('fileuploadadd', function(e, data) {
				$('button[data-file="'+idx+'"]').attr('disabled',true);
				data.process();
			}).on('fileuploadprocessalways', function (e, data) {
				if (data.files.error) {
					cAlert.open('Tidak dapat mengupload file ini. ' + lang.ukuran_file_maks + ' : ' + (upl_flsz / 1024 / 1024) + 'MB');
					$('button[data-file="'+idx+'"]').text(lang.unggah).removeAttr('disabled');
				} else {
					data.submit();
				}
			}).on('fileuploadprogressall', function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('button[data-file="'+idx+'"]').text(progress + '%');
			}).on('fileuploaddone', function (e, data) {
				$('input[data-file="'+idx+'"]').val(data.result);
				$('button[data-file="'+idx+'"]').text(lang.unggah).removeAttr('disabled');
			}).on('fileuploadfail', function (e, data) {
				cAlert.open('File gagal diupload','error');
				$('button[data-file="'+idx+'"]').text(lang.unggah).removeAttr('disabled');
			}).on('fileuploadalways', function() {
			});
		} else {
			$('#' + idx).fileupload({
				maxFileSize: upl_flsz,
				autoUpload: false,
				dataType: 'text',
				acceptFileTypes: re
			}).on('fileuploadadd', function(e, data) {
				$('button[data-file="'+idx+'"]').attr('disabled',true);
				data.process();
			}).on('fileuploadprocessalways', function (e, data) {
				if (data.files.error) {
					data.abort();
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
					$('button[data-file="'+idx+'"]').text(lang.unggah).removeAttr('disabled');
				} else {
					data.submit();
				}
			}).on('fileuploadprogressall', function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('button[data-file="'+idx+'"]').text(progress + '%');
			}).on('fileuploaddone', function (e, data) {
				if(data.result == 'invalid' || data.result == '') {
					cAlert.open(lang.file_gagal_diunggah,'error');
				} else {
					var spl_result = data.result.split('/');
					if(spl_result.length == 1) spl_result = data.result.split('\\');
					if(spl_result.length > 1) {
						var spl_last_str = spl_result[spl_result.length - 1].split('.');
						if(spl_last_str.length == 2) {
							$('input[data-file="'+idx+'"]').val(data.result);
						} else {
							cAlert.open(lang.file_gagal_diunggah,'error');
						}
					} else {
						cAlert.open(lang.file_gagal_diunggah,'error');						
					}
				}
				$('button[data-file="'+idx+'"]').text(lang.unggah).removeAttr('disabled');
			}).on('fileuploadfail', function (e, data) {
				cAlert.open(lang.file_gagal_diunggah,'error');
				$('button[data-file="'+idx+'"]').text(lang.unggah).removeAttr('disabled');
			}).on('fileuploadalways', function() {
			});
		}
	});
}

$('#filter').change(function(){
	var url = base_url + 'manajemen_rekanan/sp_rekanan/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});

var vendor = {};
var is_edit = false;
var idx = 777;
function formOpen() {
	is_edit = true;
	var response = response_edit;
	$('#additional-pasal').html('');
	if(typeof response.id != 'undefined') {
		$('#id_vendor').html('<option value="'+response.id_vendor+'">'+response.nama_rekanan+'</option>').trigger('change');		
		$('#nama_rekanan').val(response.nama_rekanan);
		$.each(response.detail,function(k,v){
			var x = parseInt(k);
			if( x < 1) {
				$('#nomor' + x).val(v.nomor);
				$('#jenis' + x).val(v.jenis);
				$('#perihal' + x).val(v.perihal);
				$('#tanggal_berlaku' + x).val(v.tanggal_mulai);
				$('#catatan' + x).val(v.catatan);			
				$('#isi_pasal' + x).val(v.isi_surat);
				$('#lampiran' + x).val(v.lampiran);
				CKEDITOR.instances['isi_pasal'+x].setData(decodeEntities(v.isi_surat));
				var konten = '<a href ="'+base_url+'assets/uploads/rekanan/'+response.id_vendor+'/'+v.file+'" target="_blank"><i class="fa-download"></i></a>';
				
				$('#file'+ x).val(v.file) ;
			} else {
				addPasal(v.nomor,v.jenis,v.perihal,v.tanggal_mulai,v.catatan,v.isi_surat,v.lampiran,v.file);
			}

			setTimeout(function(){
				$('.nomorx').attr('readonly',true);
			},300);
		});
	} else {
		get_rekanan();
	}
	is_edit = false;
}


function get_rekanan() {
	$.ajax({
		url			: base_url + 'manajemen_rekanan/sp_rekanan/get_rekanan',
		dataType	: 'json',
		success     : function(response){
			vendor 	= response.vendor;
			var konten 	= '<option value=""></option>';
			$.each(vendor,function(k,v){
				konten += '<option value="'+v.id+'">'+v.nama+'</option>';
			});
			
			$('#id_vendor').html(konten).trigger('change');
		}
	});
}

$('#id_vendor').change(function(){
	if(typeof vendor[$(this).val()] !== 'undefined') {
		var p = vendor[$(this).val()];
		$('#nama_rekanan').val(p.nama);
		$('#alamat').val(p.alamat+', '+p.nama_kelurahan+', '+p.nama_kecamatan+', '+p.nama_kota+', '+p.nama_provinsi);
	}
});

$('#btn-add').click(function(){
	initUploadFile();
	addPasal();
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('.card').remove();
});

$(document).on('click','.btn-blacklist',function(){
	__id = $(this).attr('data-id');
	cConfirm.open(lang.anda_yakin_memblacklist_data_ini,'blacklist');
});

function blacklist() {
	$.ajax({
		url : base_url + 'manajemen_rekanan/sp_rekanan/blacklist',
		data : {
			id : __id
		},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','refreshData');
		}
	});
}

$(document).on('click','.btn-pulihkan',function(){
	__id = $(this).attr('data-id');
	cConfirm.open(lang.anda_yakin_memulihkan_data_ini,'pulihkan');
});
function pulihkan() {
	$.ajax({
		url : base_url + 'manajemen_rekanan/sp_rekanan/pulihkan',
		data : {
			id : __id
		},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','refreshData');
		}
	});
}



function addPasal(nomor,jenis,perihal,tanggal_berlaku,catatan, isi,isi_pasal,file,lampiran) {
	var _nomor 	= typeof nomor == undefined ? '' : nomor;
	var _jenis 	= typeof jenis == undefined ? '' : jenis;
	var _perihal 	= typeof jenis == undefined ? '' : perihal;
	var _tanggal_berlaku 	= typeof tanggal_berlaku == undefined ? '' : tanggal_berlaku;
	var _catatan 	= typeof catatan == undefined ? '' : catatan;
	var _isi_pasal 	= typeof isi_pasal == undefined ? '' : isi_pasal;
	var _isi 	= typeof isi == undefined ? '' : isi;
	var _lampiran 	= typeof lampiran == undefined ? '' : lampiran;
	var _file 	= typeof file == undefined ? '' : file;
	var _token = "<?php echo encode_id([user('id'),(time() + 900)])?>";

	var today = new Date();
	var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
	var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
	var dateTime = date+' '+time;

	var konten = '<div class="card mb-2">'
		+ '<div class="card-header">'
			+ '<div class="form-group row">'
				+ '<label class="col-form-label col-sm-2" for="nomor'+idx+'">'+$('#btn-add').attr('data-nomor')+'</label>'
				+ '<div class="col-sm-10">'
					+ '<input type="text" name="nomor[]" id="nomor'+idx+'" autocomplete="off" class="form-control nomorx" readonly placeholder="Otomatis saat disimpan">'
				+ '</div>'
			+ '</div>'

			+ '<div class="form-group row">'
				+ '<label class="col-form-label col-sm-2" for="jenis'+idx+'">'+$('#btn-add').attr('data-jenis')+'</label>'
				+ '<div class="col-sm-10">'
					+ '<input type="text" name="jenis[]" id="jenis'+idx+'" autocomplete="off" class="form-control" data-validation="required">'
				+ '</div>'
			+ '</div>'

			+ '<div class="form-group row">'
			+ '<label class="col-form-label col-sm-2" for="perihal'+idx+'">'+$('#btn-add').attr('data-perihal')+'</label>'
			+ '<div class="col-sm-10">'
				+ '<input type="text" name="perihal[]" id="perihal'+idx+'" autocomplete="off" class="form-control" data-validation="required">'
				+ '</div>'
		    + '</div>'

			+ '<div class="form-group row">'
			+ '<label class="col-form-label col-sm-2" for="tanggal_berlaku'+idx+'">'+$('#btn-add').attr('data-tanggal_berlaku')+'</label>'
			+ '<div class="col-sm-10">'
				+ '<input type="date" name="tanggal_berlaku[]" id="tanggal_berlaku'+idx+'" autocomplete="off" class="form-control" data-validation="required">'
				+ '</div>'
			+ '</div>'
			
			+ '<div class="form-group row">'
			+ '<label class="col-form-label col-sm-2" for="catatan'+idx+'">'+$('#btn-add').attr('data-catatan')+'</label>'
			+ '<div class="col-sm-10">'
				+ '<input name="catatan[]" id="catatan'+idx+'" autocomplete="off" class="form-control" data-validation="required">'
				+ '</div>'
			+ '</div>'
			
			+ '<div class="form-group row">'
			+ '<label class="col-form-label col-sm-2" for="lampiran'+idx+'">'+$('#btn-add').attr('data-lampiran')+'</label>'
			+ '<div class="col-sm-9">'
				+ '<input type ="text" name="file[]" id="file'+idx+'" data-validation="required" data-action ="'+base_url+'upload/file/datetime'+'" data-token ="'+_token+'" autocomplete="off" class="form-control input-file" value="" placeholder="maksimal 5MB">'
				+ '</div>'

			+ '<div class="input-group-append">'
			+ '<button class="btn btn-secondary btn-file" type="button"><?php echo lang('unggah'); ?></button>'
			+ '</div>'
			+ '</div>'

		+ '</div>'	
			
		+ '<div class="card-body">'
		+ '<div class="form-group row">'
		+ '<label class="col-form-label col-sm-2" for="isi_pasal'+idx+'">'+$('#btn-add').attr('data-isi_pasal')+'</label>'
		+ '<div class="col-sm-10">'
			+ '<textarea name="isi_pasal[]" id="isi_pasal'+idx+'" class="form-control editor" data-validation="required" rows="4"></textarea>'
			+ '</div>'
		+ '</div>'
		+ '</div>'
		+ '<div class="card-footer">'
			+ '<button type="button" class="btn btn-danger btn-remove"><i class="fa-times"></i> '+lang.hapus+'</button>';
		+ '</div>'
	+ '</div>';
	$('#additional-pasal').append(konten);
	var c_id = 'isi_pasal'+idx;
	CKEDITOR.inline( c_id ,{
		toolbar : [
			{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ] },
			{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
			{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
			{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
			'/',
			{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
			{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
			{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] }
		],
		filebrowserImageBrowseUrl : base_url + 'assets/plugins/kcfinder/index.php?type=images',
		width : 'auto',
		height : '250',
		language : $('meta[name="applang"]').attr('content')
	});
	CKEDITOR.instances[c_id].on('change', function() { 
		var vdata = CKEDITOR.instances[c_id].getData();
		$('#' + c_id).val(vdata);
	});


	if(_nomor) $('#nomor' + idx).val(_nomor);
	if(_jenis) $('#jenis' + idx).val(_jenis);
	if(_perihal) $('#perihal' + idx).val(_perihal);
	if(_tanggal_berlaku) $('#tanggal_berlaku' + idx).val(_tanggal_berlaku);
	if(_catatan) $('#catatan' + idx).val(_catatan);
	if(_isi_pasal) $('#isi_pasal' + idx).val(_isi_surat);
	if(_lampiran) $('#lampiran' + idx).val(_lampiran);
	if(_file) $('#file' + idx).val(_file);
	if(_isi) {
		$('#isi_pasal' + idx).val(_isi);
		CKEDITOR.instances['isi_pasal'+idx].setData(decodeEntities(_isi));
	}
	idx++;
	initUploadFile();
}

function detail_callback(id){
	$.get(base_url+'manajemen_rekanan/sp_rekanan/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}
</script>