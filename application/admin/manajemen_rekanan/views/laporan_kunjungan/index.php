<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<?php include_lang('pengadaan'); ?>
		<div class="float-right">
			<select class="select2 infinity custom-select" id="filter">
				<option value="0"><?php echo lang('belum_dilaporkan'); ?></option>
				<option value="1"><?php echo lang('sudah_dilaporkan'); ?></option>
			</select>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('manajemen_rekanan/laporan_kunjungan/data'),'tbl_m_kunjungan_vendor');
		thead();
			tr();
			    th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_kunjungan'),'','data-content="nomor_kunjungan"');
				th(lang('rekanan'),'','data-content="nama_vendor"');
				th(lang('tanggal_kunjungan'),'','data-content="tanggal_kunjungan" data-type="daterange"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<script type="text/javascript">
$('#filter').change(function(){
	var url = base_url + 'manajemen_rekanan/laporan_kunjungan/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
</script>