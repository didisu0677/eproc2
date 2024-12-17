<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<select class="select2 infinity custom-select" id="filter">
				<option value="0"><?php echo lang('jaminan_yang_belum_diambil'); ?></option>
				<option value="1"><?php echo lang('jaminan_yang_sudah_diambil'); ?></option>
			</select>
			<?php echo access_button(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('manajemen_kontrak/penerimaan_jaminan/data'),'tbl_jaminan');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_jaminan'),'','data-content="nomor_jaminan"');
				th(lang('jenis_jaminan'),'','data-content="jenis_jaminan"');
				th(lang('nomor_spk'),'','data-content="nomor_spk"');
				th(lang('nama_pekerjaan'),'','data-content="nama_pengadaan"');
				th(lang('nilai_pekerjaan'),'text-right','data-content="nilai_pengadaan" data-type="currency"');
				th(lang('nilai_jaminan'),'text-right','data-content="nilai_jaminan" data-type="currency"');
				th(lang('nama_rekanan'),'','data-content="nama_vendor"');
				th(lang('tanggal_mulai'),'','data-content="tanggal_mulai" data-type="daterange"');
				th(lang('tanggal_selesai'),'','data-content="tanggal_selesai" data-type="daterange"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-lg','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('manajemen_kontrak/penerimaan_jaminan/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('hidden',lang('nomor_spk'),'nomor_spk');
			card_open(lang('data_pekerjaan'),'mb-2');
				input('text',lang('nomor_spk'),'spk');
				input('text',lang('nama_pekerjaan'),'nama_pengadaan','','','disabled');
				input('text',lang('nilai_pekerjaan'),'nilai_pengadaan','','','disabled');
				input('text',lang('nama_rekanan'),'nama_vendor','','','disabled');
			card_close();
			card_open(lang('data_jaminan'),'mb-2');
				input('text',lang('nomor_jaminan'),'nomor_jaminan','required|unique');
				select2(lang('jenis_jaminan'),'jenis_jaminan','required',['Pelaksanaan','Pemeliharaan']);
				input('money',lang('nilai_jaminan'),'nilai_jaminan','required');
				input('date',lang('tanggal_mulai'),'tanggal_mulai','required');
				input('date',lang('tanggal_selesai'),'tanggal_selesai','required');
				input('text',lang('bank_pemberi_jaminan'),'bank_pemberi_jaminan','required');
				label(strtoupper(lang('data_yang_menyerahkan_jaminan')));
				sub_open(1);
					input('text',lang('nama'),'nama_penyerah_jaminan','required');
					input('text',lang('telepon'),'telp_penyerah_jaminan','required|phone');
					input('text',lang('email'),'email_penyerah_jaminan','required|email');
					input('date',lang('tanggal_penyerahan'),'tanggal_penyerahan','required');
				sub_close();
			card_close();
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
?>
<script type="text/javascript">
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
</script>