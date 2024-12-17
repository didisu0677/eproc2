<p style="text-align: justify;">Kami informasikan bahwa spk berikut ini sudah habis masa berlakunya yaitu : </p>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th width="20" style="padding: 3px 5px;">No.</th>
			<th style="padding: 3px 5px;">Nama Perusahaan</th>
			<th style="padding: 3px 5px;">Nomor Kontrak</th>
			<th style="padding: 3px 5px;">Nama Pekerjaan</th>
			<th style="padding: 3px 5px;">Tanggal Kadaluarsa</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dokumen as $k => $v) { ?>
			<tr>
				<td style="text-align: center; padding: 3px 5px;"><?php echo ($k + 1); ?></td>
				<td style=" padding: 3px 5px;"><?php echo $v['nama_perusahaan']; ?></td>
				<td style=" padding: 3px 5px;"><?php echo $v['nomor_kontrak']; ?></td>
				<td style=" padding: 3px 5px;"><?php echo $v['nama_pengadaan']; ?></td>
				<td style=" padding: 3px 5px;"><?php echo $v['tanggal_selesai_kontrak']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<p style="text-align: justify;">Kami harap saudara bisa segera menyelesaikan kewajiban kontrak saudara</p>

