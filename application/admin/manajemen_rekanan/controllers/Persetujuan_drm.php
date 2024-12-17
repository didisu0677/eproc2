<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Persetujuan_drm extends BE_Controller {



	function __construct() {

		parent::__construct();

	}



	function index() {
		$data['id']				= 0;
		if(get('i')) {
			$id 				= decode_id(get('i'));
			if(isset($id[0])) {
				$p 			= get_data('tbl_m_rekomendasi_vendor','id_vendor',$id[0])->row();
				if(isset($p->id)) {
					$data['id'] = $p->id;
				}
			}
		}
		render($data);

	}



	function data($tipe='baru') {

		$config	= [

			'access_view'	=> false,

			'access_edit'	=> false,

			'access_delete'	=> false

		];

		$status_drm = 0;

		if($tipe == 'ditolak') $status_drm = 9;

		else if($tipe == 'disetujui') $status_drm = 1;



		$config['where']['approval']	= $status_drm;

		if(menu()['access_additional']) {

			$config['button'][]	= button_serverside('btn-info','btn-approve',['fa-check-circle',lang('persetujuan'),true],'act-approve',['approval'=>0]);

			$config['button'][] = button_serverside('btn-success','btn-print1',['fa-file',lang('daftar_rekanan_mampu'),true],'btn-drm',['approval'=>1]);

		}


		if(user('is_kanwil')==1){
			$kanwil	= get_data('tbl_m_rekomendasi_vendor a',[
				'select' => 'a.id_vendor',
				'join' => 'tbl_vendor b on a.id_vendor=b.id',
				'where' => [
				'b.id_unit_daftar' => user('id_unit_kerja'),
			],
			])->result();
			
			$id_vendor			= [0];
			foreach($kanwil as $a) $id_vendor[] = $a->id_vendor;

			$config['where']['id_vendor']	= $id_vendor;
		}

		$data 	= data_serverside($config);

		render($data,'json');

	}



	function detail($id=0) {

		$data 			= get_data('tbl_m_rekomendasi_vendor a',[

			'select'		=> 'a.*, b.nama,b.jenis_rekanan,b.npwp,b.kategori_rekanan,b.bentuk_badan_usaha,b.status_perusahaan,

			b.no_identitas,b.tanggal_berakhir_identitas,b.kualifikasi,b.asosiasi,b.unit_daftar,b.nama_negara,

			b.nama_provinsi,b.nama_kota,b.nama_kecamatan,b.nama_kelurahan,b.kode_pos,b.no_telepon,b.no_fax,b.hp_cp,

			b.email, b.nama_cp, b.email_cp, c.nomor_kunjungan, c.id as id_kunjungan',

			'join'			=>  [

				'tbl_vendor b ON a.id_vendor = b.id TYPE LEFT',

				'tbl_m_kunjungan_vendor c ON a.id_vendor = c.id_vendor TYPE LEFT'

			],

			'where'			=> [

				'a.id' => $id

			]

		])->row_array();



		if(isset($data['id'])) {

			render($data,'layout:false');

		} else {

			echo lang('tidak_ada_data');

		}

	}



	function persetujuan() {

		$vendor 	= get_data('tbl_m_rekomendasi_vendor','id',post('id'))->row_array();
		$user 		= get_data('tbl_user','id_vendor',$vendor['id_vendor'])->row_array();
		// debug($user);die;

		if(isset($vendor['id'])) {

			update_data('tbl_vendor',[

				'status_drm'		=> post('verifikasi'),

				'jangka_waktu'		=> $vendor['jangka_waktu'],

				'tanggal_approve'	=> date('Y-m-d'),

			],'id',$vendor['id_vendor']);

			update_data('tbl_vendor_kategori',['status_drm'	=> post('verifikasi')],'id_vendor',$vendor['id_vendor']);

			update_data('tbl_m_rekomendasi_vendor',['approval' => post('verifikasi')],'id',post('id'));

			update_data('tbl_user',['id_group'=>7],'id_vendor',$vendor['id_vendor']);

			$vendor 			= get_data('tbl_vendor','id',$vendor['id_vendor'])->row_array();
			$link               = post('verifikasi') == 9 ? base_url('account/profile') : base_url('home');
			$desctiption        = post('verifikasi') == 9 ? 'Verifikasi DRM Ditolak' : "Verifikasi DRM Diterima";
			$data_notifikasi    = [
				'title'         => 'Verifikasi DRM',
				'description'   => $desctiption,
				'notif_link'    => $link,
				'notif_date'    => date('Y-m-d H:i:s'),
				'notif_type'    => post('verifikasi') == 9 ? 'danger' : 'success',
				'notif_icon'    => 'fa-check',
				'id_user'       => $user['id'],
				'transaksi'     => 'verifikasi_drm',
				'id_transaksi'  => $user['id_vendor']
			];
			insert_data('tbl_notifikasi',$data_notifikasi);

			$email_notification = [];
			if($vendor['email']) $email_notification[$vendor['email']] = $vendor['email'];
			if($vendor['email_cp']) $email_notification[$vendor['email_cp']] = $vendor['email_cp'];
			if(setting('email_notification') && count($email_notification) ) {
				send_mail([
					'subject'		=> 'Verifikasi DRM',
					'to'			=> $email_notification,
					'nama'			=> $vendor['nama'],
					'description'	=> $desctiption,
					'url'			=> $link,
					'status'		=> post('verifikasi')
				]);
			}

		}

		echo lang('data_berhasil_disimpan');

	}



	function laporan_drm($encode_id='') {

	    $id = decode_id($encode_id);

	    $id = isset($id[0]) ? $id[0] : 0;

		$data 			= get_data('tbl_m_rekomendasi_vendor a',[

			'select'		=> 'a.*, b.kode_rekanan,b.nama,b.jenis_rekanan,b.npwp,b.kategori_rekanan,b.bentuk_badan_usaha,b.status_perusahaan,

			b.no_identitas,b.tanggal_berakhir_identitas,b.kualifikasi,b.asosiasi,b.unit_daftar,b.nama_negara,

			b.nama_provinsi,b.nama_kota,b.nama_kecamatan,b.nama_kelurahan,b.kode_pos,b.no_telepon,b.no_fax,b.hp_cp,

			b.email, b.nama_cp, b.email_cp,b.nama_pimpinan',

			'join'			=>  [

				'tbl_vendor b ON a.id_vendor = b.id TYPE LEFT'

			],

			'where'			=> [

				'a.id' => $id

			]

		])->row_array();

		if(!file_exists(FCPATH . 'assets/qrcode/'.$data['kode_rekanan'].'.png')) {
			$save_path 			= FCPATH . 'assets/qrcode';
			if(!is_dir($save_path)) {
				$oldmask = umask(0);
				mkdir($save_path);
				umask($oldmask);
			}
			$this->load->library('ciqrcode');
			$params['data']		= base_url('verifikasi?i='.encode_id($data['id_vendor']));
			$params['level']	= 'H';
			$params['size']		= 10;
			$params['logo']		= FCPATH . 'assets/images/qr_logo.png';
			$params['white']	= [40,40,40];
			$params['savename'] = $save_path.'/'.$data['kode_rekanan'].'.png';
			$this->ciqrcode->generate($params);
		}

	    if(isset($data['id'])) {

	        render($data,'pdf');

	    } else render('404');

	}



}
