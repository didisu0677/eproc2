<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<select class="select2 infinity custom-select" id="filter">
				<option value="0"><?php echo lang('belum_dikunjungi'); ?></option>
				<option value="1"><?php echo lang('sudah_dikunjungi'); ?></option>
			</select>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body" data-openid="<?php echo $id; ?>">
	<?php
	table_open('',true,base_url('manajemen_rekanan/kunjungan_langsung/data'),'tbl_vendor','data-callback="openForm"');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('kode_rekanan'),'','data-content="kode_rekanan"');
				th(lang('nama_rekanan'),'','data-content="nama"');
				th(lang('jenis_rekanan'),'','data-content="jenis_rekanan" data-replace="1:'.lang('badan_usaha').'|2:'.lang('perorangan').'"');
				th(lang('kategori_rekanan'),'','data-content="kategori_rekanan"');
				th(lang('kualifikasi'),'','data-content="kualifikasi"');
				th(lang('nomor_kunjungan'),'','data-content="nomor_kunjungan"');
				th(lang('status'),'','data-content="laporan_kunjungan" data-replace="0:'.lang('belum_dikunjungi').'|1:'.lang('layak').'|9:'.lang('tidak_layak').'"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-lg','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('manajemen_rekanan/kunjungan_langsung/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('text',lang('nomor_kunjungan'),'nomor_kunjungan','required|max-length:35','','disabled placeholder="'.lang('otomatis_saat_disimpan').'"');
			input('hidden',lang('id_vendor'),'id_vendor');
			input('text',lang('nama_rekanan'),'nama_vendor','','','readonly data-readonly="true"');
			textarea(lang('alamat_kunjungan'),'alamat_kunjungan','','','readonly data-readonly="true"');
			input('text',lang('nama_pemberi_tugas'),'nama_pemberi_tugas','required|max-length:100');
			input('text',lang('jabatan_pemberi_tugas'),'jabatan_pemberi_tugas','required|max-length:100');
			input('date',lang('tanggal_kunjungan'),'tanggal_kunjungan','required|max-length:100');
			textarea(lang('keterangan'),'keterangan');
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3"><?php echo lang('tim_kunjungan'); ?></label>
				<div class="col-sm-5 col-6">
					<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota" data-validation="required">
					<input type="hidden" name="id_anggota[]" class="id_anggota">
				</div>
				<div class="col-sm-2 col-3">
					<input type="text" name="posisi[]" autocomplete="off" class="form-control posisi" disabled value="Ketua">
				</div>
				<div class="col-sm-2 col-3">
					<button type="button" class="btn btn-block btn-success btn-icon-only btn-add-anggota"><i class="fa-plus"></i></button>
				</div>
			</div>
			<div id="additional-anggota" class="mb-2">
			</div>
			<?php
			form_button(lang('simpan'),lang('batal'));
		form_close();
modal_close();
?>
<script>
var idx = 999;
function formOpen() {
	$('.posisi').val('Ketua');
	$('#additional-anggota').html('');
	var response = response_edit;
	$('#nama_vendor').val(response.nama);
	$('#id_vendor').val(response.id);
	$('#alamat_kunjungan').val(response.alamat + ', ' + response.nama_kelurahan + ', ' + response.nama_kecamatan + ', ' + response.nama_kota + ', ' + response.nama_provinsi + ' - ' + response.kode_pos);
	$.each(response.detail,function(k,v){
		if(k == '0') {
			$('.anggota').val(v.nama_user);
			$('.id_anggota').val(v.id_user);
			$('.posisi').val(v.posisi);
		} else {
			konten = '<div class="form-group row">'
					+ '<div class="offset-sm-3 col-sm-5 col-9">'
					+ '<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota" data-validation="required" value="'+v.nama_user+'">'
					+ '<input type="hidden" name="id_anggota[]" class="id_anggota" value="'+v.id_user+'">'
					+ '</div>'
					+ '<div class="col-sm-2 col-3">'
					+ '<input type="text" name="posisi[]" autocomplete="off" class="form-control posisi" disabled value="Anggota">'
					+ '</div>'
					+ '<div class="col-sm-2 col-3">'
					+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
					+ '</div>'
					+ '</div>';
			$('#additional-anggota').append(konten);
		}
	});
}
$(document).ready(function(){
	cAutocomplete();
});
function add_row_anggota() {
	konten = '<div class="form-group row">'
			+ '<div class="offset-sm-3 col-sm-5 col-9">'
			+ '<input type="text" name="anggota[]" autocomplete="off" class="form-control anggota" data-validation="required">'
			+ '<input type="hidden" name="id_anggota[]" class="id_anggota">'
			+ '</div>'
			+ '<div class="col-sm-2 col-3">'
			+ '<input type="text" name="posisi[]" autocomplete="off" class="form-control posisi" disabled value="Anggota">'
			+ '</div>'
			+ '<div class="col-sm-2 col-3">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>';
	$('#additional-anggota').append(konten);
	cAutocomplete();
}
$('.btn-add-anggota').click(function(){
	add_row_anggota();
});
$(document).on('click','.btn-remove-anggota',function(){
	$(this).closest('.form-group').remove();
});
$(document).on('blur','.anggota',function(){
	if($(this).parent().find('.id_anggota').val() == '0' || $(this).parent().find('.id_anggota').val() == '') {
		$(this).val('');
	}
});
function cAutocomplete() {
	$('.anggota').autocomplete({
		serviceUrl: base_url + 'manajemen_rekanan/kunjungan_langsung/get_tim_kunjungan/',
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
			$(this).parent().find('.id_anggota').val(suggestion.data);
			var n = 0;
			$('.id_anggota').each(function(){
				if($(this).val() == suggestion.data) n++;
			});
			if(n > 1) {
				$(this).parent().find('.id_anggota').val('');
				$(this).val('');
			}
		}
	});
}
$('#filter').change(function(){
	var url = base_url + 'manajemen_rekanan/kunjungan_langsung/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});
$(document).on('click','.btn-print1',function(){
	window.open(base_url + 'manajemen_rekanan/kunjungan_langsung/dt_pendukung/' + $(this).attr('data-id'),'_blank');
});
$(document).on('click','.btn-print2',function(){
	window.open(base_url + 'manajemen_rekanan/kunjungan_langsung/dt_wawancara/' + $(this).attr('data-id'),'_blank');
});
function openForm() {
	if( $('[data-openid]').attr('data-openid') != '0' && $('.btn-input[data-id="'+$('[data-openid]').attr('data-openid')+'"]').length == 1 ) {
		$('.btn-input[data-id="'+$('[data-openid]').attr('data-openid')+'"]').trigger('click');
		$('[data-openid]').removeAttr('data-openid');
	}
}
</script>
