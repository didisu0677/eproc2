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
	table_open('',true,base_url('inisiasi/metode_pengadaan/data'),'tbl_metode_pengadaan');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('kode'),'','data-content="kode"');
				th(lang('metode_pengadaan'),'','data-content="metode_pengadaan"');
				th(lang('kategori'),'','data-content="tipe"');
				th(lang('batas_bawah_pengadaan'),'text-right','data-content="limit_bawah_pengadaan" data-type="currency"');
				th(lang('batas_atas_pengadaan'),'text-right','data-content="limit_atas_pengadaan" data-type="currency"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-lg');
	modal_body();
		form_open(base_url('inisiasi/metode_pengadaan/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('text',lang('kode'),'kode','required|unique|max-length:35');
			input('text',lang('metode_pengadaan'),'metode_pengadaan','required|unique|max-length:255');
			select2(lang('kategori'),'tipe','required|infinity',['Pemilihan Langsung','Penunjukan Langsung','Lelang','Jasa Langsung']);
			input('money',lang('batas_bawah_pengadaan'),'limit_bawah_pengadaan','max-length:25');
			input('money',lang('batas_atas_pengadaan'),'limit_atas_pengadaan','max-length:25');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('inisiasi/metode_pengadaan/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
