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
						<th><?php echo lang('keterangan_pengadaan'); ?></th>
						<td><?php echo $keterangan_pengadaan; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('identifikasi_pajak'); ?></th>
						<td><?php echo $identifikasi_pajak; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('_hps'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/hps/cetak_hps/'.encode_id([$id_hps,rand()])); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header"><?php echo lang('risalah_aanwijzing'); ?></div>
			<div class="card-body">
				<table class="table table-bordered table-detail mb-0">
					<tr>
						<th width="200"><?php echo lang('nomor_aanwijzing'); ?></th>
						<td><?php echo $nomor_aanwijzing; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('_rks'); ?></th>
						<td><?php 
						if($status_rks) {
							echo '[ <a href="'.base_url('pengadaan/aanwijzing/cetak_rks/'.encode_id([$id_rks_aanwijzing,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						?></td>
					</tr>
					<tr>
						<th><?php echo lang('dokumen_pendukung'); ?></th>
						<td><?php 
						if($status_rks) {
							echo '[ <a href="'.base_url('pengadaan/penawaran/dokumen_rks/'.encode_id([$id_rks_aanwijzing,rand()])).'" class="cInfo" aria-label="'.lang('dokumen_pendukung').'">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						?></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="card mb-2">
			<div class="card-header"><?php echo lang('_penawaran'); ?></div>
			<div class="card-body">
				<?php $jml_penawar = $jml_periksa = 0;  $jml_sah = 0;
					if($open_penawaran) { ?>
					<?php if(!$stat_pengadaan && $menu_access['access_additional']) { ?>
					<div class="table-responsive">
						<table class="table table-bordered table-detail">
							<thead>
								<tr>
									<th width="10"><?php echo lang('no'); ?></th>
									<th><?php echo lang('rekanan'); ?></th>
									<th><?php echo lang('tanggal_penawaran'); ?></th>
									<th><?php echo lang('pesan'); ?></th>
									<th><?php echo lang('status'); ?></th>
									<th width="10">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($penawaran as $k => $p) { ?>
								<tr>
									<td><?php echo ($k+1); ?></td>
									<td><a href="<?php echo base_url('pengadaan/penawaran/detail_vendor/'.encode_id([$p[
									'id_vendor'],rand()])); ?>" class="cInfo" aria-label="<?php echo lang('data_rekanan'); ?>"><?php echo $p['nama_vendor']; ?></a>
									<?php if($p['file_penawaran']) { ?>
									<td><?php echo c_date($p['tanggal_penawaran']); ?></td>
									<td><?php echo lang('pesan_penawaran'); ?></td>
									<td>
										<?php 
											$jml_penawar++;
											if($p['lolos_penawaran'] > 0) $jml_periksa++;
											if($p['lolos_penawaran'] == 1) $jml_sah++;
											if($p['lolos_penawaran'] == 0) echo '<em class="text-danger">['.lang('belum_diperiksa').']</em>';
											else if($p['lolos_penawaran'] == 1) echo '<span class="text-success"><i class="fa-check"></i> '.lang('sah').'</span>';
											else  echo '<span class="text-danger"><i class="fa-times"></i>'.lang('gugur').'</span>';
										?>
									</td>
									<td><button type="button" class="btn btn-sm btn-icon-only btn-info btn-penilaian" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang('periksa_kelengkapan_dokumen'); ?>" data-id="<?php echo $p['id_vendor']; ?>" data-pengadaan="<?php echo $nomor_pengadaan; ?>"><i class="fa-edit"></i></button></td>
									<?php } else { ?>
										<td colspan="4"><em class="text-danger">[ <?php echo lang('belum_memasukan_penawaran'); ?> ]</em></td>
									<?php } ?>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<?php } else { ?>
					<div class="table-responsive">
						<table class="table table-bordered table-detail">
							<thead>
								<tr>
									<th width="10"><?php echo lang('no'); ?></th>
									<th><?php echo lang('rekanan'); ?></th>
									<th><?php echo lang('tanggal_penawaran'); ?></th>
									<th><?php echo lang('pesan'); ?></th>
									<th><?php echo lang('status'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($penawaran as $k => $p) { ?>
								<tr>
									<td><?php echo ($k+1); ?></td>
									<td><a href="<?php echo base_url('pengadaan/penawaran/detail_vendor/'.encode_id([$p[
									'id_vendor'],rand()])); ?>" class="cInfo" aria-label="<?php echo lang('data_rekanan'); ?>"><?php echo $p['nama_vendor']; ?></a></td>
									<?php if($p['file_penawaran']) { ?>
									<td><?php echo c_date($p['tanggal_penawaran']); ?></td>
									<td><?php echo lang('pesan_penawaran'); ?></td>
									<td>
										<?php 
											$jml_penawar++;
											if($p['lolos_penawaran'] > 0) $jml_periksa++;
											if($p['lolos_penawaran'] == 1) $jml_sah++;
											if($p['lolos_penawaran'] == 0) echo '<em class="text-danger">['.lang('belum_diperiksa').']</em>';
											else if($p['lolos_penawaran'] == 1) echo '<strong class="text-success">'.lang('sah').'</strong>';
											else  echo '<strong class="text-danger">'.lang('gugur').'</strong>';
										?>
									</td>
									<?php } else { ?>
										<td colspan="3"><em class="text-danger">[ <?php echo lang('tidak_memasukan_penawaran'); ?> ]</em></td>
									<?php } ?>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<?php } ?>
					<table class="table table-bordered table-detail mb-0">
						<tr>
							<th width="200"><?php echo lang('berita_acara_pembukaan_penawaran'); ?></th>
							<td><?php
								if($tanggal_ba_pembukaan != '0000-00-00 00:00:00') {
									echo '[ <a href="'.base_url('pengadaan/penawaran/berita_acara/'.encode_id([$id,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
								} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
								if($menu_access['access_additional'] && !$stat_pengadaan && $open_penawaran && $jml_penawar == $jml_periksa) {
									echo ' <a href="javascript:;" id="create-berita-acara" class="btn btn-info btn-sm ml-3">';
									echo $tanggal_ba_pembukaan != '0000-00-00 00:00:00' ? lang('ubah_berita_acara') : lang('buat_berita_acara');
									echo '</a>';
								}
							?></td>
						</tr>
						<tr>
							<th><?php echo lang('daftar_pemasukan_dokumen_penawaran'); ?></th>
							<td><?php 
								if($jml_penawar == $jml_periksa) {
									echo '[ <a href="'.base_url('pengadaan/penawaran/daftar_rekanan/'.encode_id([$id,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
								} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
							?></td>
						</tr>
						<tr>
							<th><?php echo lang('resume_penawaran'); ?></th>
							<td><?php 
								if($jml_penawar == $jml_periksa) {
									echo '[ <a href="'.base_url('pengadaan/penawaran/resume/'.encode_id([$id,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
								} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
							?></td>
						</tr>
						<?php if($menu_access['access_additional']) { ?>
						<tr>
							<th><?php echo lang('transkrip_obrolan'); ?></th>
							<td><?php 
								echo '[ <a href="javascript:;" id="btn-transkrip" data-id_chat="'.$id_chat_penawaran.'" data-key="'.csrf_token(false,'return').'">'.lang('lihat_detil').'</a> ]';
							?></td>
						</tr>
						<?php } ?>
					</table>
					<?php
				} else echo '<div class="alert alert-info">'.lang('jadwal_pembukaan_dokumen_penawaran_pada').' : <strong>'.$tanggal_penawaran.'</strong></div>';?>
			</div>
		</div>
		<?php if($open_penawaran && !$stat_pengadaan && $menu_access['access_additional']) { ?>
		<div class="card">
			<div class="card-header"><?php echo $tipe_pengadaan == 'Jasa Langsung' && $jml_sah == 1 ? lang('klarifikasi_negosiasi') : lang('evaluasi_penawaran'); ?></div>
			<div class="card-body">
				<?php if($tanggal_ba_pembukaan == '0000-00-00 00:00:00') {
					echo '<div class="alert alert-info">'.lang('form_evaluasi_belum_tersedia').'</div>';
				} else { 
					$limit = 0;
					if($tipe_pengadaan == 'Lelang') $limit = setting('min_pengadaan_lelang');
					else if($tipe_pengadaan == 'Pemilihan Langsung') $limit = setting('min_pengadaan_pemilihan_langsung');
					else if($tipe_pengadaan == 'Penunjukan Langsung') $limit = setting('min_pengadaan_penunjukan_langsung');
					else if($tipe_pengadaan == 'Jasa Langsung') $limit = setting('min_pengadaan_jasa_langsung');
					if($limit > $jml_sah) {
						$t_pengadaan = $tipe_pengadaan == 'Tender' ? lang('tender') : lang('non_tender');
						echo '<div class="alert alert-warning"><strong>'.lang('metode_pengadaan').' : ' .$metode_pengadaan.'</strong><br />'.lang('untuk_melanjutkan_keproses_evaluasi_minimal_peserta_pengadaan_yang_sah_sebanyak').$limit.' '.lang('peserta').'</div>';
						echo '<button type="button" class="btn btn-danger" id="btn-batal" data-pengadaan="'.$nomor_pengadaan.'"><i class="fa-times"></i>'.lang('pengadaan_dibatalkan').'</button>';
					} else {
						form_open(base_url('pengadaan/penawaran/save'),'post','form-penawaran','data-submit="ajax" data-callback="reload" data-id="'.$id.'"');
							?>
							<div class="form-group row">
								<label class="col-form-label col-sm-12"><strong><?php echo lang('peserta'); ?> :</strong></label>
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
							col_init(3,9);
							input('hidden','nomor_pengadaan','nomor_pengadaan','',$nomor_pengadaan);
							form_button(lang('proses'),false);
						form_close();
					}
				} ?>
			</div>
		</div>
		<?php } if($stat_pengadaan == 'BATAL' && $last_pos == 'PENAWARAN' && $menu_access['access_additional']) { ?>
		<div class="card">
			<div class="card-header"><?php echo lang('evaluasi_penawaran'); ?></div>
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
modal_open('modal-penilaian',lang('periksa_kelengkapan_dokumen'),'modal-xl');
	modal_body();
		form_open(base_url('pengadaan/penawaran/save_penilaian'),'post','form-penilaian','data-callback="reload"');
			col_init(3,9);
			input('hidden','nomor_pengajuan','nomor_pengajuan','',$nomor_pengajuan);
			input('hidden','nomor_pengadaan','_nomor_pengadaan','',$nomor_pengadaan);
			input('hidden','id_vendor','id_vendor');
		?>
		<div class="table-responsive">
			<table class="table table-detail table-bordered">
				<tr>
					<td colspan="4">
						<h4 class="size-16" id="nama-rekanan">Nama Rekanan</h4>
						<p id="alamat-rekanan">Alamat Rekanan</p>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<a href="#" id="download-dokumen-persyaratan" class="btn btn-info btn-block" target="_blank"><i class="fa-file-archive"></i> <?php echo lang('unduh_dokumen_persyaratan'); ?></a>
						<div class="mt-2"><?php echo lang('kata_sandi'); ?> : <strong id="password-dokumen-persyaratan">1234567</strong></div>
					</td>
					<td width="25%">
						<a href="#" id="download-dokumen-administrasi" class="btn btn-info btn-block" target="_blank"><i class="fa-file-archive"></i> <?php echo lang('unduh_dokumen_administrasi'); ?></a>
						<div class="mt-2"><?php echo lang('kata_sandi'); ?> : <strong id="password-dokumen-administrasi">1234567</strong></div>
					</td>
					<td width="25%">
						<a href="#" id="download-dokumen-teknis" class="btn btn-info btn-block" target="_blank"><i class="fa-file-archive"></i> <?php echo lang('unduh_dokumen_teknis'); ?></a>
						<div class="mt-2"><?php echo lang('kata_sandi'); ?> : <strong id="password-dokumen-teknis">1234567</strong></div>
					</td>
					<td width="25%">
						<a href="#" id="download-dokumen-penawaran" class="btn btn-info btn-block" target="_blank"><i class="fa-file-archive"></i> <?php echo lang('unduh_dokumen_penawaran_harga'); ?></a>
						<div class="mt-2"><?php echo lang('kata_sandi'); ?> : <strong id="password-dokumen-penawaran">1234567</strong></div>
					</td>
				</tr>
			</table>
		</div>
		<?php
			foreach($grup_dokumen as $k => $d) { ?>
			<table class="table table-bordered table-detail">
				<thead>
					<tr>
						<th colspan="2"><?php echo $d; ?></th>
						<th width="30"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($dokumen_persyaratan[$k][0] as $d1) { ?>
					<tr>
						<td colspan="2"><?php echo $d1['deskripsi']; ?></td>
						<td>
							<?php if(count($dokumen_persyaratan[$k][$d1['id']]) == 0) { ?>
							<input type="hidden" name="id_persyaratan[]" value="<?php echo $d1['id']; ?>">
							<div class="custom-checkbox custom-control custom-control-inline mr-0">
								<input class="custom-control-input sah" id="sah<?php echo $d1['id']; ?>" type="checkbox" name="sah[<?php echo $d1['id']; ?>]" value="1">
								<label class="custom-control-label" for="sah<?php echo $d1['id']; ?>">&nbsp;</label>
							</div>
							<?php } else echo '&nbsp;'; ?>
						</td>
					</tr>
					<?php
					if(isset($dokumen_persyaratan[$k][$d1['id']])) { foreach($dokumen_persyaratan[$k][$d1['id']] as $d2) { ?>
					<tr>
						<td colspan="2" class="sub-1"><?php echo $d2['deskripsi']; ?></td>
						<td>
							<input type="hidden" name="id_persyaratan[]" value="<?php echo $d2['id']; ?>">
							<div class="custom-checkbox custom-control custom-control-inline mr-0">
								<input class="custom-control-input sah" id="sah<?php echo $d2['id']; ?>" type="checkbox" name="sah[<?php echo $d2['id']; ?>]" value="1">
								<label class="custom-control-label" for="sah<?php echo $d2['id']; ?>">&nbsp;</label>
							</div>
						</td>
					</tr>
					<?php }}} ?>
					<?php if($k == 'dokumen_penawaran_harga') { ?>
					<tr>
						<td><?php echo lang('nilai_total_penawaran_harga'); ?></td>
						<td colspan="2" width="100">
							<input type="text" name="nilai_total_penawaran" id="nilai_total_penawaran" class="form-control money" data-validation="required|max-length:25" autocomplete="off">
						</td>
					</tr>
					<tr>
						<td><?php echo lang('nilai_jaminan_penawaran'); ?></td>
						<td colspan="2" width="100">
							<input type="text" name="nilai_jaminan_penawaran" id="nilai_jaminan_penawaran" class="form-control money" data-validation="max-length:25" autocomplete="off">
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td colspan="2" width="100">
							<button type="submit" class="btn btn-info btn-block"><?php echo lang('simpan'); ?></button>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php
			}
		form_close();
modal_close();
modal_open('modal-berita-acara',lang('berita_acara'),'modal-xl');
	modal_body();
		form_open(base_url('pengadaan/penawaran/save_berita_acara'),'post','form-berita-acara','data-callback="reload"');
			col_init(3,9);
			input('hidden','id','id_aanwijzing','',$id);
			input('text',lang('nomor_berita_acara'),'nomor_ba_pembukaan','',$nomor_ba_pembukaan,'readonly data-readonly="true" placeholder="'.lang('otomatis_saat_disimpan').'"');
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required"><?php echo lang('tanggal'); ?></label>
				<div class="col-md-3">
					<input type="text" name="tanggal_ba_pembukaan" class="form-control dtp" placeholder="<?php echo lang('tanggal'); ?>" autocomplete="off" value="<?php echo c_date($tanggal_ba_pembukaan); ?>" data-validation="required">
				</div>
				<div class="col-md-6">
					<select id="zona_waktu_pembukaan" name="zona_waktu_pembukaan" class="form-control select2 infinity">
						<option value="WIB"<?php if($zona_waktu_pembukaan == 'WIB') echo ' selected'; ?>>WIB</option>
						<option value="WITA"<?php if($zona_waktu_pembukaan == 'WITA') echo ' selected'; ?>>WITA</option>
						<option value="WIT"<?php if($zona_waktu_pembukaan == 'WIT') echo ' selected'; ?>>WIT</option>
					</select>
				</div>
			</div>
			<?php
			textarea(lang('lokasi'),'lokasi_ba_pembukaan','required',$lokasi_ba_pembukaan);
			form_button(lang('simpan'),lang('batal'));
		form_close();
modal_close();
?>
<script type="text/javascript">
$('#create-berita-acara').click(function(e){
	e.preventDefault();
	$('#modal-berita-acara').modal();
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
$(document).on('click','.btn-penilaian',function(){
	$('#form-penilaian')[0].reset();
	var id_vendor = $(this).attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/penawaran/get_penilaian',
		data : {
			id_vendor : id_vendor,
			nomor_pengadaan : $(this).attr('data-pengadaan')
		},
		type : 'post',
		dataType : 'json',
		success : function(response){
			$('#id_vendor').val(id_vendor);
			$('#nama-rekanan').text(response.nama_vendor);
			$('#alamat-rekanan').html(response.alamat_vendor);
			$('#download-dokumen-persyaratan').attr('href',response.dok_persyaratan);
			$('#download-dokumen-administrasi').attr('href',response.dok_administrasi);
			$('#download-dokumen-teknis').attr('href',response.dok_teknis);
			$('#download-dokumen-penawaran').attr('href',response.dok_penawaran);
			$('#password-dokumen-persyaratan').text(response.pass_persyaratan);
			$('#password-dokumen-administrasi').text(response.pass_administrasi);
			$('#password-dokumen-teknis').text(response.pass_teknis);
			$('#password-dokumen-penawaran').text(response.pass_penawaran);
			$('#nilai_total_penawaran').val(response.nilai_total_penawaran);
			$('#nilai_jaminan_penawaran').val(response.nilai_jaminan_penawaran);
			$.each(response.persyaratan,function(k1,v1){
				if(v1.sah == '1') {
					$('#sah'+v1.id_persyaratan).prop('checked',true);
				}
			});
			$('#modal-penilaian').modal();
		}
	});
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
		serviceUrl: base_url + 'pengadaan/penawaran/get_user/' + $('#form-penawaran').attr('data-id'),
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
$('#btn-batal').click(function(){
	cConfirm.open(lang.anda_yakin_membatalkan_pengadaan_ini,'batalPengadaan');
});
function batalPengadaan() {
	var nomor_pengadaan = $('#btn-batal').attr('data-pengadaan');
	$.ajax({
		url : base_url + 'pengadaan/penawaran/pembatalan',
		data : {nomor_pengadaan : nomor_pengadaan},
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
		url : base_url + 'pengadaan/penawaran/inisiasi_ulang',
		data : {nomor_pengadaan : nomor_pengadaan},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}

</script>