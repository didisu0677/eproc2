<div style="text-align: center; font-size: 12px; font-weight: bold;">SURAT PERINTAH KERJA (SPK)</div>
<div style="text-align: center;">Nomor : <?php echo $nomor_spk; ?></div>
<div style="text-align: center; margin-bottom: 20px;">Tanggal : <?php echo date_indo($tanggal_spk,false); ?></div>
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
<p style="margin-bottom: 5px">Merujuk:</p>
<div style="padding-left: 20px; margin-bottom: 10px;">
	<table>
		<tr>
			<td width="10">1.</td>
			<td style="text-align: justify;">
				Surat Penawaran Harga dari Saudara Nomor: <?php echo $nomor_penawaran; ?> Tanggal: <?php echo date_indo($tanggal_penawaran); ?>;
			</td>
		</tr>
		<tr>
			<td>2.</td>
			<td style="text-align: justify;">
				Berita Acara Klarifikasi Teknis dan Negosiasi Nomor:<?php echo $klarifikasi->nomor_berita_acara; ?> Tanggal: <?php echo date_indo($klarifikasi->tanggal_berita_acara, false); ?>
			</td>
		</tr>
		<tr>
			<td>3.</td>
			<td style="text-align: justify;">
				Surat Penunjukan Pelaksana Penyedia Barang/Jasa Nomor: <?php echo $nomor_penunjukan; ?>  Tanggal: <?php echo date_indo($tanggal_penunjukan); ?>
			</td>
		</tr>
	</table>
