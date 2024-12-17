<div style="text-align: center; font-weight: 600; font-size: 12px;">DAFTAR PEMASUKAN DOKUMEN PENAWARAN HARGA</div>
<div style="text-align: center; font-weight: 600; font-size: 12px;"><?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? strtoupper($nama_pengadaan) : 'PENGADAAN '.strtoupper($nama_pengadaan); ?></div>
<div style="text-align: center; font-weight: 600; font-size: 12px; margin-bottom: 10px;">PT. PEGADAIAN (Persero)</div>
<table width="100%" border="1" style="margin-bottom: 20px;">
	<thead>
		<tr>
			<th width="20" style="text-align: center;">NO</th>
			<th style="text-align: center;">NAMA</th>
			<th style="text-align: center;">NAMA PERUSAHAAN</th>
			<th style="text-align: center;">ALAMAT EMAIL</th>
			<th style="text-align: center;">TANDA TANGAN</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($vendor as $k => $v) { ?>
		<tr>
			<td rowspan="2" style="text-align: center;"><?php echo ($k+1); ?></td>
			<td>
				<table>
					<tr>
						<td width="30">Nama</td>
						<td width="10" style="text-align: center;">:</td>
						<td><strong><?php echo $v['nama_cp']; ?></strong></td>
					</tr>
				</table>
			</td>
			<td rowspan="2"><strong><?php echo $v['nama']; ?></strong></td>
			<td rowspan="2"><strong><?php echo $v['email']; ?></strong></td>
			<td rowspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td width="30">No. HP</td>
						<td width="10" style="text-align: center;">:</td>
						<td><strong><?php echo $v['hp_cp']; ?></strong></td>
					</tr>
				</table>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<table width="100%">
	<td>&nbsp;</td>
	<td width="30%">
		DIVISI PROCUREMENT DAN PENGELOLAAN ASET TETAP
		<div style="height: 75px;">&nbsp;</div>
		<div style="font-weight: 600"><?php echo strtoupper($cur_kadiv_ppat); ?></div>
		KEPALA DIVISI
	</td>
</table>