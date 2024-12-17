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
			<div class="card-header"><?php echo lang('pemenang'); ?></div>
			<div class="card-body">
				<table class="table table-bordered table-detail mb-0">
					<tr>
						<th width="200"><?php echo lang('nama_rekanan'); ?></th>
						<td><?php echo $nama_vendor; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('alamat_rekanan'); ?></th>
						<td><?php echo $alamat_vendor; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('nilai_pekerjaan'); ?></th>
						<td><?php echo custom_format($penawaran_terakhir); ?></td>
					</tr>
					<tr>
						<th><?php echo lang('surat_penunjukan'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/spk/surat_penunjukan/'.encode_id($id)); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<tr>
						<th><?php echo lang('spk'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/spk/cetak/'.encode_id($id)); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>