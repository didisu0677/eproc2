<div style="text-align: center; font-size: 12px; font-weight: bold;">SURAT PERSETUJUAN PENETAPAN PEMENANG</div>
<table width="150" style="margin: 0 auto 20px auto;">
	<tr>
		<td>Nomor</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo $nomor_persetujuan; ?></td>
	</tr>
	<tr>
		<td>Tanggal</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo date_indo($tanggal_persetujuan,false); ?></td>
	</tr>
</table>
<p style="margin-bottom: 5px">Merujuk:</p>
<div style="padding-left: 20px; margin-bottom: 10px;">
	<table>
		<tr>
			<td width="10">a.</td>
			<td style="text-align: justify;">
				Berita Acara Evaluasi <?php echo $nama_pengadaan; ?> No:<?php echo $aanwijzing->nomor_ba_evaluasi; ?> tanggal: <?php echo date_indo($aanwijzing->tanggal_ba_evaluasi, false); ?>
			</td>
		</tr>
		<tr>
			<td>b.</td>
			<td style="text-align: justify;">
				Berita Acara Klarifikasi Teknis dan Negosiasi No:<?php echo $klarifikasi->nomor_berita_acara; ?> tanggal: <?php echo date_indo($klarifikasi->tanggal_berita_acara, false); ?>
			</td>
		</tr>
	</table>
</div>
<p>Dengan ini PT. PEGADAIAN (Persero) menetapkan <strong><?php echo $nama_vendor; ?></strong> sebagai pelaksana pekerjaan untuk <strong><?php echo $nama_pengadaan; ?></strong> tersebut di bawah ini sesuai dengan jumlah, uraian pekerjaan, harga  dengan penjelasan sebagai berikut:</p>
<table width="100%" class="table-detail">
	<tr>
		<th width="10">I.</th>
		<th>Nama Pekerjaan</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><p>Pekerjaan adalah <strong><?php echo $nama_pengadaan; ?></strong></p></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<th>II.</th>
		<th>Nilai Pekerjaan</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<p>Harga dalam pelaksanaan pekerjaan <strong><?php echo $nama_pengadaan; ?></strong> ini adalah: <strong>RP <?php echo custom_format($penawaran_terakhir).' (<em>'.terbilang($penawaran_terakhir).' rupiah</em>)'; ?></strong></p>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<th>III.</th>
		<th>Ruang Lingkup Pekerjaan</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<p>Ruang lingkup pekerjaan, Fungsi dan Tujuan, kebutuhan perangkat, jumlah, spesifikasi, lokasi pekerjaan seperti yang tercantum dalam RKS, Risalah Aanwijzing, Klarifikasi Negosiasi, beserta lampirannya.</p>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<th>IV.</th>
		<th>Jangka Waktu</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php echo pdf_img(html_entity_decode($rks->jangka_waktu)); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<th>V.</th>
		<th>Pembayaran</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<p>Tata cara Pembayaran sesuai dengan ketentuan dalam RKS, SPK / Kontrak;</p>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<th>VI.</th>
		<th>Ketentuan Lain</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<p>Menugaskan kepada Panitia <?php echo $nama_pengadaan; ?> untuk mengumumkan / memberitahukan keputusan ini kepada peserta <?php echo $nama_pengadaan; ?> yang memasukkan penawaran;</p>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>
<p>Surat Persetujuan Penetapan Pemenang ini berlaku sejak tanggal ditetapkan dengan ketentuan apabila dikemudian hari terdapat kekeliruan akan diadakan perbaikan sebagaimana mestinya.</p>
<table width="100%" border="1">
	<tr>
		<td style="text-align: center;">Jabatan</td>
		<td style="text-align: center;">Nama</td>
		<td style="text-align: center;">Tanda Tangan</td>
	</tr>
	<?php foreach($persetujuan as $p) { ?>
	<tr>
		<td style="padding: 5px;"><?php echo $p->nama_persetujuan; ?></td>
		<td style="padding: 5px;"><?php echo $p->nama_user; ?></td>
		<td style="padding: 5px;">&nbsp;</td>
	</tr>
	<?php } ?>
</table>