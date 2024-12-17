<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jenis_pengadaan extends BE_Controller {

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
		$data = get_data('tbl_jenis_pengadaan','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data = post();
		$data['is_active']	= 1;
		$response = save_data('tbl_jenis_pengadaan',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_jenis_pengadaan','id',post('id'),['id_jenis_pengadaan'=>'tbl_m_bobot_evaluasi']);
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kode' => 'kode','jenis_pengadaan' => 'jenis_pengadaan'];
		$config[] = [
			'title' => 'template_import_jenis_pengadaan',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['kode','jenis_pengadaan'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data 	= $this->simpleexcel->parsing($i,$j);
					$data['is_active']	= 1;
					$check 	= get_data('tbl_jenis_pengadaan','kode',$data['kode'])->row();
					if(!isset($check->id)) {
						if(!$data['kode']) {
							$data['kode']		= generate_code('tbl_jenis_pengadaan','kode');
						}
						$data['create_at'] 	= date('Y-m-d H:i:s');
						$data['create_by'] 	= user('nama');
						$save = insert_data('tbl_jenis_pengadaan',$data);
					}
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
		$arr = ['kode' => 'Kode','jenis_pengadaan' => 'Jenis Pengadaan'];
		$data = get_data('tbl_jenis_pengadaan')->result_array();
		$config = [
			'title' => 'data_jenis_pengadaan',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}