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
			alert(lang('klarifikasi_dan_negosiasi_tidak_dilanjutkan_ke_proses_selanjutnya'),'danger','mb-2');
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
				<table class="table table-bordered table-detail mb-2">
					<tr>
						<th width="200"><?php echo lang('rks_klarifikasi'); ?></th>
						<td><?php 
						if($id_rks_klarifikasi) {
							echo '[ <a href="'.base_url('pengadaan/klarifikasi_negosiasi/cetak_rks/'.encode_id([$id_rks_klarifikasi,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						if($menu_access['access_additional'] && $stat_pengadaan == 'KLARIFIKASI' && $open_klarifikasi) {
							echo ' <a href="javascript:;" id="create-rks" class="btn btn-info btn-sm ml-3">';
							echo $id_rks_klarifikasi ? lang('ubah_rks') : lang('buat_rks');
							echo '</a>';
						}
						?></td>
					</tr>
					<tr>
						<th><?php echo lang('dokumen_pendukung'); ?></th>
						<td><?php 
						if($id_rks_klarifikasi) {
							echo '[ <a href="'.base_url('pengadaan/klarifikasi_negosiasi/dokumen_rks/'.encode_id([$id_rks_klarifikasi,rand()])).'" class="cInfo" aria-label="'.lang('dokumen_pendukung').'">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						?></td>
					</tr>
				</table>
				<table class="table table-detail table-bordered mb-2">
					<tr>
						<th colspan="2"><?php echo lang('rekanan_yang_mengikuti'); ?></th>
					</tr>
					<tr>
						<th width="30"><?php echo lang('no'); ?></th>
						<th><?php echo lang('rekanan'); ?></th>
					</tr>
					<?php $i=1; foreach($rekanan as $a) { ?>
					<tr>
						<td class="text-center"><?php echo $i; ?></td>
						<td><a href="<?php echo base_url('pengadaan/klarifikasi_negosiasi/detail_vendor/'.encode_id([$a['id_vendor'],rand()]));; ?>" class="cInfo"><?php echo $a['nama_vendor']; ?></a>
					</tr>
					<?php $i++; } ?>
				</table>
				<?php if($menu_access['access_additional'] && $stat_pengadaan == 'KLARIFIKASI' && $status_klarifikasi != 'CLOSE') { ?>
				<div class="card mb-2">
					<div class="card-body">
						<strong class="d-block mb-2"><?php echo lang('negosiasi'); ?></strong>
						<?php if(count($rekanan) == 1) { 
							form_open(base_url('pengadaan/klarifikasi_negosiasi/satu_rekanan'),'post','form-satu_rekanan','data-callback="reload" data-submit="ajax" data-trigger="checkTotal"');
								col_init(2,10);
								input('hidden','id','id_klarifikasi','',$id);
								?>
								<div class="form-group row" id="accordion1">
									<label class="col-form-label col-sm-2" for="nilai_penawaran"><?php echo lang('nilai_penawaran'); ?></label>
									<div class="col-sm-10">
										<div class="card">
											<a class="card-header collapsed d-block" data-toggle="collapse" href="#collapseOne">
												<?php echo custom_format($hps['total_hps_pembulatan']); ?>
											</a>
											<div id="collapseOne" class="collapse" data-parent="#accordion1">
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
																<?php foreach($detail_hps as $h) { ?>
																<tr>
																	<td><?php echo $h['short_text']; ?></td>
																	<td class="text-right"><?php echo custom_format($h['quantity']); ?></td>
																	<td><?php echo $h['unit_of_measure']; ?></td>
																	<td class="text-right"><?php echo custom_format($h['price_unit']); ?></td>
																	<td class="text-right"><?php echo custom_format($h['total_value']); ?></td>
																</tr>
																<?php } ?>
															</tbody>
															<tfoot>
																<tr>
																	<th colspan="4"><?php echo strtoupper(lang('total_harga')); ?></th>
																	<th class="text-right"><?php echo custom_format($hps['total_hps_pembulatan']); ?></th>
																</tr>
															</tfoot>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php
								input('text',lang('penawaran_awal'),'penawaran_awal','',custom_format($rekanan[0]['nilai_total_penawaran']),'disabled');
								?>
								<div class="form-group row" id="accordion2">
									<label class="col-form-label col-sm-2" for="nilai_penawaran"><?php echo lang('penawaran_terakhir'); ?></label>
									<div class="col-sm-10">
										<div class="card">
											<a class="card-header collapsed d-block" data-toggle="collapse" href="#collapseTwo">
												<?php echo custom_format($penawaran_terakhir); ?>
											</a>
											<div id="collapseTwo" class="collapse show" data-parent="#accordion2">
												<div class="card-body">
													<div class="table-responsive">
														<?php if(count($penawaran_vendor) == count($detail_hps)) { ?>
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
																<?php $total = 0; foreach($penawaran_vendor as $h) { $total += $h['total_value']; ?>
																<tr>
																	<td><?php echo $h['short_text']; ?></td>
																	<td class="text-right"><?php echo custom_format($h['quantity']); ?></td>
																	<td><?php echo $h['unit_of_measure']; ?></td>
																	<td class="text-right"><?php echo custom_format($h['price_unit']); ?></td>
																	<td class="text-right"><?php echo custom_format($h['total_value']); ?></td>
																</tr>
																<?php } ?>
															</tbody>
															<tfoot>
																<tr>
																	<th colspan="4"><?php echo strtoupper(lang('total_harga')); ?></th>
																	<th class="text-right"><?php echo custom_format($total); ?></th>
																</tr>
															</tfoot>
														</table>
														<?php } else echo '-'; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-2" for="nilai_penawaran"><?php echo lang('riwayat_negosiasi'); ?></label>
									<div class="col-sm-10">
										<a href="<?php echo base_url('pengadaan/klarifikasi_negosiasi/history_negosiasi/'.encode_id($id)); ?>" class="btn btn-info btn-icon-only cInfo"><i class="fa-history"></i> <?php echo lang('lihat'); ?></a>
									</div>
								</div>
								<?php if(!$has_penawaran) { ?>
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
														<th class="text-right" id="total_sebelum_ppn">0</th>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-10 offset-sm-2">
										<div class="custom-checkbox custom-control custom-control-inline">
											<input class="custom-control-input" type="checkbox" id="penawaran_akhir" name="penawaran_akhir">
											<label class="custom-control-label" for="penawaran_akhir"><?php echo lang('tandai_sebagai_penawaran_terakhir'); ?></label>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-10 offset-sm-2">
										<button type="submit" class="btn btn-success"><?php echo lang('kirim_penawaran'); ?></button>
										<?php if(!$first_penawaran) { ?>
										<button type="button" class="btn btn-secondary" id="tutup_negosiasi"><?php echo lang('tutup_negosiasi'); ?></button>
										<?php } ?>
									</div>
								</div>
								<?php } else { ?>
									<div class="alert alert-info"><?php echo lang('menunggu_penawaran_dari_rekanan'); ?></div>
								<?php }
							form_close();
						} else {
							if($jumlah_sesi) {
								?>
								<div class="row">
									<?php if($counter <= 0) { ?>
									<div class="col-12">
									<?php } else { ?>
									<div class="col-12 col-sm-8">
									<?php } ?>
										<table class="table table-bordered table-detail mb-2">
											<tr>
												<th width="188"><?php echo lang('jumlah_sesi'); ?></th>
												<td><?php echo $jumlah_sesi; ?></td>
											</tr>
											<tr>
												<th><?php echo lang('durasi_per_sesi'); ?></th>
												<td><?php echo $lama_sesi.' '.lang('menit'); ?></td>
											</tr>
											<tr>
												<th><?php echo lang('metode_lelang'); ?></th>
												<td><?php echo $metode_lelang; ?></td>
											</tr>
											<tr>
												<th><?php echo lang('monitoring_penawaran'); ?></th>
												<td>[ <a href="<?php echo base_url('pengadaan/klarifikasi_negosiasi/monitoring_penawaran/'.encode_id($id)); ?>" class="cInfo"><?php echo lang('lihat_detil'); ?></a> ]</td>
											</tr>
											<?php if($counter <= 0) { ?>
											<tr>
												<th><?php echo lang('sesi_selanjutnya'); ?></th>
												<td><?php echo ($current_sesi + 1) > $jumlah_sesi ? '-' : lang('sesi').' '.($current_sesi + 1) ; ?></td>
											</tr>
											<tr>
												<th>&nbsp;</th>
												<td>
													<?php if($current_sesi < $jumlah_sesi && $lanjut_sesi) { ?>
													<button type="button" class="btn btn-sm btn-info" id="btn-mulai-sesi" data-id="<?php echo $id; ?>"><?php echo lang('mulai_sesi').' '.($current_sesi + 1); ?></button>
													<?php } if($current_sesi > 0) { ?>
													<button type="button" class="btn btn-sm btn-secondary" id="btn-tutup-lelang" data-id="<?php echo $id; ?>"><?php echo lang('tutup_lelang_pengadaan'); ?></button>
													<?php } ?>
												</td>
											</tr>
											<?php } else { ?>
											<tr>
												<th><?php echo lang('sesi'); ?></th>
												<td><?php echo $current_sesi; ?></td>
											</tr>
											<?php } ?>
										</table>
									</div>
									<?php if($counter > 0) { ?>
									<div class="col-sm-4 col-12">
										<div class="card">
											<div class="card-header"><?php echo lang('sisa_waktu'); ?></div>
											<div class="card-body text-center p-4">
												<h1 id="counter" id="counter" data-value="<?php echo $counter; ?>">00 : 00</h1>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
								<?php
							} else {
								form_open(base_url('pengadaan/klarifikasi_negosiasi/init_lelang'),'post','form-init-lelang','data-callback="reload" data-submit="ajax"');
									col_init(3,9);
									input('hidden','id','id_klarifikasi','',$id);
									input('text',lang('jumlah_sesi'),'jumlah_sesi','required|number|min:1');
									input('text',lang('durasi_per_sesi'),'lama_sesi','required|number|min:5','','data-append="'.lang('menit').'"');
									select2(lang('metode_lelang'),'metode_lelang','required|infinity',['Terbuka'=>lang('terbuka'),'Tertutup'=>lang('tertutup')],'_key');
									form_button(lang('simpan'),false);
								form_close();
							}
						} ?>
					</div>
				</div>
				<?php } if($status_klarifikasi == 'CLOSE') {
					if(count($rekanan) == 1) {
						?>
						<table class="table table-bordered table-detail mb-2">
							<tr>
								<th width="200"><?php echo lang('_hps'); ?></th>
								<td><?php echo custom_format($hps['total_hps_pembulatan']); ?></td>
							</tr>
							<tr>
								<th><?php echo lang('penawaran_awal'); ?></th>
								<td><?php echo custom_format($rekanan[0]['nilai_total_penawaran']); ?></td>
							</tr>
							<tr>
								<th><?php echo lang('penawaran_terakhir'); ?></th>
								<td>
									<strong class="mr-3"><?php echo custom_format($penawaran_terakhir); ?></strong>
									<a href="<?php echo base_url('pengadaan/klarifikasi_negosiasi/history_negosiasi/'.encode_id($id)); ?>" class="btn btn-info btn-icon-only btn-sm cInfo"><?php echo lang('riwayat_negosiasi'); ?></a>
								</td>
							</tr>
						</table>
						<?php
					} else {
						?>
						<table class="table table-bordered table-detail mb-2">
							<tr>
								<th width="200"><?php echo lang('_hps'); ?></th>
								<td><?php echo custom_format($hps['total_hps_pembulatan']); ?></td>
							</tr>
							<tr>
								<th><?php echo lang('rekanan_dengan_penawaran_terendah'); ?></th>
								<td><?php echo $vendor_terendah->nama_vendor; ?></td>
							</tr>
							<tr>
								<th><?php echo lang('penawaran_terakhir'); ?></th>
								<td><?php echo custom_format($vendor_terendah->penawaran_terakhir); ?></td>
							</tr>
							<tr>
								<th><?php echo lang('resume_lelang'); ?></th>
								<td>[ <a href="<?php echo base_url('pengadaan/klarifikasi_negosiasi/resume_lelang/'.encode_id($id)); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
							</tr>
						</table>
						<?php
					}
				} ?>
				<table class="table table-bordered table-detail mb-0">
					<tr>
						<th width="200"><?php echo lang('berita_acara'); ?></th>
						<td><?php 
						if($tanggal_berita_acara != '0000-00-00 00:00:00') {
							echo '[ <a href="'.base_url('pengadaan/klarifikasi_negosiasi/berita_acara/'.encode_id([$id,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						if($menu_access['access_additional'] && $stat_pengadaan == 'KLARIFIKASI' && $status_klarifikasi == 'CLOSE') {
							echo ' <a href="javascript:;" id="create-berita-acara" class="btn btn-info btn-sm ml-3">';
							echo $tanggal_berita_acara != '0000-00-00 00:00:00' ? lang('ubah_berita_acara') : lang('buat_berita_acara');
							echo '</a>';
						}
						?></td>
					</tr>
					<?php if(menu()['access_additional']) { ?>
					<tr>
						<th><?php echo lang('transkrip_obrolan'); ?></th>
						<td><?php 
							echo '[ <a href="javascript:;" id="btn-transkrip" data-id_chat="'.$id_chat.'" data-key="'.csrf_token(false,'return').'">'.lang('lihat_detil').'</a> ]';
						?></td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
		<?php if($status_klarifikasi == 'CLOSE' && $menu_access['access_additional'] && $stat_pengadaan == 'KLARIFIKASI') { ?>
		<div class="card">
			<div class="card-header"><?php echo lang('penetapan_pemenang'); ?></div>
			<div class="card-body">
				<?php if($id_rks_klarifikasi && $tanggal_berita_acara != '0000-00-00 00:00:00') {
					if($metode_negosiasi == 'Negosiasi Semua Rekanan') { 
						echo '<button type="button" class="btn btn-success" data-id="'.$id.'" id="btn-proses">'.lang('proses').'</button>';
						echo '<button type="button" class="btn btn-danger" data-id="'.$id.'" id="btn-batal">'.lang('batalkan_pengadaan').'</button>';
					} else { 
						if($penawaran_terakhir <= $hps) {
							echo '<button type="button" class="btn btn-success" data-id="'.$id.'" id="btn-proses">'.lang('proses').'</button>';
						}
						if($sisa_vendor) {
							if($tipe_pengadaan == 'Lelang') {
								echo '<button type="button" class="btn btn-warning" data-id="'.$id.'" id="btn-peninjauan">'.lang('tidak_layak_peninjauan_kandidat_selanjutnya').'</button>';
							} else {
								echo '<button type="button" class="btn btn-warning" data-id="'.$id.'" id="btn-next-negosiasi">'.lang('tidak_layak_negosiasi_kandidat_selanjutnya').'</button>';
							}
						} else {
							echo '<button type="button" class="btn btn-danger" data-id="'.$id.'" id="btn-batal">'.lang('tidak_layak_batalkan_pengadaan').'</button>';
						}
					}
				} else alert(lang('form_akan_muncul_jika_rks_klarifikasi_dan_berita_acara_sudah_dibuat')); ?>
			</div>
		</div>
		<?php } if($last_pos == 'KLARIFIKASI') { ?>
		<div class="card">
			<div class="card-header"><?php echo lang('inisiasi_ulang'); ?></div>
			<div class="card-body">
				<?php if($inisiasi_ulang) { ?>
				<div class="alert alert-warning">
					<?php echo lang('pengadaan_telah_dibatalkan_dan_diinisiasi_ulang'); ?>
				</div>
				<?php } else { ?>
				<div class="alert alert-danger">
					<?php echo lang('pengadaan_telah_dibatalkan'); ?>
				</div>
				<button type="button" class="btn btn-secondary" id="btn-reinisiasi" data-pengadaan="<?php echo $nomor_pengadaan; ?>"><i class="fa-sync"></i><?php echo lang('inisiasi_ulang'); ?></button>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<?php
modal_open('modal-berita-acara',lang('berita_acara'),'modal-xl');
	modal_body();
		form_open(base_url('pengadaan/klarifikasi_negosiasi/save_berita_acara'),'post','form-berita-acara','data-callback="reload"');
			col_init(3,9);
			input('hidden','id','_id','',$id);
			input('text',lang('nomor_berita_acara'),'nomor_berita_acara','',$nomor_berita_acara,'readonly data-readonly="true" placeholder="'.lang('otomatis_saat_disimpan').'"');
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required"><?php echo lang('tanggal'); ?></label>
				<div class="col-md-3">
					<input type="text" name="tanggal_berita_acara" class="form-control dtp" placeholder="<?php echo lang('tanggal'); ?>" autocomplete="off" value="<?php echo c_date($tanggal_berita_acara); ?>" data-validation="required">
				</div>
				<div class="col-md-6">
					<select id="zona_waktu" name="zona_waktu" class="form-control select2 infinity">
						<option value="WIB"<?php if($zona_waktu == 'WIB') echo ' selected'; ?>>WIB</option>
						<option value="WITA"<?php if($zona_waktu == 'WITA') echo ' selected'; ?>>WITA</option>
						<option value="WIT"<?php if($zona_waktu == 'WIT') echo ' selected'; ?>>WIT</option>
					</select>
				</div>
			</div>
			<?php
			textarea(lang('lokasi'),'lokasi_berita_acara','required',$lokasi_berita_acara);
			form_button(lang('simpan'),lang('batal'));
		form_close();
modal_close();
modal_open('modal-rks',lang('_rks'),'modal-xl');
	modal_body('wizard');
		form_open(base_url('pengadaan/klarifikasi_negosiasi/save_rks'),'post','form-rks','data-callback="reload"'); ?>
		<ul class="nav nav-tabs" id="tab-wizard" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="step1-tab" data-toggle="tab" href="#step1" role="tab" aria-controls="step1" aria-selected="true"><?php echo lang('informasi_rks'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="step2-tab" data-toggle="tab" href="#step2" role="tab" aria-controls="step2" aria-selected="off"><?php echo lang('syarat_ketentuan'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="step3-tab" data-toggle="tab" href="#step3" role="tab" aria-controls="step3" aria-selected="off"><?php echo lang('tor'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="step4-tab" data-toggle="tab" href="#step4" role="tab" aria-controls="step4" aria-selected="off"><?php echo lang('dokumen_pendukung'); ?></a>
			</li>
		</ul>
		<div class="tab-content" id="tab-wizardContent">
			<div class="tab-pane show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
				<?php
				col_init(3,9);
				input('hidden','id','id','',$rks['id']);
				input('hidden','nomor_pengajuan','nomor_pengajuan','',$rks['nomor_pengajuan']);
				input('text',lang('nomor_pengadaan'),'nomor_pengadaan','',$nomor_pengadaan,'data-readonly readonly');
				input('text',lang('nama_pemberi_tugas'),'pemberi_tugas','',$rks['pemberi_tugas'],'data-readonly readonly');
				input('text',lang('nama_pengadaan'),'nama_pengadaan','',$rks['nama_pengadaan'],'data-readonly readonly');
				input('text',lang('mata_anggaran'),'mata_anggaran','',$rks['mata_anggaran'],'data-readonly readonly');
				input('money',lang('usulan_hps'),'usulan_hps','',custom_format($rks['usulan_hps']),'data-readonlye readonly');
				input('money',lang('hps_panitia'),'hps_panitia','',custom_format($rks['hps_panitia']),'data-readonlye readonly');
				input('text',lang('nama_divisi'),'nama_divisi','',$rks['nama_divisi'],'data-readonly readonly');
				input('text',lang('metode_pengadaan'),'metode_pengadaan','',$rks['metode_pengadaan'],'data-readonly readonly');
				input('text',lang('jenis_pengadaan'),'jenis_pengadaan','',$rks['jenis_pengadaan'],'data-readonly readonly');
				input('text',lang('nomor_rks'),'nomor_rks','required|unique',$rks['nomor_rks'],'disabled placeholder="'.lang('otomatis_saat_disimpan').'"');
				input('date',lang('tanggal_rks'),'tanggal_rks','required',$rks['tanggal_rks']);
				?>
				<div class="form-group row hidden">
					<label class="col-form-label col-sm-3 required"><?php echo lang('batas_hps'); ?></label>
					<div class="col-md-3 col-6">
						<input type="text" id="batas_hps_bawah" name="batas_hps_bawah" autocomplete="off" class="form-control percent" placeholder="<?php echo lang('batas_minimal'); ?>" data-validation="required" data-append="%" value="<?php echo c_percent($rks['batas_hps_bawah']); ?>">
					</div>
					<div class="col-md-3 col-6">
						<input type="text" id="batas_hps_atas" name="batas_hps_atas" autocomplete="off" class="form-control percent" placeholder="<?php echo lang('batas_maksimal'); ?>" data-validation="required" data-append="%" value="<?php echo c_percent($rks['batas_hps_atas']); ?>">
					</div>
				</div>
				<?php
				toggle(lang('pasal_jaminan_penawaran'),'jaminan_penawaran',$rks['jaminan_penawaran']);
				toggle(lang('pasal_jaminan_pelaksanaan'),'jaminan_pelaksanaan',$rks['jaminan_pelaksanaan']);
				toggle(lang('pasal_jaminan_pemeliharaan'),'jaminan_pemeliharaan',$rks['jaminan_pemeliharaan']);
				?>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<button type="reset" class="btn btn-secondary"><?php echo lang('batal'); ?></button>
						<button type="button" class="btn btn-success btn-next" data-target="step2" data-trigger="checkBatasHps"><?php echo lang('selanjutnya'); ?></button>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="step2" role="tabpanel" aria-labelledby="step2-tab">
				<?php
				col_init(3,9);
				textarea(lang('syarat_umum'),'syarat_umum','required',$rks['syarat_umum'],'data-editor="inline"');
				textarea(lang('syarat_khusus'),'syarat_khusus','required',$rks['syarat_khusus'],'data-editor="inline"');
				textarea(lang('syarat_teknis'),'syarat_teknis','required',$rks['syarat_teknis'],'data-editor="inline"');
				textarea(lang('pola_pembayaran'),'pola_pembayaran','required',$rks['pola_pembayaran'],'data-editor="inline"');
				toggle(lang('sanggahan_peserta'),'sanggahan_peserta',$rks['sanggahan_peserta']);
				?>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<button type="button" class="btn btn-danger btn-prev" data-target="step1"><?php echo lang('sebelumnya'); ?></button>
						<button type="button" class="btn btn-success btn-next" data-target="step3"><?php echo lang('selanjutnya'); ?></button>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="step3" role="tabpanel" aria-labelledby="step3-tab">
				<?php
				textarea(lang('latar_belakang'),'latar_belakang','required',$rks['latar_belakang'],'data-editor="inline"');
				textarea(lang('spesifikasi'),'spesifikasi','required',$rks['spesifikasi'],'data-editor="inline"');
				textarea(lang('jumlah_kebutuhan'),'jumlah_kebutuhan','required',$rks['jumlah_kebutuhan'],'data-editor="inline"');
				textarea(lang('distribusi_kebutuhan'),'distribusi_kebutuhan','required',$rks['distribusi_kebutuhan'],'data-editor="inline"');
				textarea(lang('jangka_waktu'),'jangka_waktu','required',$rks['jangka_waktu'],'data-editor="inline"');
				textarea(lang('ruang_lingkup'),'ruang_lingkup','required',$rks['ruang_lingkup'],'data-editor="inline"');
				textarea(lang('lain_lain'),'lain_lain','required',$rks['lain_lain'],'data-editor="inline"');
				?>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<button type="button" class="btn btn-danger btn-prev" data-target="step2"><?php echo lang('sebelumnya'); ?></button>
						<button type="button" class="btn btn-success btn-next" data-target="step4"><?php echo lang('selanjutnya'); ?></button>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="step4" role="tabpanel" aria-labelledby="step4-tab">
				<div class="form-group row">
					<label class="col-form-label col-sm-3"><?php echo lang('dokumen_pendukung') ?><small><?php echo lang('maksimal'); ?> 5MB</small></label>
					<div class="col-sm-9">
						<button type="button" class="btn btn-info" id="add-file" title="<?php echo lang('tambah_dokumen'); ?>"><?php echo lang('tambah_dokumen'); ?></button>
					</div>
				</div>
				<div id="additional-file" class="mb-2"><?php
				if($rks['file']) {
					foreach(json_decode($rks['file']) as $k => $v) { ?>
						<div class="form-group row">
							<div class="col-sm-3 col-4 offset-sm-3">
								<input type="text" class="form-control" autocomplete="off" name="keterangan_file[]" placeholder="<?php echo lang('keterangan'); ?>" data-validation="required" aria-label="<?php echo lang('keterangan'); ?>" value="<?php echo $k; ?>">
							</div>
							<div class="col-sm-4 col-5">
								<input type="hidden" class="form-control" name="file[]" autocomplete="off" value="exist:<?php echo $v; ?>">
								<div class="input-group">
									<input type="text" class="form-control" autocomplete="off" disabled value="<?php echo $v; ?>">
									<div class="input-group-append">
										<a href="<?php echo base_url(dir_upload('rks')).$v; ?>" target="_blank" class="btn btn-info btn-icon-only"><i class="fa-download"></i></a>
									</div>
								</div>
							</div>
							<div class="col-sm-2 col-3">
								<button type="button" class="btn btn-danger btn-remove btn-block btn-icon-only"><i class="fa-times"></i></button>
							</div>
						</div>
					<?php }
				}
				?></div>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<button type="button" class="btn btn-danger btn-prev" data-target="step3"><?php echo lang('sebelumnya'); ?></button>
						<button type="submit" class="btn btn-success"><?php echo lang('simpan'); ?></button>
					</div>
				</div>
			</div>
		</div>
		<?php
		form_close();
	modal_footer();
modal_close();
?>
<form action="<?php echo base_url('upload/file/datetime'); ?>" class="hidden">
	<input type="hidden" name="name" value="field_document">
	<input type="hidden" name="token" value="<?php echo encode_id([user('id'),(time() + 900)]); ?>">
	<input type="file" name="document" id="upl-file">
</form>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js') ?>"></script>
<script type="text/javascript">
$('#create-berita-acara').click(function(e){
	e.preventDefault();
	$('#modal-berita-acara').modal();
});
$('#create-rks').click(function(e){
	e.preventDefault();
	$('#modal-rks .modal-body.wizard a').removeClass('active').attr('aria-selected','false');
	$('#modal-rks .modal-body.wizard li:first-child a').addClass('active').attr('aria-selected','true');
	$('#modal-rks .wizard .tab-content .tab-pane').removeClass('show').removeClass('active');
	$('#modal-rks .wizard .tab-content .tab-pane:first-child').addClass('show').addClass('active');
	if($('#id').val() == '0') {
		$('#modal-rks .modal-body.wizard .nav-tabs li a').removeAttr('data-toggle');
		$('#modal-rks .modal-body.wizard .nav-tabs li:first-child a').attr('data-toggle','tab');
	}
	$('#modal-rks').modal();
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('.form-group').remove();
});
$('#add-file').click(function(){
	$('#upl-file').click();
});
var accept 	= Base64.decode(upl_alw);
var regex 	= "(\.|\/)("+accept+")$";
var re 		= accept == '*' ? '*' : new RegExp(regex,"i");
$('#upl-file').fileupload({
	maxFileSize: upl_flsz,
	autoUpload: false,
	dataType: 'text',
	acceptFileTypes: re
}).on('fileuploadadd', function(e, data) {
	$('#add-file').attr('disabled',true);
	data.process();
	is_autocomplete = true;
}).on('fileuploadprocessalways', function (e, data) {
	if (data.files.error) {
		var explode = accept.split('|');
		var acc 	= '';
		$.each(explode,function(i){
			if(i == 0) {
				acc += '*.' + explode[i];
			} else if (i == explode.length - 1) {
				acc += ', ' + lang.atau + ' *.' + explode[i];
			} else {
				acc += ', *.' + explode[i];
			}
		});
		cAlert.open(lang.file_yang_diizinkan + ' ' + acc + '. ' + lang.ukuran_file_maks + ' : ' + (upl_flsz / 1024 / 1024) + 'MB');
		$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
	} else {
		data.submit();
	}
	is_autocomplete = false;
}).on('fileuploadprogressall', function (e, data) {
	var progress = parseInt(data.loaded / data.total * 100, 10);
	$('#add-file').text(progress + '%');
}).on('fileuploaddone', function (e, data) {
	if(data.result == 'invalid' || data.result == '') {
		cAlert.open(lang.gagal_menunggah_file,'error');
	} else {
		var spl_result = data.result.split('/');
		if(spl_result.length == 1) spl_result = data.result.split('\\');
		if(spl_result.length > 1) {
			var spl_last_str = spl_result[spl_result.length - 1].split('.');
			if(spl_last_str.length == 2) {
				var filename = data.result;
				var f = filename.split('/');
				var fl = filename.split('temp');
				var fl_link = base_url + 'assets/uploads/temp' + fl[1];
				var konten = '<div class="form-group row">'
							+ '<div class="col-sm-3 col-4 offset-sm-3">'
							+ '<input type="text" class="form-control" autocomplete="off" value="" name="keterangan_file[]" placeholder="'+lang.keterangan+'" data-validation="required" aria-label="'+lang.keterangan+'">'
							+ '</div>'
							+ '<div class="col-sm-4 col-5">'
							+ '<input type="hidden" class="form-control" name="file[]" autocomplete="off" value="'+data.result+'">'
							+ '<div class="input-group">'
							+ '<input type="text" class="form-control" autocomplete="off" disabled value="'+f[f.length - 1]+'">'
							+ '<div class="input-group-append">'
							+ '<a href="'+fl_link+'" target="_blank" class="btn btn-info btn-icon-only"><i class="fa-download"></i></a>'
							+ '</div>'
							+ '</div>'
							+ '</div>'
							+ '<div class="col-sm-2 col-3">'
							+ '<button type="button" class="btn btn-danger btn-remove btn-block btn-icon-only"><i class="fa-times"></i></button>'
							+ '</div>'
							+ '</div>';
				$('#additional-file').append(konten);
			} else {
				cAlert.open(lang.file_gagal_diunggah,'error');
			}
		} else {
			cAlert.open(lang.file_gagal_diunggah,'error');						
		}
	}
	$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
	is_autocomplete = false;
}).on('fileuploadfail', function (e, data) {
	cAlert.open(lang.gagal_menunggah_file,'error');
	$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
	is_autocomplete = false;
}).on('fileuploadalways', function() {
});
$('#btn-transkrip').click(function(e){
	e.preventDefault();
	var params = {
		'id_export' 		: $(this).attr('data-id_chat'),
		'periode' 			: '',
		'csrf_token' 		: $(this).attr('data-key')
	};
	var url = base_url + 'settings/obrolan/export';
	$.redirect(url, params, "POST", "_blank"); 
});
$('#tutup_negosiasi').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','tutupNegosiasi');
});
function tutupNegosiasi() {
	var id = $('#id_klarifikasi').val();
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/tutup_negosiasi',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-peninjauan').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','kembaliPeninjauan');
});
function kembaliPeninjauan() {
	var id = $('#btn-peninjauan').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/kembali_peninjauan',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-next-negosiasi').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','nextNegosiasi');
});
function nextNegosiasi() {
	var id = $('#btn-next-negosiasi').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/negosiasi_kandidat_lain',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-batal').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','batalPengadaan');
});
function batalPengadaan() {
	var id = $('#btn-batal').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/batal_pengadaan',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-mulai-sesi').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','mulaiSesi');
});
function mulaiSesi() {
	var id = $('#btn-mulai-sesi').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/mulai_sesi',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-tutup-lelang').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','tutupLelang');
});
function tutupLelang() {
	var id = $('#btn-tutup-lelang').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/tutup_lelang',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-proses').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','proses');
});
function proses() {
	var id = $('#btn-proses').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/proses',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-reinisiasi').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','inisiasiUlang');
});
function inisiasiUlang() {
	var nomor_pengadaan = $('#btn-reinisiasi').attr('data-pengadaan');
	$.ajax({
		url : base_url + 'pengadaan/klarifikasi_negosiasi/inisiasi_ulang',
		data : {nomor_pengadaan : nomor_pengadaan},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
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
function checkBatasHps() {
	var res 	= true;
	if( toNumber($('#batas_hps_atas').val()) < toNumber($('#batas_hps_bawah').val())) {
		if($('#batas_hps_atas').parent().find('span.error').length == 0) {
			$('#batas_hps_atas').addClass('is-invalid');
			$('#batas_hps_atas').parent().append('<span class="error">' + lang.tidak_boleh_lebih_kecil_dari_batas_hps_minimal + '</span>');
		}
		res = false;
	}
	return res;
}
$('.price_unit').keyup(function(){
	var j = moneyToNumber($(this).closest('tr').find('.quantity').text());
	var h = moneyToNumber($(this).val());
	var t = j * h;
	$(this).closest('tr').find('.total_value').text(customFormat(t));
	var t_bef_ppn = 0;
	$('.total_value').each(function(){
		t_bef_ppn += moneyToNumber($(this).text());
	});
	$('#total_sebelum_ppn').text(customFormat(t_bef_ppn));
});
function checkTotal() {
	var p_akhir = moneyToNumber($('[href="#collapseTwo"]').text());
	var p_awal = moneyToNumber($('#penawaran_awal').val());
	var ttl = moneyToNumber($('#total_hps_pembulatan').text());
	var batas = p_akhir;
	if(p_akhir == 0) {
		batas = p_awal;
	}
	if(ttl > batas) {
		cAlert.open(lang.maksimal_penawaran + ' = ' + customFormat(batas));
		return false;
	} else return true;
}
</script>