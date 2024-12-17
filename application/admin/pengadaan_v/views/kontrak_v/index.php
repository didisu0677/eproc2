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
	table_open('',true,base_url('pengadaan_v/kontrak_v/data'),'tbl_kontrak');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_kontrak'),'','data-content="nomor_kontrak"');
				th(lang('nama_pekerjaan'),'','data-content="nama_pengadaan"');
				th(lang('nilai_pekerjaan'),'text-right','data-content="nilai_pengadaan" data-type="currency"');
				th(lang('tanggal_mulai_kontrak'),'','data-content="tanggal_mulai_kontrak" data-type="daterange"');
				th(lang('tanggal_selesai_kontrak'),'','data-content="tanggal_selesai_kontrak" data-type="daterange"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
