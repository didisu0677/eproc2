
var isEdit = false;
function openForm() {
	var response = response_edit;
	$('#detail tbody').html('');
	if(typeof response.id != 'undefined') {
		isEdit = true;
		$('#nomor_pengajuan').html(response.nomor_pengajuan1).val(response.nomor_pengajuan).trigger('change');
		renderDetail(response.detail);
		$('#persen_ppn').val(cPercent(response.persen_ppn));
		calculate();
		isEdit = false;
	} else {
		view_combo();
	}
}
function view_combo() {
	$.ajax({
		url			: base_url + 'pengadaan/hps/get_combo',
		dataType	: 'json',
		success     : function(response){
			$('#nomor_pengajuan').html(response.nomor_pengajuan);
		}
	});
}
$('#nomor_pengajuan').change(function(){
	$('#deskripsi').val($(this).find(':selected').attr('data-nama_pengadaan'));
	$('#nama_divisi').val($(this).find(':selected').attr('data-nama_divisi'));
	$('#unit_kerja').val($(this).find(':selected').attr('data-unit_kerja'));
	$('#mata_anggaran').val($(this).find(':selected').attr('data-mata_anggaran'));
	$('#besar_anggaran').val($(this).find(':selected').attr('data-besar_anggaran'));
	$('#usulan_hps').val($(this).find(':selected').attr('data-usulan_hps'));
	if(!isEdit && typeof $(this).find(':selected').attr('data-usulan_hps') != 'undefined') {
		$('#detail tbody').html('');
		$.ajax({
			url : base_url + 'pengadaan/hps/sap_detail',
			data : {'nomor_pengajuan':$(this).val()},
			dataType : 'json',
			type : 'POST',
			success : function(r) {
				renderDetail(r);
			}
		});
	}
});
$('#modal-form').on('hidden.bs.modal',function(){
	$('#nomor_pengajuan').html('<option value=""></option>').trigger('change');
});
function renderDetail(r) {
	var index = 1;
	$.each(r, function(k,v){
		var konten = '<tr>' +
						'<td><input type="hidden" name="id_detail['+index+']" value="'+v.id_sap+'">'+v.material_number+'</td>' +
						'<td>'+v.short_text+'</td>' +
						'<td><input type="text" name="quantity['+index+']" autocomplete="off" class="form-control text-right money calc quantity" data-validation="required|number|max:'+v.maksimal+'" aria-label="'+$('#detail').find('th:nth-child(3)').text()+'" value="'+customFormat(v.quantity)+'" /></td>' +
						'<td>'+v.unit_of_measure+'</td>' +
						'<td><input type="text" name="price_unit['+index+']" autocomplete="off" class="form-control text-right money calc price_unit" data-validation="required|number" value="'+customFormat(v.price_unit)+'" /></td>' +
						'<td><input type="text" name="total_value['+index+']" autocomplete="off" class="form-control text-right money calculate total_value" value="'+customFormat(v.total_value)+'" data-readonly="true" readonly /></td>' +
					'</tr>';
		$('#detail tbody').append(konten);
		index++;
	});
	$(".money:not([readonly])").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});
	calculate();
}
$(document).on('keyup','.calc',function(){
	var q = $(this).closest('tr').find('.quantity').val();
	var p = $(this).closest('tr').find('.price_unit').val();
	var t = moneyToNumber(q) * moneyToNumber(p);
	$(this).closest('tr').find('.total_value').val(customFormat(t));
	calculate();
});
$(document).on('keyup','.calculate',function(){
	calculate();
});
function calculate() {
	var total = 0;
	$('.total_value').each(function(){
		total += moneyToNumber($(this).val());
	});
	$('#total_sebelum_ppn').val(customFormat(total));
}
$('#preview-detail').click(function(){
	if($('#nomor_pengajuan').val() != '') {
		var v = $('#nomor_pengajuan').val();
		$.get(base_url+'pengadaan/pengajuan/detail_sap?no_pengajuan='+v,function(result){
			cInfo.open(lang.detil,result);
		});
	}
});
$(document).on('click','.detail-pengajuan',function(){
	$.get(base_url+'pengadaan/pengajuan/detail?no_pengajuan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});
$(document).on('click','.btn-print',function(){
	var id = encodeId($(this).attr('data-id'));
	$.redirect(base_url + 'pengadaan/hps/cetak_hps/' + id, {} , 'get', '_blank');
});
