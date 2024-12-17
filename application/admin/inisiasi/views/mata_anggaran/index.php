<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php echo access_button('delete,export,import'); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('inisiasi/mata_anggaran/data'),'tbl_mata_anggaran');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('kode_mata_anggaran'),'','data-content="kode_mata_anggaran"');
				th(lang('nama_anggaran'),'','data-content="nama_anggaran"');
				th(lang('besaran_anggaran'),'text-right','data-content="besaran_anggaran" data-type="currency"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-lg');
	modal_body();
		form_open(base_url('inisiasi/mata_anggaran/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('text',lang('kode_mata_anggaran'),'kode_mata_anggaran','required|unique|max-length:35');
			input('text',lang('nama_anggaran'),'nama_anggaran','required|unique|max-length:250');
			input('money',lang('besaran_anggaran'),'besaran_anggaran','required|max-length:25');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('inisiasi/mata_anggaran/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
