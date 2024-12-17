<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<select class="select2 infinity custom-select" id="filter">
				<option value="0"><?php echo lang('belum_diverifikasi'); ?></option>
				<option value="1"><?php echo lang('lolos_verifikasi'); ?></option>
				<option value="9"><?php echo lang('tidak_lolos_verifikasi'); ?></option>
			</select>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body" data-openid="<?php echo $id; ?>">
	<?php
	table_open('',true,base_url('manajemen_rekanan/checklist_rekanan/data'),'tbl_vendor','data-callback="openForm"');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('kode_rekanan'),'','data-content="kode_rekanan"');
				th(lang('nama_rekanan'),'','data-content="nama"');
				th(lang('jenis_rekanan'),'','data-content="jenis_rekanan" data-replace="1:'.lang('badan_usaha').'|2:'.lang('perorangan').'"');
				th(lang('kategori_rekanan'),'','data-content="kategori_rekanan"');
				th(lang('kualifikasi'),'','data-content="kualifikasi"');
				th(lang('status'),'','data-content="verifikasi_dokumen" data-replace="0:'.lang('belum_diverifikasi').'|1:'.lang('lolos_verifikasi').'|9:'.lang('tidak_lolos_verifikasi').'" data-filter="false"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('manajemen_rekanan/checklist_rekanan/save'),'post','form');
			col_init(0,12);
			?>
			<div class="main-container">
				<div class="card mb-2">
					<div class="card-header"><?php echo lang('rekanan'); ?></div>
					<div class="card-body">
						<table class="table table-bordered table-detail mb-0">
							<tr>
								<th width="200"><?php echo lang('nama_rekanan'); ?></th>
								<td><?php select2('','id_vendor',''); ?></td>
							</tr>
							<tr>
								<th><?php echo lang('alamat'); ?></th>
								<td><?php textarea('','alamat','','','readonly data-readonly="true"'); ?></td>
							</tr>
						</table>
					</div>
				</div>						
			<?php 				
			form_button(lang('simpan'),lang('batal'));
		form_close();
modal_close();
?>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js') ?>"></script>
<script>
var vendor = {};
var file = {};
var is_edit = false;
var idx = 999;
function formOpen() {
	$('.chk').removeAttr('disabled');
	$('.dwlfile').html('');
	var response = response_edit;
	if(typeof response.id != 'undefined') {
		$('#id_vendor').html('<option value="'+response.id+'">'+response.nama+'</option>').trigger('change');
		$('#alamat').val(response.alamat + ', ' + response.nama_kelurahan + ', ' + response.nama_kecamatan + ', ' + response.nama_kota + ', ' + response.nama_provinsi + ' - ' + response.kode_pos);
		if(response.file.length > 0) {
			$.each(response.file,function(k,v){
				$('#file'+v.id_dokumen).parent().find('input').val(v.id_dokumen);
				if(v.file) {
					var konten = '<a href ="'+base_url+'assets/uploads/rekanan/'+response.id+'/'+v.file+'" target="_blank"><i class="fa-download"></i></a>';
					$('#file'+v.id_dokumen).html(konten);
				} else {
					$('#check'+v.id_dokumen).attr('disabled',true);
				}
			});
			$.each(response.cek,function(e,d){
				$('#keterangan'+d.id_dokumen).val(d.keterangan_tambahan);
				if(d.verifikasi == 1) {
					$('#check'+d.id_dokumen).prop("checked", true);
				}
			});
		} else {
			$('.chk').attr('disabled',true);
		}
	} else {
		view_combo();
	}
	is_edit = false;
}

function view_combo() {
	$.ajax({
		url			: base_url + 'manajemen_rekanan/checklist_rekanan/get_combo',
		dataType	: 'json',
		success     : function(response){
			vendor 	= response.vendor;
			file 	= response.file;
			var konten 	= '<option value=""></option>';
			$.each(vendor,function(k,v){
				konten += '<option value="'+v.id+'">'+v.nama+'</option>';
			});
			
			$('#id_vendor').html(konten).trigger('change');
		}
	});
}
$('#id_vendor').change(function(){
	if(typeof vendor[$(this).val()] !== 'undefined') {
		var p = vendor[$(this).val()];
		$('#nama').val(p.nama);
		$('#alamat').val(p.alamat+', '+p.nama_kelurahan+', '+p.nama_kecamatan+', '+p.nama_kota+', '+p.nama_provinsi);
		var fl = file[$(this).val()];
		$.each(fl,function(k,v){
			if(v.file) {
				var konten = '<a href ="'+base_url+'assets/uploads/rekanan/'+id_vendor+'/'+v.file+'" target="_blank"><i class="fa-download"></i></a>';
				$('#file'+v.id_dokumen).html(konten);
			} else {
				$('#check'+v.id_dokumen).attr('disabled',true);
			}
		});
	}
});
$('#filter').change(function(){
	var url = base_url + 'manajemen_rekanan/checklist_rekanan/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
$(document).on('click','.btn-detail',function(){
	$.get(base_url + 'manajemen_rekanan/checklist_rekanan/detail/' + $(this).attr('data-id'), function(res){
		cInfo.open(lang.detil,res);
	});
});
$('.btn-unduh').click(function(e){
	e.preventDefault();
	window.open(base_url + 'manajemen_rekanan/download_dokumen/' + encodeId($('#id_vendor').val()),'_blank');
});
function openForm() {
	if( $('[data-openid]').attr('data-openid') != '0' && $('.btn-input[data-id="'+$('[data-openid]').attr('data-openid')+'"]').length == 1 ) {
		$('.btn-input[data-id="'+$('[data-openid]').attr('data-openid')+'"]').trigger('click');
		$('[data-openid]').removeAttr('data-openid');
	}
}
</script>