<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php echo access_button('delete,active,inactive,export,import'); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('manajemen_rekanan/import_vendor/data'),'tbl_vendor');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('kode_rekanan'),'','data-content="kode_rekanan"');
				th(lang('jenis_rekanan'),'text-center','data-content="jenis_rekanan" data-type="boolean"');
				th(lang('nama'),'','data-content="nama"');
				th(lang('npwp'),'','data-content="npwp"');
				th(lang('id_kategori_rekanan'),'','data-content="id_kategori_rekanan"');
				th(lang('kategori_rekanan'),'','data-content="kategori_rekanan"');
				th(lang('id_bentuk_badan_usaha'),'','data-content="id_bentuk_badan_usaha"');
				th(lang('bentuk_badan_usaha'),'','data-content="bentuk_badan_usaha"');
				th(lang('id_status_perusahaan'),'','data-content="id_status_perusahaan"');
				th(lang('status_perusahaan'),'','data-content="status_perusahaan"');
				th(lang('no_identitas'),'','data-content="no_identitas"');
				th(lang('tanggal_berakhir_identitas'),'','data-content="tanggal_berakhir_identitas" data-type="daterange"');
				th(lang('id_kualifikasi'),'','data-content="id_kualifikasi"');
				th(lang('kualifikasi'),'','data-content="kualifikasi"');
				th(lang('id_asosiasi'),'','data-content="id_asosiasi"');
				th(lang('asosiasi'),'','data-content="asosiasi"');
				th(lang('id_unit_daftar'),'','data-content="id_unit_daftar"');
				th(lang('unit_daftar'),'','data-content="unit_daftar"');
				th(lang('alamat'),'','data-content="alamat"');
				th(lang('id_negara'),'','data-content="id_negara"');
				th(lang('nama_negara'),'','data-content="nama_negara"');
				th(lang('id_provinsi'),'','data-content="id_provinsi"');
				th(lang('nama_provinsi'),'','data-content="nama_provinsi"');
				th(lang('id_kota'),'','data-content="id_kota"');
				th(lang('nama_kota'),'','data-content="nama_kota"');
				th(lang('id_kecamatan'),'','data-content="id_kecamatan"');
				th(lang('nama_kecamatan'),'','data-content="nama_kecamatan"');
				th(lang('id_kelurahan'),'','data-content="id_kelurahan"');
				th(lang('nama_kelurahan'),'','data-content="nama_kelurahan"');
				th(lang('kode_pos'),'','data-content="kode_pos"');
				th(lang('no_telepon'),'','data-content="no_telepon"');
				th(lang('no_fax'),'','data-content="no_fax"');
				th(lang('email'),'','data-content="email"');
				th(lang('nama_cp'),'','data-content="nama_cp"');
				th(lang('hp_cp'),'','data-content="hp_cp"');
				th(lang('email_cp'),'','data-content="email_cp"');
				th(lang('id_divisi'),'','data-content="id_divisi"');
				th(lang('divisi'),'','data-content="divisi"');
				th(lang('terdaftar_sejak'),'','data-content="terdaftar_sejak" data-type="daterange"');
				th(lang('is_pendaftar'),'','data-content="is_pendaftar"');
				th(lang('file'),'','data-content="file"');
				th(lang('verifikasi_dokumen'),'','data-content="verifikasi_dokumen"');
				th(lang('tanggal_verifikasi'),'','data-content="tanggal_verifikasi" data-type="daterange"');
				th(lang('nomor_kunjungan'),'','data-content="nomor_kunjungan"');
				th(lang('kunjungan'),'','data-content="kunjungan"');
				th(lang('tanggal_kunjungan'),'','data-content="tanggal_kunjungan" data-type="daterange"');
				th(lang('laporan_kunjungan'),'text-center','data-content="laporan_kunjungan" data-type="boolean"');
				th(lang('nomor_rekomendasi'),'','data-content="nomor_rekomendasi"');
				th(lang('rekomendasi'),'text-center','data-content="rekomendasi" data-type="boolean"');
				th(lang('status_drm'),'text-center','data-content="status_drm" data-type="boolean"');
				th(lang('jangka_waktu'),'','data-content="jangka_waktu"');
				th(lang('tanggal_approve'),'','data-content="tanggal_approve"');
				th(lang('invalid_password'),'','data-content="invalid_password"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th(lang('status_sp'),'text-center','data-content="status_sp" data-type="boolean"');
				th(lang('id_temp'),'','data-content="id_temp"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form');
	modal_body();
		form_open(base_url('manajemen_rekanan/import_vendor/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('text',lang('kode_rekanan'),'kode_rekanan');
			toggle(lang('jenis_rekanan'),'jenis_rekanan');
			input('text',lang('nama'),'nama');
			input('text',lang('npwp'),'npwp');
			textarea(lang('id_kategori_rekanan'),'id_kategori_rekanan');
			textarea(lang('kategori_rekanan'),'kategori_rekanan');
			input('text',lang('id_bentuk_badan_usaha'),'id_bentuk_badan_usaha');
			input('text',lang('bentuk_badan_usaha'),'bentuk_badan_usaha');
			input('text',lang('id_status_perusahaan'),'id_status_perusahaan');
			input('text',lang('status_perusahaan'),'status_perusahaan');
			input('text',lang('no_identitas'),'no_identitas');
			input('date',lang('tanggal_berakhir_identitas'),'tanggal_berakhir_identitas');
			input('text',lang('id_kualifikasi'),'id_kualifikasi');
			input('text',lang('kualifikasi'),'kualifikasi');
			input('text',lang('id_asosiasi'),'id_asosiasi');
			input('text',lang('asosiasi'),'asosiasi');
			input('text',lang('id_unit_daftar'),'id_unit_daftar');
			input('text',lang('unit_daftar'),'unit_daftar');
			textarea(lang('alamat'),'alamat');
			input('text',lang('id_negara'),'id_negara');
			input('text',lang('nama_negara'),'nama_negara');
			input('text',lang('id_provinsi'),'id_provinsi');
			input('text',lang('nama_provinsi'),'nama_provinsi');
			input('text',lang('id_kota'),'id_kota');
			input('text',lang('nama_kota'),'nama_kota');
			input('text',lang('id_kecamatan'),'id_kecamatan');
			input('text',lang('nama_kecamatan'),'nama_kecamatan');
			input('text',lang('id_kelurahan'),'id_kelurahan');
			input('text',lang('nama_kelurahan'),'nama_kelurahan');
			input('text',lang('kode_pos'),'kode_pos');
			input('text',lang('no_telepon'),'no_telepon');
			input('text',lang('no_fax'),'no_fax');
			input('text',lang('email'),'email','email');
			input('text',lang('nama_cp'),'nama_cp');
			input('text',lang('hp_cp'),'hp_cp');
			input('text',lang('email_cp'),'email_cp','email');
			input('text',lang('id_divisi'),'id_divisi');
			input('text',lang('divisi'),'divisi');
			input('date',lang('terdaftar_sejak'),'terdaftar_sejak');
			input('text',lang('is_pendaftar'),'is_pendaftar');
			textarea(lang('file'),'file');
			input('text',lang('verifikasi_dokumen'),'verifikasi_dokumen');
			input('date',lang('tanggal_verifikasi'),'tanggal_verifikasi');
			input('text',lang('nomor_kunjungan'),'nomor_kunjungan');
			input('text',lang('kunjungan'),'kunjungan');
			input('date',lang('tanggal_kunjungan'),'tanggal_kunjungan');
			toggle(lang('laporan_kunjungan'),'laporan_kunjungan');
			input('text',lang('nomor_rekomendasi'),'nomor_rekomendasi');
			toggle(lang('rekomendasi'),'rekomendasi');
			toggle(lang('status_drm'),'status_drm');
			input('text',lang('jangka_waktu'),'jangka_waktu');
			input('text',lang('tanggal_approve'),'tanggal_approve');
			input('password',lang('invalid_password'),'invalid_password');
			toggle(lang('aktif').'?','is_active');
			toggle(lang('status_sp'),'status_sp');
			input('text',lang('id_temp'),'id_temp');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('manajemen_rekanan/import_vendor/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
