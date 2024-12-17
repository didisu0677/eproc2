<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Barang_jasa extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['opt_id_klasifikasi'] = get_data('tbl_m_klasifikasi','is_active = 1 AND pilihan = 1')->result_array();
		$data['opt_id_satuan'] = get_data('tbl_m_satuan','is_active',1)->result_array();
		render($data);
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_m_item','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data = post();
		$data['is_active'] = 1;
		$response = save_data('tbl_m_item',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_item','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['id_klasifikasi' => 'id_klasifikasi','kode' => 'kode','nama' => 'nama','spesifikasi' => 'spesifikasi','id_satuan' => 'id_satuan','harga' => 'harga','sumber_harga' => 'sumber_harga','tanggal_update' => 'tanggal_update','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_barang_jasa',
			'header' => $arr,
		];
		$id_klasifikasi = get_data('tbl_m_klasifikasi',[
			'select' => 'id,kode,klasifikasi',
			'where' => 'is_active = 1 AND pilihan=1'
		])->result_array();
		$config[] = [
			'title' => 'data_m_klasifikasi',
			'data' => $id_klasifikasi,
		];
		$id_satuan = get_data('tbl_m_satuan',[
			'select' => 'id,satuan',
			'where' => 'is_active = 1'
		])->result_array();
		$config[] = [
			'title' => 'data_m_satuan',
			'data' => $id_satuan,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['id_klasifikasi','kode','nama','spesifikasi','id_satuan','harga','sumber_harga','tanggal_update','is_active'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		$u = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$check_kode = get_data('tbl_m_item','kode',$data['kode'])->row();
					if(isset($check_kode->kode)) {
						$id = $check_kode->id;
						$data['update_at'] = date('Y-m-d H:i:s');
						$data['update_by'] = user('nama');
						$save = update_data('tbl_m_item',$data,'id',$id);
						if($save) $u++;
					} else {
						$data['create_at'] = date('Y-m-d H:i:s');
						$data['create_by'] = user('nama');
						$save = insert_data('tbl_m_item',$data);
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
		$arr = ['id_klasifikasi_klasifikasi' => 'Klasifikasi','kode' => 'Kode','nama' => 'Nama','spesifikasi' => 'Spesifikasi','id_satuan_satuan' => 'Satuan','harga' => '-cHarga','sumber_harga' => 'Sumber Harga','tanggal_update' => '-dTanggal Berlaku Harga','is_active' => 'Aktif'];
		$data = get_data('tbl_m_item',[
			'select' => 'tbl_m_item.*,tbl_m_klasifikasi.klasifikasi AS id_klasifikasi_klasifikasi,tbl_m_satuan.satuan AS id_satuan_satuan',
			'join' => [
				'tbl_m_klasifikasi on tbl_m_item.id_klasifikasi = tbl_m_klasifikasi.id type left',
				'tbl_m_satuan on tbl_m_item.id_satuan = tbl_m_satuan.id type left',
			]
		])->result_array();
		$config = [
			'title' => 'data_barang_jasa',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}