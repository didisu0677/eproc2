<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('manajemen_kontrak/pengambilan_jaminan/data'),'tbl_jaminan');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_jaminan'),'','data-content="nomor_jaminan" data-link="detail-jaminan"');
				th(lang('jenis_jaminan'),'','data-content="jenis_jaminan"');
				th(lang('nomor_spk'),'','data-content="nomor_spk"');
				th(lang('nama_pekerjaan'),'','data-content="nama_pengadaan"');
				th(lang('nilai_pekerjaan'),'text-right','data-content="nilai_pengadaan" data-type="currency"');
				th(lang('nilai_jaminan'),'text-right','data-content="nilai_jaminan" data-type="currency"');
				th(lang('nama_rekanan'),'','data-content="nama_vendor"');
				th(lang('tanggal_mulai'),'','data-content="tanggal_mulai" data-type="daterange"');
				th(lang('tanggal_selesai'),'','data-content="tanggal_selesai" data-type="daterange"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-pengambilan',lang('pengambilan_jaminan'),'modal-lg');
	modal_body();
		form_open(base_url('manajemen_kontrak/pengambilan_jaminan/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			echo '<div id="detail--jaminan"></div>';
			card_open(lang('data_yang_mengambil_jaminan'),'mb-2');
				input('text',lang('nama'),'nama_pengambil_jaminan','required');
				input('text',lang('telepon'),'telp_pengambil_jaminan','required|phone');
				input('text',lang('email'),'email_pengambil_jaminan','required|email');
				input('date',lang('tanggal_pengambilan'),'tanggal_pengambilan','required');
			card_close();
			form_button(lang('simpan'),lang('batal'));
		form_close();
modal_close();
?>
<script type="text/javascript">
$(document).on('click','.btn-pengambilan',function(){
	var id = $(this).attr('data-id');
	$('#form')[0].reset();
	$('#id').val(id);
	$('#detail--jaminan').html('');
	$.get(base_url+'manajemen_kontrak/penerimaan_jaminan/detail/'+id,function(result){
		$('#detail--jaminan').html(result);
	});
	$('#modal-pengambilan').modal();
});
$(document).on('click','.detail-jaminan',function(){
	var id = $(this).attr('data-id');
	$.get(base_url+'manajemen_kontrak/penerimaan_jaminan/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
});
</script>