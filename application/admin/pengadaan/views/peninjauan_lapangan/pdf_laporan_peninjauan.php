<div style="text-align: center; font-weight: 600; font-size: 14px; margin-bottom: 20px;">BERITA ACARA PENINJAUAN LAPANGAN</div>
<table style="margin-bottom: 10px;">
	<tr>
		<td width="100">Nama Perusahaan</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo $nama_vendor; ?></td>
	</tr>
	<tr>
		<td>Alamat Perusahaan</td>
		<td style="text-align: center;">:</td>
		<td><?php echo $alamat_vendor; ?></td>
	</tr>
	<tr>
		<td>Pengadaan</td>
		<td style="text-align: center;">:</td>
		<td><?php echo $nama_pengadaan; ?></td>
	</tr>
	<tr>
		<td>Tanggal</td>
		<td style="text-align: center;">:</td>
		<td><?php echo date_indo($tanggal_pelaksanaan); ?></td>
	</tr>
</table>
<table border="1" width="100%" style="margin-bottom: 10px;">
	<tr>
		<th width="10" rowspan="2" style="text-align: center;">No</th>
		<th rowspan="2" style="text-align: center;">Aspek Yang Ditinjau</th>
		<th colspan="2" style="text-align: center;">Kondisi</th>
		<th rowspan="2" style="text-align: center;">Keterangan</th>
	</tr>
	<tr>
		<th style="text-align: center; width: 80px;">Layak / Sesuai</th>
		<th style="text-align: center; width: 80px;">Tidak</th>
	</tr>
	<?php $i = 1; foreach($result as $t) { ?>
	<tr>
		<td style="text-align: center;"><?php echo $i; ?></td>
		<td><?php echo $t['deskripsi']; ?></td>
		<td style="text-align: center;"><?php if($t['kondisi'] == 'Sesuai / Layak') echo 'V'; ?></td>
		<td style="text-align: center;"><?php if($t['kondisi'] == 'Tidak') echo 'V'; ?></td>
		<td><?php echo $t['keterangan']; ?></td>
	</tr>
	<?php $i++; } foreach($lain as $t) { ?>
	<tr>
		<td style="text-align: center;"><?php echo $i; ?></td>
		<td><?php echo $t['deskripsi']; ?></td>
		<td style="text-align: center;"><?php if($t['kondisi'] == 'Sesuai / Layak') echo 'V'; ?></td>
		<td style="text-align: center;"><?php if($t['kondisi'] == 'Tidak') echo 'V'; ?></td>
		<td><?php echo $t['keterangan']; ?></td>
	</tr>
	<?php $i++; } ?>
</table>
<table border="1" width="100%" style="margin-bottom: 10px;">
	<tr>
		<td style="height: 60px">
			<div style="font-weight: bold; margin-bottom: 5px;">
				Kesimpulan Hasil Peninjauan :
			</div>
			<?php if($status_peninjauan == 1) {
				echo 'Rekanan / Vendor dinyatakan <strong>Layak</strong>, sehingga berhak melanjutkan proses pengadaan ke tahapan selanjutnya';
			} elseif($status_peninjauan == 9) {
				echo 'Rekanan / Vendor dinyatakan <strong>Tidak Layak</strong>, sehingga tidak berhak melanjutkan proses pengadaan ke tahapan selanjutnya';
			}
			?>
		</td>
	</tr>
</table>
<table border="1" width="100%">
	<tr>
		<td width="50%" style="text-align: center;"></td>
		<td style="text-align: center;">
			<div style="text-align: center;">Ketua Tim Peninjau</div>
			<div style="text-align: center;"><?php echo $kota_peninjauan.', '.date_indo($tanggal_pelaksanaan); ?></div>
			<div style="height: 40px;"></div>
			<div style="text-align: center; font-weight: bold;"><?php echo $ketua; ?></div>
		</td>
	</tr>
</table>