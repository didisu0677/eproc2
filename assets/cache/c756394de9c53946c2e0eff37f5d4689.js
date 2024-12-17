
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
