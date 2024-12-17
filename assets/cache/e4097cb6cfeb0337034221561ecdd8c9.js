
var idx = 999;
$(document).on('click','.btn-add-aspek',function(){
	var konten = '<tr>'
		+ '<td><input type="text" class="form-control" autocomplete="off" name="deskripsi1[]" data-validation="required" /></td>'
		+ '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>'
	+ '</tr>';
	$('#d1').append(konten);
});
$(document).on('click','.btn-add',function(){
	var konten = '<tr>'
		+ '<td><input type="text" class="form-control" autocomplete="off" name="deskripsi['+idx+']" data-validation="required" /></td>'
		+ '<td class="text-center">'
			+ '<div class="custom-checkbox custom-control"><input class="custom-control-input chk" type="checkbox" id="chk-th-'+idx+'" name="nomor['+idx+']" value="1"><label class="custom-control-label" for="chk-th-'+idx+'">&nbsp;</label></div>'
		+ '</td>'
		+ '<td><div class="pilihan"><input type="text" class="form-control tags" autocomplete="off" name="pilihan['+idx+']" /></div></td>'
		+ '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>'
	+ '</tr>';
	$('#d2').append(konten);
	$('.tags').tagsinput();
	idx++;
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
});
$(document).on('click','.chk',function(){
	if($(this).is(':checked')) {
		$(this).closest('tr').find('.pilihan').addClass('hidden');
	} else {
		$(this).closest('tr').find('.pilihan').removeClass('hidden');		
	}
});
