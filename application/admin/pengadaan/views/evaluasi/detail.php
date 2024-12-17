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
			<div class="card-header"><?php echo lang('evaluasi_penawaran'); ?></div>
			<div class="card-body">
				<?php 
					if($open_evaluasi) { ?>
					<?php if($menu_access['access_additional']) { ?>
					<?php if(!$jml_evaluasi) { ?>
					<div class="alert alert-warning">
						<div class="float-left">
							<?php echo lang('lbl_cek_skema_pembobotan'); ?>
						</div>
						<div class="float-right">
							<button type="button" class="btn btn-info" id="btn-pembobotan"><?php echo lang('pembobotan'); ?></button>
						</div>
						<div class="clearfix"></div>
					</div>
					<?php } elseif($jml_evaluasi != $jml_rekanan) { ?>
					<div class="alert alert-info"><?php echo lang('lbl_dihitung_jika_telah_diinput_semuanya'); ?></div>
					<?php } ?>
					<div class="table-responsive">
						<table class="table table-bordered table-detail">
							<thead>
								<tr>
									<th width="10"><?php echo lang('no'); ?></th>
									<th><?php echo lang('rekanan'); ?></th>
									<th width="50"><?php echo lang('penilaian_harga'); ?></th>
									<th width="50"><?php echo lang('penilaian_teknis'); ?></th>
									<th width="50"><?php echo lang('total_penilaian'); ?></th>
									<th width="150"><?php echo lang('status'); ?></th>
									<th width="10">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($rekanan as $k => $p) { ?>
								<tr>
									<td><?php echo ($k+1); ?></td>
									<td><a href="<?php echo base_url('pengadaan/evaluasi/detail_vendor/'.encode_id([$p[
									'id_vendor'],rand()])); ?>" class="cInfo" aria-label="<?php echo lang('data_rekanan'); ?>"><?php echo $p['nama_vendor']; ?></a></td>
									<td class="text-center"><?php echo c_percent($p['penilaian_harga']); ?></td>
									<td class="text-center"><?php echo c_percent($p['penilaian_teknis']); ?></td>
									<td class="text-center"><?php echo c_percent($p['total_penilaian']); ?></td>
									<td>
										<?php 
											if($p['is_penilaian']) {
												echo '<em class="text-success">['.lang('sudah_diinput').']</em>';
											} else {
												echo '<em class="text-danger">['.lang('belum_diinput').']</em>';
											}
										?>
									</td>
									<td>
										<?php if($stat_pengadaan == 'EVALUASI') { ?>
										<button type="button" class="btn btn-sm btn-icon-only btn-info btn-penilaian" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang('input_penilaian'); ?>" data-id="<?php echo $p['id_vendor']; ?>" data-pengajuan="<?php echo $nomor_pengajuan; ?>"><i class="fa-edit"></i></button>
										<?php } else echo '&nbsp;'; ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<?php } ?>
					<table class="table table-bordered table-detail mb-0">
						<tr>
							<th width="200"><?php echo lang('berita_acara_evaluasi_dokumen_penawaran'); ?></th>
							<td><?php
								if($tanggal_ba_evaluasi != '0000-00-00 00:00:00') {
									echo '[ <a href="'.base_url('pengadaan/evaluasi/berita_acara/'.encode_id([$id,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
								} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
								if($menu_access['access_additional'] && $open_evaluasi && $jml_evaluasi == $jml_rekanan && $stat_pengadaan == 'EVALUASI') {
									echo ' <a href="javascript:;" id="create-berita-acara" class="btn btn-info btn-sm ml-3">';
									echo $tanggal_ba_evaluasi != '0000-00-00 00:00:00' ? lang('ubah_berita_acara') : lang('buat_berita_acara');
									echo '</a>';
								}
							?></td>
						</tr>
						<tr>
							<th><?php echo lang('resume_evaluasi'); ?></th>
							<td><?php 
								if($jml_evaluasi == $jml_rekanan) {
									echo '[ <a href="'.base_url('pengadaan/evaluasi/resume/'.encode_id([$id,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
								} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
							?></td>
						</tr>
						<?php if($menu_access['access_additional']) { ?>
						<tr>
							<th><?php echo lang('transkrip_obrolan'); ?></th>
							<td><?php 
								echo '[ <a href="javascript:;" id="btn-transkrip" data-id_chat="'.$id_chat_evaluasi.'" data-key="'.csrf_token(false,'return').'">'.lang('lihat_detil').'</a> ]';
							?></td>
						</tr>
						<?php } ?>
					</table>
					<?php
				} else echo '<div class="alert alert-info">'.lang('jadwal_evaluasi_dokumen_penawaran_pada').' : <strong>'.$tanggal_penawaran.'</strong></div>';?>
			</div>
		</div>
		<?php if($menu_access['access_additional'] && $open_evaluasi && $stat_pengadaan == 'EVALUASI') { ?>
		<div class="card">
			<div class="card-header"><?php echo $tipe_pengadaan == 'Lelang' ? lang('peninjauan_lapangan') : lang('klarifikasi_dan_negosiasi'); ?></div>
			<div class="card-body">
				<?php if($tanggal_ba_evaluasi == '0000-00-00 00:00:00') {
					echo '<div class="alert alert-info">'.lang('form_peninjauan_belum_tersedia').'</div>';
				} else { 
					form_open(base_url('pengadaan/evaluasi/proses'),'post','form-lanjut','data-callback="reload" data-submit="ajax"');
						col_init(3,9);
						input('hidden','id','id_awz','',$id);
						select2(lang('metode_negosiasi'),'metode_negosiasi','required|infinity',['Negosiasi Satu Rekanan'=>lang('negosiasi_dengan_satu_rekanan'),'Negosiasi Semua Rekanan'=>lang('negosiasi_dengan_semua_rekanan_yang_lolos')],'_key');
						form_button(lang('proses'),false);
					form_close();
					?>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<?php
modal_open('modal-pembobotan',lang('pembobotan'),'modal-xl');
	modal_body();
		form_open(base_url('pengadaan/evaluasi/save_pembobotan'),'post','form-pembobotan','data-callback="reload" data-trigger="checkBobot"');
			col_init(3,3);
			input('hidden','_nomor_pengajuan','_nomor_pengajuan','',$nomor_pengajuan);
			input('percent',lang('bobot_harga'),'bobot_harga','required',c_percent($inisiasi->bobot_harga));
			input('percent',lang('bobot_teknis'),'bobot_teknis','required',c_percent($inisiasi->bobot_teknis));
			label(lang('detil_bobot_teknis'));
			?>
			<?php foreach($pembobotan[0] as $p) { ?>
			<div id="detilBobotTeknis">
				<div class="table-responsive" data-idx="<?php echo $p['id_persyaratan']; ?>">
					<table class="table table-bordered table-detail">
						<thead>
							<tr>
								<th colspan="3">
									<input type="hidden" name="idx[]" value="<?php echo $p['id_persyaratan']; ?>" />
									<input type="text" name="detil_bobot_keterangan[]" value="<?php echo $p['deskripsi']; ?>" autocomplete="off" class="form-control detil_bobot_keterangan" data-validation="required" />
								</th>
								<th width="250">
									<select class="form-control cara-hitung select2 infinity" data-width="100%" name="cara_perhitungan[]" data-validation="required">
										<option value="terbanyak"<?php if($p['tipe_rumus'] == 'terbanyak') echo ' selected'; ?>><?php echo lang('berdasarkan_poin_terbanyak'); ?></option>
										<option value="terendah"<?php if($p['tipe_rumus'] == 'terendah') echo ' selected'; ?>><?php echo lang('berdasarkan_poin_terendah'); ?></option>
										<option value="acuan"<?php if($p['tipe_rumus'] == 'acuan') echo ' selected'; ?>><?php echo lang('berdasarkan_acuan'); ?></option>
										<option value="range"<?php if($p['tipe_rumus'] == 'range') echo ' selected'; ?>><?php echo lang('berdasarkan_range_angka'); ?></option>
									</select>
								</th>
								<th width="150">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><?php echo lang('bobot'); ?></span>
										</div>
										<input type="text" name="detail_bobot[]" class="form-control percent detail_bobot" maxlength="6" autocomplete="off" value="<?php echo c_percent($p['bobot']); ?>" />
									</div>
								</th>
								<th width="50">&nbsp;</th>
							</tr>
							<tr class="header">
								<?php if($p['tipe_rumus'] != 'range') { ?>
								<th colspan="4"><?php echo $p['tipe_rumus'] == 'acuan' ? lang('acuan_nilai') : lang('poin_yang_dinilai'); ?></th>
								<?php } else { ?>
								<th colspan="2"><?php echo lang('batas_bawah'); ?></th>
								<th colspan="2" width="300"><?php echo lang('batas_atas'); ?></th>
								<?php } ?>
								<th><?php echo lang('bobot'); ?></th>
								<th>
									<button type="button" class="btn btn-success btn-sm btn-icon-only btn-add-bobot">
										<i class="fa-plus"></i>
									</button>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($pembobotan[$p['id']] as $p2) { ?>
								<tr>
									<?php if($p['tipe_rumus'] == 'range') { ?>
										<td colspan="2">
											<input type="text" name="child_batas_bawah[<?php echo $p['id_persyaratan']; ?>][]" class="form-control" data-validation="required|number" autocomplete="off" value="<?php echo $p2['batas_bawah']; ?>">
										</td>
										<td colspan="2">
											<input type="text" name="child_batas_atas[<?php echo $p['id_persyaratan']; ?>][]" class="form-control" data-validation="required|number" autocomplete="off" value="<?php echo $p2['batas_atas']; ?>">
										</td>
									<?php } else { ?>
										<td colspan="4">
											<input type="text" name="child_deskripsi[<?php echo $p['id_persyaratan']; ?>][]" class="form-control" data-validation="required" autocomplete="off" value="<?php echo $p2['deskripsi']; ?>">
										</td>
									<?php } ?>
									<td>
										<input type="text" name="child_bobot[<?php echo $p['id_persyaratan']; ?>][]" class="form-control percent child_bobot" data-validation="required" value="<?php echo c_percent($p2['bobot']); ?>" autocomplete="off" maxlength="6">
									</td>
									<td>
										<button type="button" class="btn btn-sm btn-danger btn-icon-only btn-remove-bobot"><i class="fa-times"></i></button>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<?php } ?>
			<?php
			form_button(lang('simpan'),lang('batal'));
		form_close();
modal_close();
modal_open('modal-penilaian',lang('input_penilaian'),'modal-xl');
	modal_body();
		form_open(base_url('pengadaan/evaluasi/save_penilaian'),'post','form-penilaian','data-callback="reload"');
			col_init(3,9);
			input('hidden','nomor_pengajuan','nomor_pengajuan','',$nomor_pengajuan);
			input('hidden','_nomor_pengadaan','_nomor_pengadaan','',$nomor_pengadaan);
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
		card_open(lang('input_penilaian'));
			input('text',lang('penawaran_harga'),'penawaran_harga','','','disabled');
			foreach($pembobotan[0] as $p) {
				if($p['tipe_rumus'] == 'terbanyak' || $p['tipe_rumus'] == 'terendah') {
					label($p['deskripsi']);
					sub_open(1);
					foreach($pembobotan[$p['id']] as $p2) {
						input('text',$p2['deskripsi'],'v_bobot['.$p2['id'].']','required|number');
					}
					sub_close();
				} elseif($p['tipe_rumus'] == 'range') {
					input('text',$p['deskripsi'],'v_bobot['.$p['id'].']','required|number','','data-prepend="'.lang('jumlah').'"');
				} else {
					$opt 	= [];
					foreach($pembobotan[$p['id']] as $p2) {
						$opt[]	= $p2['deskripsi'];
					}
					select2($p['deskripsi'],'v_bobot['.$p['id'].']','required|infinity',$opt);
				}
			}
			form_button(lang('simpan'),false);
		card_close();
		form_close();
modal_close();
modal_open('modal-berita-acara',lang('berita_acara'),'modal-xl');
	modal_body();
		form_open(base_url('pengadaan/penawaran/save_berita_acara'),'post','form-berita-acara','data-callback="reload"');
			col_init(3,9);
			input('hidden','id','id_aanwijzing','',$id);
			input('text',lang('nomor_berita_acara'),'nomor_ba_evaluasi','',$nomor_ba_evaluasi,'readonly data-readonly="true" placeholder="'.lang('otomatis_saat_disimpan').'"');
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required"><?php echo lang('tanggal'); ?></label>
				<div class="col-md-3">
					<input type="text" name="tanggal_ba_evaluasi" class="form-control dtp" placeholder="<?php echo lang('tanggal'); ?>" autocomplete="off" value="<?php echo c_date($tanggal_ba_evaluasi); ?>" data-validation="required">
				</div>
				<div class="col-md-6">
					<select id="zona_waktu_evaluasi" name="zona_waktu_evaluasi" class="form-control select2 infinity">
						<option value="WIB"<?php if($zona_waktu_evaluasi == 'WIB') echo ' selected'; ?>>WIB</option>
						<option value="WITA"<?php if($zona_waktu_evaluasi == 'WITA') echo ' selected'; ?>>WITA</option>
						<option value="WIT"<?php if($zona_waktu_evaluasi == 'WIT') echo ' selected'; ?>>WIT</option>
					</select>
				</div>
			</div>
			<?php
			textarea(lang('lokasi'),'lokasi_ba_evaluasi','required',$lokasi_ba_evaluasi);
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
		url : base_url + 'pengadaan/evaluasi/get_penilaian',
		data : {
			id_vendor : id_vendor,
			nomor_pengajuan : $(this).attr('data-pengajuan')
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
			$('#penawaran_harga').val(response.nilai_total_penawaran);
			$.each(response.bobot,function(k,v){
				$('#v_bobot'+v.id_pembobotan).val(v._value);
				if($('#v_bobot'+v.id_pembobotan).hasClass('select2')) {
					$('#v_bobot'+v.id_pembobotan).trigger('change');
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
	var nomor_pengajuan = $('#btn-batal').attr('data-pengajuan');
	$.ajax({
		url : base_url + 'pengadaan/penawaran/pembatalan',
		data : {nomor_pengajuan : nomor_pengajuan},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
$('#btn-pembobotan').click(function(){
	$('#modal-pembobotan').modal();
});
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
</script>