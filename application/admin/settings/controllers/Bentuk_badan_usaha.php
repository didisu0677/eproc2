<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bentuk_badan_usaha extends BE_Controller {

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
		$data = get_data('tbl_m_bentuk_badan_usaha','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data = post();
		$data['is_active'] = 1;
		$response = save_data('tbl_m_bentuk_badan_usaha',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_bentuk_badan_usaha','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kode' => 'kode','bentuk_badan_usaha' => 'bentuk_badan_usaha','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_bentuk_badan_usaha',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['kode','bentuk_badan_usaha','is_active'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		$u = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$check_kode = get_data('tbl_m_bentuk_badan_usaha','kode',$data['kode'])->row();
					if(isset($check_kode->kode)) {
						$id = $check_kode->id;
						$data['update_at'] = date('Y-m-d H:i:s');
						$data['update_by'] = user('nama');
						$save = update_data('tbl_m_bentuk_badan_usaha',$data,'id',$id);
						if($save) $u++;
					} else {
						$data['create_at'] = date('Y-m-d H:i:s');
						$data['create_by'] = user('nama');
						$save = insert_data('tbl_m_bentuk_badan_usaha',$data);
						if($save) $c++;
					}
				}
			}
		}
		$response = [
			'status' => 'success',
			'message' => $c.' '.lang('data_berhasil_disimpan').'. '.$u.' '.lang('data_berhasil_diperbaharui').'.'
		];
		@unlink($file);
		render($response,'json');
	}

	function export() {
		ini_set('memory_limit', '-1');
		$arr = ['kode' => 'Kode','bentuk_badan_usaha' => 'Bentuk Badan Usaha','is_active' => 'Aktif'];
		$data = get_data('tbl_m_bentuk_badan_usaha')->result_array();
		$config = [
			'title' => 'data_bentuk_badan_usaha',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}