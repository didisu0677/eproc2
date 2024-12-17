<img src="<?php echo dir_upload('setting').setting('logo_perusahaan'); ?>" width="150" style="margin-bottom: 20px;" />
<div style="text-align: center; font-weight: 600; font-size: 14px;">BERITA ACARA KLARIFIKASI TEKNIS DAN NEGOSIASI HARGA</div>
<div style="text-align: center; font-weight: 600; font-size: 14px;"><?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? strtoupper($nama_pengadaan) : 'PENGADAAN '.strtoupper($nama_pengadaan); ?></div>
<div style="text-align: center; font-weight: 600; font-size: 14px; margin-bottom: 10px;">PT. PEGADAIAN (PERSERO)</div>
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
<p>Pada hari ini, <strong><?php echo hari($tanggal_berita_acara); ?></strong> tanggal <strong><?php echo terbilang(date('d',strtotime($tanggal_berita_acara))); ?></strong> bulan <strong><?php echo bulan(date('m',strtotime($tanggal_berita_acara))); ?></strong> tahun <strong><?php echo terbilang(date('Y',strtotime($tanggal_berita_acara))); ?></strong> dimulai pukul <strong><?php echo date('h:i',strtotime($tanggal_berita_acara)).' '.$zona_waktu; ?></strong> s.d. selesai bertempat di <?php echo $lokasi_berita_acara; ?>, Panitia <?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? $nama_pengadaan : 'Pengadaan '.$nama_pengadaan; ?> telah melakukan Klerifikasi Teknis dan Negosiasi Harga.</p>
<?php if(count($vendor) == 1) { ?>
<p>Klarifikasi Teknis dan Negosiasi Harga dilakukan kepada peserta pengadaan yaitu <strong><?php echo $vendor[0]['nama_vendor']; ?></strong>.</p>
<?php } else { ?>
<p>Klarifikasi Teknis dan Negosiasi Harga dilakukan kepada peserta pengadaan yaitu:</p>
<div style="padding-left: 20px;">
	<table width="100%">
		<?php foreach($vendor as $k => $v) { ?>
		<tr>
			<td width="10"><?php echo ($k+1); ?>.</td>
			<td><strong><?php echo $v['nama_vendor']; ?></strong></td>
		</tr>
		<?php } ?>
	</table>
</div>
<?php } ?>
<p>Adapun hasil negosiasi harga penawaran untuk <?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? $nama_pengadaan : 'Pengadaan '.$nama_pengadaan; ?> adalah sebagai berikut:</p>
<div style="padding-left: 20px">
	<table width="100%">
		<tr>
			<td width="10">1.</td>
			<td>Harga Penawaran Peserta a.n. <?php echo $pemenang['nama_vendor']; ?> adalah: <strong><em>RP. <?php echo custom_format($pemenang['nilai_total_penawaran']).' ('.terbilang($pemenang['nilai_total_penawaran']).' Rupiah)'; ?></em></strong> termasuk pajak-pajak;</td>
		</tr>
		<tr>
			<td>2.</td>
			<td>Harga Onwer Estimate (OE) Pengadaan adalah: <strong><em>RP. <?php echo custom_format($hps).' ('.terbilang($hps).' Rupiah)'; ?></em></strong> termasuk pajak-pajak;</td>
		</tr>
		<tr>
			<td>3.</td>
			<td>Harga Penawaran yang disepakati adalah: <strong><em>RP. <?php echo custom_format($pemenang['penawaran_terakhir']).' ('.terbilang($pemenang['penawaran_terakhir']).' Rupiah)'; ?></em></strong> termasuk pajak-pajak;</td>
		</tr>
		<tr>
			<td>4.</td>
			<td>Ruang lingkup pekerjaan dan ketentuan lain dalam pengadaan ini seperti yang tercantum dalam RKS beserta perubahannya (hasil pembahasan saat Aanwijzing) serta lampiran Berita Acara ini;</td>
		</tr>
	</table>
</div>
<p style="margin-bottom: 10px">Berita Acara ini dibuat untuk dipergunakan seperlunya.</p>
<p style="font-weight: 600;">PANITIA <?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? strtoupper($nama_pengadaan) : 'PENGADAAN '.strtoupper($nama_pengadaan); ?></p>
<table style="margin-bottom: 10px;" border="1" width="100%">
	<tr>
		<th style="text-align: center;" width="20">No.</th>
		<th style="text-align: center;">Nama</th>
		<th style="text-align: center;" colspan="2">Tanda Tangan</th>
	</tr>
	<?php foreach($panitia as $k => $p) { ?>
	<tr>
		<td><?php echo ($k + 1); ?></td>
		<td><?php echo $p['nama_panitia']; ?></td>
		<?php if( ($k + 1) % 2 == 1) { ?>
		<td width="100"><?php echo ($k + 1); ?>.</td>
		<td width="100">&nbsp;</td>
		<?php } else { ?>
		<td width="100">&nbsp;</td>
		<td width="100"><?php echo ($k + 1); ?>.</td>
		<?php } ?>
	</tr>
	<?php } ?>
</table>
<p style="font-weight: 600; ">USER PENGADAAN</p>
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
<p style="font-weight: 600;">WAKIL PESERTA PELELANGAN</p>
<table style="margin-bottom: 10px;" border="1" width="100%">
	<tr>
		<th style="text-align: center;" width="20">No.</th>
		<th style="text-align: center;">Nama</th>
		<th style="text-align: center;" width="180">Nama Perusahaan</th>
		<th style="text-align: center;" width="100">Tanda Tangan</th>
	</tr>
	<?php $i = 1; foreach($vendor as $v) { ?>
	<tr>
		<td><?php echo $i; ?></td>
		<td><?php echo $v['nama_cp']; ?></td>
		<td><?php echo $v['nama_vendor']; ?></td>
		<td>&nbsp;</td>
	</tr>
	<?php $i++; } ?>
</table>
<?php 
if($peserta_lain) {
	$peserta_lain = json_decode($peserta_lain,true);
	if(count($peserta_lain) > 0) {
		?>
		<p style="font-weight: 600;">PESERTA LAIN</p>
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