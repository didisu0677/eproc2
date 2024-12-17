<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Metode_pengadaan extends BE_Controller {

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
		$data = get_data('tbl_metode_pengadaan','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data 				= post();
		$data['is_active']	= 1;
		$response = save_data('tbl_metode_pengadaan',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_metode_pengadaan','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['kode' => 'kode','metode_pengadaan' => 'metode_pengadaan','tipe' => 'kategori','limit_bawah_pengadaan' => 'limit_bawah_pengadaan','limit_atas_pengadaan' => 'limit_atas_pengadaan'];
		$config[] = [
			'title' => 'template_import_metode_pengadaan',
			'header' => $arr,
		];
		$config[] = [
			'title' 	=> 'kategori',
			'data'		=> [
				['kategori'=>'Pemilihan Langsung'],
				['kategori'=>'Penunjukan Langsung'],
				['kategori'=>'Lelang']
			]
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['kode','metode_pengadaan','tipe','limit_bawah_pengadaan','limit_atas_pengadaan'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data 	= $this->simpleexcel->parsing($i,$j);
					$check 	= get_data('tbl_metode_pengadaan','kode',$data['kode'])->row();
					if(!isset($check->id)) {
						$data['limit_atas_pengadaan']	= str_replace([',','.'], '', $data['limit_atas_pengadaan']);
						$data['limit_bawah_pengadaan']	= str_replace([',','.'], '', $data['limit_bawah_pengadaan']);
						$data['is_active']				= 1;
						if(!$data['kode']) {
							$data['kode']		= generate_code('tbl_metode_pengadaan','kode');
						}
						$data['create_at'] 	= date('Y-m-d H:i:s');
						$data['create_by'] 	= user('nama');
						$save = insert_data('tbl_metode_pengadaan',$data);
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
		$arr = ['kode' => 'Kode','metode_pengadaan' => 'Metode Pengadaan','tipe' => 'Kategori','limit_bawah_pengadaan' => '-cLimit Bawah Pengadaan','limit_atas_pengadaan' => '-cLimit Atas Pengadaan'];
		$data = get_data('tbl_metode_pengadaan')->result_array();
		$config = [
			'title' => 'data_metode_pengadaan',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}