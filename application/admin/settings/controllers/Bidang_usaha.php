<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bidang_usaha extends BE_Controller {

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
			$data['bidang_usaha'][0] = get_data('tbl_m_bidang_usaha','parent_id',0)->result();
			foreach($data['bidang_usaha'][0] as $m0) {
				$data['bidang_usaha'][$m0->id] = get_data('tbl_m_bidang_usaha','parent_id',$m0->id)->result();
			}
			$data['access_edit']	= $menu['access_edit'];
			$data['access_delete']	= $menu['access_delete'];
			$response	= array(
				'table'		=> $this->load->view('settings/bidang_usaha/table',$data,true),
				'option'	=> $this->load->view('settings/bidang_usaha/option',$data,true)
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
		$data = get_data('tbl_m_bidang_usaha','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data 		= post();
		$data['is_active']	= 1;
		$validation	= post(':validation');
		$response = save_data('tbl_m_bidang_usaha',$data,$validation);
		render($response,'json');
	}

	function delete() {
		$child	= array(
			'parent_id'	=> 'tbl_m_bidang_usaha'
		);
		$response = destroy_data('tbl_m_bidang_usaha','id',post('id'),$child);
		render($response,'json');
	}

}