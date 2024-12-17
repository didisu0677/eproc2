<div style="text-align: center; font-weight: 600; font-size: 14px; margin-bottom: 20px;">LAPORAN HASIL PENGADAAN</div>
<table border="1" width="100%" style="margin-bottom: 8px; font-size: 9px">
<tr>
<th rowspan="3" >Nomor</th>
<th rowspan="3" class="text-center align-middle">Nama Pekerjaan</th>
<th rowspan="3" class="text-center align-middle">User</th>
<th rowspan="3" class="text-center align-middle">Nilai HPS</th>
<th rowspan="3" class="text-center align-middle">Metode Pengadaan</th>
<th rowspan="3" class="text-center align-middle">Panitia Pengadaan</th>
<th rowspan="3" class="text-center align-middle">Rekanan Pengadaan</th> 
<th colspan="2" class="text-center align-middle">SPK</th>
<th rowspan="3" class="text-center align-middle">Nilai Kontrak</th>
<th rowspan="3" class="text-center align-middle">Keterangan</th>  
</tr>
<tr>
<th rowspan="2" class="text-center align-middle">Nomor</th>
<th rowspan="2" class="text-center align-middle">Tanggal</th>
</tr>    
<tr>
</tr>
<?php $i = 1; foreach($result as $t) { ?>
	<tr>
		<td style="text-align: center;"><?php echo $i; ?></td>
		<td><?php echo $t['nama_pengadaan']; ?></td>
		<td><?php echo $t['divisi']; ?></td>
		<td><?php echo custom_format($t['hps']); ?></td>
		<td><?php echo $t['metode_pengadaan']; ?></td>
		<td><?php echo $t['panitia'] ;?></td>
		<td><?php echo $t['nama_vendor']; ?></td>
		<td><?php echo $t['nomor_spk']; ?></td>
		<td><?php echo $t['tanggal_spk']; ?></td>
		<td><?php echo custom_format($t['nilai_kontrak']); ?></td>
		<td><?php echo $t['keterangan_pengadaan']; ?></td>
	</tr>
<?php $i++; } ?>
</table>