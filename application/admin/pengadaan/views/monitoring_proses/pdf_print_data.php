<div style="text-align: center; font-weight: 600; font-size: 14px; margin-bottom: 20px;">MONITORING PROSES  PENGADAAN</div>
<table border="1" width="100%" style="margin-bottom: 8px;font-size: 7px">
<tr>';
<th rowspan="3" class="text-center align-middle"><?php echo lang('no') ; ?></th>
<th rowspan="3" class="text-center align-middle"><?php echo lang('nama_pengadaan') ; ?></th>
<th rowspan="3" class="text-center align-middle"><?php echo lang('user'); ?> </th>
<th rowspan="3" class="text-center align-middle"><?php echo lang('metode_pengadaan'); ?></th>
<th rowspan="3" class="text-center align-middle"><?php echo lang('tanggal_pengadaan'); ?></th>
<th rowspan="3" class="text-center align-middle"><?php echo lang('sla'); ?></th>
<th rowspan="3" class="text-center align-middle"><?php echo lang('panitia'); ?></th> 
<th colspan="9" class="text-center align-middle"><?php echo lang('tahapan_proses'); ?></th>    
<th rowspan="3" class="text-center align-middle"><?php echo lang('keterangan'); ?></th> 
</tr>
<tr>
<th rowspan="2" class="text-center align-middle"><?php echo lang('pengumuman'); ?></th>
<th rowspan="2" class="text-center align-middle"><?php echo lang('_aanwijzing'); ?></th>
<th rowspan="2" class="text-center align-middle"><?php echo lang('pemasukan'); ?></th>
<th rowspan="2" class="text-center align-middle"><?php echo lang('evaluasi'); ?></th>
<th rowspan="2" class="text-center align-middle"><?php echo lang('kunjungan_lapangan'); ?></th>
<th rowspan="2" class="text-center align-middle"><?php echo lang('klarifikasi_negosiasi'); ?></th>
<th rowspan="2" class="text-center align-middle"><?php echo lang('penetapan'); ?></th>
<th rowspan="2" class="text-center align-middle"><?php echo lang('penunjukan'); ?></th>
<th rowspan="2" class="text-center align-middle"><?php echo lang('spk'); ?></th>
</tr>	        
<tr>
</tr>
<?php $i = 1; foreach($result as $t) { ?>

	<tr>
		<td style="text-align: center;"><?php echo $i; ?></td>
		<td><?php echo $t['nama_pengadaan']; ?></td>
		<td><?php echo $t['divisi']; ?></td>
		<td><?php echo $t['metode_pengadaan']; ?></td>
		<td><?php echo c_date($t['tanggal_pengadaan']); ?></td>
		<td></td>
		<td><?php echo $t['panitia']; ?></td>

			<?php
				for($i=1;$i <= 9;$i++) {
				if($i <= $t['urutan']) {
					$warna  = "#E6E6FA";
				}else{
					$warna  = "";					
				}	

				if($i <= 7 && $t['status_penetapan'] == 1 ) {
						$warna  = "#E6E6FA";	
					}	

					if($i <= 8 && $t['nomor_penunjukan'] != "" ) {
						$warna  = "#E6E6FA";	
					}	

					if($i <= 9 && $t['nomor_spk'] != "" ) {
						$warna  = "#E6E6FA";	
					}	
					
				?>	
				<td bgcolor=<?php echo $warna; ?> ></td>				
				<?php 				
			}
			?>

			<td><?php echo $t['keterangan_pengadaan']; ?></td>
	</tr>
<?php $i++; } ?>
</table>