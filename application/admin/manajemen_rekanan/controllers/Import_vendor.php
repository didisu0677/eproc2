<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import_vendor extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_vendor','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_vendor',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_vendor','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kode_rekanan' => 'kode_rekanan','jenis_rekanan' => 'jenis_rekanan','nama' => 'nama','npwp' => 'npwp','id_kategori_rekanan' => 'id_kategori_rekanan','kategori_rekanan' => 'kategori_rekanan','id_bentuk_badan_usaha' => 'id_bentuk_badan_usaha','bentuk_badan_usaha' => 'bentuk_badan_usaha','id_status_perusahaan' => 'id_status_perusahaan','status_perusahaan' => 'status_perusahaan','no_identitas' => 'no_identitas','tanggal_berakhir_identitas' => 'tanggal_berakhir_identitas','id_kualifikasi' => 'id_kualifikasi','kualifikasi' => 'kualifikasi','id_asosiasi' => 'id_asosiasi','asosiasi' => 'asosiasi','id_unit_daftar' => 'id_unit_daftar','unit_daftar' => 'unit_daftar','alamat' => 'alamat','id_negara' => 'id_negara','nama_negara' => 'nama_negara','id_provinsi' => 'id_provinsi','nama_provinsi' => 'nama_provinsi','id_kota' => 'id_kota','nama_kota' => 'nama_kota','id_kecamatan' => 'id_kecamatan','nama_kecamatan' => 'nama_kecamatan','id_kelurahan' => 'id_kelurahan','nama_kelurahan' => 'nama_kelurahan','kode_pos' => 'kode_pos','no_telepon' => 'no_telepon','no_fax' => 'no_fax','email' => 'email','nama_cp' => 'nama_cp','hp_cp' => 'hp_cp','email_cp' => 'email_cp','id_divisi' => 'id_divisi','divisi' => 'divisi','terdaftar_sejak' => 'terdaftar_sejak','is_pendaftar' => 'is_pendaftar','file' => 'file','verifikasi_dokumen' => 'verifikasi_dokumen','tanggal_verifikasi' => 'tanggal_verifikasi','nomor_kunjungan' => 'nomor_kunjungan','kunjungan' => 'kunjungan','tanggal_kunjungan' => 'tanggal_kunjungan','laporan_kunjungan' => 'laporan_kunjungan','nomor_rekomendasi' => 'nomor_rekomendasi','rekomendasi' => 'rekomendasi','status_drm' => 'status_drm','jangka_waktu' => 'jangka_waktu','tanggal_approve' => 'tanggal_approve','invalid_password' => 'invalid_password','is_active' => 'is_active','status_sp' => 'status_sp','id_temp' => 'id_temp'];
		$config[] = [
			'title' => 'template_import_import_vendor',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['kode_rekanan','jenis_rekanan','nama','npwp','id_kategori_rekanan','kategori_rekanan','id_bentuk_badan_usaha','bentuk_badan_usaha','id_status_perusahaan','status_perusahaan','no_identitas','tanggal_berakhir_identitas','id_kualifikasi','kualifikasi','id_asosiasi','asosiasi','id_unit_daftar','unit_daftar','alamat','id_negara','nama_negara','id_provinsi','nama_provinsi','id_kota','nama_kota','id_kecamatan','nama_kecamatan','id_kelurahan','nama_kelurahan','kode_pos','no_telepon','no_fax','email','nama_cp','hp_cp','email_cp','id_divisi','divisi','terdaftar_sejak','is_pendaftar','file','verifikasi_dokumen','tanggal_verifikasi','nomor_kunjungan','kunjungan','tanggal_kunjungan','laporan_kunjungan','nomor_rekomendasi','rekomendasi','status_drm','jangka_waktu','tanggal_approve','invalid_password','is_active','status_sp','id_temp'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$data['create_at'] = date('Y-m-d H:i:s');
					$data['create_by'] = user('nama');
					$save = insert_data('tbl_vendor',$data);
					if($save) $c++;
				}
			}
		}
		$response = [
			'status' => 'success',
			'message' => $c.' '.lang('data_berhasil_disimpan').'.'
		];
		@unlink($file);
		render($response,'json');
	}

	function export() {
		ini_set('memory_limit', '-1');
		$arr = ['kode_rekanan' => 'Kode Rekanan','jenis_rekanan' => 'Jenis Rekanan','nama' => 'Nama','npwp' => 'Npwp','id_kategori_rekanan' => 'Id Kategori Rekanan','kategori_rekanan' => 'Kategori Rekanan','id_bentuk_badan_usaha' => 'Id Bentuk Badan Usaha','bentuk_badan_usaha' => 'Bentuk Badan Usaha','id_status_perusahaan' => 'Id Status Perusahaan','status_perusahaan' => 'Status Perusahaan','no_identitas' => 'No Identitas','tanggal_berakhir_identitas' => '-dTanggal Berakhir Identitas','id_kualifikasi' => 'Id Kualifikasi','kualifikasi' => 'Kualifikasi','id_asosiasi' => 'Id Asosiasi','asosiasi' => 'Asosiasi','id_unit_daftar' => 'Id Unit Daftar','unit_daftar' => 'Unit Daftar','alamat' => 'Alamat','id_negara' => 'Id Negara','nama_negara' => 'Nama Negara','id_provinsi' => 'Id Provinsi','nama_provinsi' => 'Nama Provinsi','id_kota' => 'Id Kota','nama_kota' => 'Nama Kota','id_kecamatan' => 'Id Kecamatan','nama_kecamatan' => 'Nama Kecamatan','id_kelurahan' => 'Id Kelurahan','nama_kelurahan' => 'Nama Kelurahan','kode_pos' => 'Kode Pos','no_telepon' => 'No Telepon','no_fax' => 'No Fax','email' => 'Email','nama_cp' => 'Nama Cp','hp_cp' => 'Hp Cp','email_cp' => 'Email Cp','id_divisi' => 'Id Divisi','divisi' => 'Divisi','terdaftar_sejak' => '-dTerdaftar Sejak','is_pendaftar' => 'Is Pendaftar','file' => 'File','verifikasi_dokumen' => 'Verifikasi Dokumen','tanggal_verifikasi' => '-dTanggal Verifikasi','nomor_kunjungan' => 'Nomor Kunjungan','kunjungan' => 'Kunjungan','tanggal_kunjungan' => '-dTanggal Kunjungan','laporan_kunjungan' => 'Laporan Kunjungan','nomor_rekomendasi' => 'Nomor Rekomendasi','rekomendasi' => 'Rekomendasi','status_drm' => 'Status Drm','jangka_waktu' => 'Jangka Waktu','tanggal_approve' => 'Tanggal Approve','invalid_password' => 'Invalid Password','is_active' => 'Aktif','status_sp' => 'Status Sp','id_temp' => 'Id Temp'];
		$data = get_data('tbl_vendor')->result_array();
		$config = [
			'title' => 'data_import_vendor',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}