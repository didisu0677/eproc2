<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<select class="select2 infinity custom-select" id="filter">
				<option value="baru"><?php echo lang('rekomendasi_baru'); ?></option>
				<option value="ditolak"><?php echo lang('rekomendasi_ditolak'); ?></option>
				<option value="disetujui"><?php echo lang('rekomendasi_disetujui'); ?></option>
			</select>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body" data-openid="<?php echo $id; ?>">
	<?php
	table_open('',true,base_url('manajemen_rekanan/persetujuan_drm/data'),'tbl_m_rekomendasi_vendor','data-callback="openApproval"');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nama_rekanan'),'','data-content="nama_rekanan"');
				th(lang('alamat'),'','data-content="alamat"');
				th(lang('jangka_waktu'),'','data-content="jangka_waktu" data-suffix="'.lang('tahun').'"');
				th(lang('nomor_rekomendasi'),'','data-content="nomor_rekomendasi"');
				th(lang('tanggal_rekomendasi'),'','data-content="tanggal_rekomendasi" data-type="daterange"');
				th(lang('usulan_rekomendasi'),'','data-content="usulan_rekomendasi" data-replace="1:'.lang('direkomendasikan').'|9:'.lang('tidak_direkomendasikan').'"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<script type="text/javascript">
var id_vendor, _value;
$('#filter').change(function(){
	var url = base_url + 'manajemen_rekanan/persetujuan_drm/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
$(document).on('click','.btn-approve',function(){
	$.get(base_url + 'manajemen_rekanan/persetujuan_drm/detail/' + $(this).attr('data-id'), function(response){
		cInfo.open(lang.detil,response);
	});
});

$(document).on('click','.btn-approval',function(){
	id_vendor 	= $(this).attr('data-id');
	_value 		= $(this).attr('data-value');
	var msg 	= lang.anda_yakin_menyetujui;
	if(_value != '1') msg = lang.anda_yakin_menolak;
	cConfirm.open(msg,'save_persetujuan');
});

function save_persetujuan() {
	$.ajax({
		url : base_url + 'manajemen_rekanan/persetujuan_drm/persetujuan',
		data : {
			id : id_vendor,
			verifikasi : _value
		},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','refreshData');
		}
	});
}
function openApproval() {
	if($('.btn-approve[data-id="'+$('[data-openid]').attr('data-openid')+'"]').length == 1 ) {
		$('.btn-approve[data-id="'+$('[data-openid]').attr('data-openid')+'"]').trigger('click');
		$('[data-openid]').removeAttr('data-openid');
	}
}

$(document).on('click','.btn-print1',function(){
	window.open(base_url + 'manajemen_rekanan/persetujuan_drm/laporan_drm/' + encodeId($(this).attr('data-id')),'_blank');
});
</script>