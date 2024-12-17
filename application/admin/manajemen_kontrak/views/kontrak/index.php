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
<div class="content-body">
	<?php
	table_open('',true,base_url('manajemen_kontrak/kontrak/data'),'tbl_kontrak');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_kontrak'),'','data-content="nomor_kontrak"');
				th(lang('nomor_spk'),'','data-content="nomor_spk"');
				th(lang('nama_pekerjaan'),'','data-content="nama_pengadaan"');
				th(lang('nilai_pekerjaan'),'text-right','data-content="nilai_pengadaan" data-type="currency"');
				th(lang('nama_rekanan'),'','data-content="nama_vendor"');
				th(lang('tanggal_mulai_kontrak'),'','data-content="tanggal_mulai_kontrak" data-type="daterange"');
				th(lang('tanggal_selesai_kontrak'),'','data-content="tanggal_selesai_kontrak" data-type="daterange"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
	modal_body('wizard');
		form_open(base_url('manajemen_kontrak/kontrak/save'),'post','form'); ?>
			<ul class="nav nav-tabs" id="tab-wizard" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="step1-tab" data-toggle="tab" href="#step1" role="tab" aria-controls="step1" aria-selected="true"><?php echo lang('informasi_kontrak'); ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="step2-tab" data-toggle="tab" href="#step2" role="tab" aria-controls="step2" aria-selected="off"><?php echo lang('isi_kontrak'); ?></a>
				</li>
			</ul>
			<div class="tab-content" id="tab-wizardContent">
				<div class="tab-pane show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
					<?php
						col_init(3,9);
						input('hidden','id','id');
						card_open(lang('data_pekerjaan'),'mb-2');
							select2(lang('nomor_spk'),'nomor_spk','required');
							input('text',lang('nama_pekerjaan'),'nama_pengadaan','','','disabled');
							input('text',lang('nilai_pekerjaan'),'nilai_pengadaan','','','disabled');
							input('text',lang('nama_rekanan'),'nama_vendor','','','disabled');
						card_close();
						card_open(lang('data_kontrak'),'mb-2');
							input('text',lang('nomor_kontrak'),'nomor_kontrak','required|unique');
							input('date',lang('tanggal_mulai_kontrak'),'tanggal_mulai_kontrak','required');
							input('date',lang('tanggal_selesai_kontrak'),'tanggal_selesai_kontrak','required');
							input('date',lang('tanggal_dikeluarkan'),'tanggal_dikeluarkan','required');
							input('text',lang('tempat_dikeluarkan'),'tempat_dikeluarkan','required');
							input('money',lang('target_value'),'target_value','required|number');
							label(strtoupper(lang('pihak_yang_menandatangani')));
							label(lang('pihak').' 1');
							sub_open(1);
								input('text',lang('nama'),'nama_pihak1','required');
								input('text',lang('jabatan'),'jabatan_pihak1','required');
								textarea(lang('alamat'),'alamat_pihak1','required');
							sub_close();
							label(lang('pihak').' 2');
							sub_open(1);
								input('text',lang('nama'),'nama_pihak2','required');
								input('text',lang('jabatan'),'jabatan_pihak2','required');
								textarea(lang('alamat'),'alamat_pihak2','required');
							sub_close();
							?>
							<div class="form-group row">
								<div class="col-sm-9 offset-sm-3">
									<button type="reset" class="btn btn-secondary"><?php echo lang('batal'); ?></button>
									<button type="button" class="btn btn-success btn-next" data-target="step2" data-trigger="checkLimitRekanan"><?php echo lang('selanjutnya'); ?></button>
								</div>
							</div>
							<?php
						card_close();
					?>
				</div>
				<div class="tab-pane" id="step2" role="tabpanel" aria-labelledby="step2-tab">
					<?php for($i=0; $i < 2; $i++) { ?>
					<div class="card mb-2">
						<div class="card-header">
							<div class="form-group row">
								<label class="col-form-label col-sm-2" for="pasal<?php echo $i; ?>"><?php echo lang('pasal'); ?></label>
								<div class="col-sm-10">
									<input type="text" name="pasal[]" id="pasal<?php echo $i; ?>" autocomplete="off" class="form-control" data-validation="required">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-sm-2" for="judul_pasal<?php echo $i; ?>"><?php echo lang('judul_pasal'); ?></label>
								<div class="col-sm-10">
									<input type="text" name="judul_pasal[]" id="judul_pasal<?php echo $i; ?>" autocomplete="off" class="form-control" data-validation="required">
								</div>
							</div>
						</div>
						<div class="card-body p-0">
							<textarea name="isi_pasal[]" id="isi_pasal<?php echo $i; ?>" class="form-control editor" data-validation="required" rows="4" data-editor="inline"></textarea>
						</div>
					</div>
					<?php } ?>
					<div id="additional-pasal"></div>
					<div class="form-group row">
						<div class="col-sm-3">
							<button type="button" class="btn btn-success" id="btn-add" data-pasal="<?php echo lang('pasal'); ?>" data-judul="<?php echo lang('judul_pasal'); ?>"><i class="fa-plus"></i> <?php echo lang('tambah_pasal'); ?></button>
						</div>
						<div class="col-sm-9">
							<button type="button" class="btn btn-danger btn-prev" data-target="step1"><?php echo lang('sebelumnya'); ?></button>
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
<script type="text/javascript" src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js') ?>"></script>
<script type="text/javascript">
var is_edit = false;
var idx = 777;
function formOpen() {
	is_edit = true;
	var response = response_edit;
	$('#additional-pasal').html('');
	if(typeof response.id != 'undefined') {
		var opt = '<option value="'+response.nomor_spk+'" data-pengadaan="'+response.nama_pengadaan+'" data-vendor="'+response.nama_vendor+'" data-nilai="'+response.nilai_pengadaan+'">'+response.nomor_spk+' | '+response.nama_pengadaan+'</option>';
		$('#nomor_spk').html(opt).trigger('change');
		$.each(response.detail,function(k,v){
			var x = parseInt(k);
			if( x < 2) {
				$('#pasal' + x).val(v.pasal);
				$('#judul_pasal' + x).val(v.judul_pasal);
				$('#isi_pasal' + x).val(v.isi_pasal);
				CKEDITOR.instances['isi_pasal'+x].setData(decodeEntities(v.isi_pasal));
			} else {
				addPasal(v.pasal,v.judul_pasal,v.isi_pasal);
			}
		});
	} else {
		get_spk();
	}
	is_edit = false;
}

function get_spk() {
	$.getJSON(base_url + 'manajemen_kontrak/kontrak/get_spk',function(res){
		var konten = '<option value=""></option>';
		$.each(res,function(k,v){
			konten += '<option value="'+v.nomor_spk+'" data-pengadaan="'+v.nama_pengadaan+'" data-vendor="'+v.nama_vendor+'" data-nilai="'+v.penawaran_terakhir+'">'+v.nomor_spk+' | '+v.nama_pengadaan+'</option>';
		});
		$('#nomor_spk').html(konten).trigger('change');
	});
}
$('#nomor_spk').change(function(){
	var o = $(this).find(':selected');
	$('#nama_pengadaan').val(o.attr('data-pengadaan'));
	$('#nama_vendor').val(o.attr('data-vendor'));
	$('#nilai_pengadaan').val(customFormat(o.attr('data-nilai')));
});
$('#btn-add').click(function(){
	addPasal();
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('.card').remove();
});
function addPasal(pasal,judul,isi) {
	var _pasal 	= typeof pasal == undefined ? '' : pasal;
	var _judul 	= typeof judul == undefined ? '' : judul;
	var _isi 	= typeof isi == undefined ? '' : isi;
	var konten = '<div class="card mb-2">'
		+ '<div class="card-header">'
			+ '<div class="form-group row">'
				+ '<label class="col-form-label col-sm-2" for="pasal'+idx+'">'+$('#btn-add').attr('data-pasal')+'</label>'
				+ '<div class="col-sm-10">'
					+ '<input type="text" name="pasal[]" id="pasal'+idx+'" autocomplete="off" class="form-control" data-validation="required">'
				+ '</div>'
			+ '</div>'
			+ '<div class="form-group row">'
				+ '<label class="col-form-label col-sm-2" for="judul_pasal'+idx+'">'+$('#btn-add').attr('data-judul')+'</label>'
				+ '<div class="col-sm-10">'
					+ '<input type="text" name="judul_pasal[]" id="judul_pasal'+idx+'" autocomplete="off" class="form-control" data-validation="required">'
				+ '</div>'
			+ '</div>'
		+ '</div>'
		+ '<div class="card-body p-0">'
			+ '<textarea name="isi_pasal[]" id="isi_pasal'+idx+'" class="form-control editor" data-validation="required" rows="4"></textarea>'
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
	if(_pasal) $('#pasal' + idx).val(_pasal);
	if(_judul) $('#judul_pasal' + idx).val(_judul);
	if(_isi) {
		$('#isi_pasal' + idx).val(_isi);
		CKEDITOR.instances['isi_pasal'+idx].setData(decodeEntities(_isi));
	}
	idx++;
}
function detail_callback(id){
	$.get(base_url+'manajemen_kontrak/kontrak/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}
$(document).on('click','.btn-print',function(){
	window.open(base_url + 'manajemen_kontrak/kontrak/cetak/' + encodeId($(this).attr('data-id')),'_blank');
});
</script>