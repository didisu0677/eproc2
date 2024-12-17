<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('pengadaan/monitor_pengadaan/data'),'tbl_pengajuan');
		thead();
			tr();
			    th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_pengajuan'),'','data-content="nomor_pengajuan" data-link="detail-pengajuan"');
				th(lang('nomor_pengadaan'),'','data-content="nomor_pengadaan" data-default="['.lang('belum_tersedia').']"');
				th(lang('tanggal_pengadaan'),'','data-content="tanggal_pengadaan" data-type="daterange"');
				th(lang('nama_pengadaan'),'','data-content="nama_pengadaan"');
				th(lang('nama_divisi'),'','data-content="nama_divisi"');
				th(lang('dibuat_oleh'),'','data-content="create_by"');
				th(lang('status'),'','data-content="status_desc"');
	table_close();
	?>
</div>
<script type="text/javascript">
$(document).on('click','.detail-pengajuan',function(){
	$.get(base_url+'pengadaan/monitor_pengadaan/detail?no_pengajuan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});
</script>