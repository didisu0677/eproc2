<div style="text-align: center; font-size: 12px; font-weight: bold; margin-bottom: 20px;">HPS USULAN</div>
<table width="100%" style="margin-bottom: 15px;">
	<tr>
		<th style="text-align: left;" width="170">Nomor Pengajuan</th>
		<th style="text-align: center;" width="20">:</th>
		<td><?php echo $nomor_pengajuan; ?></td>
	</tr>
	<tr>
		<th style="text-align: left;">Nama Pengadaan</th>
		<th style="text-align: center;">:</th>
		<td><?php echo $nama_pengadaan; ?></td>
	</tr>
	<tr>
		<th style="text-align: left;">Mata Anggaran</th>
		<th style="text-align: center;">:</th>
		<td><?php echo $mata_anggaran; ?></td>
	</tr>
	<tr>
		<th style="text-align: left;">Nomor Pengajuan Pembelian (Dari SAP)</th>
		<th style="text-align: center;">:</th>
		<td><?php echo $purchase_req_item; ?></td>
	</tr>
</table>
<table width="100%" border="1" style="margin-bottom: 10px;">
	<thead>
		<tr>
			<th width="10">No.</th>
			<th width="60">Kode Material</th>
			<th>Deskripsi</th>
			<th width="40" style="text-align: right;">Jumlah</th>
			<th width="40">Satuan</th>
			<th width="70" style="text-align: right;">Harga Satuan</th>
			<th width="70" style="text-align: right;">Total</th>
		</tr>
	</thead>
	<tbody>
		<?php $total = 0; foreach($detail as $k => $d) { $total += $d['total_value']; ?>
		<tr>
			<td><?php echo $k + 1; ?></td>
			<td><?php echo $d['material_number']; ?></td>
			<td><?php echo $d['short_text']; ?></td>
			<td style="text-align: right;"><?php echo custom_format($d['quantity']); ?></td>
			<td><?php echo $d['unit_of_measure']; ?></td>
			<td style="text-align: right;"><?php echo custom_format($d['price_unit']); ?></td>
			<td style="text-align: right;"><?php echo custom_format($d['total_value']); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<table width="100%" border="1">
	<tr>
		<th style="text-align: left; border-right: 0 none; border-bottom: 0 none;">TOTAL</th>
		<th width="70" style="text-align: right; border-left: 0px none; border-bottom: 0 none; font-size: 16px;"><?php echo custom_format($total); ?></th>
	</tr>
	<tr>
		<td colspan="2" style="border-top: 0 none; text-align: right; font-style: italic; font-weight: bold;"><?php echo terbilang($total); ?></td>
	</tr>
</table>