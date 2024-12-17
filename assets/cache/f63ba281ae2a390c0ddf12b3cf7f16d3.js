
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

