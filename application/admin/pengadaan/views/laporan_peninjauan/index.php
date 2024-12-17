<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
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
	table_open('',true,base_url('pengadaan/laporan_peninjauan/data'),'tbl_aanwijzing_peninjauan');
		thead();
			tr();
			    th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_pengadaan'),'','data-content="nomor_pengadaan"');
				th(lang('nama_pengadaan'),'','data-content="nama_pengadaan"');
				th(lang('rekanan'),'','data-content="nama_vendor"');
				th(lang('nomor_surat_tugas'),'','data-content="nomor_surat_tugas"');
				th(lang('tanggal_peninjauan'),'','data-content="tanggal_peninjauan" data-type="daterange"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<script type="text/javascript">
$('#filter').change(function(){
	var url = base_url + 'pengadaan/laporan_peninjauan/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
</script>