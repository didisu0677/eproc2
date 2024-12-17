
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
