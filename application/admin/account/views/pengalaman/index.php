<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb($title); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<div class="content-body">
	<div class="main-container container">
		<div class="row">
			<div class="col-sm-9">
				<form id="form-command" action="<?php echo base_url('account/pengalaman/save'); ?>" data-callback="reload" method="post" data-submit="ajax">
					<?php $nomor = 0; ?>
					<?php for($i=0; $i < 3; $i++) { ?>
					<?php $nomor ++; ?>
					<div class="card mb-2">
						
						<div class="card-header">
							<label class="" for="nomor<?php echo $i; ?>"><?php echo $nomor; ?></label>
						</div>	
						<div class="card-body p-1">
							<div class="form-group row">
								<label class="col-form-label col-sm-2" for="pengalaman<?php echo $i; ?>"><?php echo lang('pengalaman'); ?></label>
								<div class="col-sm-10">
									<input type="text" name="pengalaman[]" id="pengalaman<?php echo $i; ?>" autocomplete="off" class="form-control" data-validation="">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-sm-2" for="deskripsi<?php echo $i; ?>"><?php echo lang('deskripsi'); ?></label>
								<div class="col-sm-10">
									<textarea name="deskripsi[]" id="deskripsi<?php echo $i; ?>" class="form-control editor" data-validation="" rows="4" data-editor="inline"></textarea>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
					<div id="additional-pengalaman"></div>
					<div class="form-group row">
						<div class="col-sm-3">
							<button type="button" class="btn btn-success" id="btn-add" data-pengalaman="<?php echo lang('pengalaman'); ?>" data-deskripsi="<?php echo lang('deskripsi'); ?>"><i class="fa-plus"></i> <?php echo lang('tambah_pengalaman'); ?></button>
						</div>
						<div class="col-sm-5">
							<button type="submit" class="btn btn-success"><?php echo lang('simpan'); ?></button>
						</div>
					</div>
				</div>
				</form>

				<div class="col-sm-3 d-none d-sm-block">
					<?php echo include_view('account/list'); ?> 
				</div>
			</div>
		</div>
	</div>
</div> 

<script type="text/javascript" src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js') ?>"></script>
<script>

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


</script>