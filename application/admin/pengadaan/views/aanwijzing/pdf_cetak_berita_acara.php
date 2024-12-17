<img src="<?php echo dir_upload('setting').setting('logo_perusahaan'); ?>" width="150" style="margin-bottom: 20px;" />
<div style="text-align: center; font-weight: 600; font-size: 14px;">BERITA ACARA PENJELASAN PEKERJAAN PELELANGAN (AANWIJZING)</div>
<div style="text-align: center; font-weight: 600; font-size: 14px; margin-bottom: 10px;"><?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? strtoupper($nama_pengadaan) : 'PENGADAAN '.strtoupper($nama_pengadaan); ?></div>
<table width="200" style="margin: 0 auto 20px auto;">
	<tr>
		<td>Nomor</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo $nomor_berita_acara; ?></td>
	</tr>
	<tr>
		<td>Tanggal</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo date_indo($tanggal_berita_acara,false); ?></td>
	</tr>
</table>
<p>Pada hari ini, <?php echo hari($tanggal_berita_acara); ?> tanggal <?php echo terbilang(date('d',strtotime($tanggal_berita_acara))); ?> bulan <?php echo bulan(date('m',strtotime($tanggal_berita_acara))); ?> tahun <?php echo terbilang(date('Y',strtotime($tanggal_berita_acara))); ?> dimulai pukul <?php echo date('H:i',strtotime($tanggal_berita_acara)).' '.$zona_waktu; ?> bertempat di <?php echo str_replace("\n", ' ', $lokasi_berita_acara); ?>, kami yang bertanda tangan di bawah ini Panitia Pelelangan PT PEGADAIAN (Persero) telah mengadakan penjelasan mengenai pelelangan pekerjaan tersebut di atas.</p>
<p>Sesuai dengan <?php echo $tipe_pengadaan == 'Tender' ? 'Pengumuman Lelang' : 'Undangan Aanwijzing'; echo ' tanggal '.date_indo($tanggal_pengumuman,false); ?> yang telah dihadiri oleh peserta pelelangan yaitu:</p>
<div style="padding-left: 30px">
	<table>
		<?php
		$i = 1;
		if($peserta_berita_acara) {
			foreach(json_decode($peserta_berita_acara,true) as $v) {
				if(isset($v['vendor'])) {
					echo '<tr><td style="padding-right: 5px;">'.$i.'.</td><td>'.$v['vendor'].'</td></tr>';
					$i++;
				}
			}
		}
		?>
	</table>
</div>
<p>Penjelasan-penjelasan, tambahan dan perubahan terhadap RKS yang dibuat pada penjelasan ini (terlampir dalam risalah Aanwijzing) merupakan bagian yang tidak terpisahkan dari berita acara ini.</p>
<p>Demikian Berita Acara ini dibuat untuk digunakan sebagaimana mestinya.</p>
<p style="font-weight: 600">PANITIA PELELANGAN PT PEGADAIAN (Persero)</p>
<table style="margin-bottom: 10px;" border="1" width="100%">
	<tr>
		<th style="text-align: center;" width="20">No.</th>
		<th style="text-align: center;">Nama</th>
		<th style="text-align: center;" width="180">Jabatan</th>
		<th style="text-align: center;" width="100">Tanda Tangan</th>
	</tr>
	<?php foreach($panitia as $k => $p) { ?>
	<tr>
		<td><?php echo ($k + 1); ?></td>
		<td><?php echo $p['nama_panitia']; ?></td>
		<td><?php echo $p['posisi_panitia']; ?></td>
		<td>&nbsp;</td>
	</tr>
	<?php } ?>
</table>
<p style="font-weight: 600">USER PENGADAAN</p>
<table style="margin-bottom: 10px;" border="1" width="100%">
	<tr>
		<th style="text-align: center;" width="20">No.</th>
		<th style="text-align: center;">Nama</th>
		<th style="text-align: center;" width="100">Tanda Tangan</th>
	</tr>
	<tr>
		<td>1</td>
		<td><?php echo $nama_creator; ?></td>
		<td>&nbsp;</td>
	</tr>
	<?php $i=2; foreach($user_pengadaan as $u) { ?>
	<tr>
		<td><?php echo $i; ?></td>
		<td><?php echo $u->nama_user; ?></td>
		<td>&nbsp;</td>
	</tr>
	<?php $i++; } ?>
</table>
<p style="font-weight: 600">WAKIL PESERTA PELELANGAN</p>
<table style="margin-bottom: 10px;" border="1" width="100%">
	<tr>
		<th style="text-align: center;" width="20">No.</th>
		<th style="text-align: center;">Nama</th>
		<th style="text-align: center;" width="180">Nama Perusahaan</th>
		<th style="text-align: center;" width="100">Tanda Tangan</th>
	</tr>
	<?php $i = 1; foreach(json_decode($peserta_berita_acara,true) as $k => $p) { ?>
	<tr>
		<td><?php echo $i; ?></td>
		<td><?php echo $p['nama_perwakilan']; ?></td>
		<td><?php echo $p['vendor']; ?></td>
		<td>&nbsp;</td>
	</tr>
	<?php $i++; } ?>
</table>
<?php 
if($peserta_lain) {
	$peserta_lain = json_decode($peserta_lain,true);
	if(count($peserta_lain) > 0) {
		?>
		<p style="font-weight: 600">PESERTA LAIN</p>
		<table style="margin-bottom: 10px;" border="1" width="100%">
			<tr>
				<th style="text-align: center;" width="20">No.</th>
				<th style="text-align: center;">Nama</th>
				<th style="text-align: center;" width="100">Tanda Tangan</th>
			</tr>
			<?php $i = 1; foreach($peserta_lain as $k => $p) { ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $p; ?></td>
				<td>&nbsp;</td>
			</tr>
			<?php $i++; } ?>
		</table>
		<?php
	}
}