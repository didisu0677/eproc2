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
	table_open('',true,base_url('settings/barang_jasa/data'),'tbl_m_item');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('klasifikasi'),'','data-content="klasifikasi" data-table="tbl_m_klasifikasi klasifikasi"');
				th(lang('kode'),'','data-content="kode"');
				th(lang('nama'),'','data-content="nama"');
				th(lang('spesifikasi'),'','data-content="spesifikasi"');
				th(lang('satuan'),'','data-content="satuan" data-table="tbl_m_satuan satuan"');
				th(lang('harga'),'text-right','data-content="harga" data-type="currency"');
				th(lang('sumber_harga'),'','data-content="sumber_harga"');
				th(lang('tanggal_berlaku_harga'),'','data-content="tanggal_update" data-type="daterange"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-lg');
	modal_body();
		form_open(base_url('settings/barang_jasa/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			select2(lang('klasifikasi'),'id_klasifikasi','required',$opt_id_klasifikasi,'id','klasifikasi');
			input('text',lang('kode'),'kode','required|unique');
			input('text',lang('nama'),'nama','required');
			textarea(lang('spesifikasi'),'spesifikasi','required');
			select2(lang('satuan'),'id_satuan','required',$opt_id_satuan,'id','satuan');
			input('money',lang('harga'),'harga','required');
			input('text',lang('sumber_harga'),'sumber_harga','required');
			input('date',lang('tanggal_berlaku_harga'),'tanggal_update','required');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/barang_jasa/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
