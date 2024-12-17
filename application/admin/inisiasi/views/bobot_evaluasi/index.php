<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php echo access_button('delete'); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('inisiasi/bobot_evaluasi/data'),'tbl_m_bobot_evaluasi');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('jenis_pengadaan'),'','data-content="jenis_pengadaan" data-table="tbl_jenis_pengadaan jenis_pengadaan"');
				th(lang('bobot_harga'),'','data-content="bobot_harga" data-type="percent"');
				th(lang('bobot_teknis'),'','data-content="bobot_teknis" data-type="percent"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-lg');
	modal_body();
		form_open(base_url('inisiasi/bobot_evaluasi/save'),'post','form','data-trigger="checkBobot"');
			col_init(3,9);
			input('hidden','id','id');
			select2(lang('jenis_pengadaan'),'id_jenis_pengadaan','required|unique',$opt_id_jenis_pengadaan,'id','jenis_pengadaan');
			input('percent',lang('bobot_harga'),'bobot_harga','required');
			input('percent',lang('bobot_teknis'),'bobot_teknis','required');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
?>
<script type="text/javascript">
function checkBobot() {
	var res 		= true;
	var b_harga 	= toNumber($('#bobot_harga').val());
	var b_teknis 	= toNumber($('#bobot_teknis').val());
	if(b_harga + b_teknis != 100) {
		res = false;
		$('#bobot_teknis,#bobot_harga').addClass('is-invalid');
		$('#bobot_teknis,#bobot_harga').parent().parent().find('.error').remove();
		$('#bobot_teknis,#bobot_harga').parent().parent().append('<span class="error">' + lang.jumlah_bobot_harus_100 + '</span>');
	}
	return res;
}
$('#bobot_teknis,#bobot_harga').keyup(function(){
	$('#bobot_teknis,#bobot_harga').parent().parent().find('.error').remove();
	$('#bobot_teknis,#bobot_harga').removeClass('is-invalid');
});
</script>