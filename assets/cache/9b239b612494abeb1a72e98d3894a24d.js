

var idx = 777;
$( document ).ready(function() {
	get_data();
});


function get_data() {
	$('#additional-pengalaman').html('');
	$.ajax({
		url			: base_url + 'account/pengalaman/get_data',
 		dataType	: 'json',
        success     : function(response){
			$.each(response.pengalaman_vendor,function(k,v){
				var x = parseInt(k);
				if( x < 3) {
					$('#pengalaman' + x).val(v.pengalaman);
					$('#deskripsi' + x).val(v.deskripsi);
					CKEDITOR.instances['deskripsi'+x].setData(decodeEntities(v.deskripsi));
				} else {
					addPengalaman(v.pengalaman,v.deskripsi);
				}
			});
         }
    });
}



$('#btn-add').click(function(){
	addPengalaman();
});

$(document).on('click','.btn-remove',function(){
	$(this).closest('.card').remove();
	nomor --;
});
var nomor = 3;
function addPengalaman(pengalaman,deskripsi) {
	nomor ++;
	var _pengalaman = typeof pengalaman == undefined ? '' : pengalaman;
	var _deskripsi 	= typeof deskripsi == undefined ? '' : deskripsi;
	var konten = '<div class="card mb-2">'
		+ '<div class="card-header">'
		    + '<label class="" for="nomor<"'+idx+'">'+nomor+'</label>'
			+ '</div>'	


		+ '<div class="card-body">'
			+ '<div class="form-group row">'
				+ '<label class="col-form-label col-sm-2" for="pengalaman'+idx+'">'+$('#btn-add').attr('data-pengalaman')+'</label>'
				+ '<div class="col-sm-10">'
					+ '<input type="text" name="pengalaman[]" id="pengalaman'+idx+'" autocomplete="off" class="form-control" data-validation="">'
				+ '</div>'
			+ '</div>'

			+ '<div class="form-group row">'
				+ '<label class="col-form-label col-sm-2" for="deskripsi'+idx+'">'+$('#btn-add').attr('data-deskripsi')+'</label>'
			+ '<div class="col-sm-10">'
			+ '<textarea name="deskripsi[]" id="deskripsi'+idx+'" class="form-control editor" data-validation="" rows="4"></textarea>'
			+ '</div>'

		+ '</div>'		
		+ '</div>'

		+ '<div class="card-footer">'
			+ '<button type="button" class="btn btn-danger btn-remove"><i class="fa-times"></i> '+lang.hapus+'</button>';
		+ '</div>'
	+ '</div>';
	$('#additional-pengalaman').append(konten);

	var c_id = 'deskripsi'+idx;
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
	if(_pengalaman) $('#pengalaman' + idx).val(_pengalaman);
	if(_deskripsi) {
		$('#deskripsi' + idx).val(_deskripsi);
		CKEDITOR.instances['deskripsi'+idx].setData(decodeEntities(_deskripsi));
	}
	idx++;
}


