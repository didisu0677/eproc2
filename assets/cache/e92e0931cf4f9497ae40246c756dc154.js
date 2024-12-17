
$(document).on('click','.detail-pengajuan',function(){
	$.get(base_url+'pengadaan/monitor_pengadaan/detail?no_pengajuan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});
