<?php foreach($vendor as $k => $v) { ?>
<div style="text-align: center; font-weight: 600; font-size: 12px;">LAMPIRAN BERITA ACARA EVALUASI SURAT PENAWARAN HARGA #<?php echo ($k + 1); ?></div>
<div style="text-align: center; font-weight: 600; font-size: 12px; margin-bottom: 20px;"><?php echo substr(strtolower($nama_pengadaan), 0, 9) == 'pengadaan' ? strtoupper($nama_pengadaan) : 'PENGADAAN '.strtoupper($nama_pengadaan); ?></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<th style="text-align: center; background: #fff;" colspan="2">SCORE PENILAIAN</th>
		<?php foreach($v as $v2) { ?>
		<th width="80" style="text-align: center; background: #fff;"><?php echo $v2['nama_vendor']; ?></th>
		<?php } ?>
	</tr>
	<tbody>
		<tr>
			<th style="color: #fff; background: #484848">A. PENILAIAN HARGA</th>
			<th style="color: #fff; background: #484848; text-align: center;" width="120">Bobot : <?php echo c_percent($inisiasi->bobot_harga); ?>%</th>
			<th colspan="<?php echo count($v); ?>" style="background: #484848;">&nbsp;</th>
		</tr>
		<tr>
			<td rowspan="3">
				<strong>Rumus :</strong>
				<table>
					<tr>
						<td>Harga Terendah</td>
						<td rowspan="2"> X <?php echo c_percent($inisiasi->bobot_harga); ?> (Bobot)</td>
					</tr>
					<tr>
						<td style="border-top: 1px solid #484848;">Harga Penawaran</td>
					</tr>
				</table>
			</td>
			<td colspan="<?php echo count($v) + 1; ?>">&nbsp;</td>
		</tr>
		<tr>
			<td>Harga Penawaran Rekanan</td>
			<?php foreach($v as $v2) { ?>
			<td style="text-align: right;<?php if($harga_terendah == $v2['nilai_total_penawaran']) echo 'background: #dfc; color: #381;'; ?>"><?php echo custom_format($v2['nilai_total_penawaran']); ?></td>
			<?php } ?>
		</tr>
		<tr>
			<td>Harga Penawaran Terendah</td>
			<?php foreach($v as $v2) { ?>
			<td style="text-align: right;"><?php echo custom_format($harga_terendah); ?></td>
			<?php } ?>
		</tr>
		<tr>
			<th colspan="2" style="border-top: 2px solid #484848; text-align: center;">Total Penilaian Terhadap Harga</th>
			<?php foreach($v as $v2) { ?>
			<th style="border-top: 2px solid #484848; text-align: center;<?php if($inisiasi->bobot_harga == $v2['penilaian_harga']) echo 'background: #dfc; color: #381;'; ?>"><?php echo c_percent($v2['penilaian_harga']); ?></th>
			<?php } ?>
		</tr>
		<tr>
			<td colspan="<?php echo count($v) + 2; ?>">&nbsp;</td>
		</tr>
		<tr>
			<th style="color: #fff; background: #484848">B. PENILAIAN DOKUMEN TEKNIS</th>
			<th style="color: #fff; background: #484848; text-align: center;">Bobot : <?php echo c_percent($inisiasi->bobot_teknis); ?>%</th>
			<th colspan="<?php echo count($v); ?>" style="background: #484848;">&nbsp;</th>
		</tr>
		<?php foreach($pembobotan[0] as $p) { 
			if($p['tipe_rumus'] == 'terbanyak' || $p['tipe_rumus'] == 'terendah') {
				echo '<tr><td colspan="'.(count($v) + 2).'" style="background: #ddd;">'.$p['deskripsi'].'</td></tr>';
			} else {
				echo '<tr><td style="background: #ddd;">'.$p['deskripsi'].'</td><td style="text-align: center; background: #ddd;">Bobot : '.c_percent($p['bobot']).'</td><td colspan="'.count($v).'" style="background: #ddd;">&nbsp;</td></tr>';
			}
			if($p['tipe_rumus'] == 'acuan') {
				?>
				<tr>
					<td colspan="2">
						<table border="0" width="100%">
							<tr>
								<th style="border-bottom: 1px solid #484848;">Acuan</th>
								<th style="border-bottom: 1px solid #484848; text-align: center;">Nilai</th>
							</tr>
							<?php foreach($pembobotan[$p['id']] as $p2) { ?>
							<tr>
								<td><?php echo $p2['deskripsi']; ?></td>
								<td style="text-align: center;"><?php echo c_percent($p2['bobot']); ?></td>
							</tr>
							<?php } ?>
						</table>
					</td>
					<?php foreach($v as $v2) { ?>
					<td style="vertical-align: bottom; text-align: center; font-size: 8px;"><?php echo isset($result[$v2['id_vendor']][$p['id']]) ? $result[$v2['id_vendor']][$p['id']]['value'] : ''; ?> </td>
					<?php } ?>
				</tr>
				<tr>
					<th colspan="2" style="border-top: 1px solid #484848; text-align: center;">Total Penilaian <?php echo $p['deskripsi']; ?></th>
					<?php foreach($v as $v2) { ?>
					<th style="border-top: 1px solid #484848; vertical-align: bottom; text-align: center;<?php if(isset($result[$v2['id_vendor']][$p['id']]) && $p['bobot'] == $result[$v2['id_vendor']][$p['id']]['bobot']) echo 'background: #dfc; color: #381;'; ?>"><?php echo isset($result[$v2['id_vendor']][$p['id']]) ? c_percent($result[$v2['id_vendor']][$p['id']]['bobot']) : '0'; ?> </th>
					<?php } ?>
				</tr>
				<tr>
					<td colspan="<?php echo count($v) + 2; ?>">&nbsp;</td>
				</tr>
				<?php
			} elseif($p['tipe_rumus'] == 'range') {
				?>
				<tr>
					<td colspan="2">
						<table border="0" width="100%">
							<tr>
								<th style="border-bottom: 1px solid #484848; text-align: center;">Nilai</th>
								<th style="border-bottom: 1px solid #484848; text-align: center;">Jumlah <?php echo $p['deskripsi']; ?></th>
							</tr>
							<?php foreach($pembobotan[$p['id']] as $p2) { ?>
							<tr>
								<td style="text-align: center;"><?php echo c_percent($p2['bobot']); ?></td>
								<td style="text-align: center;"><?php echo $p2['batas_bawah'].' - '.$p2['batas_atas']; ?></td>
							</tr>
							<?php } ?>
						</table>
					</td>
					<?php foreach($v as $v2) { ?>
					<td style="vertical-align: bottom; text-align: center;"><?php echo isset($result[$v2['id_vendor']][$p['id']]) ? $result[$v2['id_vendor']][$p['id']]['value'] : ''; ?> </td>
					<?php } ?>
				</tr>
				<tr>
					<th colspan="2" style="border-top: 1px solid #484848; text-align: center;">Total Penilaian <?php echo $p['deskripsi']; ?></th>
					<?php foreach($v as $v2) { ?>
					<th style="border-top: 1px solid #484848; vertical-align: bottom; text-align: center;<?php if(isset($result[$v2['id_vendor']][$p['id']]) && $p['bobot'] == $result[$v2['id_vendor']][$p['id']]['bobot']) echo 'background: #dfc; color: #381;'; ?>"><?php echo isset($result[$v2['id_vendor']][$p['id']]) ? c_percent($result[$v2['id_vendor']][$p['id']]['bobot']) : '0'; ?> </th>
					<?php } ?>
				</tr>
				<tr>
					<td colspan="<?php echo count($v) + 2; ?>">&nbsp;</td>
				</tr>
				<?php
			} else {
				foreach($pembobotan[$p['id']] as $k1 => $p2) {
				?>
				<tr>
					<td><?php echo ($k1 + 1).'. '.$p2['deskripsi']; ?></td>
					<td style="text-align: center;">Bobot : <?php echo c_percent($p2['bobot']); ?></td>
					<td colspan="<?php echo count($v); ?>">&nbsp;</td>
				</tr>
				<tr>
					<td rowspan="3">
						<strong>Rumus :</strong>
						<table>
							<tr>
								<td><?php echo $p['tipe_rumus'] == 'terendah' ? $p2['deskripsi'].' Terendah' : $p2['deskripsi']; ?></td>
								<td rowspan="2"> X <?php echo c_percent($p2['bobot']); ?> (Bobot)</td>
							</tr>
							<tr>
								<td style="border-top: 1px solid #484848;"><?php echo $p['tipe_rumus'] == 'terendah' ? $p2['deskripsi'] : $p2['deskripsi'].' Terbanyak'; ?></td>
							</tr>
						</table>
					</td>
					<td colspan="<?php echo count($v) + 1; ?>">&nbsp;</td>
				</tr>
				<tr>
					<td><?php echo $p2['deskripsi']; ?></td>
					<?php foreach($v as $v2) { ?>
					<td style="text-align: center;<?php if(isset($result[$v2['id_vendor']][$p2['id']]) && $referensi[$p2['id']] == $result[$v2['id_vendor']][$p2['id']]['value']) echo 'background: #dfc; color: #381;'; ?>"><?php echo isset($result[$v2['id_vendor']][$p2['id']]) ? $result[$v2['id_vendor']][$p2['id']]['value'] : ''; ?></td>
					<?php } ?>
				</tr>
				<tr>
					<td><?php echo $p2['deskripsi']; echo $p['tipe_rumus'] == 'terendah' ? ' Terendah' : ' Terbanyak'; ?></td>
					<?php foreach($v as $v2) { ?>
					<td style="text-align: center;"><?php echo $referensi[$p2['id']]; ?></td>
					<?php } ?>
				</tr>
				<tr>
					<th colspan="2" style="border-top: 1px solid #484848; text-align: center;">Total <?php echo $p2['deskripsi']; ?></th>
					<?php foreach($v as $v2) { ?>
					<th style="border-top: 1px solid #484848; text-align: center;<?php if(isset($result[$v2['id_vendor']][$p2['id']]) && $p2['bobot'] == $result[$v2['id_vendor']][$p2['id']]['bobot']) echo 'background: #dfc; color: #381;'; ?>"><?php echo c_percent($result[$v2['id_vendor']][$p2['id']]['bobot']); ?></th>
					<?php } ?>
				</tr>
				<tr>
					<td colspan="<?php echo count($v) + 2; ?>">&nbsp;</td>
				</tr>
				<?php
				}
			}
		} ?>
		<tr>
			<th colspan="2" style="border-top: 2px solid #484848; text-align: right;">Total Penilaian Teknis (Skala : 100)</th>
			<?php foreach($v as $v2) { ?>
			<th style="border-top: 2px solid #484848; text-align: center;"><?php echo c_percent($v2['total_teknis']); ?></th>
			<?php } ?>
		</tr>
		<tr>
			<th colspan="2" style="border-top: 2px solid #484848; text-align: right;">Total Penilaian Teknis (Skala : <?php echo c_percent($inisiasi->bobot_teknis); ?>)</th>
			<?php foreach($v as $v2) { ?>
			<th style="border-top: 2px solid #484848; text-align: center;"><?php echo c_percent($v2['penilaian_teknis']); ?></th>
			<?php } ?>
		</tr>
		<tr>
			<td colspan="<?php echo count($v) + 2; ?>">&nbsp;</td>
		</tr>
		<tr>
			<th colspan="2" style="background: #484848; color: #fff; text-align: center;">Total Penilaian Keseluruhan</th>
			<?php foreach($v as $v2) { ?>
			<th style="background: #484848; color: #fff; text-align: center;"><?php echo c_percent($v2['total_penilaian']); ?></th>
			<?php } ?>
		</tr>
	</tbody>
</table>
<div class="new-page"></div>
<?php } ?>
<div style="text-align: center; font-weight: 600; font-size: 12px; margin-bottom: 20px;">PERINGKAT BERDASARKAN NILAI</div>
<table width="100%" border="1" style="margin-bottom: 20px;">
	<thead>
		<tr>
			<th style="text-align: center;">Peringkat</th>
			<th style="text-align: center;">Nama</th>
			<th style="text-align: center;">Harga</th>
			<th style="text-align: center;">Nilai Akhir</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($vendor_rank as $v) { ?>
		<tr>
			<td style="text-align: center;"><?php echo $v['rank_evaluasi'];?></td>
			<td><?php echo $v['nama_vendor'];?></td>
			<td style="text-align: right;"><?php echo custom_format($v['nilai_total_penawaran']);?></td>
			<td style="text-align: center;"><?php echo c_percent($v['total_penilaian']);?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<!-- <strong>Kesimpulan:</strong>
<p>Dari Jumlah nilai akhir tersebut, maka yang diusulkan sebagai Pemenang:</p>
<table border="0">
	<tr>
		<td>Nama Rekanan</td>
		<td>&nbsp;</td>
		<td style="border: 1px solid #484848;" width="150"><?php echo $vendor_rank[0]['nama_vendor']; ?></td>
	</tr>
	<tr>
		<td>Nilai Akhir</td>
		<td>&nbsp;</td>
		<td style="border: 1px solid #484848; text-align: center;"><?php echo c_percent($vendor_rank[0]['total_penilaian']); ?></td>
	</tr>
	<tr>
		<td>Harga Penawaran</td>
		<td>&nbsp;</td>
		<td style="border: 1px solid #484848; text-align: right;"><?php echo custom_format($vendor_rank[0]['nilai_total_penawaran']); ?></td>
	</tr>
	<tr>
		<td>HPS</td>
		<td>&nbsp;</td>
		<td style="border: 1px solid #484848; text-align: right;"><?php echo custom_format($vendor_rank[0]['hps']); ?></td>
	</tr>
	<tr>
		<td>% Penawaran Terhadap HPS</td>
		<td>&nbsp;</td>
		<td style="border: 1px solid #484848; text-align: center;"><?php echo c_percent($vendor_rank[0]['persentase_total']); ?>%</td>
	</tr>
</table> -->