<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb($title); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<div class="main-container">
		<?php
		card_open(lang('data_pekerjaan'),'mb-2');
			table_open('table table-bordered table-detail');
				tr();
					th(lang('nomor_spk'),'','width="200"');
					td($nomor_spk);
				tr();
					th(lang('nama_pekerjaan'));
					td($nama_pengadaan);
				tr();
					th(lang('nilai_pekerjaan'));
					td(custom_format($nilai_pengadaan));
				tr();
					th(lang('nama_rekanan'));
					td($nama_vendor);
			table_close();
		card_close();
		card_open(lang('data_kontrak'),'mb-2');
			table_open('table table-bordered table-detail');
				tr();
					th(lang('nomor_kontrak'),'','width="200"');
					td($nomor_kontrak);
				tr();
					th(lang('tanggal_mulai_kontrak'));
					td(c_date($tanggal_mulai_kontrak));
				tr();
					th(lang('tanggal_selesai_kontrak'));
					td(c_date($tanggal_selesai_kontrak));
				tr();
					th(lang('tanggal_dikeluarkan'));
					td(c_date($tanggal_dikeluarkan));
				tr();
					th(lang('tempat_dikeluarkan'));
					td($tempat_dikeluarkan);
				tr();
					th('&nbsp;');
					td('<a href="'.base_url('manajemen_kontrak/kontrak/cetak/'.encode_id($id)).'" target="_blank" class="btn btn-info">'.lang('cetak').'</a>');
			table_close();
		card_close();
		?>
	</div>
</div>