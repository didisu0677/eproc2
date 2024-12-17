<img src="<?php echo dir_upload('setting').setting('logo_perusahaan'); ?>" width="150" style="margin-bottom: 20px;" />
<div style="text-align: center; font-weight: 600; font-size: 14px;">BERITA ACARA PENILAIAN/EVALUASI PENYEDIA BARANG/ATAU JASA</div>
<div style="text-align: center; font-weight: 600; font-size: 14px; margin-bottom: 10px;"><?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? strtoupper($nama_pengadaan) : 'PENGADAAN '.strtoupper($nama_pengadaan); ?></div>
<table width="200" style="margin: 0 auto 20px auto;">
	<tr>
		<td>Nomor</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo $nomor_ba_evaluasi; ?></td>
	</tr>
	<tr>
		<td>Tanggal</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo date_indo($tanggal_ba_evaluasi,false); ?></td>
	</tr>
</table>
<table width="100%" style="margin-bottom: 10px;">
	<tr>
		<td width="10">1.</td>
		<td style="padding-bottom: 10px;">
			Pada hari ini, <?php echo hari($tanggal_ba_evaluasi); ?> tanggal <?php echo terbilang(date('d',strtotime($tanggal_ba_evaluasi))); ?> bulan <?php echo bulan(date('m',strtotime($tanggal_ba_evaluasi))); ?> tahun <?php echo terbilang(date('Y',strtotime($tanggal_ba_evaluasi))); ?> dimulai pukul <?php echo date('h:i',strtotime($tanggal_ba_evaluasi)).' '.$zona_waktu_evaluasi; ?> bertempat di <?php echo $lokasi_ba_evaluasi; ?>, Panitia Pengadaan barang/jasa telah mengadakan rapat penilaian/evaluasi terhadap penawaran-penawaran yang telah memenuhi persyaratan administratif, untuk pekerjaan <?php echo $nama_pengadaan; ?>
		</td>
	</tr>
	<tr>
		<td>2.</td>
		<td style="padding-bottom: 10px;">
			<div style="margin-bottom: 5px">Uraian penilaian/evaluasi ini meliputi:</div>
			<div style="padding-left: 10px;">Penilaian Teknis</div>
			<div style="padding-left: 10px;">Penilaian Harga Penawaran</div>
		</td>
	</tr>
	<tr>
		<td>3.</td>
		<td style="padding-bottom: 10px;">
			<div style="margin-bottom: 5px">
				Berdasarkan hasil penilaian/evaluasi yang dilakukan oleh Panitia Pengadaan Barang/Jasa tersebut diatas, Panitia menyimpulkan untuk mengusulkan <?php echo count($vendor); ?> rekanan dengan peringkat terbaik baik dari sisi kemampuan teknis maupun harga penawaran yang diajukan yaitu:
			</div>
			<?php foreach($vendor as $k => $v) { ?>
			<div style="margin-bottom: 0; padding-left: 20px;">Peringkat <?php echo ($k+1); ?>, <strong><?php echo $v->nama_vendor; ?></strong> dengan nilai bobot  <strong><?php echo $v->total_penilaian; ?></strong>	dan harga penawaran Rp. <strong><?php echo custom_format($v->nilai_total_penawaran); ?></strong></div>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td>4.</td>
		<td style="padding-bottom: 10px;">Demikian Berita Acara ini dibuat  untuk dipergunakan seperlunya.</td>
	</tr>
</table>

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
<?php 
if($peserta_lain_penawaran) {
	$peserta_lain = json_decode($peserta_lain_penawaran,true);
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