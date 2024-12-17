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

	table_open('',true,base_url('manajemen_rekanan/calon_rekanan/data'),'tbl_vendor');

		thead();

			tr();

				th(lang('no'),'text-center','width="30" data-content="id"');

				th(lang('jenis_rekanan'),'','data-content="jenis_rekanan" data-replace="1:'.lang('badan_usaha').'|2:'.lang('perorangan').'"');

				th(lang('nama_rekanan'),'','data-content="nama"');

				th(lang('kategori_rekanan'),'','data-content="kategori_rekanan"');

				th(lang('kualifikasi'),'','data-content="kualifikasi"');

				th(lang('asosiasi'),'','data-content="asosiasi"');

				th(lang('mendaftar_di_unit'),'','data-content="unit_daftar"');

				th('&nbsp;','','width="30" data-content="action_button"');

	table_close();

	?>

</div>

<?php

modal_open('modal-change-password',lang('ubah_kata_sandi'));

	modal_body();

		form_open(base_url('manajemen_rekanan/daftar_rekanan/change_password'),'post','form-change-password');

			col_init(4,8);

			input('hidden','id','id_vendor');

			input('password',lang('kata_sandi'),'password','required|min-length:8|strong_password');

			input('password',lang('konfirmasi_kata_sandi'),'konfirmasi','required|equal:password');

			form_button(lang('ubah_kata_sandi'),lang('batal'));

		form_close();

modal_close();

?>

<script type="text/javascript">
var id_unlock = 0;
$(document).on('click','.btn-unlock',function(e){
	e.preventDefault();
	id_unlock = $(this).attr('data-id');
	cConfirm.open(lang.apakah_anda_yakin + '?','lanjut');
});
function lanjut() {
	$.ajax({
		url : base_url + 'manajemen_rekanan/calon_rekanan/unlock',
		data : {id:id_unlock},
		type : 'post',
		dataType : 'json',
		success : function(res) {
			cAlert.open(res.message,res.status,'refreshData');
		}
	});
}
$(document).on('click','.btn-change-password',function(){

	$('#modal-change-password').modal();

	$('#id_vendor').val($(this).attr('data-id'));

	$('#form-change-password')[0].reset();

	$('#form-change-password :input').removeClass('is-invalid');

	$('#form-change-password span.error').remove();

});

function detail_callback(e) {

	$.get(base_url + 'manajemen_rekanan/calon_rekanan/detail/' + e,function(res){

		cInfo.open(lang.detil, res);

	});

}

</script>