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
						<td><?php echo $pemenang->nama_vendor; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('pengumuman_penetapan'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/penetapan_pemenang/cetak/'.encode_id($id_pemenang_pengadaan)); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header"><?php echo lang('sanggah'); ?></div>
			<div class="card-body">
				<?php if($pesan) { ?>
				<table class="table table-bordered table-detail mb-2">
					<tr>
						<th width="200"><?php echo lang('pesan'); ?></th>
						<td><?php echo $pesan; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('dokumen_pendukung'); ?></th>
						<td>
							<?php if($file_pendukung) { ?>
							[ <a href="<?php echo base_url('assets/uploads/sanggah/'.$file_pendukung); ?>"><?php echo lang('unduh'); ?></a> ]</td>
						<?php } else echo '-'; ?>
					</tr>
					<tr>
						<th><?php echo lang('jawaban'); ?></th>
						<td><?php echo $jawaban ? $jawaban : '<em class="text-danger">[ '.lang('belum_tersedia').' ]</em>'; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('file_jawaban'); ?></th>
						<td>
							<?php if($file_jawaban) { ?>
							[ <a href="<?php echo base_url('assets/uploads/sanggah/'.$file_jawaban); ?>"><?php echo lang('unduh'); ?></a> ]</td>
						<?php } else echo '-'; ?>
					</tr>
				</table>
				<?php } ?>
				<?php
					if($open_sanggah && $status_sanggah == 0) {
						if($pesan && !$jawaban) {
							alert(lang('msg_kirim_sanggah_ulang'));
						}
						if(!$jawaban) {
							form_open(base_url('pengadaan_v/sanggah_v/save'),'post','form','data-submit="ajax" data-callback="reload"');
								col_init(3,9);
								input('hidden','id','id','',$id);
								textarea(lang('pesan'),'pesan','required');
								fileupload(lang('dokumen_pendukung').' (*.zip)','file_pendukung','required','data-accept="zip"');
								form_button(lang('kirim_sanggahan'),false);
							form_close();
						}
					} else {
						if(!$pesan) {
							alert(lang('masa_sanggah').' : '.date_lang($pemenang->tanggal_mulai_sanggah).' - '.date_lang($pemenang->tanggal_selesai_sanggah));
						}
					}
				?>
			</div>
		</div>
	</div>
</div>