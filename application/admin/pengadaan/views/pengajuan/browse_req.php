<div class="content-body">
	<?php
	table_open('',true,base_url('pengadaan/pengajuan/data_sap'),'sap_header');
		thead();
			tr();
				th(lang('no'),'','data-content="id"');
				th(lang('nomor_pengajuan_pembelian'),'','data-content="purchase_req_item"');
				th(lang('jabatan_pemberi_tugas'),'','data-content="jabatan"');
				th(lang('nama_pengadaan'),'','data-content="nama_pengadaan"');
				th(lang('usulan_hps'),'text-right','data-content="total_usulan" data-type="currency"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php
modal_open('modal-detail',lang('detil'),'modal-xl');
	modal_body(); ?>
		<table class="mb-3">
			<tr>
				<th width="180" class="align-top"><?php echo lang('nomor_pengajuan_pembelian'); ?></th>
				<th width="10" class="align-top">:</th>
				<td id="info1"></td>
			</tr>
			<tr>
				<th class="align-top"><?php echo lang('jabatan_pemberi_tugas'); ?></th>
				<th class="align-top">:</th>
				<td id="info2"></td>
			</tr>
			<tr>
				<th class="align-top"><?php echo lang('nama_pengadaan'); ?></th>
				<th class="align-top">:</th>
				<td id="info3"></td>
			</tr>
		</table>
		<div id="detail"></div>
	<?php
	modal_footer();
	?>
	<button type="button" class="btn btn-sm btn-success" id="btn-pilih"><?php echo lang('pilih'); ?></button>
	<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><?php echo lang('batal'); ?></button>
	<?php
modal_close();
?>
<script type="text/javascript">
var idRm = '', actRm = '';
$(document).ready(function(){
	if(!window.opener) {
		window.location = base_url + 'home/welcome';
	}
});
$(document).on('click','.btn-act-choose',function(){
	idRm = '';
	$('#info1').text($(this).closest('tr').find('td:nth-child(2)').text());
	$('#info2').text($(this).closest('tr').find('td:nth-child(3)').text());
	$('#info3').text($(this).closest('tr').find('td:nth-child(4)').text());
	get_detail($('#info1').text());
	$('#modal-detail').modal();
});
$(document).on('click','.btn-remove',function(e){
	e.preventDefault();
	idRm = $(this).attr('data-id');
	actRm = 'del';
	cConfirm.open(lang.apakah_anda_yakin + '?','rmData');
});
$(document).on('click','.btn-restore',function(e){
	e.preventDefault();
	idRm = $(this).attr('data-id');
	actRm = 'res';
	cConfirm.open(lang.apakah_anda_yakin + '?','rmData');
});
function rmData() {
	$.ajax({
		url : base_url + 'pengadaan/pengajuan/act_sap/' + actRm,
		data : {id : idRm},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			get_detail($('#info1').text());
			cAlert.open(response.message,response.status);
		}
	});
}
function get_detail(e) {
	$.get(base_url + 'pengadaan/pengajuan/detail_sap?req_no=' + e + '&rm=true',function(r){
		$('#detail').html(r);
	});
}
$('#modal-detail').on('hidden.bs.modal', function () {
	if (idRm != '') {
		refreshData();
	}
});
$('#btn-pilih').click(function(){
	if($('#total-hps').text() != '0') {
		var val1 = $('#info1').text();
		var val2 = $('#info2').text();
		var val3 = $('#info3').text();
		var val4 = $('#total-hps').text();
		window.opener.setValue(val1, val2, val3, val4);
		window.close();
	}
});
</script>