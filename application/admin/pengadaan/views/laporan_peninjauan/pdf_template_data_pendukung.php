<div style="text-align: center; font-weight: 600; font-size: 14px; margin-bottom: 20px;">DAFTAR HASIL PENINJAUAN LAPANGAN</div>
<table style="margin-bottom: 10px;">
	<tr>
		<td width="100">Nama Perusahaan</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo isset($detil['id']) ? $detil['nama_vendor'] : ''; ?></td>
	</tr>
	<tr>
		<td>Alamat Perusahaan</td>
		<td style="text-align: center;">:</td>
		<td><?php echo isset($detil['id']) ? $detil['alamat_vendor'] : ''; ?></td>
	</tr>
	<tr>
		<td>Pengadaan</td>
		<td style="text-align: center;">:</td>
		<td><?php echo isset($detil['id']) ? $detil['nama_pengadaan'] : ''; ?></td>
	</tr>
	<tr>
		<td>Tanggal</td>
		<td style="text-align: center;">:</td>
		<td></td>
	</tr>
</table>
<p>DATA PENDUKUNG</p>
<table border="1" width="100%">
	<tr>
		<th width="10" rowspan="2" style="text-align: center;">No</th>
		<th rowspan="2" style="text-align: center;">Dokumen Pendukung</th>
		<th rowspan="2" style="text-align: center;">Detil</th>
		<th colspan="2" style="text-align: center;">Kelengkapan</th>
		<th rowspan="2" style="text-align: center;">Keterangan</th>
	</tr>
	<tr>
		<th style="text-align: center;">Ada</th>
		<th style="text-align: center;">Tidak Ada</th>
	</tr>
	<?php $i = 1; foreach($template as $t) { ?>
	<tr>
		<td style="text-align: center;"><?php echo $i; ?></td>
		<td><?php echo $t['deskripsi']; ?></td>
		<td><?php 
			if($t['nomor']) echo 'Nomor ..........';
			elseif($t['pilihan']) {
				$pilihan = json_decode($t['pilihan'],true);
				if(count($pilihan) > 0) {
					echo implode(' / ', $pilihan).' *)';
				}
			}
			$i++;
		?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<?php } ?>
	<tr>
		<td style="text-align: center;"><?php echo $i; ?></td>
		<td>Lain-Lain</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
<p style="margin-bottom: 10px;"><em>*) Coret Yang Tidak Perlu</em></p>
<table border="1" width="100%">
	<tr>
		<td width="33.33%" style="text-align: center;">Penyedia Barang / Jasa</td>
		<td width="33.33%" style="text-align: center;">Tim Peninjau</td>
		<td style="text-align: center;">Mengetahui</td>		
	</tr>
	<tr>
		<td style="height: 50px; vertical-align: bottom; font-weight: bold; text-align: center;">a.n. <?php echo $detil['nama_vendor']; ?></td>
		<td style="height: 50px; vertical-align: bottom; font-weight: bold; text-align: center;"><?php echo $detil['ketua']; ?></td>
		<td style="height: 50px; vertical-align: bottom; font-weight: bold; text-align: center;"><?php echo $detil['nama_pemberi_tugas']; ?></td>
	</tr>
</table>