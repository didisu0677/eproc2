<img src="<?php echo dir_upload('setting').setting('logo_perusahaan'); ?>" width="150" style="margin-bottom: 20px;" />
<div style="text-align: center; font-weight: 600; font-size: 14px;">SURAT TUGAS</div>
<div style="text-align: center; font-weight: 600; margin-bottom: 10px;">Nomor : <?php echo $nomor_surat_tugas; ?></div>
<p style="text-align: justify;">Yang bertandatangan dibawah ini:</p>
<table style="margin-bottom: 10px" width="100%">
	<tr>
		<td width="100">Nama</td>
		<td width="20" style="text-align: center;">:</td>
		<td><strong><?php echo $nama_pemberi_tugas; ?></strong></td>
	</tr>
	<tr>
		<td>Jabatan</td>
		<td style="text-align: center;">:</td>
		<td><strong><?php echo $jabatan_pemberi_tugas; ?></strong></td>
	</tr>
</table>
<p>Dengan ini menugaskan sebagai Tim Peninjau kepada :</p>
<table width="100%" border="1" cellspacing="0" cellpadding="0" style="margin-bottom: 10px">
	<thead>
		<tr>
			<th width="20">No.</th>
			<th>Nama</th>
			<th>Jabatan</th>
			<th>Unit Kerja</th>
			<th>Keterangan</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($peninjau as $k => $v) { ?>
			<tr>
				<td style="text-align: center;"><?php echo ($k + 1); ?></td>
				<td><?php echo $v->nama_user; ?></td>
				<td><?php echo $v->jabatan_user; ?></td>
				<td><?php echo $v->unit_kerja_user; ?></td>
				<td><?php echo $v->posisi; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<p>Untuk melakukan / melaksanakan peninjauan langsung ke lokasi perusahaan Penyedia Barang / dan atau Jasa.</p>
<table style="margin-bottom: 10px" width="100%">
	<tr><td colspan="3">Tugas Dilaksanakan Pada : </td></tr>
	<tr>
		<td width="100">Hari / Tanggal</td>
		<td width="20" style="text-align: center;">:</td>
		<td><strong><?php echo hari($tanggal_peninjauan).', '.date_indo($tanggal_peninjauan); ?></strong></td>
	</tr>
	<tr>
		<td>Nama Perusahaan</td>
		<td style="text-align: center;">:</td>
		<td><strong><?php echo $nama_vendor; ?></strong></td>
	</tr>
	<tr>
		<td>Alamat Perusahaan</td>
		<td style="text-align: center;">:</td>
		<td><strong><?php echo $alamat_vendor; ?></strong></td>
	</tr>
</table>
<p style="margin-bottom: 25px">Demikian surat tugas ini diberikan untuk dilaksanakan sebaik-baiknya dan penuh tanggung jawab.</p>
<table width="100%">
	<td>&nbsp;</td>
	<td width="150">
		<div style="font-weight: bold; text-align: center;">Jakarta, <?php echo date_indo($tanggal_surat_tugas); ?></div>
		<div style="height: 90px; text-align: center;"><?php echo $jabatan_pemberi_tugas; ?></div>
		<div style="font-weight: bold; text-align: center;"><?php echo $nama_pemberi_tugas; ?>
	</td>
</table>