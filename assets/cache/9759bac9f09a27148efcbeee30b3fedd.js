
$(document).ready(function() {
    if($('#filter').val() == 'kadaluarsa'){
		$('.btn-kirim').show();	
	}else{
		$('.btn-kirim').hide();	
	}
});

$('#filter').change(function(){
	var url = base_url + 'pengadaan/daftar_kontrak/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();

	if($(this).val() == 'kadaluarsa'){
		$('.btn-kirim').show();	
	}else{
		$('.btn-kirim').hide();	
	}
});

var id_kirim = '';
$(document).on('click','.btn-kirim',function(e){
	e.preventDefault();
	id_kirim = 'kadaluarsa';
	cConfirm.open(lang.apakah_anda_yakin + '?','lanjut');
});

function lanjut() {
	$.ajax({
		url : base_url + 'pengadaan/daftar_kontrak/notifikasi_email',
		data : {id:id_kirim},
		type : 'post',
		dataType : 'json',
		success : function(res) {
			cAlert.open(res.message,res.status,'refreshData');
		}
	});
}

function detail_callback(id){
	$.get(base_url+'pengadaan/daftar_kontrak/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}

$(document).on('click','.btn-export',function(){
	window.open(base_url + 'pengadaan/daftar_kontrak/export/' + $('#filter').val(),'_blank');
});

