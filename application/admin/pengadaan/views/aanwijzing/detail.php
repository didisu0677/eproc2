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
						<th><?php echo lang('tanggal_pengadaan'); ?></th>
						<td><?php echo c_date($tanggal_pengadaan); ?></td>
					</tr>
					<tr>
						<th><?php echo lang('metode_pengadaan'); ?></th>
						<td><?php echo $metode_pengadaan; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('_bidang_usaha'); ?></th>
						<td><?php 
						if($bidang_usaha) {
							echo '<table>';
							$i = 1;
							foreach(json_decode($bidang_usaha,true) AS $v) {
								echo '<tr>';
								echo '<td width="20" style="border: 0 none;">'.$i.'</td>';
								echo '<td width="150" style="border: 0 none;">'.$v['bidang_usaha'].'</td>';
								echo '<td style="border: 0 none;">'.$v['subbidang_usaha'].'</td>';
								echo '</tr>';
								$i++;
							}
							echo '</table>';
						}
						?></td>
					</tr>
					<tr>
						<th><?php echo lang('identifikasi_pajak'); ?></th>
						<td><?php echo $identifikasi_pajak; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('keterangan_pengadaan'); ?></th>
						<td><?php echo $keterangan_pengadaan; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('_hps'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/hps/cetak_hps/'.encode_id([$id_hps,rand()])); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<tr>
						<th><?php echo lang('_rks'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/rks/cetak/'.encode_id([$id_rks_pengadaan,rand()])); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<tr>
						<th><?php echo lang('dokumen_pendukung'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/aanwijzing/dokumen_rks/'.encode_id([$id_rks_pengadaan,rand()])); ?>" class="cInfo" aria-label="<?php echo lang('dokumen_pendukung'); ?>"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header"><?php echo lang('risalah_aanwijzing'); ?></div>
			<div class="card-body">
				<?php if($menu_access['access_additional'] && $status_aanwijzing == 'AANWIJZING') {
					echo '<div class="alert alert-info">'.lang('info_buat_berita_acara_rks').'.</div>';
				} ?>
				<table class="table table-bordered table-detail mb-0">
					<tr>
						<th width="200"><?php echo lang('nomor_aanwijzing'); ?></th>
						<td><?php echo $nomor_aanwijzing; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('berita_acara'); ?></th>
						<td><?php 
						if($status_berita_acara) {
							echo '[ <a href="'.base_url('pengadaan/aanwijzing/cetak_berita_acara/'.encode_id([$id,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						if($menu_access['access_additional'] && $status_aanwijzing == 'AANWIJZING' && $open_aanwijzing) {
							echo ' <a href="javascript:;" id="create-berita-acara" class="btn btn-info btn-sm ml-3">';
							echo $status_berita_acara ? lang('ubah_berita_acara') : lang('buat_berita_acara');
							echo '</a>';
						}
						?></td>
					</tr>
					<tr>
						<th><?php echo lang('_rks'); ?></th>
						<td><?php 
						if($status_rks) {
							echo '[ <a href="'.base_url('pengadaan/aanwijzing/cetak_rks/'.encode_id([$id_rks_aanwijzing,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						if($menu_access['access_additional'] && $status_aanwijzing == 'AANWIJZING' && $open_aanwijzing) {
							echo ' <a href="javascript:;" id="create-rks" class="btn btn-info btn-sm ml-3">';
							echo $status_rks ? lang('ubah_rks') : lang('buat_rks');
							echo '</a>';
						}
						?></td>
					</tr>
					<tr>
						<th><?php echo lang('dokumen_pendukung'); ?></th>
						<td><?php 
						if($status_rks) {
							echo '[ <a href="'.base_url('pengadaan/aanwijzing/dokumen_rks/'.encode_id([$id_rks_aanwijzing,rand()])).'" class="cInfo" aria-label="'.lang('dokumen_pendukung').'">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
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
		<?php if(menu()['access_additional'] && $status_aanwijzing == 'AANWIJZING') { ?>
		<div class="card mb-2">
			<div class="card-header"><?php echo lang('proses_penawaran'); ?></div>
			<div class="card-body">
				<div class="form-group row">
					<label class="col-form-label col-sm-3"><?php echo lang('dokumen_persyaratan_dan_pembobotan'); ?></label>
					<div class="col-sm-9">
						<button type="button" class="btn btn-info btn-sm" id="btn-inisiasi"><?php echo lang('ubah'); ?></button>
					</div>
				</div>
				<?php if($status_berita_acara && $status_rks) { 
					form_open(base_url('pengadaan/aanwijzing/proses'),'post','form-lanjut','data-callback="reload" data-submit="ajax"');
						col_init(3,9);
						input('hidden','id','id_awz','',$id); ?>
						<div class="form-group row">
							<label class="col-form-label col-sm-12"><strong><?php echo lang('peserta_pembukaan_dokumen_penawaran'); ?> :</strong></label>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-3 sub-1"><?php echo lang('user_pengadaan'); ?></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" value="<?php echo $nama_creator; ?>" disabled>
							</div>
						</div>
						<?php foreach($user_pengadaan as $u) { ?>
						<div class="form-group row">
							<div class="col-sm-9 offset-sm-3">
								<input type="text" class="form-control" value="<?php echo $u->nama_user; ?>" disabled>
							</div>
						</div>
						<?php } foreach($panitia_pelaksana as $k => $p) { ?>
						<div class="form-group row">
							<?php if($k == 0) { ?>
							<label class="col-form-label col-sm-3 sub-1"><?php echo $nama_panitia; ?></label>
							<div class="col-sm-9">
							<?php } else { ?>
							<div class="col-sm-9 offset-sm-3">
							<?php } ?>
								<input type="text" class="form-control" value="<?php echo $p->nama_panitia; ?>" disabled>
							</div>
						</div>
						<?php } ?>
						<div class="form-group row">
							<label class="col-form-label col-sm-3"><?php echo lang('peserta_lain'); ?></label>
							<div class="col-sm-7 col-9">
								<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota" value="">
								<input type="hidden" name="id_anggota[]" class="id_anggota" value="">
							</div>
							<div class="col-sm-2 col-3">
								<button type="button" class="btn btn-block btn-success btn-icon-only btn-add-anggota"><i class="fa-plus"></i></button>
							</div>
						</div>
						<div id="additional-anggota" class="mb-2"></div>
						<?php
						form_button(lang('lanjut_proses_penawaran'),false);
					form_close();
				} ?>
			</div>
		</div>
		<?php } ?>
		<div class="card">
			<div class="card-header"><?php echo lang('rekanan_yang_mengikuti'); ?></div>
			<div class="card-body table-responsive">
				<table class="table table-detail table-bordered mb-0">
					<tr>
						<th width="30"><?php echo lang('no'); ?></th>
						<th><?php echo lang('rekanan'); ?></th>
					</tr>
					<?php $i=1; foreach($aanwijzing_vendor as $a) { ?>
					<tr>
						<td class="text-center"><?php echo $i; ?></td>
						<td><a href="<?php echo base_url('pengadaan/aanwijzing/detail_vendor/'.encode_id([$a['id_vendor'],rand()]));; ?>" class="cInfo"><?php echo $a['nama_vendor']; ?></a>
					</tr>
					<?php $i++; } ?>
				</table>
			</div>
		</div>
	</div>
</div>
<?php
modal_open('modal-berita-acara',lang('berita_acara'),'modal-xl');
	modal_body();
		form_open(base_url('pengadaan/aanwijzing/save_berita_acara'),'post','form-berita-acara','data-callback="reload"');
			col_init(3,9);
			input('hidden','id','id_aanwijzing','',$id);
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
			label(lang('perserta'));
			foreach($vendor as $vd) { ?>
				<div class="form-group row">
					<label class="col-form-label col-sm-3 sub-1"><?php echo $vd['nama']; ?></label>
					<div class="col-md-2">
						<input type="hidden" name="id_vendor_ba[<?php echo $vd['id']; ?>]" value="<?php echo $vd['id']; ?>">
						<input type="hidden" name="vendor_ba[<?php echo $vd['id']; ?>]" value="<?php echo $vd['nama']; ?>">
						<div class="custom-checkbox custom-control custom-control-inline">
							<input class="custom-control-input" type="checkbox" id="hadir_<?php echo $vd['id']; ?>" name="hadir[<?php echo $vd['id']; ?>]" value="1" <?php if(isset($peserta_berita_acara[$vd['id']])) echo 'checked'; ?>>
							<label class="custom-control-label" for="hadir_<?php echo $vd['id']; ?>"><?php echo lang('hadir'); ?></label>
						</div>
					</div>
					<div class="col-md-7">
						<input type="text" name="nama_perwakilan_ba[<?php echo $vd['id']; ?>]" class="form-control" placeholder="<?php echo lang('nama_perwakilan'); ?>" autocomplete="off" value="<?php echo isset($peserta_berita_acara[$vd['id']]) ? $peserta_berita_acara[$vd['id']]['nama_perwakilan'] : $vd['nama_cp']; ?>" data-prepend="<?php echo lang('nama_perwakilan'); ?>">
					</div>
				</div>
			<?php }
			form_button(lang('simpan'),lang('batal'));
		form_close();
modal_close();
modal_open('modal-rks',lang('_rks'),'modal-xl');
	modal_body('wizard');
		form_open(base_url('pengadaan/aanwijzing/save_rks'),'post','form-rks','data-callback="reload"'); ?>
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
				<a class="nav-link" id="step4-tab" data-toggle="tab" href="#step4" role="tab" aria-controls="step4" aria-selected="off"><?php echo lang('jadwal_pengadaan'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="step5-tab" data-toggle="tab" href="#step5" role="tab" aria-controls="step5" aria-selected="off"><?php echo lang('dokumen_pendukung'); ?></a>
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
				<div class="form-group row">
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
				<?php foreach($jadwal as $j) { ?>
				<div class="form-group row">
					<label class="col-form-label col-sm-3<?php if(in_array($j['kata_kunci'],$mandatori)) echo ' required'; ?>"><?php echo $j['jadwal']; ?></label>
					<div class="col-md-3 mb-1 mb-md-0">
						<input type="hidden" name="jadwal[<?php echo $j['id']; ?>]" value="<?php echo $j['id']; ?>" data-value="<?php echo $j['id']; ?>" class="jadwal">
						<textarea name="lokasi[<?php echo $j['id']; ?>]" class="form-control lokasi" placeholder="<?php echo lang('lokasi'); ?>"<?php if(in_array($j['kata_kunci'],$mandatori)) echo ' data-validation="required"'; ?>><?php echo $j['lokasi']; ?></textarea>
					</div>
					<div class="col-md-2 col-6 mb-1 mb-md-0">
						<input type="text" name="tanggal_awal[<?php echo $j['id']; ?>]" autocomplete="off" class="form-control dtp tanggal_awal" placeholder="<?php echo lang('tanggal_mulai'); ?>" aria-label="<?php echo lang('tanggal_mulai'); ?>"<?php if(in_array($j['kata_kunci'],$mandatori)) echo ' data-validation="required"'; ?> value="<?php echo $j['tanggal_awal']; ?>">
					</div>
					<div class="col-md-2 col-6 mb-1 mb-md-0">
						<input type="text" name="tanggal_akhir[<?php echo $j['id']; ?>]" autocomplete="off" class="form-control dtp tanggal_akhir" placeholder="<?php echo lang('tanggal_selesai'); ?>" aria-label="<?php echo lang('tanggal_selesai'); ?>"<?php if(in_array($j['kata_kunci'],$mandatori)) echo ' data-validation="required"'; ?> value="<?php echo $j['tanggal_akhir']; ?>">
					</div>
					<div class="col-md-2">
						<select name="zona_waktu[<?php echo $j['id']; ?>]" autocomplete="off" class="form-control select2 infinity zona">
							<option value="WIB"<?php if($j['zona_waktu'] == 'WIB') echo ' selected'; ?>>WIB</option>
							<option value="WITA"<?php if($j['zona_waktu'] == 'WITA') echo ' selected'; ?>>WITA</option>
							<option value="WIT"<?php if($j['zona_waktu'] == 'WIT') echo ' selected'; ?>>WIT</option>
						</select>
					</div>
				</div>
				<?php } ?>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<button type="button" class="btn btn-danger btn-prev" data-target="step3"><?php echo lang('sebelumnya'); ?></button>
						<button type="button" class="btn btn-success btn-next" data-target="step5" data-trigger="checkTanggal"><?php echo lang('selanjutnya'); ?></button>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="step5" role="tabpanel" aria-labelledby="step5-tab">
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
						<button type="button" class="btn btn-danger btn-prev" data-target="step4"><?php echo lang('sebelumnya'); ?></button>
						<button type="submit" class="btn btn-success"><?php echo lang('simpan'); ?></button>
					</div>
				</div>
			</div>
		</div>
		<?php
		form_close();
	modal_footer();
modal_close();
modal_open('modal-inisiasi',lang('dokumen_persyaratan_dan_pembobotan'),'modal-xl');
	modal_body('wizard');
		form_open(base_url('pengadaan/aanwijzing/save_inisiasi'),'post','form-inisiasi','data-callback="reload" data-trigger="checkBobot"');
			col_init(3,9);
			input('hidden','nomor_pengajuan','awz_nomor_pengajuan','',$nomor_pengajuan);
		?>
		<ul class="nav nav-tabs" id="tab-wizard2" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="inisiasi-step1-tab" data-toggle="tab" href="#inisiasi-step1" role="tab" aria-controls="inisiasi-step1" aria-selected="true"><?php echo lang('dokumen_persyaratan'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="inisiasi-step2-tab" data-toggle="tab" href="#inisiasi-step2" role="tab" aria-controls="inisiasi-step2" aria-selected="off"><?php echo lang('pembobotan'); ?></a>
			</li>
		</ul>
		<div class="tab-content" id="tab-wizardContent">
			<div class="tab-pane show active" id="inisiasi-step1" role="tabpanel" aria-labelledby="inisiasi-step1-tab">
				<div class="alert alert-info">
					<ul class="pl-3 mb-0">
						<li><?php echo lang('info_mandatori_kelengkapan_dokumen1'); ?></li>
						<li><?php echo lang('info_mandatori_kelengkapan_dokumen2'); ?></li>
					</ul>
				</div>
				<?php foreach($grup_dokumen as $grup => $label) { ?>
				<div class="table-responsive">
					<table class="table table-bordered table-detail">
						<thead>
							<tr>
								<th colspan="2"><?php echo strtoupper($label); ?></th>
								<th width="100">
									<div class="custom-checkbox custom-control custom-control-inline">
										<input class="custom-control-input" type="checkbox" id="mandatori_<?php echo $grup; ?>" name="mandatori[<?php echo $grup; ?>]" value="1">
										<label class="custom-control-label" for="mandatori_<?php echo $grup; ?>"><?php echo lang('mandatori'); ?></label>
									</div>
								</th>
								<th width="55">
									<button type="button" class="btn btn-success btn-icon-only btn-sm btn-add-kelengkapan" data-grup="<?php echo $grup; ?>"><i class="fa-plus"></i></button>
								</th>
								<th width="55">&nbsp;</th>
							</tr>
						</thead>
						<tbody id="grup-<?php echo $grup; ?>">
						</tbody>
						<?php if($grup == 'dokumen_penawaran_harga') { ?>
						<tfoot>
							<td colspan="2"><?php echo lang('ketentuan_bank_garansi'); ?></td>
							<td colspan="3">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text"><?php echo lang('minimal'); ?></span></div>
									<input type="text" class="form-control percent" autocomplete="off" data-validation="required" maxlength="6" name="ketentuan_bank_garansi" id="ketentuan_bank_garansi">
									<div class="input-group-append"><span class="input-group-text">%</span></div>
								</div>
							</td>
						</tfoot>
						<?php } ?>
					</table>
				</div>
				<?php } ?>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<button type="reset" class="btn btn-secondary"><?php echo lang('batal'); ?></button>
						<button type="button" class="btn btn-success btn-next" data-target="inisiasi-step2"><?php echo lang('selanjutnya'); ?></button>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="inisiasi-step2" role="tabpanel" aria-labelledby="inisiasi-step2-tab">
				<div class="form-group row">
					<label class="col-form-label col-sm-3 required"><?php echo lang('jenis_pengadaan'); ?></label>
					<div class="col-md-5 mb-2 mb-md-0">
						<select id="id_jenis_pengadaan" class="form-control select2" name="id_jenis_pengadaan" data-validation="required"  >
							<option value=""></option>
							<?php foreach ($jenis_pengadaan as $ma){ ?>
								<option value="<?php echo $ma['id'] ?>" data-bobot_harga="<?php echo c_percent($ma['bobot_harga']) ?>" data-bobot_teknis="<?php echo c_percent($ma['bobot_teknis']) ?>"><?php echo $ma['jenis_pengadaan']; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-2 col-6">
						<div class="input-group">
							<input type="text" name="bobot_harga" value="" id="bobot_harga" class="form-control percent" placeholder="<?php echo lang('bobot_harga'); ?>" aria-label="<?php echo lang('bobot_harga'); ?>" autocomplete="off">
							<div class="input-group-append"><span class="input-group-text">%</span></div>
						</div>
					</div>
					<div class="col-sm-2 col-6">
						<div class="input-group">
							<input type="text" name="bobot_teknis" value="" id="bobot_teknis" class="form-control percent" placeholder="<?php echo lang('bobot_teknis'); ?>" aria-label="<?php echo lang('bobot_teknis'); ?>" autocomplete="off">
							<div class="input-group-append"><span class="input-group-text">%</span></div>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-12"><strong><?php echo lang('detil_bobot_teknis'); ?></strong></label>
				</div>
				<div id="detilBobotTeknis"></div>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<button type="button" class="btn btn-danger btn-prev" data-target="inisiasi-step1"><?php echo lang('sebelumnya'); ?></button>
						<button type="submit" class="btn btn-success"><?php echo lang('simpan'); ?></button>
					</div>
				</div>
			</div>
		</div>
		<?php
		form_close();
modal_close();
?>
<form action="<?php echo base_url('upload/file/datetime'); ?>" class="hidden">
	<input type="hidden" name="name" value="field_document">
	<input type="hidden" name="token" value="<?php echo encode_id([user('id'),(time() + 900)]); ?>">
	<input type="file" name="document" id="upl-file">
</form>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js') ?>"></script>

<script type="text/javascript">
var idx = 999;
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
$('#create-berita-acara').click(function(e){
	e.preventDefault();
	$('#modal-berita-acara').modal();
});
$('#btn-lanjut').click(function(e){
	e.preventDefault();
	cConfirm.open(lang.apakah_anda_yakin + '?','lanjut');
});
function lanjut() {
	$.ajax({
		url : base_url + 'pengadaan/aanwijzing/proses',
		data : {id : $('#btn-lanjut').attr('data-id')},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','reload');
		}
	});
}
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
function checkTanggal() {
	var res = true;
	$('.tanggal_akhir').each(function(){
		var tanggal_akhir 	= $(this).val();
		var tanggal_awal	= $(this).closest('.row').find('.tanggal_awal').val();
		if(tanggal_akhir) {
			var x_akhir = tanggal_akhir.split(' ');
			var x_awal 	= tanggal_awal.split(' ');
			if(x_akhir.length == 2 && x_awal.length == 2) {
				var d_akhir = x_akhir[0].split('/');
				var t_akhir = x_akhir[1].split(':');
				var akhir 	= d_akhir[2] + '-' + d_akhir[1] + '-' + d_akhir[0] + ' ' + t_akhir[0] + ':';
				akhir 		+= typeof t_akhir[1] !== 'undefined' && t_akhir[1] ? t_akhir[1]+':00' : '00:00';
				var time_akhir = new Date(akhir).getTime();

				var d_awal 	= x_awal[0].split('/');
				var t_awal 	= x_awal[1].split(':');
				var awal 	= d_awal[2] + '-' + d_awal[1] + '-' + d_awal[0] + ' ' + t_awal[0] + ':';
				awal 		+= typeof t_awal[1] !== 'undefined' && t_awal[1] ? t_awal[1]+':00' : '00:00';
				var time_awal = new Date(awal).getTime();

				if(parseInt(time_awal) >= parseInt(time_akhir)) {
					res = false;
					if($(this).parent().find('span.error').length == 0) {
						$(this).addClass('is-invalid');
						$(this).parent().append('<span class="error">' + lang.tidak_boleh_lebih_awal_dari_tanggal_mulai + '</span>');
					}
				}
			} else {
				$(this).val('');
				$(this).closest('.row').find('.tanggal_awal').val('');
				$(this).closest('.row').find('.lokasi').val('');
			}
		}
	});
	return res;
}
$('.tanggal_awal').on('apply.daterangepicker', function(ev, picker) {
	var tgl = $(this).closest('.row').find('.tanggal_akhir');
	tgl.removeClass('is-invalid');
	tgl.parent().find('span.error').remove();
});
$(document).ready(function(){
	cAutocomplete();
});
function add_row_anggota() {
	konten = '<div class="form-group row">'
			+ '<div class="offset-sm-3 col-sm-7 col-9">'
			+ '<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota">'
			+ '<input type="hidden" name="id_anggota[]" class="id_anggota">'
			+ '</div>'
			+ '<div class="col-sm-2 col-3">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>';
	$('#additional-anggota').append(konten);
	cAutocomplete();
}
$('.btn-add-anggota').click(function(){
	add_row_anggota();
});
$(document).on('click','.btn-remove-anggota',function(){
	$(this).closest('.form-group').remove();
});
$(document).on('blur','.anggota',function(){
	if($(this).parent().find('.id_anggota').val() == '0' || $(this).parent().find('.id_anggota').val() == '') {
		$(this).val('');
	}
});
function cAutocomplete() {
	$('.anggota').autocomplete({
		serviceUrl: base_url + 'pengadaan/aanwijzing/get_user/' + $('#id_awz').val(),
		showNoSuggestionNotice: true,
		noSuggestionNotice: lang.data_tidak_ditemukan,
        onSearchStart: function(query) {
            readonly_ajax = false;
            is_autocomplete = true;
            if($(this).parent().find('.autocomplete-spinner').length == 0) {
                $(this).parent().append('<i class="fa-spinner spin autocomplete-spinner"></i>');
            }
        }, onSearchComplete: function (query, suggestions) {
            is_autocomplete = false;
            $(this).parent().find('.autocomplete-spinner').remove();
        }, onSearchError: function (query, jqXHR, textStatus, errorThrown) {
            is_autocomplete = false;
            $(this).parent().find('.autocomplete-spinner').remove();
        }, onSelect: function (suggestion) {
			$(this).parent().find('.id_anggota').val(suggestion.data);
			var n = 0;
			$('.id_anggota').each(function(){
				if($(this).val() == suggestion.data) n++;
			});
			if(n > 1) {
				$(this).parent().find('.id_anggota').val('');
				$(this).val('');
			}
		}
	});
}

$('#btn-inisiasi').click(function(){
	$.ajax({
		url : base_url + 'pengadaan/aanwijzing/get_inisiasi',
		data : {nomor_pengajuan: $('#awz_nomor_pengajuan').val()},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			$('#id_jenis_pengadaan').val(response.id_jenis_pengadaan).trigger('change');
			$('#bobot_harga').val(cPercent(response.bobot_harga));
			$('#bobot_teknis').val(cPercent(response.bobot_teknis));
			$('#ketentuan_bank_garansi').val(cPercent(response.ketentuan_bank_garansi));
			$('#detilBobotTeknis').html('');
			parsingDokumenPersyaratan(response.dokumen_persyaratan, response.mandatori);
			$.each(response.pembobotan[0],function(k1,v1){
				var p = $('#detilBobotTeknis [data-idx="'+v1.id_persyaratan+'"]');
				p.find('.detil_bobot_keterangan').val(v1.deskripsi);
				p.find('select').val(v1.tipe_rumus).trigger('change');
				p.find('.detail_bobot').val(cPercent(v1.bobot));
				$.each(response.pembobotan[v1.id],function(k2,v2){
					var konten = '<tr>';
					if(v1.tipe_rumus == 'range') {
						konten += '<td colspan="2"><input type="text" name="child_batas_bawah['+v1.id_persyaratan+'][]" class="form-control" data-validation="required|number" autocomplete="off" value="'+v2.batas_bawah+'"></td>';
						konten += '<td colspan="2"><input type="text" name="child_batas_atas['+v1.id_persyaratan+'][]" class="form-control" data-validation="required|number" autocomplete="off" value="'+v2.batas_atas+'"></td>';
					} else {
						konten += '<td colspan="4"><input type="text" name="child_deskripsi['+v1.id_persyaratan+'][]" class="form-control" data-validation="required" autocomplete="off" value="'+v2.deskripsi+'"></td>';
					}
					konten += '<td><input type="text" name="child_bobot['+v1.id_persyaratan+'][]" class="form-control percent child_bobot" data-validation="required" autocomplete="off" maxlength="6" value="'+cPercent(v2.bobot)+'"></td>';
					konten += '<td><button type="button" class="btn btn-sm btn-danger btn-icon-only btn-remove-bobot"><i class="fa-times"></i></button></td>';
					konten += '</tr>';
					p.find('tbody').append(konten);
					$(".percent:not([readonly])").each(function(){
						var placeholder = '';
						if(typeof $(this).attr('placeholder') != 'undefined') placeholder = $(this).attr('placeholder');
						$(this).mask('099,09',{placeholder : placeholder});
					});
				});
			});
			$('#modal-inisiasi').modal();
		}
	});
});
$('#id_jenis_pengadaan').change(function(){
	$('#bobot_harga').val($(this).find(':selected').attr('data-bobot_harga'));
	$('#bobot_teknis').val($(this).find(':selected').attr('data-bobot_teknis'));
});
$(document).on('click','.btn-add-kelengkapan',function(){
	var konten = '';
	if($(this).hasClass('add-sub')) {
		konten += '<tr data-idx="'+idx+'" data-parent="'+$(this).attr('data-idx')+'">';
		konten += '<td width="30">&nbsp;</td>';
		konten += '<td colspan="2"><input type="text" name="deskripsi['+$(this).attr('data-grup')+']['+$(this).attr('data-idx')+']['+idx+']" class="form-control deskripsi_kelengkapan" autocomplete="off" data-validation="required"></td>';
		konten += '<td>&nbsp;</td>';
		konten += '<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove-kelengkapan" data-idx="'+idx+'"><i class="fa-times"></i></button></td>';
	} else {
		konten += '<tr data-idx="'+idx+'">';
		konten += '<td colspan="3"><input type="text" name="deskripsi['+$(this).attr('data-grup')+'][0]['+idx+']" class="form-control deskripsi_kelengkapan" autocomplete="off" data-validation="required"></td>';
		konten += '<td><button type="button" class="btn btn-success btn-icon-only btn-sm btn-add-kelengkapan add-sub" data-idx="'+idx+'" data-grup="'+$(this).attr('data-grup')+'"><i class="fa-plus"></i></button></td>';
		konten += '<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove-kelengkapan" data-idx="'+idx+'"><i class="fa-times"></i></button></td>';
	}
	konten += '</tr>';
	$('#grup-'+$(this).attr('data-grup')).append(konten);
	detilBobotTeknis();
	idx++;
});
$(document).on('keyup','#grup-dokumen_teknis .deskripsi_kelengkapan',function(){
	detilBobotTeknis();
});
$(document).on('click','.btn-remove-kelengkapan',function(){
	$(this).closest('tr').remove();
	$('#detilBobotTeknis .table-responsive[data-idx="'+$(this).attr('data-idx')+'"]').remove();
	$('tr[data-parent="'+$(this).attr('data-idx')+'"]').remove();
});
function parsingDokumenPersyaratan(d,m) {
	$.each(d,function(x,y){
		if(m[x] == "1") {
			$('#mandatori_'+x).prop('checked',true);
		} else {
			$('#mandatori_'+x).prop('checked',false);
		}

		$.each(d[x][0],function(j,k){
			var konten = '<tr data-idx="'+k.id+'">'
				+ '<td colspan="3"><input type="text" name="deskripsi['+k.grup+'][0]['+k.id+']" class="form-control deskripsi_kelengkapan" autocomplete="off" data-validation="required" value="'+k.deskripsi+'"></td>'
				+ '<td><button type="button" class="btn btn-success btn-icon-only btn-sm btn-add-kelengkapan add-sub" data-idx="'+k.id+'" data-grup="'+k.grup+'"><i class="fa-plus"></i></button></td>'
				+ '<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove-kelengkapan" data-idx="'+k.id+'"><i class="fa-times"></i></button></td>'
			+ '</tr>';
			$('#grup-'+k.grup).append(konten);
			$.each(d[x][k.id],function(e,f){
				var konten = '<tr data-idx="'+f.id+'" data-parent="'+k.id+'"><td width="30">&nbsp;</td>'
					+ '<td colspan="2"><input type="text" name="deskripsi['+k.grup+']['+k.id+']['+f.id+']" class="form-control deskripsi_kelengkapan" autocomplete="off" data-validation="required" value="'+f.deskripsi+'"></td>'
					+ '<td>&nbsp;</td>'
					+ '<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove-kelengkapan" data-idx="'+f.id+'"><i class="fa-times"></i></button></td>'
				+ '</tr>';
				$('#grup-'+k.grup).append(konten);
			});
		});
	});
	detilBobotTeknis();
}

function detilBobotTeknis() {
	$('#grup-dokumen_teknis tr').each(function(){
		if(typeof $(this).attr('data-parent') == 'undefined') {
			if($('#detilBobotTeknis .table-responsive[data-idx="'+$(this).attr('data-idx')+'"]').length == 1) {
				$('#detilBobotTeknis .table-responsive[data-idx="'+$(this).attr('data-idx')+'"]').find('.detil_bobot_keterangan').val($(this).find('input').val());
			} else {
				var konten = '<div class="table-responsive" data-idx="'+$(this).attr('data-idx')+'">' +
					'<table class="table table-bordered table-detail">' +
						'<thead>' +
							'<tr>' +
								'<th colspan="3"><input type="hidden" name="idx[]" value="'+$(this).attr('data-idx')+'" /><input type="text" name="detil_bobot_keterangan[]" value="'+$(this).find('input').val()+'" autocomplete="off" class="form-control detil_bobot_keterangan" data-validation="required" /></th>' +
								'<th width="250">' +
									'<select class="form-control cara-hitung" name="cara_perhitungan[]" data-validation="required">' +
										'<option value="terbanyak">' + lang.berdasarkan_poin_terbanyak + '</option>' +
										'<option value="terendah">' + lang.berdasarkan_poin_terendah + '</option>' +
										'<option value="acuan">' + lang.berdasarkan_acuan + '</option>' +
										'<option value="range">' + lang.berdasarkan_range_angka + '</option>' +
									'</select>' +
								'</th>' +
								'<th width="150">' +
									'<div class="input-group">' +
										'<div class="input-group-prepend"><span class="input-group-text">'+lang.bobot+'</span></div>' +
										'<input type="text" name="detail_bobot[]" class="form-control percent detail_bobot" maxlength="6" autocomplete="off" />' +
									'</div>' +
								'</th>' +
								'<th width="50">&nbsp;</th>' +
							'</tr>' +
							'<tr class="header">' +
								'<th colspan="4">' + lang.poin_yang_dinilai + '</th>' +
								'<th>' + lang.bobot + '</th>' +
								'<th><button type="button" class="btn btn-success btn-sm btn-icon-only btn-add-bobot"><i class="fa-plus"></i></button></th>' +
							'</tr>' +
						'</thead>' +
						'<tbody></tbody>' +
					'</table>' +
				'</div>';
				$('#detilBobotTeknis').append(konten);
				$('#detilBobotTeknis .table-responsive').last().find('select').select2({
					minimumResultsForSearch: Infinity,
					dropdownParent : $('#detilBobotTeknis .table-responsive').last().find('select').parent(),
					width: '100%'
				});
				$(".percent:not([readonly])").each(function(){
					var placeholder = '';
					if(typeof $(this).attr('placeholder') != 'undefined') placeholder = $(this).attr('placeholder');
					$(this).mask('099,09',{placeholder : placeholder});
				});
			}
		}
	});
}
$(document).on('change','.cara-hitung',function(){
	$(this).closest('table').find('tbody').html('');
	var konten = '';
	if($(this).val() == 'acuan') {
		konten += '<th colspan="4">' + lang.acuan_nilai + '</th>' +
			'<th>' + lang.bobot + '</th>' +
			'<th><button type="button" class="btn btn-success btn-sm btn-icon-only btn-add-bobot"><i class="fa-plus"></i></th>';
	} else if($(this).val() == 'range') {
		konten += '<th colspan="2">' + lang.batas_bawah + '</th>' +
			'<th colspan="2" width="300">' + lang.batas_atas + '</th>' +
			'<th>' + lang.bobot + '</th>' +
			'<th><button type="button" class="btn btn-success btn-sm btn-icon-only btn-add-bobot"><i class="fa-plus"></i></th>';
	} else {
		konten += '<th colspan="4">' + lang.poin_yang_dinilai + '</th>' +
			'<th>' + lang.bobot + '</th>' +
			'<th><button type="button" class="btn btn-success btn-sm btn-icon-only btn-add-bobot"><i class="fa-plus"></i></th>';
	}
	$(this).closest('table').find('.header').html(konten);
});
function checkBobot() {
	var res 		= true;
	var b_harga 	= toNumber($('#bobot_harga').val());
	var b_teknis 	= toNumber($('#bobot_teknis').val());
	if(b_harga + b_teknis != 100) {
		res = false;
		$('#bobot_teknis,#bobot_harga').addClass('is-invalid');
		$('#bobot_teknis,#bobot_harga').parent().parent().find('.error').remove();
		$('#bobot_teknis,#bobot_harga').parent().parent().append('<span class="error">' + lang.jumlah_bobot_harus_100 + '</span>');
	}
	var jml_detail	= 0;
	$('.detail_bobot').each(function(){
		jml_detail += toNumber($(this).val());
	});
	if(jml_detail != 100) {
		res = false;
		$('.detail_bobot').addClass('is-invalid');
		$('.detail_bobot').parent().parent().find('.error').remove();
		$('.detail_bobot').parent().parent().append('<span class="error">' + lang.jumlah_bobot_harus_100 + '</span>');
	}
	var sum_child = 0;
	$('#detilBobotTeknis .table-responsive').each(function(){
		if($(this).find('tbody').find('tr').length > 0) {
			sum_child++;
		}
		var p = $(this).find('table');
		var t = p.find('thead').find('select').val();
		var c = p.find('thead').find('.detail_bobot').val();

		if(t == 'terendah' || t == 'terbanyak') {
			var jml_child = 0;
			p.find('.child_bobot').each(function(){
				jml_child += toNumber($(this).val());
			});
			if(jml_child != toNumber(c)) {
				res = false;
				p.find('.child_bobot').addClass('is-invalid');
				p.find('.child_bobot').parent().find('.error').remove();
				p.find('.child_bobot').parent().append('<span class="error">' + lang.jumlah_harus + ' ' + c + '</span>');
			}
		} else {
			p.find('.child_bobot').each(function(){
				if(toNumber($(this).val()) > toNumber(c)) {
					res = false;
					$(this).addClass('is-invalid');
					$(this).parent().find('.error').remove();
					$(this).parent().append('<span class="error">' + lang.maksimal + ' ' + c + '</span>');
				}
			});
		}
	});
	if(sum_child != $('#detilBobotTeknis .table-responsive').length) {
		res = false;
		cAlert.open(lang.semua_detil_bobot_teknis_harus_dijabarkan);
	}
	return res;
}
$('#bobot_teknis,#bobot_harga').keyup(function(){
	$('#bobot_teknis,#bobot_harga').parent().parent().find('.error').remove();
	$('#bobot_teknis,#bobot_harga').removeClass('is-invalid');
});
$(document).on('keyup','.detail_bobot',function(){
	$('.detail_bobot').parent().parent().find('.error').remove();
	$('.detail_bobot').removeClass('is-invalid');
});
$(document).on('keyup','.child_bobot',function(){
	$(this).closest('tbody').find('.child_bobot').each(function(){
		$(this).removeClass('is-invalid');
		$(this).parent().find('.error').remove();
	});
});
$(document).on('click','.btn-add-bobot',function(){
	var p = $(this).closest('table');
	var i = p.find('thead').find('input[type="hidden"]').val();
	var t = p.find('thead').find('select').val();
	var konten = '<tr>';
	if(t == 'range') {
		konten += '<td colspan="2"><input type="text" name="child_batas_bawah['+i+'][]" class="form-control" data-validation="required|number" autocomplete="off"></td>';
		konten += '<td colspan="2"><input type="text" name="child_batas_atas['+i+'][]" class="form-control" data-validation="required|number" autocomplete="off"></td>';
	} else {
		konten += '<td colspan="4"><input type="text" name="child_deskripsi['+i+'][]" class="form-control" data-validation="required" autocomplete="off"></td>';
	}
	konten += '<td><input type="text" name="child_bobot['+i+'][]" class="form-control percent child_bobot" data-validation="required" autocomplete="off" maxlength="6"></td>';
	konten += '<td><button type="button" class="btn btn-sm btn-danger btn-icon-only btn-remove-bobot"><i class="fa-times"></i></button></td>';
	konten += '</tr>';
	p.find('tbody').append(konten);
	$(".percent:not([readonly])").each(function(){
		var placeholder = '';
		if(typeof $(this).attr('placeholder') != 'undefined') placeholder = $(this).attr('placeholder');
		$(this).mask('099,09',{placeholder : placeholder});
	});
});
$(document).on('click','.btn-remove-bobot',function(){
	$(this).closest('tr').remove();
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
</script>