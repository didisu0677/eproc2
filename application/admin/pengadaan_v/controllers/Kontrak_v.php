<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kontrak_v extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		include_lang('manajemen_kontrak');
		render();
	}

	function data() {
		$config	= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false,
			'button'		=> [
				button_serverside('btn-info',base_url('pengadaan_v/kontrak_v/detail/'),['fa-search',lang('detil'),true],'btn-detail'),
			],
			'where'			=> [
				'id_vendor'			=> user('id_vendor')
			],
			'sort_by'		=> 'id',
			'sort'			=> 'DESC'
		];
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_kontrak','id = '.$id[0].' AND id_vendor = '.user('id_vendor'))->row_array();
			if(isset($data['id'])) {
				$data['title']				= $data['nomor_kontrak'];
				include_lang('manajemen_kontrak');
				render($data);
			} else render('404');
		} else render('404');
	}

}