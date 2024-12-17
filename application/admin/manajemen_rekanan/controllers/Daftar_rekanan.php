<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Daftar_rekanan extends BE_Controller {



	function __construct() {

		parent::__construct();

	}



	function index() {

		$data['kategori_rekanan']   = get_data('tbl_m_kategori_rekanan','is_active=1')->result_array();

		$data['bentuk_badan_usaha'] = get_data('tbl_m_bentuk_badan_usaha','is_active=1')->result_array();

		$data['status_perusahaan']  = get_data('tbl_m_status_perusahaan','is_active=1')->result_array();

		$data['kualifikasi']        = get_data('tbl_m_kualifikasi','is_active=1')->result_array();

		$data['asosiasi']           = get_data('tbl_m_asosiasi','is_active=1')->result_array();

		$data['unit']               = get_data('tbl_m_unit','is_active=1')->result_array();

		$data['negara']             = get_data('tbl_m_negara')->result_array();

		$data['provinsi']           = get_data('tbl_m_wilayah','parent_id=0')->result_array();
		$data['kelompok_vendor']	= get_data('tbl_m_kelompok_vendor')->result_array();
		$data['kode_bank']			= get_data('tbl_m_kode_bank')->result_array();
		$data['recont']				= get_data('tbl_m_recont_account')->result_array();

		render($data);

	}



	function data($type='rekanan') {

		$config	= [];

		$vendor_spk = get_data('tbl_kontrak')->result();
		$v_spk=[''];
		foreach ($vendor_spk as $v )  {
			$v_spk[] = $v->id_vendor; 
		}

		$access = menu();
		if(setting('jumlah_salah_password')) {
			$config['button'][]	= button_serverside('btn-primary','btn-unlock',['fa-unlock',lang('buka_kunci'),true],'act-unlock',['invalid_password >=' => setting('jumlah_salah_password')]);
		}

		

		if($type == 'blacklist') {

			$config['access_edit']			= false;

			$config['where']['is_active']	= 0;

		} elseif($type=='spk'){
			$config['where_in']['id']	= $v_spk;			
			if($access['access_additional']) {
				$config['button'][]	= button_serverside('btn-primary','btn-spk',['far fa-copy',lang('spk'),true],'act-dokumen');
				$config['button'][]	= button_serverside('btn-default','btn-dokumen',['far fa-copy',lang('dokumen'),true],'act-dokumen');
				$config['button'][]	= button_serverside('btn-primary','btn-pengalaman',['far fa-award',lang('pengalaman'),true],'act-dokumen');
			}

		}else {

			$config['where']['is_active']	= 1;

			if($access['access_additional']) {

				$config['button'][]	= button_serverside('btn-default','btn-dokumen',['far fa-copy',lang('dokumen'),true],'act-dokumen');
				$config['button'][]	= button_serverside('btn-primary','btn-pengalaman',['far fa-award',lang('pengalaman'),true],'act-dokumen');

			}

			if($access['access_edit']) {

				$config['button'][]	= button_serverside('btn-success','btn-change-password',['fa-key',lang('ubah_kata_sandi'),true],'act-change-password');

			}

		}

		$config['where']['status_drm']	= 1; 
		
		if(user('is_kanwil')==1){
		    $config['where']['id_unit_daftar']	= user('id_unit_kerja');
		}

		$data 			= data_serverside($config);

		render($data,'json');

	}



	function get_data() {

		$data 					= get_data('tbl_vendor','id',post('id'))->row_array();

		$data['id_kategori'] 	= json_decode($data['id_kategori_rekanan'],true);

		$parent_provinsi 		= $data['id_negara'] == 101 ? '0' : '-1';

		$data['opt_provinsi']	= select_option(get_data('tbl_m_wilayah','parent_id',$parent_provinsi)->result_array(),'id','nama');

		$data['opt_kota']		= select_option(get_data('tbl_m_wilayah','parent_id',$data['id_provinsi'])->result_array(),'id','nama');

		$data['opt_kecamatan']	= select_option(get_data('tbl_m_wilayah','parent_id',$data['id_kota'])->result_array(),'id','nama');

		$data['opt_kelurahan']	= select_option(get_data('tbl_m_wilayah','parent_id',$data['id_kecamatan'])->result_array(),'id','nama');

		$data['opt_provinsi']	.= '<option value="999">'.lang('lainnya').'</option>';

		$data['opt_kota']		.= '<option value="999">'.lang('lainnya').'</option>';

		$data['opt_kecamatan']	.= '<option value="999">'.lang('lainnya').'</option>';

		$data['opt_kelurahan']	.= '<option value="999">'.lang('lainnya').'</option>';

		$data['tanggal_berakhir_identitas']	= c_date($data['tanggal_berakhir_identitas']);

		render($data,'json');

	}



	function save() {

		$data					= post();

		$bentuk_badan_usaha     = get_data('tbl_m_bentuk_badan_usaha','id',post('id_bentuk_badan_usaha'))->row_array();

		$status_perusahaan      = get_data('tbl_m_status_perusahaan','id',post('id_status_perusahaan'))->row_array();

		$kualifikasi            = get_data('tbl_m_kualifikasi','id',post('id_kualifikasi'))->row_array();

		$asosiasi               = get_data('tbl_m_asosiasi','id',post('id_asosiasi'))->row_array();

		$unit_daftar            = get_data('tbl_m_unit','id',post('id_unit_daftar'))->row_array();

		$negara                 = get_data('tbl_m_negara','id',post('id_negara'))->row_array();


		$data['kode_badan_usaha']		= isset($bentuk_badan_usaha['bentuk_badan_usaha']) ? $bentuk_badan_usaha['kode'] : '';
		$data['bentuk_badan_usaha']     = isset($bentuk_badan_usaha['bentuk_badan_usaha']) ? $bentuk_badan_usaha['bentuk_badan_usaha'] : '';

		$data['status_perusahaan']      = isset($status_perusahaan['status_perusahaan']) ? $status_perusahaan['status_perusahaan'] : '';

		$data['kualifikasi']            = isset($kualifikasi['kualifikasi']) ? $kualifikasi['kualifikasi'] : '';

		$data['asosiasi']               = isset($asosiasi['asosiasi']) ? $asosiasi['asosiasi'] : '';

		$data['unit_daftar']            = isset($unit_daftar['unit']) ? $unit_daftar['unit'] : '';

		$data['nama_negara']            = isset($negara['nama']) ? $negara['nama'] : '';



		if(is_array(post('id_kategori_rekanan')) && count(post('id_kategori_rekanan')) > 0) { 

			$kategori_rekanan           = get_data('tbl_m_kategori_rekanan','id',post('id_kategori_rekanan'))->result_array();

			$data['id_kategori_rekanan']= json_encode(post('id_kategori_rekanan'));

			$_kategori                  = [];

			foreach($kategori_rekanan as $k) {

				$_kategori[]            = $k['kategori'];

			}

			$data['kategori_rekanan']   = implode(', ',$_kategori);

		}

		if(!$data['id']) {

			$data['is_active']			= 1;

			$data['terdaftar_sejak']	= date('Y-m-d');

		}

		$data['status_drm']				= 1;



		$response 	= save_data('tbl_vendor',$data,post(':validation'));

		if($response['status'] == 'success') {

			$new_vendor = get_data('tbl_vendor','id',$response['id'])->row();

			if(!$data['id']) {

				$user 		= [

					'id_group'		=> 7,

					'kode'			=> $new_vendor->kode_rekanan,

					'nama'			=> $new_vendor->nama,

					'username'		=> $new_vendor->kode_rekanan,

					'password'		=> c_password($this->generate_password()),

					'email'			=> $new_vendor->email_cp,

					'telepon'		=> $new_vendor->hp_cp,

					'jabatan'		=> 'Rekanan',

					'id_vendor'		=> $new_vendor->id,

					'is_active'		=> 1,

					'create_at'		=> date('Y-m-d H:i:s'),

					'create_by'		=> user('nama')

				];

				insert_data('tbl_user',$user);

			} else {

				$user 		= [

					'nama'			=> $new_vendor->nama,

					'email'			=> $new_vendor->email_cp,

					'telepon'		=> $new_vendor->hp_cp,

					'update_at'		=> date('Y-m-d H:i:s'),

					'update_by'		=> user('nama')

				];

				update_data('tbl_user',$user,'id_vendor',$new_vendor->id);

			}



			delete_data('tbl_vendor_kategori','id_vendor',$response['id']);

			$user_dt	= get_data('tbl_user','id_vendor',$response['id'])->row();

			$id_user 	= isset($user_dt->id) ? $user_dt->id : 0;

			$r 			= [];

			foreach(post('id_kategori_rekanan') as $k) {

				$r[] = [

					'id_vendor'             => $response['id'],

					'email'					=> $data['email_cp'],

					'id_user'				=> $id_user,

					'id_kategori_rekanan'   => $k,

					'is_active'				=> isset($data['is_active']) ? $data['is_active'] : 0

				];

			}

			insert_batch("tbl_vendor_kategori",$r);



		}

		render($response,'json');

	}



    function generate_password() {

        $data = [

            substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz'), 0, 6),

            substr(str_shuffle('1234567890'), 0, 1),

            substr(str_shuffle('!@#$%&?=+-_'), 0, 1)

        ];

        shuffle($data);

        return $data[0].$data[1].$data[2];

    }





	function delete() {

		$response = destroy_data('tbl_vendor','id',post('id'),[

			'id_vendor'	=> ['tbl_user','tbl_vendor_kategori']

		]);

		render($response,'json');

	}



	function detail($id=0) {

		$kode_rekanan = get('kode_rekanan');

		$data	= get_data('tbl_vendor a',[
			'select' 	=> 'a.*,b.username',
			'join'		=> 'tbl_user b on a.id = b.id_vendor',
			'where'		=> [
				'a.id' => $id,
				'OR a.kode_rekanan' => $kode_rekanan
			],
		])->row_array();


		

		if(isset($data['id'])) {

			render($data,'layout:false');

		} else {

			echo lang('tidak_ada_data');

		}

	}



	function blacklist() {

		update_data('tbl_vendor',['is_active'=>0],'id',post('id'));

		update_data('tbl_user',['is_block'=>1],'id_vendor',post('id'));

		echo lang('data_berhasil_disimpan');

	}



	function pulihkan() {

		update_data('tbl_vendor',['is_active'=>1],'id',post('id'));

		update_data('tbl_user',['is_block'=>0],'id_vendor',post('id'));

		echo lang('data_berhasil_disimpan');

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

	function pengalaman($id='') {
		$data				= get_data('tbl_vendor','id',$id)->row_array();
		$data['pengalaman'] 	= get_data('tbl_pengalaman_vendor',[
			'where' => [
			'id_vendor'=>$id,
		],
		'sort_by' => 'id',	
		])->result();
		render($data,'layout:false');
	}


}