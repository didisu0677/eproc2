
var idx_header = 1;
$('#btn-add-group').click(function(){
	if($('#add-group').val() == '') {
		$('#add-group').focus();
	} else {
		var nama_grup = $('#add-group').val();
		$('#add-group').val('');
		var konten = '<div class="pengadaan-header mb-2 border-top pt-2">' +
			'<div class="row">' +
				'<h4 class="col-form-label col-3" style="text-transform: uppercase;">'+nama_grup+'</h4>' +
				'<div class="col-sm-9 text-right">' +
					'<button type="button" class="btn btn-danger btn-sm btn-remove-group"><i class="fa-times"></i> '+lang.hapus_grup+'</button>' +
				'</div>' +
			'</div>' +
			'<input type="hidden" name="nama_grup['+idx_header+']" value="'+nama_grup+'">' +
			'<div class="form-group row">' +
				'<label class="col-form-label col-sm-3 required" for="nama_vendor'+idx_header+'">'+lang.nama_vendor+'</label>' +
				'<div class="col-sm-9">' +
					'<input type="text" name="nama_vendor['+idx_header+']" id="nama_vendor'+idx_header+'" autocomplete="off" class="form-control" data-validation="required">' +
				'</div>' +
			'</div>' +
			'<div class="form-group row">' +
				'<label class="col-form-label col-sm-3 required" for="alamat_vendor'+idx_header+'">'+lang.alamat+'</label>' +
				'<div class="col-sm-9">' +
					'<input type="text" name="alamat_vendor['+idx_header+']" id="alamat_vendor'+idx_header+'" autocomplete="off" class="form-control" data-validation="required">' +
				'</div>' +
			'</div>' +
			'<div class="form-group row">' +
				'<label class="col-form-label col-sm-3" for="npwp_vendor'+idx_header+'">'+lang.npwp+'</label>' +
				'<div class="col-sm-9">' +
					'<input type="text" name="npwp_vendor['+idx_header+']" id="npwp_vendor'+idx_header+'" autocomplete="off" class="form-control">' +
				'</div>' +
			'</div>' +
			'<div class="table-responsive">' +
				'<table class="table table-bordered table-app mb-3">' +
					'<thead>' +
						'<tr>' +
							'<th><button type="button" class="btn btn-success btn-icon-only btn-sm btn-add-detail" data-index="'+idx_header+'"><i class="fa-plus"></i></button></th>' +
							'<th>'+lang.deskripsi+'</th>' +
							'<th>'+lang.satuan+'</th>' +
							'<th>'+lang.harga+'</th>' +
							'<th>'+lang.jumlah+'</th>' +
							'<th>'+lang.total+'</th>' +
						'</tr>' +
					'</thead>' +
					'<tbody></tbody>' +
					'<tfoot>' +
						'<tr>' +
							'<th colspan="5" class="text-right" style="text-transform: uppercase">'+lang.total+'</th>' +
							'<th class="text-right total-harga"></th>' +
						'</tr>' +
					'</tfoot>' +
				'</table>' +
			'</div>' +
		'</div>';

		$('#detail-pengadaan').append(konten);
		idx_header++;
	}
});
$(document).on('click','.btn-add-detail',function(){
	var idx = $(this).attr('data-index');
	var konten = '<tr>' +
		'<td><button type="button" class="btn btn-sm btn-danger btn-icon-only btn-remove"><i class="fa-times"></i></button></td>' +
		'<td><input type="text" autocomplete="off" name="deskripsi['+idx+'][]" class="form-control deskripsi" data-validation="required" /></td>' +
		'<td><input type="text" autocomplete="off" name="satuan['+idx+'][]" class="form-control satuan" /></td>' +
		'<td><input type="text" autocomplete="off" name="harga['+idx+'][]" class="form-control text-right harga money" data-validation="required" /></td>' +
		'<td><input type="text" autocomplete="off" name="jumlah['+idx+'][]" class="form-control text-right jumlah" data-validation="required" /></td>' +
		'<td><input type="text" autocomplete="off" name="total['+idx+'][]" class="form-control text-right total" disabled /></td>' +
	'</tr>';
	$(this).closest('table').find('tbody').append(konten);
	$(".money:not([readonly])").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});
});
$(document).on('keyup','.harga,.jumlah',function(){
	var harga = moneyToNumber($(this).closest('tr').find('.harga').val());
	var jumlah = toNumber($(this).closest('tr').find('.jumlah').val());
	var total = harga * jumlah;
	$(this).closest('tr').find('.total').val(customFormat(total));

	var _total = 0;
	$(this).closest('tbody').find('.total').each(function(){
		_total += moneyToNumber($(this).val());
	});
	$(this).closest('table').find('.total-harga').text(customFormat(_total));
});
$(document).on('click','.btn-remove-group',function(){
	$(this).closest('.pengadaan-header').remove();
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
	var _total = 0;
	$(this).closest('tbody').find('.total').each(function(){
		_total += moneyToNumber($(this).val());
	});
	$(this).closest('table').find('.total-harga').text(customFormat(_total));
});
$(document).on('click','.btn-print',function(){
	var id = encodeId($(this).attr('data-id'));
	window.open(base_url + 'pengadaan/swakelola/print/' + id, '_blank');
});
function init_form() {
	$('#detail-pengadaan').html('');
	var res = response_edit;
	if(typeof res.id != 'undefined') {
		$.each(res.header,function(k,v){
			var idx = idx_header;
			$('#add-group').val(v.nama_grup);
			$('#btn-add-group').trigger('click');
			var last = $('#detail-pengadaan .pengadaan-header').last();
			last.find('#nama_vendor'+idx).val(v.nama_vendor);
			last.find('#alamat_vendor'+idx).val(v.alamat_vendor);
			last.find('#npwp_vendor'+idx).val(v.npwp_vendor);

			$.each(res.detail[v.id],function(x,y){
				$('.btn-add-detail[data-index="'+idx+'"]').trigger('click');
				var last = $('.btn-add-detail[data-index="'+idx+'"]').closest('table').children('tbody').find('tr').last();
				last.find('.deskripsi').val(y.deskripsi);
				last.find('.satuan').val(y.satuan);
				last.find('.jumlah').val(y.jumlah);
				last.find('.harga').val(customFormat(y.harga)).trigger('keyup');
			});
		});
	}
}
