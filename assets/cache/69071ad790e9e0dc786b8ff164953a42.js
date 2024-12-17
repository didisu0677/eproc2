
var is_edit = false;
var idx = 777;
function formOpen() {
	is_edit = true;
	var response = response_edit;
	$('#additional-pasal').html('');
	if(typeof response.id != 'undefined') {
		var opt = '<option value="'+response.nomor_spk+'" data-pengadaan="'+response.nama_pengadaan+'" data-vendor="'+response.nama_vendor+'" data-nilai="'+response.nilai_pengadaan+'">'+response.nomor_spk+' | '+response.nama_pengadaan+'</option>';
		$('#nomor_spk').html(opt).trigger('change');
		$.each(response.detail,function(k,v){
			var x = parseInt(k);
			if( x < 2) {
				$('#pasal' + x).val(v.pasal);
				$('#judul_pasal' + x).val(v.judul_pasal);
				$('#isi_pasal' + x).val(v.isi_pasal);
				CKEDITOR.instances['isi_pasal'+x].setData(decodeEntities(v.isi_pasal));
			} else {
				addPasal(v.pasal,v.judul_pasal,v.isi_pasal);
			}
		});
	} else {
		get_spk();
	}
	is_edit = false;
}

function get_spk() {
	$.getJSON(base_url + 'manajemen_kontrak/kontrak/get_spk',function(res){
		var konten = '<option value=""></option>';
		$.each(res,function(k,v){
			konten += '<option value="'+v.nomor_spk+'" data-pengadaan="'+v.nama_pengadaan+'" data-vendor="'+v.nama_vendor+'" data-nilai="'+v.penawaran_terakhir+'">'+v.nomor_spk+' | '+v.nama_pengadaan+'</option>';
		});
		$('#nomor_spk').html(konten).trigger('change');
	});
}
$('#nomor_spk').change(function(){
	var o = $(this).find(':selected');
	$('#nama_pengadaan').val(o.attr('data-pengadaan'));
	$('#nama_vendor').val(o.attr('data-vendor'));
	$('#nilai_pengadaan').val(customFormat(o.attr('data-nilai')));
});
$('#btn-add').click(function(){
	addPasal();
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('.card').remove();
});
function addPasal(pasal,judul,isi) {
	var _pasal 	= typeof pasal == undefined ? '' : pasal;
	var _judul 	= typeof judul == undefined ? '' : judul;
	var _isi 	= typeof isi == undefined ? '' : isi;
	var konten = '<div class="card mb-2">'
		+ '<div class="card-header">'
			+ '<div class="form-group row">'
				+ '<label class="col-form-label col-sm-2" for="pasal'+idx+'">'+$('#btn-add').attr('data-pasal')+'</label>'
				+ '<div class="col-sm-10">'
					+ '<input type="text" name="pasal[]" id="pasal'+idx+'" autocomplete="off" class="form-control" data-validation="required">'
				+ '</div>'
			+ '</div>'
			+ '<div class="form-group row">'
				+ '<label class="col-form-label col-sm-2" for="judul_pasal'+idx+'">'+$('#btn-add').attr('data-judul')+'</label>'
				+ '<div class="col-sm-10">'
					+ '<input type="text" name="judul_pasal[]" id="judul_pasal'+idx+'" autocomplete="off" class="form-control" data-validation="required">'
				+ '</div>'
			+ '</div>'
		+ '</div>'
		+ '<div class="card-body p-0">'
			+ '<textarea name="isi_pasal[]" id="isi_pasal'+idx+'" class="form-control editor" data-validation="required" rows="4"></textarea>'
		+ '</div>'
		+ '<div class="card-footer">'
			+ '<button type="button" class="btn btn-danger btn-remove"><i class="fa-times"></i> '+lang.hapus+'</button>';
		+ '</div>'
	+ '</div>';
	$('#additional-pasal').append(konten);

	var c_id = 'isi_pasal'+idx;
	CKEDITOR.inline( c_id ,{
		toolbar : [
			{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ] },
			{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
			{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
			{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
			'/',
			{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
			{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
			{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] }
		],
		filebrowserImageBrowseUrl : base_url + 'assets/plugins/kcfinder/index.php?type=images',
		width : 'auto',
		height : '250',
		language : $('meta[name="applang"]').attr('content')
	});
	CKEDITOR.instances[c_id].on('change', function() { 
		var vdata = CKEDITOR.instances[c_id].getData();
		$('#' + c_id).val(vdata);
	});
	if(_pasal) $('#pasal' + idx).val(_pasal);
	if(_judul) $('#judul_pasal' + idx).val(_judul);
	if(_isi) {
		$('#isi_pasal' + idx).val(_isi);
		CKEDITOR.instances['isi_pasal'+idx].setData(decodeEntities(_isi));
	}
	idx++;
}
function detail_callback(id){
	$.get(base_url+'manajemen_kontrak/kontrak/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}
$(document).on('click','.btn-print',function(){
	window.open(base_url + 'manajemen_kontrak/kontrak/cetak/' + encodeId($(this).attr('data-id')),'_blank');
});
