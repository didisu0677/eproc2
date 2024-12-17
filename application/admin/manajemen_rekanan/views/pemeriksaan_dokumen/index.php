<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php if($menu_access['access_view'] && user('is_kanwil')==0) {
				?>
				<button type="button" class="btn btn-primary btn-sm btn-kirim"><i class="fa-envelope"></i><?php echo lang('kirim_email_notifikasi'); ?></button>
				<?php
			} ?>

			<select class="select2 infinity custom-select" id="filter">
				<option value="belum_kadaluarsa"><?php echo lang('belum_kadaluarsa'); ?></option>
				<option value="kadaluarsa"><?php echo lang('kadaluarsa'); ?></option>
			</select>
			<?php echo access_button(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('manajemen_rekanan/pemeriksaan_dokumen/data'),'tbl_upl_dokumenvendor');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('kode_rekanan'),'','width="60" data-content="kode_rekanan" data-link="detail-vendor"');
				th(lang('nama_rekanan'),'','width="100" data-content="nama" data-table="tbl_vendor"');
				th(lang('nama_dokumen'),'','data-content="nama_dokumen"');
				th(lang('tanggal_kadaluarsa'),'','data-content="tanggal_kadaluarsa" data-type="daterange"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>

<script>

$(document).ready(function() {
    if($('#filter').val() == 'kadaluarsa'){
		$('.btn-kirim').show();	
	}else{
		$('.btn-kirim').hide();	
	}
});

$('#filter').change(function(){
	var url = base_url + 'manajemen_rekanan/pemeriksaan_dokumen/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();

	if($(this).val() == 'kadaluarsa'){
		$('.btn-kirim').show();	
	}else{
		$('.btn-kirim').hide();	
	}
});

$(document).on('click','.detail-vendor',function(){
	$.get(base_url+'manajemen_rekanan/daftar_rekanan/detail/?kode_rekanan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});

function detail_callback(id){
	$.get(base_url+'manajemen_rekanan/pemeriksaan_dokumen/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}

var id_unlock = '';
$(document).on('click','.btn-kirim',function(e){
	e.preventDefault();
	id_unlock = 'kadaluarsa';
	cConfirm.open(lang.apakah_anda_yakin + '?','lanjut');
});

function lanjut() {
	$.ajax({
		url : base_url + 'manajemen_rekanan/pemeriksaan_dokumen/notifikasi_email',
		data : {id:id_unlock},
		type : 'post',
		dataType : 'json',
		success : function(res) {
			cAlert.open(res.message,res.status,'refreshData');
		}
	});
}
</script>