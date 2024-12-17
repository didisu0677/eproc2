<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grup_persetujuan_penetapan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['opt_id_user'] = get_data('tbl_user',[
			'where'	=> [
				'is_active'	=> 1,
				'id_group'	=> id_group_access('approval_pengadaan')
			]
		])->result_array();
		if(user('is_kanwil')) {
			$data['unit_kerja']	= get_data('tbl_m_unit','id = "'.user('id_unit_kerja').'"')->result_array();
		} else {
			$data['unit_kerja']	= get_data('tbl_m_unit','is_active = 1')->result_array();
		}
		render($data);
	}

	function data() {
		$config['access_view'] = false;
		if(get('id_unit_kerja')) $config['where']['id_unit_kerja'] = get('id_unit_kerja');
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_m_penyetuju_penetapan','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
	    $data = post();
	    $dt_user = get_data('tbl_user','id',post('id_user'))->row();
	    if(isset($dt_user->id)){
			$data['username']		= $dt_user->username;
			$data['nama_lengkap']	= $dt_user->nama;
	    }
	    $data['is_active']	= 1;
	    
		$response = save_data('tbl_m_penyetuju_penetapan',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_penyetuju_penetapan','id',post('id'));
		render($response,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['nama_persetujuan' => 'nama_persetujuan','limit_persetujuan' => 'limit_persetujuan','id_user' => 'id_user'];
		$config[] = [
			'title' => 'template_import_grup_persetujuan_penetapan',
			'header' => $arr,
		];
		$id_user = get_data('tbl_user',[
			'select' => 'id,username,nama',
			'where' => 'is_active = 1'
		])->result_array();
		$config[] = [
			'title' => 'data_user',
			'data' => $id_user,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['nama_persetujuan','limit_persetujuan','id_user'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$dt_user = get_data('tbl_user','id',post('id_user'))->row();
					if(isset($dt_user->id)){
						$data['username']		= $dt_user->username;
						$data['nama_lengkap']	= $dt_user->nama;
					}			
					$data['create_at'] = date('Y-m-d H:i:s');
					$data['create_by'] = user('nama');
					$save = insert_data('tbl_m_penyetuju_penetapan',$data);
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
		$arr = ['nama_persetujuan' => 'Nama Persetujuan','limit_persetujuan' => 'Limit Persetujuan','username' => 'Username','nama_lengkap'=>'Nama Lengkap'];
		$data = get_data('tbl_m_penyetuju_penetapan')->result_array();
		$config = [
			'title' => 'data_grup_persetujuan_penetapan',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}