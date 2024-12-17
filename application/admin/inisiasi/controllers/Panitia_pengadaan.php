<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panitia_pengadaan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['user']	= get_data('tbl_user',[
			'where'	=> [
				'is_active'	=> 1,
				'id_group'	=> id_group_access('inisiasi_pengadaan')
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
		$config	= [];
		if(get('id_unit_kerja')) $config['where']['id_unit_kerja'] = get('id_unit_kerja');
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_m_panitia_pengadaan','id',post('id'))->row_array();
		$data['detail']	= get_data('tbl_anggota_panitia a',[
		    'select'	=> 'a.*,b.deskripsi',
		    'join'		=> 'tbl_m_panitia_pengadaan b ON a.id_m_panitia = b.id TYPE LEFT',
		    'where'		=> 'a.id_m_panitia = '.post('id'),
		    'sort_by'	=> 'id'
		])->result_array();
		render($data,'json');
	}

	function save() {
	    $data 			= post();
	    $nama_panitia	= post('nama_panitia');
	    $jabatan		= post('jabatan');
	    $posisi_panitia	= post('posisi_panitia');
	    $username		= post('username');
	    $data['is_active']	= 1;
	    $response		= save_data('tbl_m_panitia_pengadaan',$data,post(':validation'));
	    if($response['status'] == 'success') {
	        $dt_master	= get_data('tbl_m_panitia_pengadaan','id',$response['id'])->row_array();
	        delete_data('tbl_anggota_panitia','id_m_panitia',$response['id']);

			$c	= [];
			if(is_array($nama_panitia)) {
				foreach($nama_panitia as $k => $v) {
					$c[$k]		= [
		                'id_m_panitia'		=> $response['id'],
		                'id_panitia'		=> isset($dt_master['id_panitia']) ? $dt_master['id_panitia'] : '',
		                'nama_panitia'		=> $nama_panitia[$k],
		                'jabatan'			=> $jabatan[$k],
		                'posisi_panitia'	=> $posisi_panitia[$k],
		                'username'			=> $username[$k],
					];
					$dt_user 			= get_data('tbl_user','username',$username[$k])->row_array();
					$c[$k]['userid']	= isset($dt_user['id']) ? $dt_user['id'] : '';
				}
			}
			if(count($c) > 0) insert_batch('tbl_anggota_panitia',$c);
	    }
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_panitia_pengadaan','id',post('id'),[
			'id_m_panitia'	=> 'tbl_anggota_panitia'
		]);
		render($response,'json');
	}

}