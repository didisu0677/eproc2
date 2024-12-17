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
				<div class="table-responsive">
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
							<th><?php echo lang('keterangan_pengadaan'); ?></th>
							<td><?php echo $keterangan_pengadaan; ?></td>
						</tr>
						<tr>
							<th><?php echo lang('_rks'); ?></th>
							<td>[ <a href="<?php echo base_url('pengadaan/rks/cetak/'.encode_id([$id_rks,rand()])); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
						</tr>
						<tr>
							<th><?php echo lang('dokumen_pendukung'); ?></th>
							<td>[ <a href="<?php echo base_url('pengadaan/pendaftaran_pengadaan/dokumen/'.encode_id([$id_rks,rand()])); ?>" class="cInfo" aria-label="<?php echo lang('dokumen_pendukung'); ?>"><?php echo lang('lihat_detil'); ?></a> ]</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header"><?php echo lang('proses_aanwijzing'); ?></div>
			<div class="card-body">
				<?php 
				if($status_pengadaan != 'BATAL') {
					if($open_pendaftaran) {
						form_open(base_url('pengadaan/pendaftaran_pengadaan/save'),'post','form','data-submit="ajax" data-callback="toList" data-id="'.$id.'"'); ?>
							<div class="mb-2"><strong><?php echo $tipe_pengadaan == 'Lelang' ? lang('rekanan_yang_daftar') : lang('rekanan_yang_diundang'); ?> : </strong></div>
							<div class="table-responsive">
								<table class="table table-bordered table-detail" id="table-vendor" data-min="<?php 
									if($tipe_pengadaan == 'Lelang') echo setting('min_memasukan_lelang');
									else if($tipe_pengadaan == 'Pemilihan Langsung') echo setting('min_memasukan_pemilihan_langsung');
									else echo setting('min_memasukan_penunjukan_langsung'); ?>">
									<tr>
										<th>&nbsp;</th>
										<th><?php echo lang('rekanan'); ?></th>
										<th><?php echo lang('dokumen_persyaratan'); ?></th>
										<th><?php echo lang('pesan'); ?></th>
									</tr>
									<?php foreach($bidder as $b) { ?>
									<tr>
										<td width="10">
											<div class="custom-checkbox custom-control custom-control-inline mr-0">
												<input class="custom-control-input bidder" id="id_vendor<?php echo $b->id_vendor; ?>" type="checkbox" name="id_vendor[]" value="<?php echo $b->id_vendor; ?>"<?php if(!$b->is_submit) echo ' disabled'; ?><?php if(isset($vendor_peserta[$b->id_vendor])) echo ' checked'; ?>>
												<label class="custom-control-label" for="id_vendor<?php echo $b->id_vendor; ?>">&nbsp;</label>
											</div>
										</td>
										<td><a href="<?php echo base_url('pengadaan/pendaftaran_pengadaan/detail_vendor/'.encode_id([$b->id_vendor,rand()])); ?>" class="cInfo"><?php echo $b->nama_vendor; ?></a></td>
										<td>
											<?php 
											if($b->file_persyaratan) {
												$file = json_decode($b->file_persyaratan,true);
												foreach($file as $password => $fl) { ?>
													<a href="<?php echo base_url(dir_upload('dokumen_rekanan').$fl); ?>" target="_blank" data-toggle="tooltip" title="<?php echo lang('kata_sandi').' : '.$password; ?>" data-placement="right"><i class="fa-file-archieve"></i> <?php echo $fl; ?></a>
												<?php }
											} ?>
										</td>
										<td><?php 
											if($b->is_submit) echo $b->pesan; 
											else echo '<i class="text-danger">['.lang('belum_konfirmasi').']</i>';
										?></td>
									</tr>
									<?php } ?>
								</table>
							</div>
							<div id="form-lanjut">
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
										<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota" value="<?php echo $nama_peserta_lain; ?>">
										<input type="hidden" name="id_anggota[]" class="id_anggota" value="<?php echo $id_peserta_lain; ?>">
									</div>
									<div class="col-sm-2 col-3">
										<button type="button" class="btn btn-block btn-success btn-icon-only btn-add-anggota"><i class="fa-plus"></i></button>
									</div>
								</div>
								<div id="additional-anggota" class="mb-2">
									<?php foreach($peserta_lain as $k => $v) { ?>
									<div class="form-group row">
										<div class="offset-sm-3 col-sm-7 col-9">
											<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota" value="<?php echo $v; ?>">
											<input type="hidden" name="id_anggota[]" class="id_anggota" value="<?php echo $k; ?>">
										</div>
										<div class="col-sm-2 col-3">
											<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>
										</div>
									</div>
									<?php } ?>
								</div>
								<?php
								col_init(0,12);
								input('hidden','id_pengadaan','id_pengadaan','',$id);
								form_button(lang('proses_aanwijzing'),false);
								?>
							</div>
							<div id="form-batal">
								<div class="alert alert-warning">
									<strong><?php echo lang('metode_pengadaan').' : '.$metode_pengadaan;?></strong><br />
									<?php echo lang('minimal_rekanan_yang_melanjutkan_keproses_aanwijzing').' : ';
										if($tipe_pengadaan == 'Lelang') echo setting('min_memasukan_lelang');
										else if($tipe_pengadaan == 'Pemilihan Langsung') echo setting('min_memasukan_pemilihan_langsung');
										else echo setting('min_memasukan_penunjukan_langsung');
									?>
								</div>
								<button type="button" class="btn btn-danger" id="btn-batal" data-pengadaan="<?php echo $nomor_pengadaan; ?>"><i class="fa-times"></i><?php echo lang('pengadaan_dibatalkan'); ?></button>
							</div>
							<?php
						form_close();
					} else { ?>
						<div class="alert alert-info"><?php echo lang('pendaftaran_pengadaan_dibuka_pada_tanggal').' <strong>'.$tanggal_pendaftaran.'</strong>'; ?></div>
					<?php }
				} else if($inisiasi_ulang == 0) { ?>
					<div class="alert alert-danger">
						<?php echo lang('pengadaan_telah_dibatalkan'); ?>
					</div>
					<button type="button" class="btn btn-secondary" id="btn-reinisiasi" data-pengadaan="<?php echo $nomor_pengadaan; ?>"><i class="fa-sync"></i><?php echo lang('inisiasi_ulang'); ?></button>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function toList() {
	window.location = base_url + 'pengadaan/pendaftaran_pengadaan';
}
$(document).ready(function(){
	if($('.bidder:checked').length >= toNumber($('#table-vendor').attr('data-min'))) {
		$('#form-lanjut').show();
		$('#form-batal').hide();
	} else {
		$('#form-lanjut').hide();
		$('#form-batal').show();
	}
});
$('.bidder').click(function(){
	if($('.bidder:checked').length >= toNumber($('#table-vendor').attr('data-min'))) {
		$('#form-lanjut').show();
		$('#form-batal').hide();
	} else {
		$('#form-lanjut').hide();
		$('#form-batal').show();
	}
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
		serviceUrl: base_url + 'pengadaan/pendaftaran_pengadaan/get_user/' + $('#form').attr('data-id'),
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
		url : base_url + 'pengadaan/pendaftaran_pengadaan/pembatalan',
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
		url : base_url + 'pengadaan/pendaftaran_pengadaan/inisiasi_ulang',
		data : {nomor_pengadaan : nomor_pengadaan},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'toList');
		}
	});
}
</script>