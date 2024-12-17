<p style="text-align: justify;">Yang bertandatangan dibawah ini:</p>
<table style="margin-bottom: 10px">
	<tr>
		<td width="100">Nama</td>
		<td width="20" style="text-align: center;">:</td>
		<td><strong><?php echo $detail->nama_pemberi_tugas; ?></strong></td>
	</tr>
	<tr>
		<td>Jabatan</td>
		<td style="text-align: center;">:</td>
		<td><strong><?php echo $detail->jabatan_pemberi_tugas; ?></strong></td>
	</tr>
</table>
<p>Dengan ini menugaskan sebagai Tim Peninjau kepada :</p>
<table width="100%" border="1" cellspacing="0" cellpadding="0" style="margin-bottom: 10px">
	<thead>
		<tr>
			<th width="20" style="padding: 3px 5px;">No.</th>
			<th style="padding: 3px 5px;">Nama</th>
			<th style="padding: 3px 5px;">Jabatan</th>
			<th style="padding: 3px 5px;">Unit Kerja</th>
			<th style="padding: 3px 5px;">Keterangan</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($peninjau as $k => $v) { ?>
			<tr>
				<td style="text-align: center; padding: 3px 5px;"><?php echo ($k + 1); ?></td>
				<td style=" padding: 3px 5px;"><?php echo $v->nama_user; ?></td>
				<td style=" padding: 3px 5px;"><?php echo $v->jabatan_user; ?></td>
				<td style=" padding: 3px 5px;"><?php echo $v->unit_kerja_user; ?></td>
				<td style=" padding: 3px 5px;"><?php echo $v->posisi; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<p>Untuk melakukan / melaksanakan peninjauan langsung ke lokasi perusahaan Penyedia Barang / dan atau Jasa.</p>
<table style="margin-bottom: 10px">
	<tr><td colspan="3">Tugas Dilaksanakan Pada : </td></tr>
	<tr>
		<td width="150">Hari / Tanggal</td>
		<td width="20" style="text-align: center;">:</td>
		<td><strong><?php echo hari($detail->tanggal_peninjauan).', '.date_indo($detail->tanggal_peninjauan); ?></strong></td>
	</tr>
	<tr>
		<td>Nama Perusahaan</td>
		<td style="text-align: center;">:</td>
		<td><strong><?php echo $detail->nama_vendor; ?></strong></td>
	</tr>
	<tr>
		<td>Alamat Perusahaan</td>
		<td style="text-align: center;">:</td>
		<td><strong><?php echo $detail->alamat_vendor; ?></strong></td>
	</tr>
</table>
<p>Demikian pemberitahuan ini dibuat untuk dilaksanakan sebaik-baiknya dan penuh tanggung jawab.</p>