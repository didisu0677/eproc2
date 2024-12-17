<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mata_anggaran extends BE_Controller {

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
		$data = get_data('tbl_mata_anggaran','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data 				= post();
		$data['is_active']	= 1;
		$response = save_data('tbl_mata_anggaran',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_mata_anggaran','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kode_mata_anggaran' => 'kode_mata_anggaran','nama_anggaran' => 'nama_anggaran','besaran_anggaran' => 'besaran_anggaran'];
		$config[] = [
			'title' => 'template_import_mata_anggaran',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['kode_mata_anggaran','nama_anggaran','besaran_anggaran'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data 	= $this->simpleexcel->parsing($i,$j);
					$check	= get_data('tbl_mata_anggaran','kode_mata_anggaran',$data['kode_mata_anggaran'])->row();
					if(!isset($check->id)) {
						$data['besaran_anggaran']	= str_replace(['.',','], '', $data['besaran_anggaran']);
						$data['is_active']			= 1;
						if(!$data['kode_mata_anggaran']) {
							$data['kode_mata_anggaran']	= generate_code('tbl_mata_anggaran','kode_mata_anggaran');
						}
						$data['create_at'] 			= date('Y-m-d H:i:s');
						$data['create_by'] 			= user('nama');
						$save = insert_data('tbl_mata_anggaran',$data);
						if(isset($save) && $save) $c++;
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
		$arr = ['kode_mata_anggaran' => 'Kode Mata Anggaran','nama_anggaran' => 'Nama Anggaran','besaran_anggaran' => '-cBesaran Anggaran'];
		$data = get_data('tbl_mata_anggaran')->result_array();
		$config = [
			'title' => 'data_mata_anggaran',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}