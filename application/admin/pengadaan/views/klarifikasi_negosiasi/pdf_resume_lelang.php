<img src="<?php echo dir_upload('setting').setting('logo_perusahaan'); ?>" width="150" style="margin-bottom: 20px;" />
<div style="text-align: center; font-weight: 600; font-size: 14px;">RESUME LELANG</div>
<div style="text-align: center; font-weight: 600; font-size: 14px; margin-bottom: 10px;"><?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? strtoupper($nama_pengadaan) : 'PENGADAAN '.strtoupper($nama_pengadaan); ?></div>
<div style="font-weight: bold; font-size: 12px;">PENAWARAN AWAL</div>
<table border="1" width="100%" style="margin-bottom: 10px;">
	<tr>
		<th align="center" style="background: #f7f7f7">REKANAN</th>
		<th align="center" style="background: #f7f7f7">PENAWARAN</th>
	</tr>
	<?php foreach($vendor as $v) { ?>
	<tr>
		<td><?php echo $v['nama_vendor']; ?></td>
		<td align="right"<?php if($v['nilai_total_penawaran'] == $min_awal) echo ' style="background: #dfc; color: #381; font-weight: 600;"'; ?>><?php echo custom_format($v['nilai_total_penawaran']); ?></td>
	</tr>
	<?php } ?>
</table>
<?php for($i=1; $i <= $max_sesi; $i++) { ?>
<div style="font-weight: bold; font-size: 12px;">SESI <?php echo $i; ?></div>
<table border="1" width="100%" style="margin-bottom: 10px;">
	<tr>
		<th align="center" style="background: #f7f7f7">REKANAN</th>
		<th align="center" style="background: #f7f7f7">PENAWARAN</th>
	</tr>
	<?php foreach($vendor as $v) { ?>
	<tr>
		<td><?php echo $v['nama_vendor']; ?></td>
		<td align="right"<?php if(isset($lelang[$i][$v['id_vendor']]) && $lelang[$i][$v['id_vendor']] && $lelang[$i][$v['id_vendor']] == $lelang[$i]['min']) echo '  style="background: #dfc; color: #381; font-weight: 600;"'; ?>><?php echo isset($lelang[$i][$v['id_vendor']]) && $lelang[$i][$v['id_vendor']] ? custom_format($lelang[$i][$v['id_vendor']]) : '-'; ?></td>
	</tr>
	<?php } ?>
</table>
<?php } ?>