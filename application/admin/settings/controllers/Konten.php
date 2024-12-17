<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Konten extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['page']	= get('p') == 'disclaimer' ? 'disclaimer' : 'kebijakan';
		render($data);
	}

	function save() {
		$check 	= get_data('tbl_setting','_key',post('key'))->row();
		if(isset($check->id)) {
			update_data('tbl_setting',['_value'=>post('konten','html')],'_key',post('key'));
		} else {
			insert_data('tbl_setting',['_value'=>post('konten','html'),'_key'=>post('key')]);
		}
		$response 	= array(
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_diperbaharui')
		);
		render($response,'json');
	}

}