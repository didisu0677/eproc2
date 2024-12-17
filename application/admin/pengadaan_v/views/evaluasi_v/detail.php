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
						<th><?php echo lang('keterangan_pengadaan'); ?></th>
						<td><?php echo $keterangan_pengadaan; ?></td>
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
		<div class="card mb-2">
			<div class="card-header"><?php echo lang('evaluasi_penawaran'); ?></div>
			<div class="card-body">
				<table class="table table-bordered table-detail mb-0">
					<tr>
						<th width="200"><?php echo lang('berita_acara_evaluasi_dokumen_penawaran'); ?></th>
						<td><?php
						if($aanwijzing->tanggal_ba_evaluasi != '0000-00-00 00:00:00') {
							echo '[ <a href="'.base_url('pengadaan/evaluasi/berita_acara/'.encode_id([$aanwijzing->id,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>