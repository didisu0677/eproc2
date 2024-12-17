<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php echo access_button(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('pengadaan/disposisi/data'),'tbl_disposisi');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_pengajuan'),'','data-content="nomor_pengajuan" data-link="detail-pengajuan"');
				th(lang('nomor_disposisi'),'','data-content="nomor_disposisi"');
				th(lang('tanggal_disposisi'),'','data-content="tanggal_disposisi" data-type="daterange"');
				th(lang('delegator'),'','data-content="nama_user"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<select class="d-none" id="user-delegator">
	<?php foreach($delegator as $d) { ?>
		<option value="<?php echo $d['id']; ?>" data-unit="<?php echo $d['id_unit_kerja']; ?>"><?php echo $d['nama']; ?></option>
	<?php } ?>
</select>
<?php 
modal_open('modal-form','','modal-lg','data-openCallback="openForm"');
	modal_body();
		form_open(base_url('pengadaan/disposisi/save'),'post','form');
			col_init(3,9);
			card_open(lang('pengajuan'),'mb-2');
				input('hidden','id','id');
				select2(lang('nomor_pengajuan'),'nomor_pengajuan','required');
				textarea(lang('nama_pengadaan'),'nama_pengadaan','','','disabled');
				input('text',lang('tanggal_pengadaan'),'tanggal_pengadaan','','','disabled');
				input('text',lang('unit_kerja'),'unit_kerja','','','disabled');
				input('text',lang('divisi'),'divisi','','','disabled');
				input('text',lang('mata_anggaran'),'mata_anggaran','','','disabled');
				input('text',lang('besar_anggaran'),'besar_anggaran','','','disabled');
				input('text',lang('usulan_hps'),'usulan_hps','','','disabled');
			card_close();
			card_open(lang('disposisi'),'mb-2');
				input('text',lang('nomor_disposisi'),'nomor_disposisi','required');
				input('date',lang('tanggal_disposisi'),'tanggal_disposisi','required');
				select2(lang('ditujukan'),'id_user','required');
				textarea(lang('catatan'),'catatan','required');
			card_close();
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
?>

<script>
function openForm() {
	$('#nama_pengadaan').val('');
	$('#tanggal_pengadaan').val('');
	$('#mata_anggaran').val('');
	$('#unit_kerja').val('');
	$('#divisi').val('');
	$('#besar_anggaran').val('');
	$('#usulan_hps').val('');
	$('#id_user').html('');

	var response = response_edit;
	if(typeof response.id != 'undefined') {
		$('#nomor_pengajuan').html(response.nomor_pengajuan).trigger('change');
    	$('#id_user').val(response.id_user).trigger('change');
	} else {
		view_combo();
	}
}
function view_combo() {
	$('#nomor_pengajuan').html('').trigger('change');
	$.ajax({
		url			: base_url + 'pengadaan/disposisi/get_combo',
 		dataType	: 'json',
        success     : function(response){
        	$('#nomor_pengajuan').html(response.nomor_pengajuan).trigger('change');
         }
    });
}
$('#nomor_pengajuan').change(function(){
	$('#nama_pengadaan').val($(this).find(':selected').attr('data-nama_pengadaan'));
	$('#tanggal_pengadaan').val($(this).find(':selected').attr('data-tanggal_pengadaan'));
	$('#mata_anggaran').val($(this).find(':selected').attr('data-mata_anggaran'));
	$('#unit_kerja').val($(this).find(':selected').attr('data-unit_kerja'));
	$('#divisi').val($(this).find(':selected').attr('data-divisi'));
	$('#besar_anggaran').val($(this).find(':selected').attr('data-besar_anggaran'));
	$('#usulan_hps').val($(this).find(':selected').attr('data-usulan_hps'));

	$('#id_user').html('<option value=""></option>');
	$('#user-delegator [data-unit="'+$(this).find(':selected').attr('data-unit')+'"]').each(function(){
		$('#id_user').append('<option value="'+$(this).attr('value')+'">'+$(this).text()+'</option>');
	});
	$('#id_user').trigger('change');
});
function detail_callback(id){
	$.get(base_url+'pengadaan/disposisi/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}
$(document).on('click','.detail-pengajuan',function(){
	$.get(base_url+'pengadaan/pengajuan/detail?no_pengajuan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});
</script>