
var id_vendor, _value;
$('#filter').change(function(){
	var url = base_url + 'manajemen_rekanan/persetujuan_drm/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
$(document).on('click','.btn-approve',function(){
	$.get(base_url + 'manajemen_rekanan/persetujuan_drm/detail/' + $(this).attr('data-id'), function(response){
		cInfo.open(lang.detil,response);
	});
});

$(document).on('click','.btn-approval',function(){
	id_vendor 	= $(this).attr('data-id');
	_value 		= $(this).attr('data-value');
	var msg 	= lang.anda_yakin_menyetujui;
	if(_value != '1') msg = lang.anda_yakin_menolak;
	cConfirm.open(msg,'save_persetujuan');
});

function save_persetujuan() {
	$.ajax({
		url : base_url + 'manajemen_rekanan/persetujuan_drm/persetujuan',
		data : {
			id : id_vendor,
			verifikasi : _value
		},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','refreshData');
		}
	});
}
function openApproval() {
	if($('.btn-approve[data-id="'+$('[data-openid]').attr('data-openid')+'"]').length == 1 ) {
		$('.btn-approve[data-id="'+$('[data-openid]').attr('data-openid')+'"]').trigger('click');
		$('[data-openid]').removeAttr('data-openid');
	}
}

$(document).on('click','.btn-print1',function(){
	window.open(base_url + 'manajemen_rekanan/persetujuan_drm/laporan_drm/' + encodeId($(this).attr('data-id')),'_blank');
});
