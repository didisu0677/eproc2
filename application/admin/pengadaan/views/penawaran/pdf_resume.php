<?php foreach($vendor as $k => $v) { ?>
<div style="text-align: center; font-weight: 600; font-size: 12px;">LAMPIRAN BERITA ACARA PEMBUKAAN DOKUMEN PENAWARAN</div>
<div style="text-align: center; font-weight: 600; font-size: 12px;"><?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? strtoupper($nama_pengadaan) : 'PENGADAAN '.strtoupper($nama_pengadaan); ?></div>
<div style="text-align: center; font-weight: 600; font-size: 12px; margin-bottom: 10px;">CEKLIS KELENGKAPAN DOKUMEN PERSYARATAN #<?php echo ($k + 1); ?></div>

<table width="100%" border="0" class="striped">
	<tr>
		<th style="text-align: center; background: #fff;">Tahapan Pengecekan Dokumen Penawaran</th>
		<?php foreach($v as $v2) { ?>
		<th width="80" style="text-align: center; background: #fff;"><?php echo $v2['nama_vendor']; ?></th>
		<?php } ?>
	</tr>
	<tbody>
		<?php foreach($grup_dokumen as $k_gd => $v_gd) {
			echo '<tr>';
				echo '<th colspan="'.(count($v) + 1).'" style="background: #484848; color: #fff;">'.$v_gd.'</th>';
			echo '</tr>';
			$i = 1;
			foreach($dokumen_persyaratan[$k_gd][0] as $k_dp1 => $v_dp1) {
				echo '<tr>';
					echo '<td>'.$v_dp1['deskripsi'].'</td>';
					foreach($v as $v2) {
						$ceklis = isset($ceklis_vendor[$v2['id_vendor']][$v_dp1['id']]) && $ceklis_vendor[$v2['id_vendor']][$v_dp1['id']] ? 'V' : 'X';
						if($ceklis == 'V') {
							echo '<td style="text-align: center; background: #dfc; color: #381;">'.$ceklis.'</td>';
						} else {
							echo '<td style="text-align: center; background: #fcc; color: #933;">'.$ceklis.'</td>';
						}
					}
				echo '</tr>';
				foreach($dokumen_persyaratan[$k_gd][$v_dp1['id']] as $k_dp1 => $v_dp2) {
					echo '<tr>';
						echo '<td style="padding-left: 20px;">- &nbsp;'.$v_dp2['deskripsi'].'</td>';
						foreach($v as $v2) {
							$ceklis = isset($ceklis_vendor[$v2['id_vendor']][$v_dp2['id']]) && $ceklis_vendor[$v2['id_vendor']][$v_dp2['id']] ? 'V' : 'X';
							if($ceklis == 'V') {
								echo '<td style="text-align: center; background: #dfc; color: #381;">'.$ceklis.'</td>';
							} else {
								echo '<td style="text-align: center; background: #fcc; color: #933;">'.$ceklis.'</td>';
							}
						}
					echo '</tr>';
				}
			}
			if($k_gd != 'dokumen_penawaran_harga') {
				echo '<tr>';
					echo '<th style="background: #fff; text-align: center; border-top: 2px solid #484848;">Status '.$v_gd.'</th>';
					foreach($v as $v2) {
						$status = isset($status_dokumen[$v2['id_vendor']][$k_gd]) ? $status_dokumen[$v2['id_vendor']][$k_gd] : 0;
						if($status == 1) {
							$label = isset($mandatori[$k_gd]) && $mandatori[$k_gd] ? 'Sah' : 'Lengkap';
							echo '<th style="text-align: center; background: #dfc; color: #381; border-top: 2px solid #484848;">'.$label.'</th>';
						} else {
							$label = isset($mandatori[$k_gd]) && $mandatori[$k_gd] ? 'Gugur' : 'Tdk Lengkap';
							echo '<th style="text-align: center; background: #fcc; color: #933; border-top: 2px solid #484848;">'.$label.'</th>';
						}
					}
				echo '</tr>';
			} else {
				echo '<tr>';
					echo '<td>Ketentuan Bank Garansi (minimal '.c_percent($ketentuan_bank_garansi).'%)</td>';
					foreach($v as $v2) {
						if($v2['persentase_jaminan'] >= $ketentuan_bank_garansi) {
							echo '<td style="text-align: center; background: #dfc; color: #381;">V</td>';
						} else {
							echo '<td style="text-align: center; background: #fcc; color: #933;">X</td>';
						}
					}
				echo '</tr>';
				echo '<tr>';
					echo '<td style="padding-left: 20px">- &nbsp;Nilai Jaminan Rekanan</td>';
					foreach($v as $v2) {
						echo '<td style="text-align: right;">'.custom_format($v2['nilai_jaminan_penawaran']).'</td>';
					}
				echo '</tr>';
				echo '<tr>';
					echo '<td style="padding-left: 20px">- &nbsp;Nilai Jaminan Seharusnya</td>';
					foreach($v as $v2) {
						echo '<td style="text-align: right;">'.custom_format($v2['nilai_jaminan_seharusnya']).'</td>';
					}
				echo '</tr>';
				echo '<tr>';
					echo '<td>Perbandingan Harga Penawaran Terhadap OE (dibawah '.c_percent($batas_hps_bawah).'% atau diatas '.c_percent($batas_hps_atas).'% dari HPS)</td>';
					foreach($v as $v2) {
						if($v2['persentase_total'] <= $batas_hps_atas && $v2['persentase_total'] >= $batas_hps_bawah) {
							echo '<td style="text-align: center; background: #dfc; color: #381;">V</td>';
						} else {
							echo '<td style="text-align: center; background: #fcc; color: #933;">X</td>';
						}
					}
				echo '</tr>';
				echo '<tr>';
					echo '<td style="padding-left: 20px">- &nbsp;Harga Penawaran Rekanan</td>';
					foreach($v as $v2) {
						echo '<td style="text-align: right;">'.custom_format($v2['nilai_total_penawaran']).'</td>';
					}
				echo '</tr>';
				echo '<tr>';
					echo '<td style="padding-left: 20px">- &nbsp;Harga OE Pegadaian</td>';
					foreach($v as $v2) {
						echo '<td style="text-align: right;">'.custom_format($v2['hps']).'</td>';
					}
				echo '</tr>';
				echo '<tr>';
					echo '<td style="padding-left: 20px">- &nbsp;Persentase Harga Terhadap OE</td>';
					foreach($v as $v2) {
						echo '<td style="text-align: center;">'.c_percent($v2['persentase_total']).'%</td>';
					}
				echo '</tr>';
				echo '<tr>';
					echo '<td>Persyaratan Peserta</td>';
					foreach($v as $v2) {
						$status = isset($status_dokumen[$v2['id_vendor']]['persyaratan_peserta']) ? $status_dokumen[$v2['id_vendor']]['persyaratan_peserta'] : 0;
						if($status) {
							echo '<td style="text-align: center; background: #dfc; color: #381;">V</td>';
						} else {
							echo '<td style="text-align: center; background: #fcc; color: #933;">X</td>';
						}
					}
				echo '</tr>';
				echo '<tr>';
					echo '<th style="background: #fff; text-align: center; border-top: 2px solid #484848;">Status Penawaran</th>';
					foreach($v as $v2) {
						$status = $v2['lolos_penawaran'];
						if($status == 1) {
							echo '<th style="text-align: center; background: #dfc; color: #381; border-top: 2px solid #484848;">Sah</th>';
						} else {
							echo '<th style="text-align: center; background: #fcc; color: #933; border-top: 2px solid #484848;">Gugur</th>';
						}
					}
				echo '</tr>';
			}
		}
		?>
	</tbody>
</table>
<div class="new-page"></div>
<?php } ?>
<div style="text-align: center; font-weight: 600; font-size: 12px; margin-bottom: 10px;">PERINGKAT HARGA</div>
<table width="100%" border="1">
	<thead>
		<tr>
			<th style="text-align: center;" width="10">Peringkat</th>
			<th style="text-align: center;">Nama</th>
			<th style="text-align: center;">Status</th>
			<th style="text-align: center;">Harga</th>
			<th style="text-align: center;">% BG</th>
			<th style="text-align: center;">% dari OE</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($peringkat_vendor as $k => $v) { ?>
		<tr>
			<td style="text-align: center;"><?php echo ($k + 1); ?></td>
			<td><?php echo $v['nama_vendor']; ?></td>
			<?php 
			if($v['lolos_penawaran'] == 1) {
				echo '<th style="text-align: center; background: #dfc; color: #381;">Sah</th>'; 
			} else {
				echo '<th style="text-align: center; background: #fcc; color: #933;">Gugur</th>';
			}
			?>
			<td style="text-align: right;"><?php echo custom_format($v['nilai_total_penawaran']); ?></td>
			<td style="text-align: center;"><?php echo c_percent($v['persentase_jaminan']); ?>%</td>
			<td style="text-align: center;"><?php echo c_percent($v['persentase_total']); ?>%</td>
		</tr>
		<?php } ?>
	</tbody>
</table>