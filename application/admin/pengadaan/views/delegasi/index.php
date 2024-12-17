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
	table_open('',true,base_url('pengadaan/delegasi/data'),'tbl_delegasi_pengadaan');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_pengajuan'),'','data-content="nomor_pengajuan" data-link="detail-pengajuan"');
				th(lang('nama_pengadaan'),'','data-content="nama_pengadaan"');
				th(lang('nomor_delegasi'),'','data-content="nomor_delegasi"');
				th(lang('tanggal_delegasi'),'','data-content="tanggal_delegasi" data-type="daterange"');
				th(lang('panitia'),'','data-content="nama_panitia"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<select class="d-none" id="list-panitia">
	<?php foreach($panitia as $u) {
		echo '<option value="'.$u['id'].'" data-unit="'.$u['id_unit_kerja'].'">'.$u['deskripsi'].'</option>';
	} ?>
</select>
<?php 
modal_open('modal-form','','modal-lg','data-openCallback="openForm"');
	modal_body();
		form_open(base_url('pengadaan/delegasi/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			card_open(lang('pengajuan'),'mb-2');
				select2(lang('nomor_pengajuan'),'nomor_pengajuan','required');
				textarea(lang('nama_pengadaan'),'nama_pengadaan','','','disabled');
				input('text',lang('tanggal_pengadaan'),'tanggal_pengadaan','','','disabled');
				input('text',lang('unit_kerja'),'unit_kerja','','','disabled');
				input('text',lang('divisi'),'divisi','','','disabled');
				input('text',lang('mata_anggaran'),'mata_anggaran','','','disabled');
				input('text',lang('besar_anggaran'),'besar_anggaran','','','disabled');
				input('text',lang('usulan_hps'),'usulan_hps','','','disabled');
			card_close();
			card_open(lang('delegasi'));
				input('text',lang('nomor_delegasi'),'nomor_delegasi','required');
				input('date',lang('tanggal_delegasi'),'tanggal_delegasi','required');			
				select2(lang('panitia'),'id_m_panitia','required');
				form_button(lang('simpan'),lang('batal'));
			card_close();
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

	$('#id_m_panitia').html('');
	var response = response_edit;
	if(typeof response.id != 'undefined') {
		$('#nomor_pengajuan').html(response.nomor_pengajuan1).trigger('change');
    	$('#id_m_panitia').val(response.id_m_panitia).trigger('change');
	} else {
		view_combo();
	}
}
function view_combo() {
	$.ajax({
		url			: base_url + 'pengadaan/delegasi/get_combo',
 		dataType	: 'json',
        success     : function(response){
        	$('#nomor_pengajuan').html(response.nomor_pengajuan);
         }
    });
}
$('#nomor_pengajuan').change(function(){
	$('#nama_pengadaan').val($(this).find(':selected').attr('data-nama_pengadaan'));
	$('#tanggal_pengadaan').val($(this).find(':selected').attr('data-tanggal_pengadaan'));
	$('#mata_anggaran').val($(this).find(':selected').attr('data-mata_anggaran'));
	$('#divisi').val($(this).find(':selected').attr('data-divisi'));
	$('#unit_kerja').val($(this).find(':selected').attr('data-unit_kerja'));
	$('#besar_anggaran').val($(this).find(':selected').attr('data-besar_anggaran'));
	$('#usulan_hps').val($(this).find(':selected').attr('data-usulan_hps'));

	$('#id_m_panitia').html('<option value=""></option>');
	$('#list-panitia [data-unit="'+$(this).find(':selected').attr('data-unit')+'"]').each(function(){
		$('#id_m_panitia').append('<option value="'+$(this).attr('value')+'">'+$(this).text()+'</option>');
	});
	$('#id_m_panitia').trigger('change');
});
$(document).on('click','.detail-pengajuan',function(){
	$.get(base_url+'pengadaan/pengajuan/detail?no_pengajuan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});
function detail_callback(id){
	$.get(base_url+'pengadaan/delegasi/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}
</script>