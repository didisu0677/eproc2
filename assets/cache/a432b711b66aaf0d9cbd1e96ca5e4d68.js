
$('#btn-add-detail').click(function(){
	var konten = '<tr>' +
		'<td><button type="button" class="btn btn-sm btn-danger btn-icon-only btn-remove"><i class="fa-times"></i></button></td>' +
		'<td><input type="text" autocomplete="off" name="deskripsi[]" class="form-control deskripsi" data-validation="required" /></td>' +
		'<td><input type="text" autocomplete="off" name="satuan[]" class="form-control satuan" /></td>' +
		'<td><input type="text" autocomplete="off" name="harga[]" class="form-control text-right harga money" data-validation="required" /></td>' +
		'<td><input type="text" autocomplete="off" name="jumlah[]" class="form-control text-right jumlah" data-validation="required" /></td>' +
		'<td><input type="text" autocomplete="off" name="total[]" class="form-control text-right total" disabled /></td>' +
	'</tr>';
	$('#table-detail tbody').append(konten);
	$(".money:not([readonly])").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
	calculate();
});
$(document).on('keyup','.harga,.jumlah',function(){
	var harga = moneyToNumber($(this).closest('tr').find('.harga').val());
	var jumlah = toNumber($(this).closest('tr').find('.jumlah').val());
	var total = harga * jumlah;
	$(this).closest('tr').find('.total').val(customFormat(total));
	calculate();
});
$(document).on('click','.btn-print',function(){
	var id = encodeId($(this).attr('data-id'));
	window.open(base_url + 'pengadaan/pembelian_langsung/print/' + id, '_blank');
});
function calculate() {
	var total = 0;
	$('.total').each(function(){
		total += moneyToNumber($(this).val());
	});
	$('#total-harga').text(customFormat(total));
}
function cek_detail() {
	var ret = false;
	if($('.jumlah').length == 0) {
		$('#btn-add-detail').trigger('click');
	} else ret = true;
	return ret;
}
function init_form() {
	$('#table-detail tbody').html('');
	var res = response_edit;
	if(typeof res.id != 'undefined') {
		$.each(res.detail,function(k,v){
			$('#btn-add-detail').trigger('click');
			var last = $('#table-detail tbody tr').last();
			last.find('.deskripsi').val(v.deskripsi);
			last.find('.satuan').val(v.satuan);
			last.find('.jumlah').val(v.jumlah);
			last.find('.harga').val(customFormat(v.harga)).trigger('keyup');
		});
	}
}
