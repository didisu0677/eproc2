
var idx = 999;
$(document).on('click','.btn-add',function(){
	var konten = '';
	if($(this).hasClass('add-sub')) {
		konten += '<tr data-idx="'+idx+'" data-parent="'+$(this).attr('data-idx')+'">';
		konten += '<td width="30">&nbsp;</td>';
		konten += '<td colspan="2"><input type="text" name="deskripsi['+$(this).attr('data-grup')+']['+$(this).attr('data-idx')+']['+idx+']" class="form-control" autocomplete="off" data-validation="required|max-length:150"></td>';
		konten += '<td>&nbsp;</td>';
		konten += '<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove" data-idx="'+idx+'"><i class="fa-times"></i></button></td>';
	} else {
		konten += '<tr data-idx="'+idx+'">';
		konten += '<td colspan="3"><input type="text" name="deskripsi['+$(this).attr('data-grup')+'][0]['+idx+']" class="form-control" autocomplete="off" data-validation="required|max-length:150"></td>';
		konten += '<td><button type="button" class="btn btn-success btn-icon-only btn-sm btn-add add-sub" data-idx="'+idx+'" data-grup="'+$(this).attr('data-grup')+'"><i class="fa-plus"></i></button></td>';
		konten += '<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove" data-idx="'+idx+'"><i class="fa-times"></i></button></td>';
	}
	konten += '</tr>';
	$('#grup-'+$(this).attr('data-grup')).append(konten);
	idx++;
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
	$('tr[data-parent="'+$(this).attr('data-idx')+'"]').remove();
});
