<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<select class="select2 infinity custom-select" id="filter">
				<option value="1"><?php echo lang('semua_pengadaan'); ?></option>
				<option value="9"><?php echo lang('pemenang'); ?></option>
			</select>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('pengadaan_v/penetapan_pemenang_v/data'),'tbl_pemenang_pengadaan');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_pengadaan'),'','data-content="nomor_pengadaan"');
				th(lang('tanggal_pengadaan'),'','data-content="tanggal_pengadaan" data-type="daterange"');
				th(lang('nama_pengadaan'),'','data-content="nama_pengadaan"');
				th(lang('nama_rekanan'),'','data-content="nama_vendor"');
				th(lang('alamat'),'','data-content="alamat_vendor"');	
				th(lang('penawaran_terakhir'),'','data-content="penawaran_terakhir" data-type="currency"');	
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<script type="text/javascript">
$('#filter').change(function(){
	var url = base_url + 'pengadaan_v/penetapan_pemenang_v/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
</script>