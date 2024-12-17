
$('#id_user').change(function(){
	$('#nama_persetujuan').val($(this).find(':selected').attr('data-jabatan'));
});
$('#filter-unit').change(function(){
	$('[data-serverside]').attr('data-serverside',base_url + 'inisiasi/grup_persetujuan_penetapan/data?id_unit_kerja=' + $(this).val());
	refreshData();
});
$(document).ready(function(){
	$('#filter-unit').trigger('change');
})
function openForm() {
	$('#id_unit_kerja').val($('#filter-unit').val());
	$('#unit_kerja').val($('#filter-unit').find(':selected').text());
	if(typeof response_edit.id != 'undefined') {
		$('#nama_persetujuan').val(response_edit.nama_persetujuan);
	}
}
