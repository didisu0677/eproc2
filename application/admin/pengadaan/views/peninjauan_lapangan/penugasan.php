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
			<div class="card-header"><?php echo lang('hasil_evaluasi_penawaran'); ?></div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-detail table-app">
						<thead>
							<tr>
								<th width="10"><?php echo lang('rangking'); ?></th>
								<th><?php echo lang('rekanan'); ?></th>
								<th width="50"><?php echo lang('nilai_total_penawaran_harga'); ?></th>
								<th width="50"><?php echo lang('penilaian_harga'); ?></th>
								<th width="50"><?php echo lang('penilaian_teknis'); ?></th>
								<th width="50"><?php echo lang('total_penilaian'); ?></th>
								<th><?php echo lang('status'); ?></th>
								<th width="10">&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($rekanan as $k => $p) { ?>
							<tr>
								<td class="text-center"><?php echo $p['rank_evaluasi']; ?></td>
								<td><a href="<?php echo base_url('pengadaan/peninjauan_lapangan/detail_vendor/'.encode_id([$p[
								'id_vendor'],rand()])); ?>" class="cInfo" aria-label="<?php echo lang('data_rekanan'); ?>"><?php echo $p['nama_vendor']; ?></a></td>
								<td class="text-right"><?php echo custom_format($p['nilai_total_penawaran']); ?></td>
								<td class="text-center"><?php echo c_percent($p['penilaian_harga']); ?></td>
								<td class="text-center"><?php echo c_percent($p['penilaian_teknis']); ?></td>
								<td class="text-center"><?php echo c_percent($p['total_penilaian']); ?></td>
								<td>
									<?php if($p['tanggal_peninjauan'] != '0000-00-00') {
										echo '<span class="text-success">['.lang('sudah_ada_penugasan').']</span>';
									} ?>
								</td>
								<td class="button">
									<?php if($stat_pengadaan == 'PENINJAUAN' && !$p['status_peninjauan'] && !$p['is_proses'] && ($create_tugas || (!$create_tugas && $cur_id_tugas == $p['id']) )) { ?>
									<button type="button" class="btn btn-sm btn-icon-only btn-info btn-penugasan" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang('penugasan_peninjauan_lapangan'); ?>" data-id="<?php echo $p['id']; ?>"><i class="fa-edit"></i></button>
									<?php } if($p['tanggal_peninjauan'] != '0000-00-00') { ?>
									<a href="<?php echo base_url('pengadaan/peninjauan_lapangan/surat_tugas/'.encode_id($p['id'])); ?>" target="_blank" class="btn btn-success btn-sm btn-icon-only" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang('surat_tugas'); ?>"><i class="fa-file-alt"></i></a>
									<?php } ?>
								</td>
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
modal_open('modal-penugasan',lang('penugasan'),'modal-lg');
	modal_body();
		form_open(base_url('pengadaan/peninjauan_lapangan/save_penugasan'),'post','form-penugasan','data-callback="reload" data-id="'.$id.'"');
			col_init(3,9);
			input('hidden','id','id');
			input('text',lang('nomor_surat_tugas'),'nomor_surat_tugas','','','disabled placeholder="'.lang('otomatis_saat_disimpan').'"');
			input('text',lang('nama_rekanan'),'nama_vendor','','','disabled');
			textarea(lang('alamat_rekanan'),'alamat_vendor','','','readonly data-readonly="true"');
			label(lang('detil_penugasan'));
			input('text',lang('nama_pemberi_tugas'),'nama_pemberi_tugas','required');
			input('text',lang('jabatan_pemberi_tugas'),'jabatan_pemberi_tugas','required');
			input('date',lang('tanggal_peninjauan'),'tanggal_peninjauan','required');
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3"><?php echo lang('tim_peninjauan'); ?></label>
				<div class="col-sm-5 col-6">
					<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota" data-validation="required">
					<input type="hidden" name="id_anggota[]" class="id_anggota">
				</div>
				<div class="col-sm-2 col-3">
					<input type="text" name="posisi[]" autocomplete="off" class="form-control posisi" disabled value="Ketua">
				</div>
				<div class="col-sm-2 col-3">
					<button type="button" class="btn btn-block btn-success btn-icon-only btn-add-anggota"><i class="fa-plus"></i></button>
				</div>
			</div>
			<div id="additional-anggota" class="mb-2">
			</div>
			<?php
			form_button(lang('simpan'),lang('batal'));
		form_close();
modal_close();
?>
<script type="text/javascript">
$(document).on('click','.btn-penugasan',function(){
	$('#form-penugasan')[0].reset();
	$('#additional-anggota').html('');
	var id = $(this).attr('data-id');
	$.ajax({
		url : base_url + 'pengadaan/peninjauan_lapangan/init_penugasan',
		data : {id : id},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			$('#id').val(id);
			$('#nomor_surat_tugas').val(response.nomor_surat_tugas);
			$('#tanggal_peninjauan').val(response.tanggal_peninjauan);
			$('#nama_vendor').val(response.nama_rekanan);
			$('#alamat_vendor').val(response.alamat_rekanan);
			$('#nama_pemberi_tugas').val(response.nama_pemberi_tugas);
			$('#jabatan_pemberi_tugas').val(response.jabatan_pemberi_tugas);
			$('#modal-penugasan').modal();
			$.each(response.detail,function(k,v){
				if(k == '0') {
					$('.anggota').val(v.nama_user);
					$('.id_anggota').val(v.id_user);
				} else {
					konten = '<div class="form-group row">'
							+ '<div class="offset-sm-3 col-sm-5 col-9">'
							+ '<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota" data-validation="required" value="'+v.nama_user+'">'
							+ '<input type="hidden" name="id_anggota[]" class="id_anggota" value="'+v.id_user+'">'
							+ '</div>'
							+ '<div class="col-sm-2 col-3">'
							+ '<input type="text" name="posisi[]" autocomplete="off" class="form-control posisi" disabled value="Anggota">'
							+ '</div>'
							+ '<div class="col-sm-2 col-3">'
							+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
							+ '</div>'
							+ '</div>';
					$('#additional-anggota').append(konten);
				}
			});
		}
	});
});
$(document).ready(function(){
	cAutocomplete();
});
function add_row_anggota() {
	konten = '<div class="form-group row">'
			+ '<div class="offset-sm-3 col-sm-5 col-9">'
			+ '<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota" data-validation="required">'
			+ '<input type="hidden" name="id_anggota[]" class="id_anggota">'
			+ '</div>'
			+ '<div class="col-sm-2 col-3">'
			+ '<input type="text" name="posisi[]" autocomplete="off" class="form-control posisi" disabled value="Anggota">'
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
		serviceUrl: base_url + 'pengadaan/peninjauan_lapangan/get_tim_peninjauan/' + $('#form-penugasan').attr('data-id'),
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
</script>