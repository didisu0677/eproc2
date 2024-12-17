<div style="text-align: center; font-weight: 600; font-size: 14px; margin-bottom: 20px;">LAPORAN HASIL WAWANCARA REKANAN</div>
<table style="margin-bottom: 10px;">
	<tr>
		<td width="100">Nama Perusahaan</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo isset($detil['id']) ? $detil['nama_vendor'] : ''; ?></td>
	</tr>
	<tr>
		<td>Tanggal</td>
		<td style="text-align: center;">:</td>
		<td></td>
	</tr>
</table>
<table border="1" width="100%" style="margin-bottom: 10px;">
	<tr>
		<th width="10" rowspan="" style="text-align: center;">No</th>
		<th rowspan="" style="text-align: center;">Pertanyaaan</th>
		<th rowspan="" style="text-align: center;">Jawaban</th>
	</tr>
	<?php $i = 1; foreach($template as $t) { ?>
	<tr>
		<td style="text-align: center;"><?php echo $i; ?></td>
		<td><?php echo $t['deskripsi']; ?></td>
		<td></td>
	</tr>
	<?php $i++; } ?>
	<tr>
		<td style="text-align: center; height: 100"><?php echo $i; ?></td>
		<td>Lain-Lain</td>
		<td></td>
	</tr>
</table>
<table border="1" width="100%" style="margin-bottom: 10px;">
	<tr>
		<td style="height: 60px">Kesimpulan Hasil Kunjungan dan Wawancara :</td>
	</tr>
</table>
<table border="1" width="100%">
	<tr>
		<td width="50%" style="text-align: center;"></td>
		<td style="text-align: center;">
			<div style="text-align: center;">Ketua Tim Peninjau</div>
			<div style="height: 40px;"></div>
			<div style="text-align: center; font-weight: bold;"><?php echo $detil['ketua']; ?></div>
		</td>
	</tr>
</table>