<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('manajemen_rekanan/rekomendasi_usulan/data'),'tbl_vendor');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('kode_rekanan'),'','data-content="kode_rekanan"');
				th(lang('nama_rekanan'),'','data-content="nama"');
				th(lang('jenis_rekanan'),'','data-content="jenis_rekanan" data-replace="1:'.lang('badan_usaha').'|2:'.lang('perorangan').'"');
				th(lang('kategori_rekanan'),'','data-content="kategori_rekanan"');
				th(lang('kualifikasi'),'','data-content="kualifikasi"');
				th(lang('nomor_rekomendasi'),'','data-content="nomor_rekomendasi"');
				th(lang('status'),'','data-content="rekomendasi" data-replace="0:'.lang('belum_diinput').'|1:'.lang('direkomendasikan').'|9:'.lang('tidak_direkomendasikan').'"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('manajemen_rekanan/rekomendasi_usulan/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('hidden',lang('id_vendor'),'id_vendor','required');
			input('text',lang('nama_rekanan'),'nama_rekanan','','','readonly data-readonly');
			textarea(lang('alamat'),'alamat','','','readonly data-readonly');
			col_init(3,4);
			input('text',lang('nomor_rekomendasi'),'nomor_rekomendasi','required|unique|max-length:35','','disabled placeholder="'.lang('otomatis_saat_disimpan').'"');
			input('date',lang('tanggal_rekomendasi'),'tanggal_rekomendasi','required');
			select2(lang('usulan_rekomendasi'),'usulan_rekomendasi','required|infinity',[1=>lang('direkomendasikan'),9=>lang('tidak_direkomendasikan')],'_key');
			input('text',lang('jangka_waktu'),'jangka_waktu','number|required|max-length:2','','',lang('tahun'));
			col_init(3,9);
			textarea(lang('catatan_rekanan'),'catatan_rekanan');
			textarea(lang('keterangan_admin'),'keterangan_admin');
			input('text',lang('rekomendasi_oleh'),'rekomendasi_oleh','required|max-length:100');
			input('text',lang('jabatan'),'jabatan','required|max-length:100');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
?>
<script>
function formOpen() {
	var response = response_edit;
	$('#nama_rekanan').val(response.nm_vendor);
	$('#id_vendor').val(response.i_vendor);
	$('#alamat').val(response.alamat_vendor + ', ' + response.nama_kelurahan + ', ' + response.nama_kecamatan + ', ' + response.nama_kota + ', ' + response.nama_provinsi + ' - ' + response.kode_pos);
}
</script>
