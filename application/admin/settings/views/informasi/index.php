<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php echo access_button('delete,active,inactive'); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('settings/informasi/data'),'tbl_informasi');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('judul'),'','data-content="judul"');
				th(lang('informasi'),'','data-content="informasi"');
				th(lang('publikasi'),'text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-lg','data-openCallback="updateSummernote"');
	modal_body();
		form_open(base_url('settings/informasi/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('text',lang('judul'),'judul','required');
			textarea(lang('informasi'),'informasi','required','','data-editor');
			toggle(lang('publikasi'),'is_active');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
?>
<script src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js'); ?>"></script>