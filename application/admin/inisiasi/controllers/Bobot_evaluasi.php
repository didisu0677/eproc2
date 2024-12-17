<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bobot_evaluasi extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['opt_id_jenis_pengadaan'] = get_data('tbl_jenis_pengadaan','is_active',1)->result_array();
		render($data);
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_m_bobot_evaluasi','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$data 				= post();
		$response 			= save_data('tbl_m_bobot_evaluasi',$data,post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_m_bobot_evaluasi','id',post('id'));
		render($response,'json');
	}

}