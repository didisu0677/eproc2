<div style="text-align: center; font-size: 12px; font-weight: bold;">SURAT PENUNJUKAN PELAKSANA PEKERJAAN</div>
<div style="text-align: center;">Nomor : <?php echo $nomor_penunjukan; ?></div>
<div style="text-align: center; margin-bottom: 20px;">Tanggal : <?php echo date_indo($tanggal_penunjukan,false); ?></div>
<table width="100%">
	<tr>
		<td>&nbsp;</td>
		<td width="40%">
			<table width="100%">
				<tr><td>Kepada Yth.</td></tr>
				<tr><td>Pimpinan / Direktur</td></tr>
				<tr><td><?php echo $nama_vendor; ?></td></tr>
				<tr><td>Di</td></tr>
				<tr><td style="padding-left: 20px;">Tempat</td></tr>
			</table>
		</td>
	</tr>
</table>
<p style="margin-bottom: 5px">Menimbang:</p>
<div style="padding-left: 20px; margin-bottom: 10px;">
	<table>
		<tr>
			<td width="10">1.</td>
			<td style="text-align: justify;">
				Telah dilaksanakan proses tahapan <strong><?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? $nama_pengadaan : 'Pengadaan '.$nama_pengadaan; ?></strong>  sesuai dengan Pedoman Pengadaan Barang dan Jasa (PPBJ) dan ketentuan perubahannya;
			</td>
		</tr>
		<tr>
			<td>2.</td>
			<td style="text-align: justify;">
				Tidak adanya keberatan dari pihak-pihak terkait
			</td>
		</tr>
	</table>
</div>
<p>Dengan ini PT. PEGADAIAN (Persero) menunjuk PT.  PACKET SYSTEMS INDONESIA sebagai pelaksana pekerjaan untuk Pengadaan tersebut di bawah ini sesuai dengan jumlah, uraian singkat pekerjaan, dan harga sebagai berikut:</p>
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
		<th style="vertical-align: top">V.</th>
		<th>Ketentuan lain sesuai RKS, Berita Acara Aanwijzing dan hasil klarifikasi teknis serta ketentuan lain diatur secara lebih detail di dalam Kontrak.</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<p>Surat penunjukan ini berlaku sejak tanggal ditetapkan dengan ketentuan apabila dikemudian hari terdapat kekeliruan akan diadakan perbaikan sebagaimana mestinya.</p>
		</td>
	</tr>
</table>
<table width="100%">
	<tr>
		<td width="50%">&nbsp;</td>
		<td style="text-align: center;">
			PT. PEGADAIAN (Persero)<br>
			<?php //if(user('is_kanwil') == 0) { echo 'A.n. Direksi' ; 
			//}else{
			//	echo 'A.n. Pinwil' ;	
			//}
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="height: 50px">&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="text-align: center;"><strong><?php echo $ttd_penunjukan; ?></strong></td>
	</tr>
	<tr>
		<td style="text-align: center;">&nbsp;</td>
		<td style="text-align: center;"><?php echo $jabatan_ttd_penunjukan; ?></td>
	</tr>
</table>