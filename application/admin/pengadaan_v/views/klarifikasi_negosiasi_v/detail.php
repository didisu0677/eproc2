<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb($title); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<div class="main-container">
		<?php if($stat_pengadaan == 'BATAL') {
			alert(lang('tidak_ada_kesepakatan_harga'),'danger','mb-2');
		} ?>
		<div class="card mb-2">
			<div class="card-header"><?php echo lang('_pengadaan'); ?></div>
			<div class="card-body">
				<table class="table table-bordered table-detail mb-0">
					<tr>
						<th width="200"><?php echo lang('nomor_pengadaan'); ?></th>
						<td><?php echo $nomor_pengadaan; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('nama_pengadaan'); ?></th>
						<td><?php echo $nama_pengadaan; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('identifikasi_pajak'); ?></th>
						<td><?php echo $identifikasi_pajak; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('keterangan_pengadaan'); ?></th>
						<td><?php echo $keterangan_pengadaan; ?></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header"><?php echo lang('klarifikasi_dan_negosiasi'); ?></div>
			<div class="card-body">
				<?php if($status_klarifikasi != 'CLOSE') { ?>
				<div class="card mb-2">
					<div class="card-body">
						<strong class="d-block mb-2"><?php echo lang('negosiasi'); ?></strong>
						<?php if(count($rekanan) == 1) { 
							if($penawaran_panitia && !$penawaran_vendor) {
								if($is_terakhir) {
									alert(lang('ini_adalah_penawaran_terakhir_dari_panitia'));
								}
								form_open(base_url('pengadaan_v/klarifikasi_negosiasi_v/satu_rekanan'),'post','form-satu_rekanan','data-callback="reload" data-submit="ajax" data-trigger="checkTotal"');
									col_init(2,10);
									input('hidden','id','id_klarifikasi','',$id);
									input('hidden','id','id_negosiasi','',$id_negosiasi);
									input('hidden','id','p_terakhir','',$penawaran_terakhir);
									input('hidden','id','total_penawaran');
									?>
									<div class="form-group row" id="accordion0">
										<label class="col-form-label col-sm-2" for="penawaran_terakhir"><?php echo lang('penawaran_terakhir'); ?></label>
										<div class="col-sm-10">
											<div class="card">
												<a class="card-header collapsed d-block" data-toggle="collapse" href="#collapseO">
													<?php echo custom_format($penawaran_terakhir); ?>
												</a>
												<div id="collapseO" class="collapse" data-parent="#accordion0">
													<div class="card-body">
														<?php if(count($penawaran_terakhir_detail)) { ?>
														<div class="table-responsive">
															<table class="table table-bordered table-app table-items">
																<thead>
																	<tr>
																		<th><?php echo lang('deskripsi'); ?></th>
																		<th class="text-right"><?php echo lang('jumlah'); ?></th>
																		<th><?php echo lang('satuan'); ?></th>
																		<th class="text-right"><?php echo lang('harga_satuan'); ?></th>
																		<th class="text-right"><?php echo lang('total'); ?></th>
																	</tr>
																</thead>
																<tbody>
																	<?php $total = 0; foreach($penawaran_terakhir_detail as $h) { $total += $h['total_value']; ?>
																	<tr>
																		<td><?php echo $h['short_text']; ?></td>
																		<td class="text-right"><?php echo custom_format($h['quantity']); ?></td>
																		<td><?php echo $h['unit_of_measure']; ?></td>
																		<td class="text-right"><?php echo custom_format($h['price_unit']); ?></td>
																		<td class="text-right"><?php echo custom_format($h['total_value']); ?></td>
																	</tr>
																	<?php }
																	?>
																</tbody>
																<tfoot>
																	<tr>
																		<th colspan="4"><?php echo strtoupper(lang('total_harga')); ?></th>
																		<th class="text-right"><?php echo custom_format($total); ?></th>
																	</tr>
																</tfoot>
															</table>
														</div>
														<?php }else  echo '-'; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row" id="accordion1">
										<label class="col-form-label col-sm-2" for="penawaran_panitia"><?php echo lang('penawaran_panitia'); ?></label>
										<div class="col-sm-10">
											<div class="card">
												<a class="card-header collapsed d-block" data-toggle="collapse" href="#collapseOne">
													<?php echo custom_format($penawaran_panitia); ?>
												</a>
												<div id="collapseOne" class="collapse show" data-parent="#accordion1">
													<div class="card-body">
														<div class="table-responsive">
															<table class="table table-bordered table-app table-items">
																<thead>
																	<tr>
																		<th><?php echo lang('deskripsi'); ?></th>
																		<th class="text-right"><?php echo lang('jumlah'); ?></th>
																		<th><?php echo lang('satuan'); ?></th>
																		<th class="text-right"><?php echo lang('harga_satuan'); ?></th>
																		<th class="text-right"><?php echo lang('total'); ?></th>
																	</tr>
																</thead>
																<tbody>
																	<?php $total = 0; foreach($penawaran_panitia_detail as $h) { $total += $h['total_value']; ?>
																	<tr>
																		<td><?php echo $h['short_text']; ?></td>
																		<td class="text-right"><?php echo custom_format($h['quantity']); ?></td>
																		<td><?php echo $h['unit_of_measure']; ?></td>
																		<td class="text-right"><?php echo custom_format($h['price_unit']); ?></td>
																		<td class="text-right"><?php echo custom_format($h['total_value']); ?></td>
																	</tr>
																	<?php }
																	?>
																</tbody>
																<tfoot>
																	<tr>
																		<th colspan="4"><?php echo strtoupper(lang('total_harga')); ?></th>
																		<th class="text-right"><?php echo custom_format($total); ?></th>
																	</tr>
																</tfoot>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-sm-2" for="nilai_penawaran"><?php echo lang('_penawaran'); ?></label>
										<div class="col-sm-10">
											<div class="table-responsive">
												<table class="table table-bordered table-app table-items">
													<thead>
														<tr>
															<th><?php echo lang('deskripsi'); ?></th>
															<th class="text-right"><?php echo lang('jumlah'); ?></th>
															<th><?php echo lang('satuan'); ?></th>
															<th width="170" class="text-right"><?php echo lang('harga_satuan'); ?></th>
															<th class="text-right"><?php echo lang('total'); ?></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<?php foreach($detail_hps as $h) { ?>
															<tr>
																<td><?php echo $h['short_text']; ?></td>
																<td class="text-right quantity"><?php echo custom_format($h['quantity']); ?></td>
																<td><?php echo $h['unit_of_measure']; ?></td>
																<td class="text-right">
																	<input type="text" class="form-control money price_unit text-right" name="price_unit[<?php echo $h['id']; ?>]" autocomplete="off" data-validation="required">
																</td>
																<td class="text-right total_value">0</td>
															</tr>
															<?php } ?>
														</tr>
													</tbody>
													<tfoot>
														<tr>
															<th colspan="4"><?php echo strtoupper(lang('total_harga')); ?></th>
															<th class="text-right" id="total_hps_pembulatan">0</th>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
									</div>
									<?php
									form_button(lang('kirim_penawaran'),false);
								form_close();
							} else {
								alert(lang('menunggu_penawaran_dari_panitia'));
							}
						} else {
							if($counter > 0) {
								if($current_sesi == $jumlah_sesi) {
									alert(lang('ini_adalah_sesi_negosiasi_terakhir'),'warning','mb-2');
								}
							?>
							<div class="row">
								<div class="col-sm-8">
									<table class="table table-bordered table-detail mb-2">
										<tr>
											<th><?php echo lang('peserta_negosiasi'); ?></th>
											<?php if($metode_lelang == 'Terbuka') { ?>
											<th><?php echo lang('penawaran_sesi_sebelumnya'); ?></th>
											<?php } ?>
										</tr>
										<?php foreach($vendor as $v) { ?>
										<tr>
											<td><?php echo $v->nama_vendor; ?></td>
											<?php if($metode_lelang == 'Terbuka') { ?>
											<td <?php if($v->penawaran_sebelumnya == $max_penawaran) echo ' class="bg-success text-white font-weight-bold"'; ?>><?php echo $v->penawaran_sebelumnya ? custom_format($v->penawaran_sebelumnya) : '-'; ?></td>
										<?php } ?>
										</tr>
										<?php } ?>
									</table>
									<?php if($metode_lelang == 'Tertutup') { ?>
									<table class="table table-bordered table-detail mb-2">
										<tr>
											<th><?php echo lang('penawaran_terkecil_sesi_sebelumnya'); ?></th>
											<td class="font-weight-bold"><?php echo custom_format($max_penawaran); ?></td>
										</tr>
									</table>
									<?php } ?>
								</div>
								<div class="col-sm-4">
									<div class="card mb-2">
										<div class="card-header"><?php echo lang('sesi').' '; echo $current_sesi ? $current_sesi : '-'; ?></div>
										<div class="card-body text-center p-3">
											<h1 id="counter" data-value="<?php echo $counter; ?>">00 : 00</h1>
										</div>
									</div>
								</div>
							</div>
							<?php 
							form_open(base_url('pengadaan_v/klarifikasi_negosiasi_v/lelang'),'post','form-lelang','data-callback="reload" data-submit="ajax" data-trigger="checkNilaiLelang"');
								col_init(2,10);
								input('hidden','id','id_klarifikasi','',$id);
								input('hidden','sesi','sesi','',$current_sesi);
								input('hidden','batas_penawaran','batas_penawaran','',$max_penawaran);
								if($penawaran) {
								?>
								<div class="alert alert-info"><?php echo lang('anda_sudah_melakukan_penawaran_pada_sesi_ini_dengan_total_penawaran').' : <strong>'.custom_format($penawaran).'</strong>'; ?></div>
								<?php } ?>
								<div class="form-group row">
									<label class="col-form-label col-sm-2" for="nilai_penawaran"><?php echo lang('_penawaran'); ?></label>
									<div class="col-sm-10">
										<div class="table-responsive">
											<table class="table table-bordered table-app table-items">
												<thead>
													<tr>
														<th><?php echo lang('deskripsi'); ?></th>
														<th class="text-right"><?php echo lang('jumlah'); ?></th>
														<th><?php echo lang('satuan'); ?></th>
														<th width="170" class="text-right"><?php echo lang('harga_satuan'); ?></th>
														<th class="text-right"><?php echo lang('total'); ?></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<?php foreach($detail_hps as $h) { ?>
														<tr>
															<td><?php echo $h['short_text']; ?></td>
															<td class="text-right quantity"><?php echo custom_format($h['quantity']); ?></td>
															<td><?php echo $h['unit_of_measure']; ?></td>
															<td class="text-right">
																<input type="text" class="form-control money price_unit text-right" name="price_unit[<?php echo $h['id']; ?>]" autocomplete="off" data-validation="required">
															</td>
															<td class="text-right total_value">0</td>
														</tr>
														<?php } ?>
													</tr>
												</tbody>
												<tfoot>
													<tr>
														<th colspan="4"><?php echo strtoupper(lang('total_harga')); ?></th>
														<th class="text-right" id="total_hps_pembulatan">0</th>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
								</div>
								<?php
								form_button(lang('kirim_penawaran'),false);
							form_close();
							} else {
								if($current_sesi < $jumlah_sesi) {
									alert(lang('sesi_negosiasi_belum_dibuka'));
								} else {
									alert(lang('sesi_negosiasi_sudah_selesai'));
								}
							}
						} ?>
					</div>
				</div>
				<?php } ?>
				<table class="table table-bordered table-detail mb-0">
					<tr>
						<th width="200"><?php echo lang('berita_acara'); ?></th>
						<td><?php 
						if($tanggal_berita_acara != '0000-00-00 00:00:00') {
							echo '[ <a href="'.base_url('pengadaan/klarifikasi_negosiasi/berita_acara/'.encode_id([$id,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						?></td>
					</tr>
					<tr>
						<th><?php echo lang('rks_klarifikasi'); ?></th>
						<td><?php 
						if($id_rks_klarifikasi) {
							echo '[ <a href="'.base_url('pengadaan/klarifikasi_negosiasi/cetak_rks/'.encode_id([$id_rks_klarifikasi,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						?></td>
					</tr>
					<tr>
						<th><?php echo lang('dokumen_pendukung'); ?></th>
						<td><?php 
						if($id_rks_klarifikasi) {
							echo '[ <a href="'.base_url('pengadaan_v/klarifikasi_negosiasi_v/dokumen_rks/'.encode_id([$id_rks_klarifikasi,rand()])).'" class="cInfo" aria-label="'.lang('dokumen_pendukung').'">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						?></td>
					</tr>

				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	if($('#counter').length == 1) {
		var counter = toNumber($('#counter').attr('data-value'));
		setInterval(function(){
			counter--;
			var menit = counter / 60;
			var minute = Math.floor(menit);
			var detik = counter - (minute * 60);
			var string_menit = minute < 10 ? '0' + minute : minute;
			var string_detik = detik < 10 ? '0' + detik : detik;
			$('#counter').text(string_menit+' : '+string_detik);
			if(counter <= 0) {
				reload();
			}
		},1000);
	}
});
$('.price_unit').keyup(function(){
	var j = moneyToNumber($(this).closest('tr').find('.quantity').text());
	var h = moneyToNumber($(this).val());
	var t = j * h;
	$(this).closest('tr').find('.total_value').text(customFormat(t));
	var t_bef_ppn = 0;
	$('.total_value').each(function(){
		t_bef_ppn += moneyToNumber($(this).text());
	});
	$('#total_hps_pembulatan').text(customFormat(t_bef_ppn));
	$('#total_penawaran').val(t_bef_ppn);
});
function checkTotal() {
	var p_akhir = moneyToNumber($('[href="#collapseO"]').text());
	var ttl = moneyToNumber($('#total_hps_pembulatan').text());
	var batas = p_akhir;
	if(ttl > batas) {
		cAlert.open(lang.maksimal_penawaran + ' = ' + customFormat(batas));
		return false;
	} else return true;
}
function checkNilaiLelang() {

	var p_akhir = toNumber($('#batas_penawaran').val());
	var ttl = moneyToNumber($('#total_hps_pembulatan').text());
	var batas = p_akhir;
	if(ttl > batas) {
		cAlert.open(lang.maksimal_penawaran + ' = ' + customFormat(batas));
		return false;
	} else return true;
}
</script>