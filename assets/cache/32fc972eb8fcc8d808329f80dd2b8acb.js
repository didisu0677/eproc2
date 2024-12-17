
$('#filter').change(function(){
	var url = base_url + 'manajemen_kontrak/penerimaan_jaminan/data?status=' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
function formOpen() {
	is_edit = true;
	var response = response_edit;
	$('#nomor_spk').val('');
	if(typeof response.id != 'undefined') {
		$('#nomor_spk').val(response.nomor_spk);
		$('#spk').val(response.nomor_spk + ' | ' + response.nama_pengadaan).attr('disabled',true);
	} else {
		$('#spk').removeAttr('disabled');
	}
	is_edit = false;
}
$('#spk').autocomplete({
	serviceUrl: base_url + 'manajemen_kontrak/penerimaan_jaminan/get_spk',
	groupBy: 'group',
	noCache: true,
	showNoSuggestionNotice: true,
	noSuggestionNotice: lang.data_tidak_ditemukan,
	onSearchStart: function(query) {
		readonly_ajax = false;
		is_autocomplete = true;
		if($(this).parent().find('.autocomplete-spinner').length == 0) {
			$(this).parent().append('<i class="fa-spinner spin autocomplete-spinner"></i>');
		}
	}, onSearchComplete: function (query, suggestions) {
		is_autocomplete = false;
		$(this).parent().find('.autocomplete-spinner').remove();
	}, onSearchError: function (query, jqXHR, textStatus, errorThrown) {
		is_autocomplete = false;
		$(this).parent().find('.autocomplete-spinner').remove();
	}, onSelect: function (suggestion) {
		$('#nomor_spk').val(suggestion.data);
		$('#nama_pengadaan').val(suggestion.nama_pengadaan);
		$('#nama_vendor').val(suggestion.nama_vendor);
		$('#nilai_pengadaan').val(customFormat(suggestion.nilai_pengadaan));
	}
});
function detail_callback(id){
	$.get(base_url+'manajemen_kontrak/penerimaan_jaminan/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}
