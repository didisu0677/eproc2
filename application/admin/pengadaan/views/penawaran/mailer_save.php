<p style="text-align: justify;">Pembukaan dan verifikasi dokumen penawaran pengadaan <strong>"<?php echo $nama_pengadaan; ?>"</strong> telah selesai. Berikut daftar Rekanan yang lolos ke tahap berikutnya: </p>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th width="20" style="padding: 3px 5px;">No.</th>
			<th style="padding: 3px 5px;">Nama Rekanan</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($vendor as $k => $v) { ?>
			<tr>
				<td style="text-align: center; padding: 3px 5px;"><?php echo ($k + 1); ?></td>
				<td style=" padding: 3px 5px;"><?php echo $v['nama_vendor']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<p style="text-align: justify;">Tim panitia pengadaan akan melakukan evaluasi atas dokumen prnawaran yang lolos.</p>
<div style="text-align:center; padding: 10px;">
	<a href="<?php echo $url; ?>" style="background: #16D39A; color: #fff; padding: .5rem 1rem; border-radius: .175rem; text-decoration: none;">Lihat</a>
</div>