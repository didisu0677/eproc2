<img src="<?php echo dir_upload('setting').setting('logo_perusahaan'); ?>" width="150" style="margin-bottom: 20px;" />
<table width="100%">
	<td>&nbsp;</td>
	<td width="150">
		<div style="font-weight: bold; text-align: center;">Jakarta, <?php echo date_indo($tanggal_mulai); ?></div>
	</td>
</table>

<table style="margin-bottom: 10px" width="100%">
	<tr>
		<td width="100">Nomor</td>
		<td width="20" style="text-align: center;">:</td>
		<td><strong><?php echo $nomor; ?></strong></td>
	</tr>
	<tr>
		<td>Lampiran</td>
		<td style="text-align: center;">:</td>
		<td><strong><?php echo '-'; ?></strong></td>
	</tr>
	<tr>
		<td>Urgensi</td>
		<td style="text-align: center;">:</td>
		<td><strong><?php echo 'Sangat Segera'; ?></strong></td>
	</tr>
</table>
<p>Kepada Yth :</p>
<table style="margin-bottom: 10px" width="100%">
	<tr>
		<td><strong><?php echo $nama_rekanan; ?></strong></td>
	</tr>
	<tr>
		<td><strong><?php echo $alamat; ?></strong></td>
	</tr>

</table>
<table style="margin-bottom: 10px" width="100%">
	<tr>
		<td width="100">Perihal</td>
		<td width="20" style="text-align: center;">:</td>
		<td><strong><?php echo $perihal; ?></strong></td>
	</tr>
</table>

<?php if(isset($isi_surat) && !empty($isi_surat)) {
    echo html_entity_decode($isi_surat) ;
}?>

<br>
<br>    
<table width="100%">
	<td>&nbsp;</td>
	<td width="150">
		<div style="font-weight: bold; text-align: center;">Jakarta, <?php echo date_indo($tanggal_mulai); ?></div>
		<div style="height: 90px; text-align: center;"><?php echo 'PT. PEGADAIAN (Persero)'; ?></div>
		<div style="font-weight: bold; text-align: center;"><u><?php echo $nama_pembuat; ?></u>
		<div style="font-weight: bold; text-align: center;"><?php echo $jabatan; ?>
	</td>
</table>
