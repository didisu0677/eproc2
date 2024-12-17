<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluasi_v extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		include_lang('pengadaan');
		render();
	}

	function data() {
		$config	= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false,
			'button'		=> [
				button_serverside('btn-info',base_url('pengadaan_v/evaluasi_v/detail/'),['fa-search',lang('detil'),true],'btn-detail'),
			],
			'where'			=> [
				'id_vendor'			=> user('id_vendor'),
				'lolos_penawaran'	=> 1
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
			$data 			= get_data('tbl_aanwijzing_vendor','id = '.$id[0].' AND id_vendor = '.user('id_vendor'))->row_array();
			if(isset($data['id'])) {
				$this->load->helper('pengadaan');
				$data['title']				= $data['nomor_pengadaan'];
				$data['id_rks_aanwijzing']	= id_by_nomor($data['nomor_pengajuan'],'rks','aanwijzing');
				$data['aanwijzing']			= get_data('tbl_aanwijzing','nomor_pengadaan',$data['nomor_pengadaan'])->row();
				include_lang('pengadaan');
				render($data);
			} else render('404');
		} else render('404');
	}

	function dokumen_rks($encode_id='') {
		$id 	= decode_id($encode_id);
		if(count($id) == 2) {
			$rks 	= get_data('tbl_rks','id',$id[0])->row();
			if(isset($rks->id) && $rks->file) {
				$data['file']	= json_decode($rks->file,true);
				if(count($data['file'])) render($data,'layout:false');
				else echo lang('tidak_ada_data');
			} else echo lang('tidak_ada_data');
		} else echo lang('tidak_ada_data');
	}
}