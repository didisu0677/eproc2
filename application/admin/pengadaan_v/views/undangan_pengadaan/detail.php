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
						<th><?php echo lang('_rks'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/rks/cetak/'.encode_id([$id_rks,rand()])); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<tr>
						<th><?php echo lang('_spph'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan_v/undangan_pengadaan/spph/'.encode_id([$id,rand()])); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<tr>
						<th><?php echo lang('detil_pengadaan'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan_v/detil_pengadaan/'.$id_pengadaan); ?>" class="cInfo"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<tr>
						<th><?php echo lang('dokumen_pendukung'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan_v/undangan_pengadaan/dokumen/'.encode_id([$id_rks,rand()])); ?>" class="cInfo" aria-label="<?php echo lang('dokumen_pendukung'); ?>"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<tr>
						<th><?php echo lang('rekanan_yang_diundang'); ?></th>
						<td>
							<ul class="pl-2 mb-0">
								<?php 
								foreach($bidder as $b) { 
									if($b->id_vendor == user('id_vendor')) {
										echo '<li><strong>'.$b->nama_vendor.'</strong></li>';
									} else {
										echo '<li>'.$b->nama_vendor.'</li>';
									}
								} 
								?>
							</ul>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="card">
			<div class="card-header"><?php echo lang('konfirmasi_pengadaan'); ?></div>
			<div class="card-body">
				<?php
				if($open_pendaftaran) {
					if($is_submit) {
					?>
					<div class="alert alert-info"><?php echo lang('msg_sudah_daftar'); ?></div>
					<button type="button" data-id="<?php echo $id; ?>" class="btn btn-danger" id="btn-batal"><i class="fa-times"></i> <?php echo lang('batalkan_pendaftaran'); ?></button>
					<?php
					} else {
						form_open(base_url('pengadaan_v/undangan_pengadaan/save'),'post','form','data-submit="ajax" data-callback="toList" data-trigger="checkSetuju"');
							col_init(2,10);
							input('hidden','id','id','',$id);
							fileupload(lang('dokumen_persyaratan').' (*.zip)','file_persyaratan','required','data-accept="zip"');
							textarea(lang('pesan'),'pesan');
							?>
							<div class="form-group row mb-3">
								<div class="col-sm-10 offset-sm-2">
									<div class="custom-checkbox custom-control custom-control-inline">
										<input class="custom-control-input" type="checkbox" id="setuju" name="setuju">
										<label class="custom-control-label" for="setuju"><?php echo lang('desc_setuju_tender'); ?></label>
									</div>
								</div>
							</div>
							<?php
							form_button(lang('konfirmasi'),false);
						form_close();
					}
				} else { ?>
					<div class="alert alert-info"><?php echo lang('pendaftaran_pengadaan_dibuka_pada_tanggal').' <strong>'.$tanggal_pendaftaran.'</strong>'; ?></div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function toList() {
	window.location = base_url + 'pengadaan_v/undangan_pengadaan';
}
function batalDaftar() {
	var id_remove = $('#btn-batal').attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan_v/undangan_pengadaan/unreg',
		data : {id: id_remove},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','toList');
		}
	});
}
$('#setuju').click(function(){
	if($(this).is(':checked')) {
		$('button[type="submit"]').removeAttr('disabled');
	} else {
		$('button[type="submit"]').attr('disabled',true);
	}
});
$('#btn-batal').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','batalDaftar');
});
function checkSetuju() {
	var res = true;
	if(!$('#setuju').is(':checked')) {
		res = false;
		cAlert.open(lang.anda_harus_menyetujui_syarat_dan_ketentuan_proses_lelang)
	}

	return res;
}
</script>