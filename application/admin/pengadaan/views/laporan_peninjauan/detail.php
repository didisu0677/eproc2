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
			<div class="card-header"><?php echo lang('penugasan'); ?></div>
			<div class="card-body">
				<table class="table table-bordered table-detail mb-0">
					<tr>
						<th width="250"><?php echo lang('nomor_surat_tugas'); ?></th>
						<td><?php echo $nomor_surat_tugas.' <span class="ml-2">[ <a href="'.base_url('pengadaan/peninjauan_lapangan/surat_tugas/'.encode_id($id)).'" target="_blank">'.lang('lihat_detil').'</a> ]</span>'; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('tanggal_pelaksanaan'); ?></th>
						<td><?php echo c_date($tanggal_peninjauan); ?></td>
					</tr>
					<tr>
						<th><?php echo lang('perusahaan_yang_dikunjungi'); ?></th>
						<td><?php echo $nama_vendor; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('alamat'); ?></th>
						<td><?php echo $alamat_vendor; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('daftar_hasil_peninjauan_lapangan'); ?></th>
						<td><?php if($status_peninjauan == 0) {
							echo '<span class="text-danger mr-3">[ '.lang('belum_tersedia').' ]</span> <span>[ <a href="'.base_url('pengadaan/laporan_peninjauan/template_data_pendukung/'.encode_id($id)).'" target="_blank">'.lang('lihat_templat').'</a> ]</span>';
						} else {
							echo '<span>[ <a href="'.base_url('pengadaan/peninjauan_lapangan/data_pendukung/'.encode_id($id)).'" target="_blank">'.lang('lihat_detil').'</a> ]</span>';
						} ?></td>
					</tr>
					<tr>
						<th><?php echo lang('berita_acara_peninjauan_lapangan'); ?></th>
						<td><?php if($status_peninjauan == 0) {
							echo '<span class="text-danger mr-3">[ '.lang('belum_tersedia').' ]</span> <span>[ <a href="'.base_url('pengadaan/laporan_peninjauan/template_laporan_peninjauan/'.encode_id($id)).'" target="_blank">'.lang('lihat_templat').'</a> ]</span>';
						} else {
							echo '<span>[ <a href="'.base_url('pengadaan/peninjauan_lapangan/laporan_peninjauan/'.encode_id($id)).'" target="_blank">'.lang('lihat_detil').'</a> ]</span>';
						} ?></td>
					</tr>
					<?php if($stat_pengadaan == 'PENINJAUAN' && !$is_proses && !$close_penugasan) { ?>
					<tr>
						<th>&nbsp;</th>
						<td>
							<button type="button" class="btn btn-sm btn-info" id="btn-laporan"><?php echo $status_peninjauan == 0 ? lang('buat_laporan') : lang('ubah_laporan'); ?></button>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
		<div class="card">
			<div class="card-header"><?php echo lang('tim_peninjauan'); ?></div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-detail table-app">
						<thead>
							<tr>
								<th width="10"><?php echo lang('no'); ?></th>
								<th><?php echo lang('nama'); ?></th>
								<th><?php echo lang('jabatan'); ?></th>
								<th><?php echo lang('unit_kerja'); ?></th>
								<th><?php echo lang('keterangan'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($tim as $k => $p) { ?>
							<tr>
								<td class="text-center"><?php echo ($k+1); ?></td>
								<td><?php echo $p->nama_user; ?></td>
								<td><?php echo $p->jabatan_user; ?></td>
								<td><?php echo $p->unit_kerja_user; ?></td>
								<td><?php echo $p->posisi; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
modal_open('modal-laporan',lang('laporan'),'modal-xl');
	modal_body();
		$_d1 	= json_decode($data_pendukung,true);
		$_d2 	= json_decode($hasil_peninjauan,true);
		form_open(base_url('pengadaan/laporan_peninjauan/save'),'post','form-penugasan','data-callback="reload"');
			col_init(3,9);
			input('hidden','id','id','',$id);
			input('text',lang('nama_perusahaan'),'nama_vendor','',$nama_vendor,'disabled');
			input('text',lang('alamat_perusahaan'),'alamat_vendor','',$alamat_vendor,'disabled');
			input('date',lang('tanggal_pelaksanaan'),'tanggal_pelaksanaan','required',c_date($tanggal_pelaksanaan));
			?>
			<div class="table-responsive mb-3">
				<table class="table table-app table-bordered table-detail">
					<thead>
						<tr><th colspan="5"><?php echo lang('data_pendukung'); ?></th>
						<tr>
							<th colspan="2"><?php echo lang('dokumen_pendukung'); ?></th>
							<th width="200"><?php echo lang('detil'); ?></th>
							<th width="150"><?php echo lang('kelengkapan'); ?></th>
							<th width="400"><?php echo lang('keterangan'); ?></th>
						</tr>
					</thead>
					<tbody id="d1">
						<?php foreach($template2 as $t) { 
							$deskripsi 		= '';
							$detil 			= '';
							$kelengkapan 	= '';
							$keterangan 	= '';
							if(isset($_d1[$t['id']])) {
								$deskripsi 		= $_d1[$t['id']]['deskripsi'];
								$detil 			= $_d1[$t['id']]['detil'];
								$kelengkapan 	= $_d1[$t['id']]['kelengkapan'];
								$keterangan 	= $_d1[$t['id']]['keterangan'];
							}
							echo '<tr>';
							echo '<td colspan="2"><input type="hidden" name="deskripsi['.$t['id'].']" value="'.$t['deskripsi'].'">'.$t['deskripsi'].'</td>';
							echo '<td>';
							if($t['nomor']) {
								$nomor 	= str_replace('Nomor ', '', $detil);
								echo '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">'.lang('nomor').'</span></div><input type="text" name="nomor['.$t['id'].']" class="form-control" autocomplete="off" value="'.$nomor.'"></div>';
							} else {
								$pilihan = $t['pilihan'] ? json_decode($t['pilihan'],true) : [];
								if(count($pilihan) > 0) {
									echo '<select class="form-control select2 infinity" data-width="100%" name="detil['.$t['id'].']">';
									echo '<option value=""></option>';
									foreach($pilihan as $p) {
										if($p == $detil) 
											echo '<option value="'.$p.'" selected>'.$p.'</option>';
										else 
											echo '<option value="'.$p.'">'.$p.'</option>';
									}
									echo '</select>';
								}
							}
							echo '</td>';
							echo '<td><select class="form-control select2 infinity" data-width="100%" name="kelengkapan['.$t['id'].']">';
							echo '<option value=""></option>';
							foreach(['Ada'=>lang('ada'),'Tidak Ada'=>lang('tidak_ada')] AS $x => $y) {
								if($x == $kelengkapan)
									echo '<option value="'.$x.'" selected>'.$y.'</option>';
								else 
									echo '<option value="'.$x.'">'.$y.'</option>';
							}
							echo '</select></td>';
							echo '<td><input type="text" name="keterangan['.$t['id'].']" class="form-control" autocomplete="off" value="'.$keterangan.'"></td>';
							echo '</tr>';
						} ?>
						<tr>
							<td width="10"><button type="button" class="btn btn-sm btn-success btn-icon-only btn-add-pendukung"><i class="fa-plus"></i></button></td>
							<td colspan="4"><?php echo lang('lain_lain'); ?></td>
						</tr>
						<?php if(isset($_d1['lain'])) { foreach($_d1['lain'] as $dl) { ?>
						<tr>
							<td>
								<button type="button" class="btn btn-sm btn-danger btn-remove btn-icon-only"><i class="fa-times"></i></button>
							</td>
							<td>
								<input type="text" class="form-control" name="deskripsi_lain[]" autocomplete="off" data-validation="required" value="<?php echo $dl['deskripsi']; ?>">
							</td>
							<td>
								<input type="text" class="form-control" name="detil_lain[]" autocomplete="off" value="<?php echo $dl['detil']; ?>">
							</td>
							<td>
								<select class="form-control select2 infinity" data-width="100%" name="kelengkapan_lain[]" data-validation="required">
									<option value=""></option>
									<option value="Ada"<?php if($dl['kelengkapan'] == 'Ada') echo ' selected'; ?>><?php echo lang('ada'); ?></option>
									<option value="Tidak Ada"<?php if($dl['kelengkapan'] == 'Tidak Ada') echo ' selected'; ?>><?php echo lang('tidak_ada'); ?></option>
								</select>
							</td>
							<td>
								<input type="text" class="form-control" name="keterangan_lain[]" autocomplete="off" value="<?php echo $dl['keterangan']; ?>">
							</td>
						</tr>
						<?php }} ?>
					</tbody>
				</table>
			</div>
			<div class="table-responsive mb-2">
				<table class="table table-app table-bordered table-detail">
					<thead>
						<tr><th colspan="4"><?php echo lang('hasil_peninjauan_lapangan'); ?></th>
						<tr>
							<th colspan="2"><?php echo lang('aspek_yang_dinilai'); ?></th>
							<th width="200"><?php echo lang('kondisi'); ?></th>
							<th width="400"><?php echo lang('keterangan'); ?></th>
						</tr>
					</thead>
					<tbody id="d2">
						<?php foreach($template1 as $t) { 
							$deskripsi 		= '';
							$kondisi 		= '';
							$keterangan 	= '';
							if(isset($_d2[$t['id']])) {
								$deskripsi 		= $_d2[$t['id']]['deskripsi'];
								$kondisi 		= $_d2[$t['id']]['kondisi'];
								$keterangan 	= $_d2[$t['id']]['keterangan'];
							}

							echo '<tr>';
							echo '<td colspan="2"><input type="hidden" name="deskripsi1['.$t['id'].']" value="'.$t['deskripsi'].'">'.$t['deskripsi'].'</td>';
							echo '<td><select class="form-control select2 infinity" data-width="100%" name="kondisi['.$t['id'].']">';
							echo '<option value=""></option>';
							foreach(['Sesuai / Layak'=>lang('sesuai_layak'),'Tidak'=>lang('tidak')] AS $x => $y) {
								if($x == $kondisi)
									echo '<option value="'.$x.'" selected>'.$y.'</option>';
								else 
									echo '<option value="'.$x.'">'.$y.'</option>';
							}
							echo '</select></td>';
							echo '<td><input type="text" name="keterangan1['.$t['id'].']" class="form-control" autocomplete="off" value="'.$keterangan.'"></td>';
							echo '</tr>';
						} ?>
						<tr>
							<td width="10"><button type="button" class="btn btn-sm btn-success btn-icon-only btn-add-aspek"><i class="fa-plus"></i></button></td>
							<td colspan="3"><?php echo lang('lain_lain'); ?></td>
						</tr>
						<?php if(isset($_d2['lain'])) { foreach($_d2['lain'] as $dl) { ?>
						<tr>
							<td>
								<button type="button" class="btn btn-sm btn-danger btn-remove btn-icon-only"><i class="fa-times"></i></button>
							</td>
							<td>
								<input type="text" class="form-control" name="deskripsi1_lain[]" autocomplete="off" data-validation="required" value="<?php echo $dl['deskripsi']; ?>">
							</td>
							<td>
								<select class="form-control select2 infinity" data-width="100%" name="kondisi_lain[]" data-validation="required">
									<option value=""></option>
									<option value="Sesuai / Layak"<?php if($dl['kondisi'] == 'Sesuai / Layak') echo ' selected'; ?>><?php echo lang('sesuai_layak'); ?></option>
									<option value="Tidak"<?php if($dl['kondisi'] == 'Tidak') echo ' selected'; ?>><?php echo lang('tidak'); ?></option>
								</select>
							</td>
							<td>
								<input type="text" class="form-control" name="keterangan1_lain[]" autocomplete="off" value="<?php echo $dl['keterangan']; ?>">
							</td>
						</tr>
						<?php }} ?>
					</tbody>
				</table>
			</div>
			<?php
			select2(lang('kesimpulan'),'status_peninjauan','required|infinity',[1=>lang('layak'),9=>lang('tidak_layak')],'_key','',$status_peninjauan);
			form_button(lang('simpan'),lang('batal'));
		form_close();
modal_close();
?>
<script type="text/javascript">
$('#btn-laporan').click(function(){
	$('#modal-laporan').modal();
});
$('.btn-add-pendukung').click(function(){
	var konten = '<tr>'
		+ '<td><button type="button" class="btn btn-sm btn-danger btn-remove btn-icon-only"><i class="fa-times"></i></button></td>'
		+ '<td><input type="text" class="form-control" name="deskripsi_lain[]" autocomplete="off" data-validation="required"></td>'
		+ '<td><input type="text" class="form-control" name="detil_lain[]" autocomplete="off"></td>'
		+ '<td><select class="form-control select2 infinity" data-width="100%" name="kelengkapan_lain[]" data-validation="required">'
			+ '<option value=""></option>'
			+ '<option value="Ada">'+lang.ada+'</option>'
			+ '<option value="Tidak Ada">'+lang.tidak_ada+'</option>'
		+ '</select></td>'
		+ '<td><input type="text" class="form-control" name="keterangan_lain[]" autocomplete="off"></td>'
	+ '</tr>';
	$('#d1').append(konten);
	$('#d1 tr').last().find('select').select2({
		placeholder: '',
		minimumResultsForSearch: Infinity,
		dropdownParent : $('#d1 tr').last().find('select').parent(),
		width: '100%'
	});
});
$('.btn-add-aspek').click(function(){
	var konten = '<tr>'
		+ '<td><button type="button" class="btn btn-sm btn-danger btn-remove btn-icon-only"><i class="fa-times"></i></button></td>'
		+ '<td><input type="text" class="form-control" name="deskripsi1_lain[]" autocomplete="off" data-validation="required"></td>'
		+ '<td><select class="form-control select2 infinity" data-width="100%" name="kondisi_lain[]" data-validation="required">'
			+ '<option value=""></option>'
			+ '<option value="Sesuai / Layak">'+lang.sesuai_layak+'</option>'
			+ '<option value="Tidak">'+lang.tidak+'</option>'
		+ '</select></td>'
		+ '<td><input type="text" class="form-control" name="keterangan1_lain[]" autocomplete="off"></td>'
	+ '</tr>';
	$('#d2').append(konten);
	$('#d2 tr').last().find('select').select2({
		placeholder: '',
		minimumResultsForSearch: Infinity,
		dropdownParent : $('#d2 tr').last().find('select').parent(),
		width: '100%'
	});
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
});
</script>