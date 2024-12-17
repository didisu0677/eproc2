<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kunjungan_langsung extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['id']				= 0;
		if(get('i')) {
			$id 				= decode_id(get('i'));
			if(isset($id[0])) $data['id'] = $id[0];
		}
		render($data);
	}

	function data($status = 0) {
		$config				= [
			'access_view' 	=> false,
			'access_delete'	=> false,
			'access_edit'	=> false,
		    'access_edit'	=> true,
			'where'			=> [
				'is_pendaftar'			=> 1,
				'verifikasi_dokumen'	=> 1,
			]
		];
		if($status == 0) $config['where']['laporan_kunjungan']	= 0;
		else  $config['where']['laporan_kunjungan !=']			= 0;
		if(menu()['access_additional']) {
			$config['button'][]	= button_serverside('btn-success','btn-input',['fa-edit',lang('input'),true],'btn-input',['laporan_kunjungan'=>0]);
		}
		$config['button']		= [
			button_serverside('btn-success','btn-print1',['fa-file',lang('daftar_hasil_kunjungan'),true],'btn-kunjungan',['laporan_kunjungan !='=>0]),
			button_serverside('btn-info','btn-print2',['fa-file-alt',lang('hasil_wawancara'),true],'btn-wawancara',['laporan_kunjungan !='=>0]),
		];
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_vendor a',[
			'select'	=> 'a.*,b.nama_pemberi_tugas,b.jabatan_pemberi_tugas,b.tanggal_kunjungan,b.keterangan',
			'join'		=> 'tbl_m_kunjungan_vendor b ON a.id = b.id_vendor AND b.status_kunjungan = 0 TYPE LEFT',
			'where'		=> [
				'a.id'	=> post('id')
			]
		])->row_array();
		$data['detail']	= get_data('tbl_petugas_kunjungan',[
			'where'		=> [
				'nomor_kunjungan'	=> $data['nomor_kunjungan']
			],
			'sort_by'	=> 'posisi',
			'sort'		=> 'desc'
		])->result();

		render($data,'json');
	}

	function save() {
		$data 		= post();
		$data['id']	= 0;
		$vendor 	= get_data('tbl_vendor','id',post('id_vendor'))->row();
		$check 		= get_data('tbl_m_kunjungan_vendor',[
			'where'	=> [
				'id_vendor'			=> post('id_vendor'),
				'status_kunjungan'	=> 0
			]
		])->row();
		if(isset($check->id)) {
			$data['id']		= $check->id;
		}
		$response 			= save_data('tbl_m_kunjungan_vendor',$data,post(':validation'),true);
		if($response['status'] == 'success') {
			$kunjungan 		= get_data('tbl_m_kunjungan_vendor','id',$response['id'])->row();
			$id_anggota		= post('id_anggota');
			$ketua 			= $id_anggota[0];
			if(is_array($id_anggota) && count($id_anggota) > 0) {
				$user 	= get_data('tbl_user a',[
					'select'	=> 'a.id,a.nama,a.jabatan,b.unit AS unit_kerja,a.email',
					'join'		=> 'tbl_m_unit b ON a.id_unit_kerja = b.id TYPE LEFT',
					'where'		=> [
						'a.id'	=> $id_anggota
					]
				])->result();
				delete_data('tbl_petugas_kunjungan','nomor_kunjungan',$kunjungan->nomor_kunjungan);
				$data_save	= [];
				foreach($user as $u) {
					$id_user[] 		= $u->id;
					$email_user[] 	= $u->email;
					$data_save[] 	= [
						'nomor_kunjungan'		=> $kunjungan->nomor_kunjungan,
						'id_user'				=> $u->id,
						'nama_user'				=> $u->nama,
						'jabatan_user'			=> $u->jabatan,
						'unit_kerja_user'		=> $u->unit_kerja,
						'posisi'				=> $ketua == $u->id ? 'Ketua' : 'Anggota'
					];
				}
				if(count($data_save) > 0) {
					insert_batch('tbl_petugas_kunjungan',$data_save);
				}
			}
			update_data('tbl_vendor',['nomor_kunjungan'=>$kunjungan->nomor_kunjungan,'tanggal_kunjungan'=>$kunjungan->tanggal_kunjungan,'kunjungan'=>1],'id',post('id_vendor'));

			if($vendor->kunjungan == 0) {
				$user 				= get_data('tbl_user','id_vendor',$vendor->id)->row();
				$link               = base_url('account/profile/');
				$desctiption        = 'Tim PT. Pegadaian (Persero) akan melakukan kunjungan pada tanggal '.date_indo($data['tanggal_kunjungan']);
				$data_notifikasi    = [
					'title'         => 'Kunjungan',
					'description'   => $desctiption,
					'notif_link'    => $link,
					'notif_date'    => date('Y-m-d H:i:s'),
					'notif_type'    => 'info',
					'notif_icon'    => 'fa-map-marker',
					'id_user'       => $user->id,
					'transaksi'     => 'kunjungan_langsung',
					'id_transaksi'  => $vendor->id
				];
				insert_data('tbl_notifikasi',$data_notifikasi);

				$email_notification = [];
				if($vendor->email) $email_notification[$vendor->email] = $vendor->email;
				if($vendor->email_cp) $email_notification[$vendor->email_cp] = $vendor->email_cp;
				if(setting('email_notification') && count($email_notification) ) {
					send_mail([
						'subject'		=> 'Kunjungan',
						'to'			=> $email_notification,
						'nama'			=> $vendor->nama,
						'description'	=> $desctiption,
						'url'			=> $link
					]);
				}
			}
		}
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function get_tim_kunjungan() {
		$data['suggestions']	= [];
		$user 					= get_data('tbl_user',[
			'where'				=> [
				'is_active'		=> 1,
				'id_vendor'		=> 0
			],
			'like'				=> [
				'nama'			=> get('query')
			]
		])->result();
		foreach($user as $u) {
			$data['suggestions'][] = [
				'value'	=> $u->nama . ' | ' . $u->kode,
				'data'	=> $u->id
			];
		}
		render($data,'json');
	}

	function dt_pendukung($id=0) {
		$check 	= get_data('tbl_m_kunjungan_vendor','id_vendor',$id)->row_array();
		if(isset($check['id'])) {
			redirect('manajemen_rekanan/laporan_kunjungan/data_pendukung/'.encode_id($check['id']));
		}
	}

	function dt_wawancara($id=0) {
		$check 	= get_data('tbl_m_kunjungan_vendor','id_vendor',$id)->row_array();
		if(isset($check['id'])) {
			redirect('manajemen_rekanan/laporan_kunjungan/laporan_wawancara/'.encode_id($check['id']));
		}
	}

}