<?php include_lang('pengadaan'); ?>
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
		<?php if($approve == 0) { ?>
		<div class="alert alert-info"><?php echo lang('menunggu_persetujuan'); ?></div>
		<?php } elseif($approve == 1) { ?>
		<div class="alert alert-success"><?php echo lang('pemenang_pengadaan_sudah_disetujui'); ?></div>
		<?php } else { ?>
		<div class="alert alert-danger"><?php echo lang('pemenang_pengadaan_ditolak').' : <strong>'.$alasan_ditolak.'</strong>'; ?></div>
		<?php } ?>
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
					<tr>
						<th><?php echo lang('_hps'); ?></th>
						<td><?php echo custom_format($hps); ?></td>
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
						<th><?php echo lang('penawaran_awal'); ?></th>
						<td><?php echo custom_format($penawaran_awal); ?></td>
					</tr>
					<tr>
						<th><?php echo lang('penawaran_terakhir'); ?></th>
						<td><?php echo custom_format($penawaran_terakhir); ?></td>
					</tr>
					<?php if($approve == 1) { ?>
					<tr>
						<th><?php echo lang('surat_persetujuan'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan_v/penetapan_pemenang_v/surat_persetujuan/'.encode_id($id)); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<?php } if($lama_sanggah && $approve == 1) { ?>
					<tr>
						<th><?php echo lang('pengumuman_penetapan'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan_v/penetapan_pemenang_v/cetak/'.encode_id($id)); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
		<?php if($approve == 1 && !$lama_sanggah) { 
			if($tipe_pengadaan == 'Lelang') {
				if(!$lama_sanggah) {
			?>
		<div class="card">
			<div class="card-header"><?php echo lang('sanggah'); ?></div>
			<div class="card-body">
				<?php
				form_open(base_url('pengadaan_v/penetapan_pemenang_v/save'),'post','form-sanggah','data-submit="ajax" data-callback="reload"');
					col_init(3,3);
					input('hidden','id','id','',$id);
					input('text',lang('lama_masa_sanggah'),'lama_sanggah','required|number|min:1','','data-append="'.lang('hari').'"');
					input('date',lang('tanggal_mulai'),'tanggal_mulai_sanggah','required');
					input('date',lang('tanggal_selesai'),'tanggal_selesai_sanggah','required');
					form_button(lang('simpan'),false);
				form_close();
				?>
			</div>
		</div>
		<?php 
				}
			} elseif($status_sanggah == 0) { ?>
		<div class="card">
			<div class="card-header"><?php echo lang('spk'); ?></div>
			<div class="card-body">
				<button type="button" class="btn btn-info" id="btn-proses" data-pengadaan="<?php echo $nomor_pengadaan; ?>"><?php echo lang('proses'); ?></button>
			</div>
		</div>	
			<?php }
		} elseif($approve == 9) { ?>
		<div class="card">
			<div class="card-header"><?php echo lang('inisiasi_ulang'); ?></div>
			<div class="card-body">
				<?php if($inisiasi_ulang) { ?>
				<div class="alert alert-warning">
					<?php echo lang('pengadaan_telah_dibatalkan_dan_diinisiasi_ulang'); ?>
				</div>
				<?php } else { ?>
				<button type="button" class="btn btn-secondary" id="btn-reinisiasi" data-pengadaan="<?php echo $nomor_pengadaan; ?>"><i class="fa-sync"></i><?php echo lang('inisiasi_ulang'); ?></button>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
