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
			<div class="card-header"><?php echo lang('pemenang'); ?></div>
			<div class="card-body">
				<table class="table table-bordered table-detail mb-0">
					<tr>
						<th width="200"><?php echo lang('nama_rekanan'); ?></th>
						<td><?php echo $nama_vendor; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('alamat_rekanan'); ?></th>
						<td><?php echo $alamat_vendor; ?></td>
					</tr>
					<tr>
						<th><?php echo lang('nilai_pekerjaan'); ?></th>
						<td><?php echo custom_format($penawaran_terakhir); ?></td>
					</tr>
					<tr>
						<th><?php echo lang('surat_persetujuan'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/penetapan_pemenang/surat_persetujuan/'.encode_id($id)); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<tr>
						<th><?php echo lang('pengumuman_penetapan'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/penetapan_pemenang/cetak/'.encode_id($id)); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="card">
			<div class="card-header"><?php echo lang('sanggahan_peserta_pengadaan'); ?></div>
			<div class="card-body">
				<div class="table-responsive mb-3">
					<table class="table table-bordered table-app table-detail">
						<thead>
							<tr>
								<th><?php echo lang('nama_rekanan'); ?></th>
								<th><?php echo lang('status'); ?></th>
								<th width="30">&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($vendor as $v) { ?>
							<tr>
								<td><?php echo $v->nama_vendor; ?></td>
								<td><?php 
									if($v->pesan && !$v->jawaban) echo '<em class="text-danger">[ '.lang('belum_dijawab').' ]</em>';
									else if($v->pesan && $v->jawaban) echo '<em class="text-success">[ '.lang('sudah_dijawab').' ]</em>';
								?></td>
								<td><?php 
									if($v->pesan) {
										echo '<button type="button" class="btn btn-sm btn-icon-only btn-info btn-jawab" data-id="'.$v->id.'" title="'.lang('jawab').'" data-toggle="tooltip"><i class="fa-edit"></i></button>';
									}
								?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="font-weight-bold mb-2"><?php echo lang('kesimpulan'); ?></div>
				<?php 
				if(!$status_sanggah) {
					if($close_sanggah) { 
						if($sudah_dijawab) {
						?>
						<div id="button-section">
							<button type="button" class="btn btn-success" id="btn-proses" data-id="<?php echo $id; ?>"><?php echo lang('proses'); ?></button>
							<button type="button" class="btn btn-danger" id="btn-batal" data-id="<?php echo $id; ?>"><?php echo lang('batalkan_pengadaan'); ?></button>
						</div>
						<div id="form-section" class="hidden">
							<?php
							form_open(base_url('pengadaan/sanggah/proses'),'post','form-spk','data-submit="ajax" data-callback="reload"');
								col_init(2,4);
								input('hidden','id','id','',$id);
								input('date',lang('tanggal_spk'),'tanggal_spk','required');
								input('date',lang('tanggal_jatuh_tempo'),'tanggal_jatuh_tempo_spk','required');
								select2(lang('kelompok_pembelian'),'kelompok_pembelian','required',$kelompok_pembelian,'kode','kelompok_pembelian');
								toggle(lang('ada_kontrak').'?','is_kontrak',false,$chk_kontrak);
								input('date',lang('tanggal_po'),'tanggal_po','required');
								form_button(lang('simpan'),lang('batal'));
							form_close();
							?>
						</div>
						<?php
						} else alert(lang('terdapat_sanggahan_yang_belum_dijawab'));
					} else alert(lang('masa_sanggah_belum_berakhir'));
				} else {
					echo $status_sanggah == 1 ? lang('diproses') : lang('dibatalkan');
					if($status_sanggah == 9) {
						if($inisiasi_ulang) {
							alert(lang('pengadaan_telah_dibatalkan_dan_diinisiasi_ulang'),'warning');
						} else {
							?>
							<button type="button" class="btn btn-secondary" id="btn-reinisiasi" data-pengadaan="<?php echo $nomor_pengadaan; ?>"><i class="fa-sync"></i><?php echo lang('inisiasi_ulang'); ?></button>
							<?php
						}
					}
				} ?>
			</div>
		</div>
	</div>
</div>
<?php
modal_open('modal-jawab',lang('sanggah'),'modal-lg');
	modal_body();
		?>
		<table class="table table-bordered table-detail" id="table-info"></table>
		<?php
		if($status_sanggah == 0) {
			form_open(base_url('pengadaan/sanggah/save_jawab'),'post','form-jawab','data-callback="reload"');
				col_init(3,9);
				input('hidden','id','id');
				textarea(lang('jawaban'),'jawaban','required');
				fileupload(lang('file_jawaban').' (*.zip)','file_jawaban','required','data-accept="zip"');
				form_button(lang('simpan'),lang('batal'));
			form_close();
		}
modal_close();
?>
<script type="text/javascript">
$(document).on('click','.btn-jawab',function(){
	var id = $(this).attr('data-id');
	$.ajax({
		url 	: base_url + 'pengadaan/sanggah/get_data',
		data 	: {id:id},
		type 	: 'post',
		dataType: 'json',
		success : function(r){
			$('#id').val(id);
			var konten = '<tr><th width="200">' + lang.pesan + '</th><td>'+r.pesan+'</td></tr>';
			konten += '<tr><th>' + lang.file_pendukung + '</th><td>';
			if(r.file_pendukung == '') konten += '-';
			else {
				konten += '[ <a href="'+base_url+'assets/uploads/sanggah/'+r.file_pendukung+'" target="_blank">'+lang.unduh+'</a> ]';
			}
			konten += '</td></tr>';
			if(r.jawaban != '') {
				konten += '<tr><th>' + lang.jawaban + '</th><td>'+r.jawaban+'</td></tr>';
				konten += '<tr><th>' + lang.file_jawaban + '</th><td>';
				if(r.file_jawaban == '') konten += '-';
				else {
					konten += '[ <a href="'+base_url+'assets/uploads/sanggah/'+r.file_jawaban+'" target="_blank">'+lang.unduh+'</a> ]';
				}
			}
			$('#table-info').html(konten);
			$('#modal-jawab').modal();
		}
	});
});
$('#btn-batal').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','prosesBatal');
});
function prosesBatal() {
	var id = $('#btn-proses').attr('data-id');
	$.ajax({
		url 	: base_url + 'pengadaan/sanggah/pembatalan',
		data 	: {id:id},
		type 	: 'post',
		dataType: 'json',
		success : function(r){
			cAlert.open(r.message,r.status,'reload');
		}
	});
}
$('#btn-proses').click(function(){
	$('#form-section').removeClass('hidden');
	$('#button-section').addClass('hidden');
});
$('#form-spk button[type="reset"]').click(function(){
	$('#form-section').addClass('hidden');
	$('#button-section').removeClass('hidden');
	$('#is_kontrak').trigger('click');
});
$('#is_kontrak').click(function(){
	if($(this).is(':checked')) {
		$('#tanggal_po').closest('.row').addClass('hidden');
		$('#tanggal_po').val('');
		$('#tanggal_po').removeAttr('data-validation');
	} else {
		$('#tanggal_po').closest('.row').removeClass('hidden');
		$('#tanggal_po').addClass('data-validation','required');		
	}
});
$('#btn-reinisiasi').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','inisiasiUlang');
});
function inisiasiUlang() {
	var nomor_pengadaan = $('#btn-reinisiasi').attr('data-pengadaan');
	$.ajax({
		url : base_url + 'pengadaan/sanggah/inisiasi_ulang',
		data : {nomor_pengadaan : nomor_pengadaan},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
</script>