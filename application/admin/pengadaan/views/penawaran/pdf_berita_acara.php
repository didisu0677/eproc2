<img src="<?php echo dir_upload('setting').setting('logo_perusahaan'); ?>" width="150" style="margin-bottom: 20px;" />
<div style="text-align: center; font-weight: 600; font-size: 14px;">BERITA ACARA PEMBUKAAN SURAT PENAWARAN HARGA</div>
<div style="text-align: center; font-weight: 600; font-size: 14px; margin-bottom: 10px;"><?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? strtoupper($nama_pengadaan) : 'PENGADAAN '.strtoupper($nama_pengadaan); ?></div>
<table width="200" style="margin: 0 auto 20px auto;">
	<tr>
		<td>Nomor</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo $nomor_ba_pembukaan; ?></td>
	</tr>
	<tr>
		<td>Tanggal</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo date_indo($tanggal_ba_pembukaan,false); ?></td>
	</tr>
</table>
<p>Pada hari ini, <?php echo hari($tanggal_ba_pembukaan); ?> tanggal <?php echo terbilang(date('d',strtotime($tanggal_ba_pembukaan))); ?> bulan <?php echo bulan(date('m',strtotime($tanggal_ba_pembukaan))); ?> tahun <?php echo terbilang(date('Y',strtotime($tanggal_ba_pembukaan))); ?> yang bertanda tangan dibawah ini panitia pelelangan/pengadaan barang PT PEGADAIAN (Persero) masing-masing sebagai berikut:</p>
<table style="margin-bottom: 10px;" width="100%">
	<tr>
		<th width="30">No.</th>
		<th width="180">Nama</th>
		<th>Jabatan</th>
	</tr>
	<?php foreach($panitia as $k => $p) { ?>
	<tr>
		<td><?php echo ($k + 1); ?></td>
		<td><?php echo $p['nama_panitia']; ?></td>
		<td><?php echo $p['posisi_panitia']; ?></td>
	</tr>
	<?php } ?>
</table>
<p style="font-weight: 600;">PESERTA PELELANGAN</p>
<table width="100%">
	<tr>
		<td width="10">1.</td>
		<td>Penyedia Barang/Jasa  yang mengikuti Rapat Penjelasan (Aanwijzing)</td>
		<td width="30" style="text-align: center;">=</td>
		<td width="90"><strong><?php echo $jumlah_vendor; ?></strong> Perusahaan</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>Penyedia Barang/Jasa yang tidak memasukkan Surat Penawaran Harga (SPH) (mengundurkan diri dan terlambat) </td>
		<td style="text-align: center;">=</td>
		<td><strong><?php echo $jumlah_vendor - $jumlah_penawar; ?></strong> Perusahaan</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>Penyedia Barang/Jasa yang memasukkan Surat Penawaran Harga (SPH) </td>
		<td style="text-align: center;">=</td>
		<td><strong><?php echo $jumlah_penawar; ?></strong> Perusahaan</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td>2.</td>
		<td colspan="3" style="text-align: justify;">
			Dari <strong><?php echo $jumlah_penawar; ?></strong>  Perusahaan yang memasukkan surat penawaran harga, <strong><?php echo $jumlah_penawar - $jumlah_sah; ?></strong> Perusahaan dinyatakan tidak sah karena tidak memenuhi persyaratan yang ditentukan dalam RKS dan Berita Acara Hasil Aanwijzing  dan <strong><?php echo $jumlah_sah; ?></strong> Perusahaan dinyatakan sah karena memenuhi persyaratan yang sesuai dengan RKS dan Berita Acara Hasil Aanwijzing
		</td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
		<td>3.</td>
		<td colspan="3" style="text-align: justify;">
			Demikian BERITA ACARA ini ditanda tangani bersama oleh panitia pelelangan/pengadaan barang/jasa   PT.  PEGADAIAN (Persero) bersama wakil-wakil rekanan.
		</td>
	</tr>
</table>
<p style="font-weight: 600; text-align: center;">PANITIA <?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? strtoupper($nama_pengadaan) : 'PENGADAAN '.strtoupper($nama_pengadaan); ?></p>
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
<p style="font-weight: 600; text-align: center; ">USER PENGADAAN</p>
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
<p style="font-weight: 600; text-align: center;">WAKIL PESERTA PELELANGAN</p>
<table style="margin-bottom: 10px;" border="1" width="100%">
	<tr>
		<th style="text-align: center;" width="20">No.</th>
		<th style="text-align: center;">Nama</th>
		<th style="text-align: center;" width="180">Nama Perusahaan</th>
		<th style="text-align: center;" width="100">Tanda Tangan</th>
	</tr>
	<?php $i = 1; foreach(json_decode($peserta_ba_pembukaan,true) as $k => $p) { ?>
	<tr>
		<td><?php echo $i; ?></td>
		<td><?php echo $p['nama_perwakilan']; ?></td>
		<td><?php echo $p['vendor']; ?></td>
		<td>&nbsp;</td>
	</tr>
	<?php $i++; } ?>
</table>
<?php 
if($peserta_lain_penawaran) {
	$peserta_lain = json_decode($peserta_lain_penawaran,true);
	if(count($peserta_lain) > 0) {
		?>
		<p style="font-weight: 600; text-align: center;">PESERTA LAIN</p>
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