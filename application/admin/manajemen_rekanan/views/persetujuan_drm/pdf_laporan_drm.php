<div style="text-align: center; font-size: 12px; font-weight: bold;">DAFTAR REKANAN MAMPU</div>

<div style="text-align: center;">Nomor : <?php echo $nomor_rekomendasi; ?></div>



<br>

<br>

<p>PT. PEGADAIAN (Persero) Menyatakan bahwa,</p>

<div style="margin-bottom: 10px; padding-left: 20px">

<table style="margin-bottom: 10px;">

	<tr>

		<td width="100">Nama Perusahaan</td>

		<td width="10" style="text-align: center;">:</td>

		<td><?php echo $nama_rekanan; ?></td>

	</tr>

	<tr>

		<td>Nama Pimpinan</td>

		<td style="text-align: center;">:</td>

		<td><?php echo $nama_pimpinan; ?></td>

	</tr>

	<tr>

		<td>Alamat Perusahaan</td>

		<td style="text-align: center;">:</td>

		<td><?php echo $alamat; ?></td>

	</tr>

	<tr>

		<td>NPWP</td>

		<td style="text-align: center;">:</td>

		<td><?php echo $npwp; ?></td>

	</tr>

</table>

</div>



<p style="margin-bottom: 10px">Terdaftar sebagai rekanan di lingkungn PT. PEGADAIAN (Persero),</p>



<table width="100%" border="1" style="margin-bottom: 20px;">

	<thead>

		<tr>

			<th width="20" style="text-align: center;">No.</th>

			<th style="text-align: center;">Jenis Rekanan</th>

			<th style="text-align: center;">Bidang Usaha</th>

			<th style="text-align: center;">Kualifikasi</th>

		</tr>

	</thead>

	<tbody>

		<tr>

			<td style="text-align: center;"><?php echo '1'; ?></td>

			</td>

			<td><?php echo $jenis_rekanan == 1 ? lang('badan_usaha') : lang('perorangan'); ?></td>

			<td><?php echo $kategori_rekanan; ?></td>

			<td><?php echo $kualifikasi; ?></td>

		</tr>

	</tbody>

</table>



<p>Dengan ketentuan sebagai berikut:</p>

<div style="margin-bottom: 10px; padding-left: 20px">

<table width="100%" class="table-detail">

	<tr>

		<td width="10">1.</td>

		<td>PT. PEGADAIAN (Persero) tidak berkewajiban memberikan pekerjaan kepada perusahaan yang tercatat sebagai rekanan</td>

	</tr>

	<tr>

		<td>2.</td>

		<td>Memenuhi ketentuan-ketentuan PT. PEGADAIAN (Persero)</td>

	</tr>

	<tr>

		<td>3.</td>

		<td>Memberitahukan/menyampaikan data-data perusahaan yang mengalami perubahan</td>

	</tr>

	<tr>

		<td>4.</td>

		<td>Daftar Rekanan Mampu ini berlaku sejak <?php echo date_indo(date("Y/m/d",strtotime($tanggal_rekomendasi))); ?> sampai dengan tanggal <?php echo date_indo(date("Y/m/d",strtotime('+'.$jangka_waktu. 'years', strtotime($tanggal_rekomendasi)))); ?> </td>

	</tr>

	

</table>

</div>



<br>

<br>

<table width="100%">

	<tr>

		<td width="50%">&nbsp;</td>

		<td style="text-align: center;">

			<?php echo 'Jakarta'.', '.date_indo(date("Y/m/d")); ?><br>

			PT. PEGADAIAN (Persero)<br>

			Divisi Procurement

		</td>

	</tr>

	<tr>

		<td>&nbsp;</td>

		<td style="text-align: center;"><img src="assets/qrcode/<?php echo $kode_rekanan; ?>.png" style="width: 150px;" /></td>

	</tr>

	<tr>

		<td style="text-align: center;">&nbsp;</td>

		<td style="text-align: center;"><?php echo 'Super Administrator'; ?></td>

	</tr>

</table>

