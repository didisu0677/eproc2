<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php if($menu_access['access_view']) {
				?>
				<button type="button" class="btn btn-primary btn-sm btn-kirim"><i class="fa-envelope"></i><?php echo lang('kirim_email_notifikasi'); ?></button>
				<?php
			} ?>

			<select class="select2 infinity custom-select" id="filter">
				<option value="belum_kadaluarsa"><?php echo lang('belum_kadaluarsa'); ?></option>
				<option value="kadaluarsa"><?php echo lang('kadaluarsa'); ?></option>
			</select>
			<a class="btn btn-info btn-xs btn-export"><i class="fa fa-download"></i><?php echo lang('export'); ?></a>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('pengadaan/daftar_kontrak/data'),'tbl_kontrak');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('nomor_kontrak'),'','data-content="nomor_kontrak"');
				th(lang('nomor_spk'),'','data-content="nomor_spk"');
				th(lang('nomor_pengadaan'),'','data-content="nomor_pengadaan"');
				th(lang('nama_pengadaan'),'','data-content="nama_pengadaan"');
				th(lang('nilai_pengadaan'),'text-right','data-content="nilai_pengadaan" data-type="currency"');
				th(lang('nama_vendor'),'','data-content="nama_vendor"');
				th(lang('tanggal_mulai_kontrak'),'','data-content="tanggal_mulai_kontrak" data-type="daterange"');
				th(lang('tanggal_selesai_kontrak'),'','data-content="tanggal_selesai_kontrak" data-type="daterange"');
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
	var url = base_url + 'pengadaan/daftar_kontrak/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();

	if($(this).val() == 'kadaluarsa'){
		$('.btn-kirim').show();	
	}else{
		$('.btn-kirim').hide();	
	}
});

var id_kirim = '';
$(document).on('click','.btn-kirim',function(e){
	e.preventDefault();
	id_kirim = 'kadaluarsa';
	cConfirm.open(lang.apakah_anda_yakin + '?','lanjut');
});

function lanjut() {
	$.ajax({
		url : base_url + 'pengadaan/daftar_kontrak/notifikasi_email',
		data : {id:id_kirim},
		type : 'post',
		dataType : 'json',
		success : function(res) {
			cAlert.open(res.message,res.status,'refreshData');
		}
	});
}

function detail_callback(id){
	$.get(base_url+'pengadaan/daftar_kontrak/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}

$(document).on('click','.btn-export',function(){
	window.open(base_url + 'pengadaan/daftar_kontrak/export/' + $('#filter').val(),'_blank');
});

</script>