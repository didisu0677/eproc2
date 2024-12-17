
$(document).ready(function(){
	if($('#counter').length == 1) {
		var counter = toNumber($('#counter').attr('data-value'));
		setInterval(function(){
			counter--;
			var menit = counter / 60;
			var minute = Math.floor(menit);
			var detik = counter - (minute * 60);
			var string_menit = minute < 10 ? '0' + minute : minute;
			var string_detik = detik < 10 ? '0' + detik : detik;
			$('#counter').text(string_menit+' : '+string_detik);
			if(counter <= 0) {
				reload();
			}
		},1000);
	}
});
$('.price_unit').keyup(function(){
	var j = moneyToNumber($(this).closest('tr').find('.quantity').text());
	var h = moneyToNumber($(this).val());
	var t = j * h;
	$(this).closest('tr').find('.total_value').text(customFormat(t));
	var t_bef_ppn = 0;
	$('.total_value').each(function(){
		t_bef_ppn += moneyToNumber($(this).text());
	});
	$('#total_hps_pembulatan').text(customFormat(t_bef_ppn));
	$('#total_penawaran').val(t_bef_ppn);
});
function checkTotal() {
	var p_akhir = moneyToNumber($('[href="#collapseO"]').text());
	var ttl = moneyToNumber($('#total_hps_pembulatan').text());
	var batas = p_akhir;
	if(ttl > batas) {
		cAlert.open(lang.maksimal_penawaran + ' = ' + customFormat(batas));
		return false;
	} else return true;
}
function checkNilaiLelang() {

	var p_akhir = toNumber($('#batas_penawaran').val());
	var ttl = moneyToNumber($('#total_hps_pembulatan').text());
	var batas = p_akhir;
	if(ttl > batas) {
		cAlert.open(lang.maksimal_penawaran + ' = ' + customFormat(batas));
		return false;
	} else return true;
}
