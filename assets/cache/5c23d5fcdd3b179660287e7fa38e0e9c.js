
$('#btn-laporan').click(function(){
	$('#modal-laporan').modal();
});
$('.btn-add-pendukung').click(function(){
	var konten = '<tr>'
		+ '<td><button type="button" class="btn btn-sm btn-danger btn-remove btn-icon-only"><i class="fa-times"></i></button></td>'
		+ '<td><input type="text" class="form-control" name="deskripsi_lain[]" autocomplete="off" data-validation="required"></td>'
		+ '<td><input type="text" class="form-control" name="detil_lain[]" autocomplete="off"></td>'
		+ '<td><select class="form-control select2 infinity" data-width="100%" name="kelengkapan_lain[]" data-validation="required">'
			+ '<option value=""></option>'
			+ '<option value="Ada">'+lang.ada+'</option>'
			+ '<option value="Tidak Ada">'+lang.tidak_ada+'</option>'
		+ '</select></td>'
		+ '<td><input type="text" class="form-control" name="keterangan_lain[]" autocomplete="off"></td>'
	+ '</tr>';
	$('#d1').append(konten);
	$('#d1 tr').last().find('select').select2({
		placeholder: '',
		minimumResultsForSearch: Infinity,
		dropdownParent : $('#d1 tr').last().find('select').parent(),
		width: '100%'
	});
});
$('.btn-add-aspek').click(function(){
	var konten = '<tr>'
		+ '<td><button type="button" class="btn btn-sm btn-danger btn-remove btn-icon-only"><i class="fa-times"></i></button></td>'
		+ '<td><input type="text" class="form-control" name="deskripsi1_lain[]" autocomplete="off" data-validation="required"></td>'
		+ '<td><select class="form-control select2 infinity" data-width="100%" name="kondisi_lain[]" data-validation="required">'
			+ '<option value=""></option>'
			+ '<option value="Sesuai / Layak">'+lang.sesuai_layak+'</option>'
			+ '<option value="Tidak">'+lang.tidak+'</option>'
		+ '</select></td>'
		+ '<td><input type="text" class="form-control" name="keterangan1_lain[]" autocomplete="off"></td>'
	+ '</tr>';
	$('#d2').append(konten);
	$('#d2 tr').last().find('select').select2({
		placeholder: '',
		minimumResultsForSearch: Infinity,
		dropdownParent : $('#d2 tr').last().find('select').parent(),
		width: '100%'
	});
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
});
