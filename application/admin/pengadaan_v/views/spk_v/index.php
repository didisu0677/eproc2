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
	table_open('',true,base_url('pengadaan_v/spk_v/data'),'tbl_pemenang_pengadaan');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_pengadaan'),'','data-content="nomor_pengadaan"');
				th(lang('tanggal_pengadaan'),'','data-content="tanggal_pengadaan" data-type="daterange"');
				th(lang('nama_pengadaan'),'','data-content="nama_pengadaan"');
				th(lang('nilai_pekerjaan'),'','data-content="penawaran_terakhir" data-type="currency"');	
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>