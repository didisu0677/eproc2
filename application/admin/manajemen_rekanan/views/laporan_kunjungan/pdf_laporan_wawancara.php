<div style="text-align: center; font-weight: 600; font-size: 14px; margin-bottom: 20px;">LAPORAN HASIL WAWANCARA REKANAN</div>
<table style="margin-bottom: 10px;">
	<tr>
		<td width="100">Nama Perusahaan</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo $nama_vendor; ?></td>
	</tr>
	<tr>
		<td>Tanggal</td>
		<td style="text-align: center;">:</td>
		<td><?php echo date_indo($tanggal_kunjungan); ?></td>
	</tr>
</table>
<table border="1" width="100%" style="margin-bottom: 10px;">
	<tr>
		<th width="10" rowspan="" style="text-align: center;">No</th>
		<th rowspan="" style="text-align: center;">PERTANYAAN</th>
		<th rowspan="" style="text-align: center;">JAWABAN</th>
	</tr>

	<?php $i = 1; foreach($result as $t) { ?>
	<tr>
		<td style="text-align: center;"><?php echo $i; ?></td>
		<td><?php echo $t['deskripsi']; ?></td>
		<td><?php echo $t['keterangan']; ?></td>
	</tr>
	<?php $i++; } foreach($lain as $t) { ?>
	<tr>
		<td style="text-align: center;"><?php echo $i; ?></td>
		<td><?php echo $t['deskripsi']; ?></td>
		<td><?php echo $t['keterangan']; ?></td>
	</tr>
	<?php $i++; } ?>
</table>
<table border="1" width="100%" style="margin-bottom: 10px;">
	<tr>
		<td style="height: 60px">
			<div style="font-weight: bold; margin-bottom: 5px;">
				Kesimpulan Hasil Kunjungan dan Wawancara :
			</div>
			<?php if($status_kunjungan == 1) {
				echo '<strong>'.$nama_vendor.'</strong> dinyatakan <strong>Layak</strong> untuk menjadi Rekanan / Vendor '.setting('company');
			} elseif($status_kunjungan == 9) {
				echo '<strong>'.$nama_vendor.'</strong> dinyatakan <strong>Tidak Layak</strong> untuk menjadi Rekanan / Vendor '.setting('company');
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
			<div style="text-align: center;"><?php echo date_indo($tanggal_kunjungan); ?></div>
			<div style="height: 40px;"></div>
			<div style="text-align: center; font-weight: bold;"><?php echo $ketua; ?></div>
		</td>
	</tr>
</table>