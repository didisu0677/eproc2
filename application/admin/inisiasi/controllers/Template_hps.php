<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_hps extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['klasifikasi']	= get_data('tbl_m_klasifikasi','is_active = 1 AND pilihan = 1')->result_array();
		render($data);
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_template_hps','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$response = save_data('tbl_template_hps',post(),post(':validation'));
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_template_hps','id',post('id'));
		render($response,'json');
	}

}