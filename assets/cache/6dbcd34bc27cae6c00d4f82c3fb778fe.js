
$('#tes-ftp').click(function(e){
	e.preventDefault();
	if(validation('ftp-sap')) {
		$.ajax({
			url : base_url + 'settings/web_setting/check_ftp',
			data : {
				protocol : $('#ftp_protocol').val(),
				host : $('#ftp_host').val(),
				port : $('#ftp_port').val(),
				user : $('#ftp_user').val(),
				pass : $('#ftp_pass').val(),
				file : $('#ftp_file').val(),
			},
			type : 'post',
			success : function(e) {
				cAlert.open(e);
			}
		});
	}
});
$(document).ready(function(){
	$('#export_type, #export_prefix').trigger('change');
});
$('#export_type').change(function(){
	var ext = $(this).find(':selected').attr('data-extention');
	$('.ext-file').html('.' + ext);
});
$('#export_prefix').change(function(){
	var prefix = '';
	var today = new Date();
	var d = String(today.getDate()).padStart(2, '0');
	var m = String(today.getMonth() + 1).padStart(2, '0');
	var y = today.getFullYear();

	if($(this).val() == '-Y-m-d') {
		prefix = '-' + y + '-' + m + '-' + d;
	} else if($(this).val() == '_Y_m_d') {
		prefix = '_' + y + '_' + m + '_' + d;
	} else if($(this).val() == 'Ymd') {
		prefix = y+m+d;
	}
	$('.prefix-file').text(prefix);
});
$('#export_zip').click(function(){
	if($(this).is(':checked')) {
		$('#desc1').addClass('hidden');
		$('#desc2').removeClass('hidden');
	} else {
		$('#desc2').addClass('hidden');
		$('#desc1').removeClass('hidden');
	}
});
