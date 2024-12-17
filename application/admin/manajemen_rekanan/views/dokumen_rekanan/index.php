<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php echo access_button('delete,active,inactive,export,import'); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('manajemen_rekanan/dokumen_rekanan/data'),'tbl_m_dokumen_rekanan');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('kode_dokumen'),'','data-content="kode_dokumen"');
				th(lang('nama_dokumen'),'','data-content="nama_dokumen"');
				th(lang('status_dokumen'),'','data-content="status_dokumen"');
				th(lang('keterangan'),'','data-content="keterangan"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form');
	modal_body();
		form_open(base_url('manajemen_rekanan/dokumen_rekanan/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('text',lang('kode_dokumen'),'kode_dokumen');
			input('text',lang('nama_dokumen'),'nama_dokumen');
			select2(lang('status_dokumen'),'status_dokumen','required|infinity',array('Mandatory','Tidak Mandatory'));
			input('text',lang('keterangan'),'keterangan');
			toggle(lang('aktif').'?','is_active');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('manajemen_rekanan/dokumen_rekanan/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
