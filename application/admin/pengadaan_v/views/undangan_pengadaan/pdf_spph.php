<img src="<?php echo dir_upload('setting').setting('logo_perusahaan'); ?>" width="150" style="margin-bottom: 10px;" />
<table width="100%" style="margin-bottom: 20px">
	<tr>
		<td>
			<table>
				<tr>
					<td width="70">Nomor</td>
					<td width="10" style="text-align: center;">:</td>
					<td><?php echo $nomor_spph; ?></td>
				</tr>
				<tr>
					<td>Lampiran</td>
					<td style="text-align: center;">:</td>
					<td>1 Berkas</td>
				</tr>
				<tr>
					<td>Perihal</td>
					<td style="text-align: center;">:</td>
					<td><strong>SURAT PERMINTAAN PENAWARAN HARGA</strong></td>
				</tr>
			</table>
		</td>
		<td width="40%">
			<table>
				<tr>
					<td>
						Jakarta, <?php echo date_indo($tanggal_spph,false); ?><br /><br />
						Yth.<br />
						<?php if($vendor->jenis_rekanan == 1) echo 'Pimpinan / Direktur Utama<br />'; ?>
						<strong><?php echo $vendor->nama; ?></strong><br />
						<strong><?php echo $vendor->alamat.', '.$vendor->nama_kelurahan.', '.$vendor->nama_kecamatan.', '.$vendor->nama_kota.' - '.$vendor->nama_provinsi; ?></strong><br />
						Di<br />
						Tempat
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<p>Dengan ini kami sampaikan Permintaan Penawaran Harga kepada Perusahaan Saudara atas pekerjaan yang akan kami adakan sebagimana terinci dalam uraian sebagai berikut: </p>
<table border="1" width="100%">
	<thead>
		<tr>
			<th style="font-weight: 400; text-align: center;" width="20">NO.</th>
			<th style="font-weight: 400; text-align: center;">SPESIFIKASI / URAIAN BARANG</th>
			<th style="font-weight: 400; text-align: center;" width="100">KUANTITAS</th>
		</tr>
		<tr>
			<th style="font-weight: 400; text-align: center; font-size: 8px;">1</th>
			<th style="font-weight: 400; text-align: center; font-size: 8px;">2</th>
			<th style="font-weight: 400; text-align: center; font-size: 8px;">3</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="text-align: center; ?>">1.</td>
			<td><?php echo $nama_pengadaan; ?></td>
			<td style="text-align: center;">1 Paket</td>
		</tr>
	</tbody>
</table>
<p>Untuk kelancaran Proses Pengadaan dapat kami informasikan  bahwa Penjelasan Pekerjaan (aanwijzing)<?php if($tanggal_peninjauan) echo ' dan peninjauan lapangan'; ?> akan dilaksanakan pada :</p>
<div style="padding-left: 20px">
	<table>
		<tr>
			<td width="100">Hari</td>
			<td width="10" style="text-align: center;">:</td>
			<td><?php echo $tanggal_aanwijzing ? hari($tanggal_aanwijzing) : '-'; ?></td>
		</tr>
		<tr>
			<td>Tanggal</td>
			<td style="text-align: center;">:</td>
			<td><?php echo $tanggal_aanwijzing ? date_indo($tanggal_aanwijzing,false) : '-'; ?></td>
		</tr>
		<tr>
			<td>Jam</td>
			<td style="text-align: center;">:</td>
			<td><?php echo $tanggal_aanwijzing ? date('H:i',strtotime($tanggal_aanwijzing)).' '.$zona_aanwijzing .' s/d Selesai' : '-'; ?></td>
		</tr>
		<tr>
			<td>Acara</td>
			<td style="text-align: center;">:</td>
			<td>Aanwijzing (Penjelasan Pekerjaan)</td>
		</tr>
		<tr>
			<td>Tempat</td>
			<td style="text-align: center;">:</td>
			<td><?php echo $tanggal_aanwijzing ? str_replace("\n", '<br />', $tempat_aanwijzing) : '-'; ?></td>
		</tr>
	</table>
</div>
<?php if($tanggal_peninjauan) { ?>
<br />
<div style="padding-left: 20px">
	<table>
		<tr>
			<td width="100">Hari</td>
			<td width="10" style="text-align: center;">:</td>
			<td><?php echo $tanggal_peninjauan ? hari($tanggal_peninjauan) : '-'; ?></td>
		</tr>
		<tr>
			<td>Tanggal</td>
			<td style="text-align: center;">:</td>
			<td><?php echo $tanggal_peninjauan ? date_indo($tanggal_peninjauan,false) : '-'; ?></td>
		</tr>
		<tr>
			<td>Jam</td>
			<td style="text-align: center;">:</td>
			<td><?php echo $tanggal_peninjauan ? date('H:i',strtotime($tanggal_peninjauan)).' '.$zona_aanwijzing .' s/d Selesai' : '-'; ?></td>
		</tr>
		<tr>
			<td>Acara</td>
			<td style="text-align: center;">:</td>
			<td>Peninjauan Lapangan</td>
		</tr>
		<tr>
			<td>Tempat</td>
			<td style="text-align: center;">:</td>
			<td><?php echo $tanggal_peninjauan ? str_replace("\n", '<br />', $tempat_peninjauan) : '-'; ?></td>
		</tr>
	</table>
</div>
<?php } ?>
<p>Demikian disampaikan dan terima kasih.</p>
<br />
<table width="100%">
	<tr>
		<td>&nbsp;</td>
		<td width="200" style="text-align: center;">
			PT. Pegadaian (Persero)<br />
			<strong>DIVISI <?php echo $nama_divisi; ?></strong>
			<br /><br /><br /><br /><br />
			<strong><?php echo $nama_tanda_tangan; ?></strong><br />
			<?php echo $jabatan_tanda_tangan; ?>
		</td>
	</tr>
</table>