<p style="text-align: justify;">Berdasarkan hasil pemeriksaan kami atas dokumen perusahaan anda yang terdaftar di sistem procurement PT. pegadaian (persero). kami informasikan bahwa dokumen berikut ini sudah habis masa berlakunya yaitu : </p>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th width="20" style="padding: 3px 5px;">No.</th>
			<th style="padding: 3px 5px;">Nama Perusahaan</th>
			<th style="padding: 3px 5px;">Nama Dokumen</th>
			<th style="padding: 3px 5px;">Tanggal Kadaluarsa</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dokumen as $k => $v) { ?>
			<tr>
				<td style="text-align: center; padding: 3px 5px;"><?php echo ($k + 1); ?></td>
				<td style=" padding: 3px 5px;"><?php echo $v['nama_perusahaan']; ?></td>
				<td style=" padding: 3px 5px;"><?php echo $v['nama_dokumen']; ?></td>
				<td style=" padding: 3px 5px;"><?php echo $v['tanggal_kadaluarsa']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<p style="text-align: justify;">Anda bisa melakukan upload dan update dokumen yang terbaru dengan mengclick link dibawah ini</p>
<div style="text-align:center; padding: 10px;">
	<a href="<?php echo $url; ?>" style="background: #16D39A; color: #fff; padding: .5rem 1rem; border-radius: .175rem; text-decoration: none;">update dokumen</a>
</div>

