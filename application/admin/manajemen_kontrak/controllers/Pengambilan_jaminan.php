<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengambilan_jaminan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$config				= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false,
			'where'			=> [
				'status'	=> 0
			],
			'button'		=> button_serverside('btn-success','btn-pengambilan',['fa-edit',lang('pengambilan_jaminan'),true],'act-pengambilan')
		];
		if(user('is_kanwil')) {
			$config['where']['id_unit_kerja']	= user('id_unit_kerja');
		}
		$data = data_serverside($config);
		render($data,'json');
	}

	function detail($id=0) {
		$data 	= get_data('tbl_jaminan','id',$id)->row_array();
		if(isset($data['id'])) {
			render($data,'layout:false');
		} else echo lang('tidak_ada_data');
	}

	function save() {
		$data 	= post();
		$data['id'] = isset($data['id']) && $data['id'] ? $data['id'] : 0;
		if($data['id']) $data['status'] = 1;
		$response = save_data('tbl_jaminan',$data,post(':validation'),true);
		if($response['status'] == 'success' && $data['id']) {
			$dt 	= get_data('tbl_jaminan','id',$data['id'])->row();
			if(isset($dt->id)) {
				update_data('tbl_pemenang_pengadaan',['status_jaminan'=>0],'nomor_spk',$dt->nomor_spk);
			}
		}
		render($response,'json');
	}
}