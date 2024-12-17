<?php if(isset($metode_negosiasi)) {
	if($metode_negosiasi == 'Negosiasi Satu Rekanan') { ?>
		<p style="text-align: justify;">Evaluasi Penawaran atas <strong>"<?php echo $nama_pengadaan; ?>"</strong> telah selesai. Tahapan berikutnya adalah Klarifikasi dan Negosiasi dengan Tim Panitia Pengadaan <strong>"<?php echo $nama_pengadaan; ?>"</strong>.</p>
	<?php } else { ?>
		<p style="text-align: justify;">Evaluasi Penawaran atas <strong>"<?php echo $nama_pengadaan; ?>"</strong> telah selesai. Berikut daftar Rekanan yang lolos ke tahap berikutnya: </p>
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
		<p style="text-align: justify;">Tim panitia pengadaan akan melakukan klarifikasi dan negosiasi kepada peserta pengadaan yang lolos.</p>
	} ?>
<?php } else { ?>
<p style="text-align: justify;">Evaluasi dokumen penawaran pengadaan <strong>"<?php echo $nama_pengadaan; ?>"</strong> telah selesai. Diperlukan tindaklanjut penugasan <strong>Peninjauan Lapangan</strong> untuk rekanan yang lolos.</p>
<p style="text-align: justify;">Klik tombol dibawah untuk tindakan langsung.</p>
<div style="text-align:center; padding: 10px;">
	<a href="<?php echo $url; ?>" style="background: #16D39A; color: #fff; padding: .5rem 1rem; border-radius: .175rem; text-decoration: none;">Lihat</a>
</div>
<?php } ?>