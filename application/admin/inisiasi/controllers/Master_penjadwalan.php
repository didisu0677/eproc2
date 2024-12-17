<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_penjadwalan extends BE_Controller {

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
		$data = get_data('tbl_m_penjadwalan','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data = post();
		$data['is_active']	= 1;
		$response = save_data('tbl_m_penjadwalan',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_penjadwalan','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kode' => 'kode','kata_kunci' => 'Kata Kunci','jadwal' => 'jadwal'];
		$config[] = [
			'title' => 'template_import_master_penjadwalan',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['kode','kata_kunci','jadwal'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data 	= $this->simpleexcel->parsing($i,$j);
					$data['is_active']	= 1;
					$cek 	= get_data('tbl_m_penjadwalan','kode',$data['kode'])->row();
					if(!isset($cek->id)) {
						$check 	= get_data('tbl_m_penjadwalan','kata_kunci',$data['kata_kunci'])->row();
						if(isset($check->id)) {
							$data['update_at'] 	= date('Y-m-d H:i:s');
							$data['update_by'] 	= user('nama');
							$save = update_data('tbl_m_penjadwalan',$data,'id',$check->id);
						} else {
							if(!$data['kode']) {
								$data['kode']		= generate_code('tbl_m_penjadwalan','kode');
							}
							$data['create_at'] 	= date('Y-m-d H:i:s');
							$data['create_by'] 	= user('nama');
							$save = insert_data('tbl_m_penjadwalan',$data);
						}
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
		$arr = ['kode' => 'Kode','kata_kunci' => 'Kata Kunci','jadwal' => 'Jadwal'];
		$data = get_data('tbl_m_penjadwalan')->result_array();
		$config = [
			'title' => 'data_master_penjadwalan',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}