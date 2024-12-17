
$('.chk-input').click(function(){
	if(!$(this).is(':checked') && $(this).closest('.form-group').find('.chk-laporan').length == 1) {
		$(this).closest('.form-group').find('.chk-laporan').prop('checked',false);
	}
});
$('.chk-laporan').click(function(){
	if($(this).is(':checked') && $(this).closest('.form-group').find('.chk-input').length == 1) {
		$(this).closest('.form-group').find('.chk-input').prop('checked',true);
	}
});
