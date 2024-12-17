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
						<th><?php echo lang('metode_negosiasi'); ?></th>
						<td><?php echo $metode_negosiasi; ?></td>
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
			<div class="card-header"><?php echo lang('hasil_peninjauan_lapangan'); ?></div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-detail table-app">
						<thead>
							<tr>
								<th width="10"><?php echo lang('rangking'); ?></th>
								<th><?php echo lang('rekanan'); ?></th>
								<th><?php echo lang('surat_tugas'); ?></th>
								<th><?php echo lang('daftar_hasil_peninjauan_lapangan'); ?></th>
								<th><?php echo lang('berita_acara_peninjauan_lapangan'); ?></th>
								<th><?php echo lang('kesimpulan'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($rekanan as $k => $p) { ?>
							<tr>
								<td class="text-center"><?php echo $p['rank_evaluasi']; ?></td>
								<td><a href="<?php echo base_url('pengadaan/peninjauan_lapangan/detail_vendor/'.encode_id([$p[
								'id_vendor'],rand()])); ?>" class="cInfo" aria-label="<?php echo lang('data_rekanan'); ?>"><?php echo $p['nama_vendor']; ?></a></td>
								<td><?php
									if($p['tanggal_peninjauan'] == '0000-00-00') {
										echo '<em class="text-danger">[ '.lang('tidak_tersedia').' ]</em>';
									} else {
										echo '[ <a href="'.base_url('pengadaan/peninjauan_lapangan/surat_tugas/'.encode_id($p['id'])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
									}
								?></td>
								<td><?php
									if(!$p['status_peninjauan']) {
										echo '<em class="text-danger">[ '.lang('tidak_tersedia').' ]</em>';
									} else {
										echo '[ <a href="'.base_url('pengadaan/peninjauan_lapangan/data_pendukung/'.encode_id($p['id'])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
									}
								?></td>
								<td><?php
									if(!$p['status_peninjauan']) {
										echo '<em class="text-danger">[ '.lang('tidak_tersedia').' ]</em>';
									} else {
										echo '[ <a href="'.base_url('pengadaan/peninjauan_lapangan/laporan_peninjauan/'.encode_id($p['id'])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
									}
								?></td>
								<td><?php
								if($p['status_peninjauan'] == 1) echo '<span class="text-success">'.lang('layak').'</span>';
								else if($p['status_peninjauan'] == 9) echo '<span class="text-danger">'.lang('tidak_layak').'</span>';
								?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php if($menu_access['access_additional'] && $stat_pengadaan == 'PENINJAUAN') { ?>
		<div class="card mt-2">
			<div class="card-header"><?php echo lang('klarifikasi_dan_negosiasi'); ?></div>
			<div class="card-body">
				<?php if($jml_penugasan == 0) {
					echo '<div class="alert alert-info">'.lang('form_klarifikasi_akan_muncul_jika_sudah_ada_laporan_peninjauan_lapangan').'</div>';
				} elseif($jml_penugasan != $jml_laporan) { 
					echo '<div class="alert alert-info">'.lang('form_klarifikasi_akan_muncul_jika_semua_tugas_peninjauan_lapangan_sudah_dilaksanakan').'</div>';
				} elseif($jml_layak == 0) {
					echo '<div class="alert alert-danger">'.lang('pengadaan_tidak_dapat_dilanjutkan').'</div>';
					echo '<button type="button" class="btn btn-danger" id="btn-batal" data-pengadaan="'.$nomor_pengadaan.'"><i class="fa-times"></i>'.lang('pengadaan_dibatalkan').'</button>';
				} else {
					form_open(base_url('pengadaan/peninjauan_lapangan/save'),'post','form-peninjauan','data-submit="ajax" data-callback="reload" data-id="'.$id.'"');
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
				} ?>
			</div>
		</div>
		<?php } if($stat_pengadaan == 'BATAL' && $last_pos == 'PENINJAUAN' && $menu_access['access_additional']) { ?>
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
<script type="text/javascript">
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
		serviceUrl: base_url + 'pengadaan/peninjauan_lapangan/get_user/' + $('#form-peninjauan').attr('data-id'),
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
		url : base_url + 'pengadaan/peninjauan_lapangan/pembatalan',
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