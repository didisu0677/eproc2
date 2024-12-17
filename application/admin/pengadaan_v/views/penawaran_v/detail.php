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
		<div class="card mb-2">
			<div class="card-header"><?php echo lang('_pengadaan'); ?></div>
			<div class="card-body">
				<table class="table table-bordered table-detail mb-0">
					<tr>
						<th width="200"><?php echo lang('nomor_pengadaan'); ?></th>
						<td><?php echo $nomor_pengadaan; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('nama_pengadaan'); ?></th>
						<td><?php echo $nama_pengadaan; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('identifikasi_pajak'); ?></th>
						<td><?php echo $identifikasi_pajak; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('keterangan_pengadaan'); ?></th>
						<td><?php echo $keterangan_pengadaan; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('detil_pengadaan'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan_v/detil_pengadaan?id_awz='.$aanwijzing->id); ?>" class="cInfo"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header"><?php echo lang('risalah_aanwijzing'); ?></div>
			<div class="card-body">
				<table class="table table-bordered table-detail mb-0">
					<tr>
						<th width="200"><?php echo lang('nomor_aanwijzing'); ?></th>
						<td><?php echo $nomor_aanwijzing; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('_rks'); ?></th>
						<td><?php 
						if($status_rks) {
							echo '[ <a href="'.base_url('pengadaan/aanwijzing/cetak_rks/'.encode_id([$id_rks_aanwijzing,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						?></td>
					</tr>
					<tr>
						<th><?php echo lang('dokumen_pendukung'); ?></th>
						<td><?php 
						if($status_rks) {
							echo '[ <a href="'.base_url('pengadaan_v/penawaran_v/dokumen_rks/'.encode_id([$id_rks_aanwijzing,rand()])).'" class="cInfo" aria-label="'.lang('dokumen_pendukung').'">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						?></td>
					</tr>
				</table>
			</div>
		</div>
		<?php if($status_aanwijzing == 'PENAWARAN') { ?>
		<div class="card mb-2">
			<div class="card-header"><?php echo lang('_penawaran'); ?></div>
			<div class="card-body">
				<?php
					if($open_penawaran) {
						if($lolos_penawaran == 0) {
							if($jumlah_edit < 4) {
								if($file_penawaran) {
									echo '<div class="alert alert-info">';
									echo lang('lbl_penawaran_terkirim_perubahan').' ';
									if($jumlah_edit == 1) echo lang('lbl_tiga_kali_perubahan');
									else if($jumlah_edit == 2) echo lang('lbl_dua_kali_perubahan');
									else  echo lang('lbl_sekali_perubahan');
									echo '</div>';
								}
								form_open(base_url('pengadaan_v/penawaran_v/save'),'post','form','data-submit="ajax" data-callback="reload"');
									col_init(3,9);
									input('hidden','nomor_pengadaan','nomor_pengadaan','',$nomor_pengadaan);
									input('text',lang('nomor_surat_penawaran_harga'),'nomor_penawaran','required|max-length:30');
									fileupload(lang('dokumen_administrasi').' (*.zip)','file_administrasi','required','data-accept="zip"');
									fileupload(lang('dokumen_teknis').' (*.zip)','file_teknis','required','data-accept="zip"');
									fileupload(lang('dokumen_penawaran_harga').' (*.zip)','file_penawaran_harga','required','data-accept="zip"');
									input('money',lang('nilai_total_penawaran_harga'),'nilai_total_penawaran','required|max-length:25','','data-prepend="RP"');
									input('money',lang('nilai_jaminan_penawaran'),'nilai_jaminan_penawaran','max-length:25','','data-prepend="RP"');
									textarea(lang('pesan'),'pesan_penawaran');
									form_button(lang('kirim_penawaran'),false);
								form_close();
							} else {
								echo '<div class="alert alert-info">'.lang('lbl_penawaran_terkirim').'</div>';
							}
						} else {
							if($lolos_penawaran == 1) {
								echo '<div class="alert alert-success">'.lang('dokumen_penawaran_anda_telah_dilakukan_verifikasi_dan_dinyatakan').' : <strong>'.strtoupper(lang('sah')).'</strong></div>';
							} else {
								echo '<div class="alert alert-danger">'.lang('dokumen_penawaran_anda_telah_dilakukan_verifikasi_dan_dinyatakan').' : <strong>'.strtoupper(lang('gugur')).'</strong></div>';
							}
						}
					} else {
						if($lolos_penawaran == 0) {
							echo '<div class="alert alert-info">'.lang('jadwal_pemasukan_dokumen_penawaran_pada').' : <strong>'.$tanggal_penawaran.'</strong></div>';
						} else {
							if($lolos_penawaran == 1) {
								echo '<div class="alert alert-success">'.lang('dokumen_penawaran_anda_telah_dilakukan_verifikasi_dan_dinyatakan').' : <strong>'.strtoupper(lang('sah')).'</strong></div>';
							} else {
								echo '<div class="alert alert-danger">'.lang('dokumen_penawaran_anda_telah_dilakukan_verifikasi_dan_dinyatakan').' : <strong>'.strtoupper(lang('gugur')).'</strong></div>';
							}							
						}
					}
				?>
			</div>
		</div>
		<?php } else { ?>
		<div class="card mb-2">
			<div class="card-header"><?php echo lang('_penawaran'); ?></div>
			<div class="card-body">
				<div class="alert alert-info"><?php echo lang('penawaran_pengadaan_ini_sudah_ditutup'); ?></div>
				<?php if($tipe_pengadaan == "Tender") { ?>
				<table class="table table-bordered table-detail mb-0">
					<tr>
						<th width="200"><?php echo lang('berita_acara_pembukaan_penawaran'); ?></th>
						<td><?php
						if($aanwijzing->tanggal_ba_pembukaan != '0000-00-00 00:00:00') {
							echo '[ <a href="'.base_url('pengadaan/penawaran/berita_acara/'.encode_id([$aanwijzing->id,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						?></td>
					</tr>
				</table>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>