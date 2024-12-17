<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Calon_rekanan extends BE_Controller {



	function __construct() {

		parent::__construct();

	}



	function index() {

		$data['id']			= 0;

		$id					= decode_id(get('i'));

		if(is_array($id) && isset($id[0])) $data['id'] = $id[0];

		render($data);

	}



	function data() {

		$config	= [

			'access_view'	=> true,

			'access_edit'	=> false,

			'access_delete'	=> false

		];

		if(setting('jumlah_salah_password')) {
			$config['button'][]	= button_serverside('btn-primary','btn-unlock',['fa-unlock',lang('buka_kunci'),true],'act-unlock',['invalid_password >=' => setting('jumlah_salah_password')]);
		}


		$config['where']['status_drm']	= 0;

		$config['button'][]	= button_serverside('btn-success','btn-change-password',['fa-key',lang('ubah_kata_sandi'),true],'act-change-password');

		$data 	= data_serverside($config);

		render($data,'json');

	}



	function detail($id=0) {

		$data = get_data('tbl_vendor','id',$id)->row_array();

		if(isset($data['id'])) {

			render($data,'layout:false');

		} else {

			echo lang('tidak_ada_data');

		}

	}



	function change_password() {

		$data 	= post();

		update_data('tbl_user',[

			'password'				=> $data['password'],

			'change_password_by'	=> user('nama'),

			'change_password_at'	=> date('Y-m-d H:i:s')

		],'id_vendor',$data['id_vendor']);

		$user 	= get_data('tbl_user','id_vendor',$data['id_vendor'])->row();

		if(isset($user->id)) {

			$check 	= get_data('tbl_history_password',[

				'where'	=> [

					'id_user'	=> $user->id,

					'password'	=> md5(post('password'))

				]

			])->row();

			if(isset($check->id)) {

				update_data('tbl_history_password',['tanggal'=>date('Y-m-d H:i:s')],'id',$check->id);

			} else {

				insert_data('tbl_history_password',[

					'id_user'	=> $user->id,

					'password'	=> md5(post('password')),

					'tanggal'	=> date('Y-m-d H:i:s')

				]);

			}

		}

		render([

			'status'	=> 'success',

			'message'	=> lang('data_berhasil_disimpan')

		],'json');

	}

	function unlock() {
		$data = [
			'id'				=> post('id'),
			'invalid_password'	=> 0
		];
		$res = save_data('tbl_vendor',$data,[],true);
		update_data('tbl_user',['invalid_password'=>0],'id_vendor',post('id'));
		render($res,'json');
	}



}