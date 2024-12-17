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
		<?php if($approve == 0) { ?>
		<div class="alert alert-info"><?php echo lang('menunggu_persetujuan'); ?></div>
		<?php } elseif($approve == 1) { ?>
		<div class="alert alert-success"><?php echo lang('pemenang_pengadaan_sudah_disetujui'); ?></div>
		<?php } else { ?>
		<div class="alert alert-danger"><?php echo lang('pemenang_pengadaan_ditolak').' : <strong>'.$alasan_ditolak.'</strong>'; ?></div>
		<?php } ?>
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
						<th><?php echo lang('_hps'); ?></th>
						<td><?php echo custom_format($hps); ?></td>
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
						<th><?php echo lang('penawaran_awal'); ?></th>
						<td><?php echo custom_format($penawaran_awal); ?></td>
					</tr>
					<tr>
						<th><?php echo lang('penawaran_terakhir'); ?></th>
						<td><?php echo custom_format($penawaran_terakhir); ?></td>
					</tr>
					<?php if($approve == 1) { ?>
					<tr>
						<th><?php echo lang('surat_persetujuan'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/penetapan_pemenang/surat_persetujuan/'.encode_id($id)); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<?php } if($lama_sanggah && $approve == 1) { ?>
					<tr>
						<th><?php echo lang('pengumuman_penetapan'); ?></th>
						<td>[ <a href="<?php echo base_url('pengadaan/penetapan_pemenang/cetak/'.encode_id($id)); ?>" target="_blank"><?php echo lang('lihat_detil'); ?></a> ]</td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
		<?php if($approve == 1 && !$lama_sanggah) { 
			if($tipe_pengadaan == 'Lelang') {
				if(!$lama_sanggah) {
			?>
		<div class="card">
			<div class="card-header"><?php echo lang('sanggah'); ?></div>
			<div class="card-body">
				<?php
				form_open(base_url('pengadaan/penetapan_pemenang/save'),'post','form-sanggah','data-submit="ajax" data-callback="reload"');
					col_init(2,4);
					input('hidden','id','id','',$id);
					input('text',lang('lama_masa_sanggah'),'lama_sanggah','required|number|min:1','','data-append="'.lang('hari').'"');
					input('date',lang('tanggal_mulai'),'tanggal_mulai_sanggah','required');
					input('date',lang('tanggal_selesai'),'tanggal_selesai_sanggah','required');
					form_button(lang('simpan'),false);
				form_close();
				?>
			</div>
		</div>
		<?php 
				}
			} elseif($status_sanggah == 0) { ?>
		<div class="card">
			<div class="card-header"><?php echo lang('spk'); ?></div>
			<div class="card-body">
				<?php
				form_open(base_url('pengadaan/penetapan_pemenang/proses_spk'),'post','form-spk','data-submit="ajax" data-callback="reload"');
					col_init(2,4);
					input('hidden','nomor_pengadaan','nomor_pengadaan','',$nomor_pengadaan);
					input('date',lang('tanggal_spk'),'tanggal_spk','required');
					input('date',lang('tanggal_jatuh_tempo'),'tanggal_jatuh_tempo_spk','required');
					select2(lang('kelompok_pembelian'),'kelompok_pembelian','required',$kelompok_pembelian,'kode','kelompok_pembelian');
					toggle(lang('ada_kontrak').'?','is_kontrak',false,$chk_kontrak);
					input('date',lang('tanggal_po'),'tanggal_po','required');
					label(lang('tanda_tangan_spk'));
					input('text',lang('nama'),'ttd_spk','required|max-length:100');
					input('text',lang('jabatan'),'jabatan_ttd_spk','required|max-length:100');
					input('text',lang('lokasi_ttd'),'lokasi_ttd_spk','required|max-length:100');

					form_button(lang('simpan'),false);
				form_close();
				?>
			</div>
		</div>	
			<?php }
		} elseif($approve == 9) { ?>
		<div class="card">
			<div class="card-header"><?php echo lang('inisiasi_ulang'); ?></div>
			<div class="card-body">
				<?php if($inisiasi_ulang) { ?>
				<div class="alert alert-warning">
					<?php echo lang('pengadaan_telah_dibatalkan_dan_diinisiasi_ulang'); ?>
				</div>
				<?php } else { ?>
				<button type="button" class="btn btn-secondary" id="btn-reinisiasi" data-pengadaan="<?php echo $nomor_pengadaan; ?>"><i class="fa-sync"></i><?php echo lang('inisiasi_ulang'); ?></button>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<script type="text/javascript">
$('#btn-reinisiasi').click(function(){
	cConfirm.open(lang.apakah_anda_yakin+'?','inisiasiUlang');
});
function inisiasiUlang() {
	var nomor_pengadaan = $('#btn-reinisiasi').attr('data-pengadaan');
	$.ajax({
		url : base_url + 'pengadaan/penetapan_pemenang/inisiasi_ulang',
		data : {nomor_pengadaan : nomor_pengadaan},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			cAlert.open(response.message,response.status,'reload');
		}
	});
}
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

</script>