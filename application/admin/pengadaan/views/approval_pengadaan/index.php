<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body" data-openid="<?php echo $id; ?>">
	<?php
	table_open('',true,base_url('pengadaan/approval_pengadaan/data'),'tbl_pengajuan','data-callback="openApproval"');
		thead();
			tr();
			    th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_pengajuan'),'','data-content="nomor_pengajuan" data-link="detail-pengajuan"');
				th(lang('tanggal_pengadaan'),'','data-content="tanggal_pengadaan" data-type="daterange"');
				th(lang('nama_pengadaan'),'','data-content="nama_pengadaan"');
				th(lang('nama_divisi'),'','data-content="nama_divisi"');
				th(lang('dibuat_oleh'),'','data-content="create_by"');	
				th(lang('panitia'),'','data-content="nama_panitia"');	
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>

<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" id="modal-approve" >
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php echo lang('persetujuan'); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body mb-0">
				<div class="table-responsive">
					<table class="table table-bordered table-app table-detail table-normal mb-0">
						<tr>
							<th width="150"><?php echo lang('nomor_pengajuan'); ?></th>
							<td colspan="3" id="nomor_pengajuan"></td>
						</tr>
						<tr>
							<th><?php echo lang('tanggal_pengajuan'); ?></th>
							<td colspan="3" id="tanggal_pengajuan"></td>
						</tr>
						<tr>
							<th><?php echo lang('unit_kerja'); ?></th>
							<td colspan="3" id="unit_kerja"></td>
						</tr>
						<tr>
							<th><?php echo lang('divisi'); ?></th>
							<td colspan="3" id="nama_divisi"></td>
						</tr>
						<tr>
							<th><?php echo lang('tanggal_pengadaan'); ?></th>
							<td colspan="3" id="tanggal_pengadaan"></td>
						</tr>
						<tr>
							<th><?php echo lang('nama_pemberi_tugas'); ?></th>
							<td colspan="3" id="pemberi_tugas"></td>
						</tr>
						<tr>
							<th><?php echo lang('nama_pengadaan'); ?></th>
							<td colspan="3" id="nama_pengadaan"></td>
						</tr>
						<tr>
							<th><?php echo lang('mata_anggaran'); ?></th>
							<td id="mata_anggaran"></td>
							<th width="100"><?php echo lang('besar_anggaran'); ?></th>
							<td id="besar_anggaran"></td>
						</tr>
						<tr>
							<th><?php echo lang('_hps'); ?></th>
							<td id="hps_panitia" colspan="3"></td>
						</tr>
						<tr>
							<th><?php echo lang('metode_pengadaan'); ?></th>
							<td id="metode_pengadaan" colspan="3"></td>
						</tr>
						<tr>
							<th><?php echo lang('rks'); ?></th>
							<td id="rks" colspan="3"></td>
						</tr>
						<tr>
							<th><?php echo lang('persetujuan'); ?></th>
							<td colspan="3" class="select-100">
								<select class="form-control select2 infinity" id="persetujuan">
									<option value="1"><?php echo lang('disetujui'); ?></option>
									<option value="8"><?php echo lang('dikembalikan'); ?></option>
									<option value="9"><?php echo lang('ditolak'); ?></option>
								</select>
							</td>
						</tr>
						<tr id="row-alasan">
							<th data-dikembalikan="<?php echo lang('alasan_dikembalikan'); ?>" data-ditolak="<?php echo lang('alasan_ditolak'); ?>"><?php echo lang('alasan_disetujui_dikembalikan_ditolak'); ?></th>
							<td colspan="3">
								<textarea name="alasan" id="alasan" class="form-control" rows="4"></textarea>
							</td>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<td colspan="3">
								<button type="button" class="btn btn-info btn-approve btn-sm"><?php echo lang('simpan'); ?></button>
								<button type="reset" class="btn btn-secondary btn-sm"><?php echo lang('batal'); ?></button>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
var id_pengajuan = 0;
var value_persetujuan = 0;
function detail_callback(e) {
	$.ajax({
		url 	: base_url + 'pengadaan/approval_pengadaan/get_data',
		data 	: {id:e},
		type 	: 'post',
		dataType : 'json',
		success : function(response) {
			id_pengajuan = response.id;
			$.each(response,function(k,v){
				if(k == 'hps_panitia') {
					$('#hps_panitia').html('<a href="'+base_url+'pengadaan/hps/cetak_hps/'+encodeId(response.id_hps)+'" target="_blank">'+customFormat(response.hps_panitia)+'</a>');
				} else if(k == 'id_rks') {
					$('#rks').html('<a href="'+base_url+'pengadaan/rks/cetak/'+encodeId(response.id_rks)+'" target="_blank">'+response.nomor_rks+'</a>');
				} else {
					if(k.indexOf('tanggal') != -1) v = cDate(v);
					else if(k == 'besar_anggaran') v = numberFormat(v,0,',','.');
					if($('#'+k).length == 1) $('#' + k).text(v);
				}
			});
			$('#alasan').val('');
			$('#modal-approve').modal();
			$('#persetujuan').val('1').trigger('change');
			setTimeout(function(){
				$('#persetujuan').focus().select2('open');
			},700);
		}
	});
}
function save_persetujuan() {
	$.ajax({
		url 	: base_url + 'pengadaan/approval_pengadaan/save_persetujuan',
		data 	: {
			id : id_pengajuan,
			value : value_persetujuan,
			alasan : $('#alasan').val()
		},
		type	: 'post',
		success : function(response) {
			cAlert.open(response,'success','refreshData');
		}
	});
}
function openApproval() {
	if( $('.btn-act-view[data-id="'+$('[data-openid]').attr('data-openid')+'"]').length == 1 ) {
		$('.btn-act-view[data-id="'+$('[data-openid]').attr('data-openid')+'"]').trigger('click');
		$('[data-openid]').removeAttr('data-openid');
	}
}
$('#persetujuan').change(function(){
	if($(this).val() == '1') {
		$('#row-alasan').hide();
		$('#alasan').val('');
	} else if($(this).val() == '8') {
		$('#row-alasan').show();
		$('#row-alasan th').text($('#row-alasan th').attr('data-dikembalikan'));
	} else {
		$('#row-alasan').show();
		$('#row-alasan th').text($('#row-alasan th').attr('data-ditolak'));		
	}
});
$(document).on('click','.btn-approve',function(){
	if($('#alasan').val() != '' || $('#persetujuan').val() == '1' ) {
		value_persetujuan 	= $('#persetujuan').val();
		var msg 			= lang.anda_yakin_menyetujui;
		if(value_persetujuan == '9') msg = lang.anda_yakin_menolak;
		else if(value_persetujuan == '8') msg = lang.anda_yakin_mengembalikan;
		cConfirm.open(msg,'save_persetujuan');
	} else {
		$('#alasan').focus();
	}
});
$(document).on('click','.detail-pengajuan',function(){
	$.get(base_url+'pengadaan/pengajuan/detail?no_pengajuan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});
</script>