<table width="100%" style="margin-bottom: 20px;">
	<tr>
		<td width="60%">
			<table width="90%">
				<tr>
					<td width="50">Nomor</td>
					<td width="10" style="text-align: center;">:</td>
					<td><?php echo $nomor_pengumuman; ?></td>
				</tr>
				<tr>
					<td>Lampiran</td>
					<td style="text-align: center;">:</td>
					<td>1 (satu) Berkas</td>
				</tr>
				<tr>
					<td>Perihal</td>
					<td style="text-align: center;">:</td>
					<td>Pengumuman Penetapan Pelaksana <strong><em><?php echo $nama_pengadaan; ?></em></strong></td>
				</tr>
			</table>
		</td>
		<td>
			<table width="100%">
				<tr><td>Jakarta, <?php echo date_indo($tanggal_pengumuman); ?></td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>Kepada Yth.</td></tr>
				<tr><td>Pimpinan / Direktur</td></tr>
				<?php foreach($vendor as $v) { ?>
				<tr><td><?php echo $v['nama_vendor']; ?></td></tr>
				<?php } ?>
				<tr><td>Di</td></tr>
				<tr><td style="padding-left: 20px;">Tempat</td></tr>
			</table>
		</td>
	</tr>
</table>
<p style="margin-bottom: 10px">
Berdasarkan proses Pengadaan, bersama ini kami umumkan penetapan sebagai calon pelaksana pekerjaan untuk <strong><?php echo $nama_pengadaan; ?></strong>. adalah:
</p>
<div style="margin-bottom: 10px; padding-left: 20px">
	<table>
		<tr>
			<td width="80">Nama Perusahaan</td>
			<td width="10" style="text-align: center;">:</td>
			<td><strong><?php echo $nama_vendor; ?></strong></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td style="text-align: center;">:</td>
			<td><strong><?php echo $alamat_vendor; ?></strong></td>
		</tr>
		<tr>
			<td>Nilai Pengadaan</td>
			<td style="text-align: center;">:</td>
			<td><strong>RP <?php echo custom_format($penawaran_terakhir).' (<em>'.terbilang($penawaran_terakhir).' rupiah</em>)'; ?></strong></td>
		</tr>
	</table>
</div>
<p style="margin-bottom: 10px">
Bahwa sesuai dengan ketentuan Pedoman Pengadaan Barang dan Jasa PT. PEGADAIAN (Persero), maka  disampaikan hal-hal sebagai berikut:
</p>
<table style="margin-bottom: 10px" width="100%">
	<tr>
		<td width="10">1.</td>
		<td style="text-align: justify;">Bahwa mulai sejak tanggal pengumuman penetapan calon pelaksana pekerjaan <?php echo $nama_pengadaan; ?> ini, dimulailah masa sanggah sampai dengan <?php echo $lama_sanggah; ?> Hari Kerja ke depan, yaitu sejak tanggal:  <strong><?php echo date_indo($tanggal_mulai_sanggah).' s.d. '.date_indo($tanggal_selesai_sanggah); ?></strong>. Sanggahan harus secara tertulis, dengan menyerahkan jaminan sanggah berupa bank garansi dari Bank Pemerintah sebesar <?php echo c_percent($inisiasi->ketentuan_bank_garansi); ?> % dari penawaran calon pemenang I,  dan harus disertai bukti. Jawaban sanggah akan disampaikan paling lama 14 hari kerja sejak diterima.</td>
	</tr>
	<tr>
		<td>2.</td>
		<td style="text-align: justify;">Bahwa Keputusan tentang Rekanan / Vendor yang akan menjadi Pelaksana Pekerjaan adalah setelah berakhirnya masa sanggah atau bila sanggahan dinyatakan tidak benar, melalui <strong>Surat Penunjukan</strong> dan <strong>SPK</strong>.</td>
	</tr>
</table>
<p style="margin-bottom: 20px">
Demikian kami sampaikan dan kepada rekanan/vendor yang telah ikut serta dalam proses <?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? $nama_pengadaan : 'Pengadaan '.$nama_pengadaan; ?> ini diucapkan terima kasih.
</p>
<table width="100%">
	<tr>
		<td>&nbsp;</td>
		<td width="200">
			<div style="font-weight: bold">PT. PEGADAIAN (Persero)</div>
			<div style="height: 50px">&nbsp;</div>
			<div style="font-weight: bold"><?php echo $tanda_tangan; ?></div>
			<div><?php echo $jabatan_tanda_tangan; ?></div>
		</td>
	</tr>
</table>