</div>
<p>Dengan ini PT. PEGADAIAN (Persero) memerintahkan kepada <strong><?php echo $nama_vendor; ?></strong> untuk mulai melaksanakan pekerjaan <strong><?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? $nama_pengadaan : 'Pengadaan '.$nama_pengadaan; ?></strong>  tersebut di bawah ini sesuai dengan jumlah, uraian pekerjaan, harga serta syarat-syarat yang tercantum dalam RKS dan Surat Perintah Kerja (SPK) sebagai berikut: </p>
<table width="100%" class="table-detail tabel-noborder">
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
		<th>Ruang Lingkup</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<div style="font-weight: bold">Latar Belakang</div>
			<?php echo pdf_img(html_entity_decode($rks->latar_belakang)); ?><br />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<div style="font-weight: bold">Spesifikasi</div>
			<?php echo pdf_img(html_entity_decode($rks->spesifikasi)); ?><br />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<div style="font-weight: bold">Jumlah Kebutuhan</div>
			<?php echo pdf_img(html_entity_decode($rks->jumlah_kebutuhan)); ?><br />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<div style="font-weight: bold">Ruang Lingkup</div>
			<?php echo pdf_img(html_entity_decode($rks->ruang_lingkup)); ?><br />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<div style="font-weight: bold">Lain-lain</div>
			<?php echo pdf_img(html_entity_decode($rks->lain_lain)); ?>
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
		<th>Jaminan Pengadaan</th>
	</tr>
	<?php if(isset($jaminan['jaminan_penawaran'])) { ?>
	<tr>
		<td>&nbsp;</td>
		<td>
			<div style="font-weight: bold">Jaminan Penawaran</div>
			<?php echo pdf_img(html_entity_decode($jaminan['jaminan_penawaran'])); ?><br />
		</td>
	</tr>
	<?php } if(isset($jaminan['jaminan_pelaksanaan'])) { ?>
	<tr>
		<td>&nbsp;</td>
		<td>
			<div style="font-weight: bold">Jaminan Pelaksanaan</div>
			<?php echo pdf_img(html_entity_decode($jaminan['jaminan_pelaksanaan'])); ?><br />
		</td>
	</tr>
	<?php } if(isset($jaminan['jaminan_pemeliharaan'])) { ?>
	<tr>
		<td>&nbsp;</td>
		<td>
			<div style="font-weight: bold">Jaminan Pemeliharaan</div>
			<?php echo pdf_img(html_entity_decode($jaminan['jaminan_pemeliharaan'])); ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<th>VI.</th>
		<th>Persyaratan Pembayaran</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<table width="100%">
				<tr>
					<td width="20">1.</td>
					<td>Pemberi Tugas tidak memberi Uang Muka Pembayaran (UMP)</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<table width="100%">
				<tr>
					<td width="20">2.</td>
					<td>
						<?php echo pdf_img(html_entity_decode($rks->pola_pembayaran)); ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<table width="100%">
				<tr>
					<td width="20">3.</td>
					<td>Pembayaran  dilakukan  melalui  Divisi  Tresuri  Kantor  Pusat  PT.  Pegadaian  (Persero)  di  Jakarta,  dengan  cara  transfer  melalui  Rekening  Bank Pelaksana Pekerjaan</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<table width="100%">
				<tr>
					<td width="20">4.</td>
					<td>
						Persyaratan pembayaran dengan melampirkan :
						<ul>
							<li>Surat Permohonan Pembayaran yang mencantumkan nomor rekening rekanan</li>
							<li>Kuitansi rangkap 2 (dua) lembar pertama bermaterai Rp. 6.000,- (enam ribu rupiah)</li>
							<li>Surat tagihan/Invoice</li>
							<li>Berita Acara Serah Terima I dan II yang ditandatangani para pihak</li>
							<li>Fotocopy SPK (Surat Perintah Kerja) atau Fotocopy perjanjian/kontrak (tidak dengan lampirannya)</li>
							<li>e-Faktur Pajak</li>
							<li>Fotocopy NPWP</li>
						</ul>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<table width="100%">
				<tr>
					<td width="20">5.</td>
					<td>
						Jadwal pembayaran kepada rekanan dilakukan 2 (dua) kali dalam sebulan yaitu setiap tanggal 11 dan tanggal 26 dengan penjelasan sebagai berikut :
						<ul>
							<li>Apabila berkas tagihan diterima oleh Divisi Tresuri Kantor Pusat PT. Pegadaian (Persero) di Jakarta pada rentang tanggal 06 sampai dengan tanggal 20, maka pembayaran dilakukan pada tanggal 26 (faktur pajak dibuat dalam bulan berjalan);</li>
							<li>Apabila berkas tagihan diterima oleh Divisi Tresuri Kantor Pusat PT. Pegadaian (Persero) di Jakarta pada rentang tanggal 21 sampai dengan tanggal 05 bulan berikutnya, maka pembayaran dilakukan pada tanggal 11 (faktur pajak dibuat pada bulan berikutnya).</li>
						</ul>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>
<p style="font-weight: bold; text-decoration: underline;">Syarat-syarat</p>
<table width="100%" class="table-detail tabel-noborder" style="margin-bottom: 20px;">
	<tr>
		<th width="10">1.</th>
		<th>Persyaratan Khusus:</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>Syarat-syarat lain yang tidak diatur di dalam SPK ini mengacu pada ketentuan yang termuat dalam Kontrak.</td>
	</tr>
	<tr>
		<th>2.</th>
		<th>Persyaratan Lain:</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<table width="100%">
				<tr>
					<td width="20">a.</td>
					<td>
						PT. PEGADAIAN (Persero) dibebaskan dari segala bentuk tuntutan apapun dari Pihak Ketiga yang berkaitan dengan SPK ini.
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<table width="100%">
				<tr>
					<td width="20">b.</td>
					<td>
						Setiap perubahan mengenai jumlah, uraian pekerjaan, harga, dan syarat yang tercantum dalam Kontrak  dan SPK ini harus disetujui secara tertulis oleh PT. PEGADAIAN (Persero) dan Perusahaan Saudara.
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<table width="100%">
				<tr>
					<td width="20">c.</td>
					<td>
						Sebagai konfirmasi Persetujuan, Saudara wajib menandatangani SPK asli ini di atas materai yang cukup, dan mengembalikan copy/tembusan paling lambat 2 (dua) hari kerja, sejak diterimanya SPK ini sebagai pemberitahuan, baik yang disampaikan melalui faksimile maupun kurir.
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%">
	<tr>
		<td width="50%">Diterima Tanggal</td>
		<td style="text-align: center;">
			PT. PEGADAIAN (Persero)<br>
			<?php echo $lokasi_ttd_spk ; 
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
		<td style="text-align: center;"><strong><?php echo $ttd_spk; ?></strong></td>
	</tr>
	<tr>
		<td style="text-align: center;">Direktur</td>
		<td style="text-align: center;"><?php echo $jabatan_ttd_spk; ?></td>
	</tr>
</table>