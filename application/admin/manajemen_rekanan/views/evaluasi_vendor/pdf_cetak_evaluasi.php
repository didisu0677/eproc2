<div style="text-align: center; font-weight: 600; font-size: 14px; margin-bottom: 20px;">EVALUASI REKANAN/ VENDOR PT. PEGADAIAN (Persero)</div>
<table border="1" width="100%">
	<tr>
		<th colspan = "3" style="text-align: center;">Informasi Pengadaan</th>
	</tr>
	<tr>
		<td width="100">Nama User</td>
		<td width="10" style="text-align: center;">:</td>
		<td></td>
	</tr>

	<tr>
		<td>Nama rekanan</td>
		<td width="10" style="text-align: center;">:</td>
		<td><?php echo $nama_vendor; ?></td>
	</tr>

	<tr>
		<td>Nama Pekerjaan</td>
		<td style="text-align: center;">:</td>
		<td><?php echo $nama_pengadaan; ?></td>
	</tr>

	<tr>
		<td>Nilai Kontrak</td>
		<td style="text-align: center;">:</td>
		<td><?php echo number_format($nilai_kontrak); ?></td>
	</tr>
</table>

<p></p>
<br>
<br>
<table border="1" width="100%" style="margin-bottom: 10px;">
	<tr>
		<th rowspan="2" style="text-align: center;">Aspek yang di evaluasi</th>
		<th rowspan="2" style="text-align: center;">Sangat Baik</th>
		<th rowspan="2" style="text-align: center;">Baik</th>
		<th rowspan="2" style="text-align: center;">Cukup Baik</th>
		<th rowspan="2" style="text-align: center;">Kurang Baik</th>
		<th rowspan="2" style="text-align: center;">Tidak Baik</th>
	</tr>
	<tr>
	</tr>	

		<?php $i = 1; foreach($result as $t) { ?>
			<tr>
			
				<td><?php echo $t['nama_evaluasi']; ?></td>
				<td style="text-align: center;"><?php if($t['sangat_baik'] == 1) echo 'V'; ?></td>
				<td style="text-align: center;"><?php if($t['baik'] == 1) echo 'V'; ?></td>
				<td style="text-align: center;"><?php if($t['cukup_baik'] == 1) echo 'V'; ?></td>
				<td style="text-align: center;"><?php if($t['kurang_baik'] == 1) echo 'V'; ?></td>
				<td style="text-align: center;"><?php if($t['tidak_baik'] == 1) echo 'V'; ?></td>

			</tr>
		<?php $i++; } ?>


		<?php $i = 1; foreach($lain as $t) { ?>
			<tr>
			
				<td><?php echo $t['nama_evaluasi']; ?></td>
				<td style="text-align: center;"><?php if($t['sangat_baik'] == 1) echo 'V'; ?></td>
				<td style="text-align: center;"><?php if($t['baik'] == 1) echo 'V'; ?></td>
				<td style="text-align: center;"><?php if($t['cukup_baik'] == 1) echo 'V'; ?></td>
				<td style="text-align: center;"><?php if($t['kurang_baik'] == 1) echo 'V'; ?></td>
				<td style="text-align: center;"><?php if($t['tidak_baik'] == 1) echo 'V'; ?></td>

			</tr>
		<?php $i++; } ?>

</table>

<p>INDIKATOR KRITERIA</p>

<table width="100%" style="margin-bottom: 10px;">
	<?php $i = 1; foreach($kriteria as $t) { ?>
	<tr>
		<td width="10" style="text-align: center;">-</td>
		<td width="120"><?php echo $t['nama']; ?></td>
		<td width="10" style="text-align: center;">:</td> 
		<td>
		<div class="wrappable" >
			<?php echo $t['keterangan']; ?> 
		</div>
		</td>
	<?php $i++; } ?>
</table>

<br>

<table border="0" width="100%">
	<tr>
		<th colspan = "3" style="text-align: center;">Rekomendasi Untuk Pengadaan Selanjutnya </th>
	</tr>
	<tr>
		<td width="60" style="text-align: center;"><?php if($hasil_rekomendasi == 1) { echo '  ( ___X___ )' ;} else { echo  ' ( _______ )' ;}?></td>
		<td>Disarankan untuk bisa menjadi peserta pengadaan selanjutnya di Pegadaian.</td>
	</tr>	
	<tr>
		<td width="120" style="text-align: center;"><?php if($hasil_rekomendasi == 0) { echo '  (    X    )' ;} else { echo  ' ( _______ )' ;}?></td>
		<td nowrap>Tidak Disarankan untuk bisa menjadi peserta pengadaan selanjutnya di Pegadaian.</td>
	</tr>	

</table>

<br>
<br>
<br>

<table border="0" width="100%">
	<tr>
		<th colspan = "3" style="text-align: center;">Keterangan, Catatan dan Informasi Lain tentang Rekanan/Vendor:</th>
	</tr>
</table>

<div style="text-align: center; margin-bottom: 20px;"><?php echo $keterangan_lain;?></div>


<br>
<br>
<br>
<table border="1" width="100%">
	<tr>
		<th colspan = "2" style="text-align: center;">Yang mengevaluasi</th>
	</tr>
	<tr>
		<td rowspan = "1" style="text-align: left;">Nama    :</td>
		<td rowspan= "2"></td>
	</tr>
	<tr>
		<td rowspan ="2" style="text-align: left;">Jabatan :</td>	
	</tr>	
	<tr>
		<td style="text-align: center;">tanda tangan</td>
	</tr>

</table>