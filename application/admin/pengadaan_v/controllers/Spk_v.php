<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spk_v extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		include_lang('pengadaan');
		render();
	}

	function data() {
		$config['where']['id_vendor']			= user('id_vendor');
		$config['access_edit'] 					= false;
		$config['access_delete'] 				= false;
		$config['access_view'] 					= false;
		$config['sort_by'] 						= 'id';
		$config['sort']							= 'DESC';
		$config['where']['status_sanggah']		= '1';
		$config['button'][]						= button_serverside('btn-info',base_url('pengadaan_v/spk_v/detail/'),['fa-search',lang('detil'),true],'btn-detail');

		$data 	= data_serverside($config);
		render($data,'json');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_pemenang_pengadaan','id = "'.$id[0].'" AND id_vendor = "'.user('id_vendor').'"')->row_array();
			if(isset($data['id'])) {
				include_lang('pengadaan');
				$data['title']	= $data['nomor_pengadaan'];
				render($data);
			} else render('404');
		} else render('404');
	}
}