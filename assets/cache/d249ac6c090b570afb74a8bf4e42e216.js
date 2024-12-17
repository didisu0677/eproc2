
var vendor = {};
var file = {};
var is_edit = false;
var idx = 999;
function formOpen() {
	$('.chk').removeAttr('disabled');
	$('.dwlfile').html('');
	var response = response_edit;
	if(typeof response.id != 'undefined') {
		$('#id_vendor').html('<option value="'+response.id+'">'+response.nama+'</option>').trigger('change');
		$('#alamat').val(response.alamat + ', ' + response.nama_kelurahan + ', ' + response.nama_kecamatan + ', ' + response.nama_kota + ', ' + response.nama_provinsi + ' - ' + response.kode_pos);
		if(response.file.length > 0) {
			$.each(response.file,function(k,v){
				$('#file'+v.id_dokumen).parent().find('input').val(v.id_dokumen);
				if(v.file) {
					var konten = '<a href ="'+base_url+'assets/uploads/rekanan/'+response.id+'/'+v.file+'" target="_blank"><i class="fa-download"></i></a>';
					$('#file'+v.id_dokumen).html(konten);
				} else {
					$('#check'+v.id_dokumen).attr('disabled',true);
				}
			});
			$.each(response.cek,function(e,d){
				$('#keterangan'+d.id_dokumen).val(d.keterangan_tambahan);
				if(d.verifikasi == 1) {
					$('#check'+d.id_dokumen).prop("checked", true);
				}
			});
		} else {
			$('.chk').attr('disabled',true);
		}
	} else {
		view_combo();
	}
	is_edit = false;
}

function view_combo() {
	$.ajax({
		url			: base_url + 'manajemen_rekanan/checklist_rekanan/get_combo',
		dataType	: 'json',
		success     : function(response){
			vendor 	= response.vendor;
			file 	= response.file;
			var konten 	= '<option value=""></option>';
			$.each(vendor,function(k,v){
				konten += '<option value="'+v.id+'">'+v.nama+'</option>';
			});
			
			$('#id_vendor').html(konten).trigger('change');
		}
	});
}
$('#id_vendor').change(function(){
	if(typeof vendor[$(this).val()] !== 'undefined') {
		var p = vendor[$(this).val()];
		$('#nama').val(p.nama);
		$('#alamat').val(p.alamat+', '+p.nama_kelurahan+', '+p.nama_kecamatan+', '+p.nama_kota+', '+p.nama_provinsi);
		var fl = file[$(this).val()];
		$.each(fl,function(k,v){
			if(v.file) {
				var konten = '<a href ="'+base_url+'assets/uploads/rekanan/'+id_vendor+'/'+v.file+'" target="_blank"><i class="fa-download"></i></a>';
				$('#file'+v.id_dokumen).html(konten);
			} else {
				$('#check'+v.id_dokumen).attr('disabled',true);
			}
		});
	}
});
$('#filter').change(function(){
	var url = base_url + 'manajemen_rekanan/checklist_rekanan/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
$(document).on('click','.btn-detail',function(){
	$.get(base_url + 'manajemen_rekanan/checklist_rekanan/detail/' + $(this).attr('data-id'), function(res){
		cInfo.open(lang.detil,res);
	});
});
$('.btn-unduh').click(function(e){
	e.preventDefault();
	window.open(base_url + 'manajemen_rekanan/download_dokumen/' + encodeId($('#id_vendor').val()),'_blank');
});
function openForm() {
	if( $('[data-openid]').attr('data-openid') != '0' && $('.btn-input[data-id="'+$('[data-openid]').attr('data-openid')+'"]').length == 1 ) {
		$('.btn-input[data-id="'+$('[data-openid]').attr('data-openid')+'"]').trigger('click');
		$('[data-openid]').removeAttr('data-openid');
	}
}
