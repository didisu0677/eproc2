<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Klasifikasi extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function sortable() {
		render();
	}

	function data($tipe = 'table') {
		$menu = menu();
		if($menu['access_view']) {
			$data['klasifikasi'][0] = get_data('tbl_m_klasifikasi','parent_id',0)->result();
			foreach($data['klasifikasi'][0] as $m0) {
				$data['klasifikasi'][$m0->id] = get_data('tbl_m_klasifikasi','parent_id',$m0->id)->result();
				foreach($data['klasifikasi'][$m0->id] as $m1) {
					$data['klasifikasi'][$m1->id] = get_data('tbl_m_klasifikasi','parent_id',$m1->id)->result();
				}
			}
			$data['access_edit']	= $menu['access_edit'];
			$data['access_delete']	= $menu['access_delete'];
			$response	= array(
				'table'		=> $this->load->view('settings/klasifikasi/table',$data,true),
				'option'	=> $this->load->view('settings/klasifikasi/option',$data,true)
			);
		} else {
			$response	= array(
				'status'	=> 'error',
				'message'	=> 'Permission Denied'
			);
		}
		render($response,'json');
	}

	function get_data() {
		$data = get_data('tbl_m_klasifikasi','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data 		= post();
		$data['is_active']	= 1;
		$validation	= post(':validation');
		$response = save_data('tbl_m_klasifikasi',$data,$validation);
		if($response['status'] == 'success') {
			$mn = get_data('tbl_m_klasifikasi','id',$response['id'])->row_array();
			if($mn['parent_id'] == 0) {
				update_data('tbl_m_klasifikasi',array('level1'=>$mn['id']),'id',$mn['id']);
			} else {
				$parent = get_data('tbl_m_klasifikasi','id',$mn['parent_id'])->row_array();
				$data_update = array(
					'level1' => $parent['level1'],
					'level2' => $parent['level2'],
					'level3' => $parent['level3']
				);
				if(!$parent['level2']) $data_update['level2'] = $mn['id'];
				else if(!$parent['level3']) $data_update['level3'] = $mn['id'];
				update_data('tbl_m_klasifikasi',$data_update,'id',$mn['id']);
			}
		}
		render($response,'json');
	}

	function delete() {
		$child	= array(
			'level1'	=> 'tbl_m_klasifikasi',
			'level2'	=> 'tbl_m_klasifikasi'
		);
		$response = destroy_data('tbl_m_klasifikasi','id',post('id'),$child);
		render($response,'json');
	}

}