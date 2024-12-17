<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Definisi_pasal extends BE_Controller {

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
		$data = get_data('tbl_m_definisi_pasal','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data = post();
		$data['is_active']	= 1;
		$data['deskripsi'] = post('deskripsi','html');
		$response = save_data('tbl_m_definisi_pasal',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_definisi_pasal','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kode' => 'kode','kata_kunci' => 'kata_kunci','deskripsi' => 'deskripsi','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_definisi_pasal',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['kode','kata_kunci','deskripsi','is_active'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		$u = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$check_kode_jaminan = get_data('tbl_m_definisi_pasal','kode',$data['kode'])->row();
					$check_kata_kunci = get_data('tbl_m_definisi_pasal','kata_kunci',$data['kata_kunci'])->row();
					if(isset($check_kode_jaminan->kode) || isset($check_kata_kunci->kata_kunci)) {
						$id = 0;
						$id = isset($check_kode_jaminan->id) ? $check_kode_jaminan->id : $id;
						$id = isset($check_kata_kunci->id) ? $check_kata_kunci->id : $id;
						$data['update_at'] = date('Y-m-d H:i:s');
						$data['update_by'] = user('nama');
						$save = update_data('tbl_m_definisi_pasal',$data,'id',$id);
						if($save) $u++;
					} else {
						$data['create_at'] = date('Y-m-d H:i:s');
						$data['create_by'] = user('nama');
						$save = insert_data('tbl_m_definisi_pasal',$data);
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
		$arr = ['kode' => 'Kode','kata_kunci' => 'Kata Kunci','deskripsi' => 'Deskripsi','is_active' => 'Aktif'];
		$data = get_data('tbl_m_definisi_pasal')->result_array();
		$config = [
			'title' => 'data_definisi_pasal',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}