<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Searching extends BE_Controller {

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
		$data['divisi']             = get_data('tbl_m_divisi','is_active=1')->result_array();
		$data['negara']             = get_data('tbl_m_negara')->result_array();
		$data['provinsi']           = get_data('tbl_m_wilayah','parent_id=0')->result_array();
		render($data);
	}

	function data($type='rekanan') {
		$config	= [];
		$access = menu();
		if($type == 'blacklist') {
			$config['access_edit']			= false;
			$config['where']['is_active']	= 0;
			if($access['access_additional']) {
				$config['button'][]	= button_serverside('btn-success','btn-pulihkan',['fa-user-check',lang('pulihkan'),true],'act-pulihkan');
			}
		} else {
			// $config['where']['is_active']	= 1;
			if($access['access_additional']) {
				$config['button'][]	= button_serverside('btn-secondary','btn-blacklist',['fa-user-times',lang('blacklist'),true],'act-blacklist');
			}
			if($access['access_edit']) {
				$config['button'][]	= button_serverside('btn-success','btn-change-password',['fa-key',lang('ubah_kata_sandi'),true],'act-change-password');
			}
		}
		$data 	= data_serverside($config);
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
		$divisi                 = get_data('tbl_m_divisi','id',post('id_divisi'))->row_array();
		$negara                 = get_data('tbl_m_negara','id',post('id_negara'))->row_array();

		$data['bentuk_badan_usaha']     = isset($bentuk_badan_usaha['bentuk_badan_usaha']) ? $bentuk_badan_usaha['bentuk_badan_usaha'] : '';
		$data['status_perusahaan']      = isset($status_perusahaan['status_perusahaan']) ? $status_perusahaan['status_perusahaan'] : '';
		$data['kualifikasi']            = isset($kualifikasi['kualifikasi']) ? $kualifikasi['kualifikasi'] : '';
		$data['asosiasi']               = isset($asosiasi['asosiasi']) ? $asosiasi['asosiasi'] : '';
		$data['unit_daftar']            = isset($unit_daftar['unit']) ? $unit_daftar['unit'] : '';
		$data['divisi']                 = isset($divisi['divisi']) ? $divisi['divisi'] : '';
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

		$response 	= save_data('tbl_vendor',$data,post(':validation'));
		if($response['status'] == 'success') {
			$new_vendor = get_data('tbl_vendor','id',$response['id'])->row();
			if(!$data['id']) {
				$user 		= [
					'id_group'		=> 7,
					'kode'			=> $new_vendor->kode_rekanan,
					'nama'			=> $new_vendor->nama,
					'username'		=> $new_vendor->kode_rekanan,
					'password'		=> c_password($new_vendor->hp_cp),
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
					'id_kategori_rekanan'   => $k
				];
			}
			insert_batch("tbl_vendor_kategori",$r);

		}
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_vendor','id',post('id'),[
			'id_vendor'	=> ['tbl_user','tbl_vendor_kategori']
		]);
		render($response,'json');
	}

	function detail($id='') {
		$data = get_data('tbl_vendor','id',$id)->row_array();
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
		update_data('tbl_user',['password'=>$data['password']],'id_vendor',$data['id_vendor']);
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

}
