<?php include_lang('pengadaan')?>
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
						<th><?php echo lang('_rks'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/rks/cetak/'.encode_id([$id_rks_pengadaan,rand()])); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<tr>
						<th><?php echo lang('detil_pengadaan'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan_v/detil_pengadaan?id_awz='.$id); ?>" class="cInfo"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<tr>
						<th><?php echo lang('dokumen_pendukung'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/aanwijzing/dokumen_rks/'.encode_id([$id_rks_pengadaan,rand()])); ?>" class="cInfo" aria-label="<?php echo lang('dokumen_pendukung'); ?>"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<tr>
						<th><?php echo lang('transkrip_obrolan'); ?></th>
						<td><?php 
							echo '[ <a href="javascript:;" id="btn-transkrip" data-id_chat="'.$id_chat.'" data-key="'.csrf_token(false,'return').'">'.lang('lihat_detil').'</a> ]';
						?></td>
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
						<th><?php echo lang('berita_acara'); ?></th>
						<td><?php 
						if($status_berita_acara) {
							echo '[ <a href="'.base_url('pengadaan/aanwijzing/cetak_berita_acara/'.encode_id([$id,rand()])).'" target="_blank">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						?></td>
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
							echo '[ <a href="'.base_url('pengadaan_v/aanwijzing_v/dokumen_rks/'.encode_id([$id_rks_aanwijzing,rand()])).'" class="cInfo" aria-label="'.lang('dokumen_pendukung').'">'.lang('lihat_detil').'</a> ]';
						} else echo '<i class="text-danger">[ '.lang('belum_tersedia').' ]</i>';
						?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
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
</script>