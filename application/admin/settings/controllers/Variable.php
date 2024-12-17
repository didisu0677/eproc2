<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Variable extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['panitia']  = get_data('tbl_m_panitia_pengadaan a',[
		    'select'	=> 'a.*,b.nama',
		    'join'		=> 'tbl_user b ON a.id_panitia = b.kode type LEFT',
		               'where' => [
		                   'a.is_active' => 1
		               ]
		])->result_array();
		render($data);
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_m_variable','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data = post();
		$divisi = get_data('tbl_user','id',$data['panitia_id'])->row();
		$data['nama_panitia'] = $divisi->nama;
		// debug($data['nama_panitia']);die;
		$response = save_data('tbl_m_variable',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_variable','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['panitia_id' => 'panitia_id','lokasi_pengadaan' => 'lokasi_pengadaan','pemberi_tugas' => 'pemberi_tugas','peserta_kurang_dari' => 'peserta_kurang_dari','peserta_sah_kurang_dari' => 'peserta_sah_kurang_dari','bayar_lewat' => 'bayar_lewat','bayar_di' => 'bayar_di','lokasi' => 'lokasi','ttd' => 'ttd','jabatan' => 'jabatan','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_variable',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['panitia_id','lokasi_pengadaan','pemberi_tugas','peserta_kurang_dari','peserta_sah_kurang_dari','bayar_lewat','bayar_di','lokasi','ttd','jabatan','is_active'];
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
					$save = insert_data('tbl_m_variable',$data);
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
		$arr = ['panitia_id' => 'Panitia Id','lokasi_pengadaan' => 'Lokasi Pengadaan','pemberi_tugas' => 'Pemberi Tugas','peserta_kurang_dari' => 'Peserta Kurang Dari','peserta_sah_kurang_dari' => 'Peserta Sah Kurang Dari','bayar_lewat' => 'Bayar Lewat','bayar_di' => 'Bayar Di','lokasi' => 'Lokasi','ttd' => 'Ttd','jabatan' => 'Jabatan','is_active' => 'Aktif'];
		$data = get_data('tbl_m_variable')->result_array();
		$config = [
			'title' => 'data_variable',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}
