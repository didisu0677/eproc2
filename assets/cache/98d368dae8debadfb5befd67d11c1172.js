

$(document).ready(function() {
    if($('#filter').val() == 'kadaluarsa'){
		$('.btn-kirim').show();	
	}else{
		$('.btn-kirim').hide();	
	}
});

$('#filter').change(function(){
	var url = base_url + 'manajemen_rekanan/pemeriksaan_dokumen/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();

	if($(this).val() == 'kadaluarsa'){
		$('.btn-kirim').show();	
	}else{
		$('.btn-kirim').hide();	
	}
});

$(document).on('click','.detail-vendor',function(){
	$.get(base_url+'manajemen_rekanan/daftar_rekanan/detail/?kode_rekanan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});

function detail_callback(id){
	$.get(base_url+'manajemen_rekanan/pemeriksaan_dokumen/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}

var id_unlock = '';
$(document).on('click','.btn-kirim',function(e){
	e.preventDefault();
	id_unlock = 'kadaluarsa';
	cConfirm.open(lang.apakah_anda_yakin + '?','lanjut');
});

function lanjut() {
	$.ajax({
		url : base_url + 'manajemen_rekanan/pemeriksaan_dokumen/notifikasi_email',
		data : {id:id_unlock},
		type : 'post',
		dataType : 'json',
		success : function(res) {
			cAlert.open(res.message,res.status,'refreshData');
		}
	});
}
