<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_kunjungan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data($status=0) {
		$config	= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false,
		];
		$config['button'][]	= button_serverside('btn-info',base_url('manajemen_rekanan/laporan_kunjungan/detail/'),['fa-search',lang('detil'),true],'btn-detail');
		if($status == 0) $config['where']['status_kunjungan']	= '0';
		else {
			$config['where']['status_kunjungan !=']	= '0';
			$config['sort_by']							= 'id';
			$config['sort']								= 'DESC';
		}
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function detail($encode_id='') {
		$id 	= decode_id($encode_id);
		if(count($id) == 2) {
			$data 	= get_data('tbl_m_kunjungan_vendor','id',$id[0])->row_array();
			if(isset($data['id'])) {
			    $data['tim']	= get_data('tbl_petugas_kunjungan',[
			        'where'	=> [
			            'nomor_kunjungan'	=> $data['nomor_kunjungan']
			        ],
			        'sort_by'	=> 'posisi',
			        'sort'		=> 'DESC'
			    ])->result();
				$data['template1']	= get_data('tbl_template_kunjungan',[
					'where'		=> 'grup = "wawancara"',
					'sort_by'	=> 'id'
				])->result_array();
				$data['template2']	= get_data('tbl_template_kunjungan',[
					'where'		=> 'grup = "data_pendukung"',
					'sort_by'	=> 'id'
				])->result_array();
				render($data);
			} else render('404');
		} else render('404');		
	}

	function save() {
		$deskripsi 				= post('deskripsi');
		$nomor 					= post('nomor');
		$detil 					= post('detil');
		$kelengkapan			= post('kelengkapan');
		$keterangan				= post('keterangan');
		$deskripsi_lain 		= post('deskripsi_lain');
		$detil_lain 			= post('detil_lain');
		$kelengkapan_lain		= post('kelengkapan_lain');
		$keterangan_lain		= post('keterangan_lain');

		$deskripsi1				= post('deskripsi1');
		$keterangan1			= post('keterangan1');
		$deskripsi1_lain		= post('deskripsi1_lain');
		$kondisi_lain			= post('kondisi_lain');
		$keterangan1_lain		= post('keterangan1_lain');

		$data 					= post();
		$awz 					= get_data('tbl_m_kunjungan_vendor','id',$data['id'])->row();
		if(isset($awz->id)) {
			$vendor 			= get_data('tbl_vendor','id',$awz->id_vendor)->row();
			$user 				= get_data('tbl_user','id_vendor',$vendor->id)->row();
			$data['kota_peninjauan']	= $vendor->id ? trim(str_replace(['Kota','Kabupaten','Kab.'], '', $vendor->nama_kota)) : 'Jakarta';
		}

		$data_pendukung			= $hasil_kunjungan = [];
		foreach($deskripsi as $k => $v) {
			$data_pendukung[$k]	= [
				'deskripsi'		=> $deskripsi[$k],
				'detil'			=> '',
				'kelengkapan'	=> $kelengkapan[$k],
				'keterangan'	=> $keterangan[$k]
			];
			if(isset($nomor[$k])) {
				$data_pendukung[$k]['detil']	= 'Nomor '.$nomor[$k];
			} elseif(isset($detil[$k])) {
				$data_pendukung[$k]['detil']	= $detil[$k];
			}
		}
		$data_pendukung['lain']	= [];
		if(is_array($deskripsi_lain) && count($deskripsi_lain) > 0) {
			foreach($deskripsi_lain as $k => $v) {
				$data_pendukung['lain'][$k]	= [
					'deskripsi'		=> $deskripsi_lain[$k],
					'detil'			=> $detil_lain[$k],
					'kelengkapan'	=> $kelengkapan_lain[$k],
					'keterangan'	=> $keterangan_lain[$k]
				];
			}
		}

		foreach ($deskripsi1 as $k => $v) {
			$hasil_kunjungan[$k]	= [
				'deskripsi'		=> $deskripsi1[$k],
				'keterangan'	=> $keterangan1[$k]
			];
		}
		$hasil_kunjungan['lain']	= [];
		if(is_array($deskripsi1_lain) && count($deskripsi1_lain) > 0) {
			foreach($deskripsi1_lain as $k => $v) {
				$hasil_kunjungan['lain'][$k]	= [
					'deskripsi'		=> $deskripsi1_lain[$k],
					'keterangan'	=> $keterangan1_lain[$k]
				];
			}
		}

		$data['data_pendukung']		= json_encode($data_pendukung);
		$data['hasil_kunjungan']	= json_encode($hasil_kunjungan);

		$response 		= save_data('tbl_m_kunjungan_vendor',$data,[],true);
		if($response['status'] == 'success') {
		    update_data('tbl_vendor',['laporan_kunjungan'=>post('status_kunjungan')],'id',$awz->id_vendor);

		    $link               = base_url('account/profile/');
			$desctiption        = post('status_kunjungan') == 9 ? 'Tidak lolos verifikasi' : 'Lolos verifikasi';
			$data_notifikasi    = [
				'title'         => 'Hasil Kunjungan',
				'description'   => $desctiption,
				'notif_link'    => $link,
				'notif_date'    => date('Y-m-d H:i:s'),
				'notif_type'    => post('status_kunjungan') == 9 ? 'danger' : 'success',
				'notif_icon'    => 'fa-file-alt',
				'id_user'       => $user->id,
				'transaksi'     => 'laporan_kunjungan',
				'id_transaksi'  => $vendor->id
			];
			insert_data('tbl_notifikasi',$data_notifikasi);

			$email_notification = [];
			if($vendor->email) $email_notification[$vendor->email] = $vendor->email;
			if($vendor->email_cp) $email_notification[$vendor->email_cp] = $vendor->email_cp;
			if(setting('email_notification') && count($email_notification) ) {
				send_mail([
					'subject'		=> 'Hasil Kunjungan',
					'to'			=> $email_notification,
					'nama'			=> $vendor->nama,
					'description'	=> $desctiption,
					'url'			=> $link,
					'status'		=> post('status_kunjungan')
				]);
			}
		}
		render($response,'json');
	}
	
	function data_pendukung($encode_id='') {
	    $id = decode_id($encode_id);
	    $id = isset($id[0]) ? $id[0] : 0;
	    $data 		= get_data('tbl_m_kunjungan_vendor','id',$id)->row_array();
	    
	    if(isset($data['id'])) {
	        $ketua 		= get_data('tbl_petugas_kunjungan',[
	            'where'		=> [
	                 'posisi'				=> 'Ketua',
	                 'nomor_kunjungan'	=> $data['nomor_kunjungan']
	             ]
	        ])->row();
	        $data['ketua']		= isset($ketua->nama_user) ? $ketua->nama_user : '';
	        $data['result']		= $data['data_pendukung'] ? json_decode($data['data_pendukung'],true) : [];
	        $data['lain']		= isset($data['result']['lain']) ? $data['result']['lain'] : [];
	        unset($data['result']['lain']);
	        render($data,'pdf');
	    } else render('404');
	}
	
	function laporan_wawancara($encode_id='') {
	    $id = decode_id($encode_id);
	    $id = isset($id[0]) ? $id[0] : 0;
	    $data 		= get_data('tbl_m_kunjungan_vendor','id',$id)->row_array();
	    if(isset($data['id'])) {
	        $ketua 		= get_data('tbl_petugas_kunjungan',[
	            'where'		=> [
	                'posisi'				=> 'Ketua',
	                'nomor_kunjungan'	=> $data['nomor_kunjungan']
	            ]
	        ])->row();
	        $data['ketua']		= isset($ketua->nama_user) ? $ketua->nama_user : '';
	        $data['result']		= $data['hasil_kunjungan'] ? json_decode($data['hasil_kunjungan'],true) : [];
	        $data['lain']		= isset($data['result']['lain']) ? $data['result']['lain'] : [];
	        unset($data['result']['lain']);

	        render($data,'pdf');
	    } else render('404');
	}
	

	function template_data_pendukung($encode_id='') {
		$id = decode_id($encode_id);
		$id = isset($id[0]) ? $id[0] : 0;
		$data['detil'] 	= get_data('tbl_m_kunjungan_vendor','id',$id)->row_array();
		$ketua 			= get_data('tbl_petugas_kunjungan',[
			'where'		=> [
				'posisi'				=> 'Ketua',
				'nomor_kunjungan'	=> $data['detil']['nomor_kunjungan']
			]
		])->row();
		$data['detil']['ketua']	= isset($ketua->nama_user) ? $ketua->nama_user : '';
		$data['template']	= get_data('tbl_template_kunjungan',[
			'where'		=> 'grup = "data_pendukung"',
			'sort_by'	=> 'id'
		])->result_array();
		render($data,'pdf');
	}

	function template_laporan_wawancara($encode_id='') {
		$id = decode_id($encode_id);
		$id = isset($id[0]) ? $id[0] : 0;
		$data['detil'] 	= get_data('tbl_m_kunjungan_vendor','id',$id)->row_array();
		$ketua 			= get_data('tbl_petugas_kunjungan',[
			'where'		=> [
				'posisi'				=> 'Ketua',
				'nomor_kunjungan'	=> $data['detil']['nomor_kunjungan']
			]
		])->row();
		$data['detil']['ketua']	= isset($ketua->nama_user) ? $ketua->nama_user : '';
		$data['template']	= get_data('tbl_template_kunjungan',[
			'where'		=> 'grup = "wawancara"',
			'sort_by'	=> 'id'
		])->result_array();
		
		render($data,'pdf');
	}

}