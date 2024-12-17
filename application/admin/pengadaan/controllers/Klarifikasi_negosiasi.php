<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Klarifikasi_negosiasi extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data($status=1) {
		$config	= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false,
			'button'		=> [
				button_serverside('btn-info',base_url('pengadaan/klarifikasi_negosiasi/detail/'),['fa-search',lang('detil'),true],'btn-detail'),
			]
		];
		if(menu()['access_additional']) {
			$anggota_panitia	= get_data('tbl_anggota_panitia','userid',user('id'))->result();
			$id_panitia			= [0];
			foreach($anggota_panitia as $a) $id_panitia[] = $a->id_m_panitia;
			$config['where']['id_panitia']	= $id_panitia;
		} else {
			$config['where']['id_creator']	= user('id');
		}
		if($status == 1) $config['where']['stat_pengadaan']	= 'KLARIFIKASI';
		else {
			$config['sort_by'] 	= 'id';
			$config['sort']		= 'DESC';
			$config['where']['stat_pengadaan !=']	= ['KLARIFIKASI'];
		}
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_klarifikasi','id = '.$id[0])->row_array();
			if(isset($data['id'])) {
				$this->load->helper('pengadaan');
				$data['title']				= $data['nomor_pengadaan'];
				$data['id_rks_aanwijzing']	= id_by_nomor($data['nomor_pengajuan'],'rks','aanwijzing');
				$data['id_rks_klarifikasi']	= id_by_nomor($data['nomor_pengajuan'],'rks','klarifikasi');

				$tanggal_klarifikasi		= get_data('tbl_jadwal_pengadaan','kata_kunci = "klarifikasi_negosiasi" AND nomor_pengajuan = "'.$data['nomor_pengajuan'].'"')->row();
				$data['open_klarifikasi']	= false;
				if(isset($tanggal_klarifikasi->id)) {
					if(strtotime($tanggal_klarifikasi->tanggal_awal) <= strtotime('now')) {
						$data['open_klarifikasi'] 	= true;
					}
					$data['tanggal_klarifikasi']	= c_date($tanggal_klarifikasi->tanggal_awal).' - '.c_date($tanggal_klarifikasi->tanggal_akhir);
				}
				$data['rks']				= get_data('tbl_rks','nomor_pengajuan = "'.$data['nomor_pengajuan'].'" AND tipe_rks = "klarifikasi"')->row_array();
				if(!isset($data['rks']['id'])) {
					$data['rks']			= get_data('tbl_rks','nomor_pengajuan = "'.$data['nomor_pengajuan'].'" AND tipe_rks = "aanwijzing"')->row_array();
					$data['rks']['id']			= 0;
					$data['rks']['file']		= '[]';
					$data['rks']['nomor_rks']	= '';
					$data['rks']['tanggal_rks']	= '';
				} else {
					$data['rks']['tanggal_rks']	= c_date($data['rks']['tanggal_rks']);
				}

				$pengajuan 					= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
				$data['hps']				= get_data('tbl_m_hps','nomor_hps',$pengajuan->no_hps)->row_array();
				$data['detail_hps']			= get_data('tbl_hps_detail','id_hps',$data['hps']['id'])->result_array();

				$data['rekanan']			= get_data('tbl_klarifikasi_vendor','id_klarifikasi',$data['id'])->result_array();
				if(count($data['rekanan']) == 1) {
					$data['penawaran_vendor']	= $data['detail_hps'];
					$last_penawaran 		= get_data('tbl_klarifikasi_negosiasi',[
						'where'				=> [
							'id_klarifikasi'		=> $data['id'],
							'penawaran_vendor >'	=> 0,
						],
						'sort_by'			=> 'id',
						'sort'				=> 'DESC',
						'limit'				=> 1
					])->row();
					$last_negosiasi 		= get_data('tbl_klarifikasi_negosiasi',[
						'where'				=> [
							'id_klarifikasi'		=> $data['id'],
						],
						'sort_by'			=> 'id',
						'sort'				=> 'DESC',
						'limit'				=> 1
					])->row();
					$data['first_penawaran']		= isset($last_penawaran->id) ? 0 : 1;
					$data['penawaran_terakhir']		= isset($last_penawaran->id) ? $last_penawaran->penawaran_vendor : 0;
					$data['is_penawaran_akhir']		= isset($last_negosiasi->id) ? $last_negosiasi->penawaran_terakhir : 0;
					$data['has_penawaran']			= isset($last_negosiasi->id) && $last_negosiasi->penawaran_vendor == 0 ? 1 : 0;
					$data['penawaran_panitia']		= isset($last_negosiasi->id) ? $last_negosiasi->penawaran_panitia : 0;
					if(isset($last_negosiasi->id)) {
						$penawaran_vendor			= get_data('tbl_klarifikasi_detail',[
							'where'					=> [
								'id_detail'			=> $last_negosiasi->id,
								'tipe'				=> 'negosiasi',
								'tipe_detail'		=> 'vendor'
							],
							'sort_by'				=> 'id',
							'sort'					=> 'asc'
						])->result_array();
						$_p = [];
						foreach($penawaran_vendor as $p) {
							$_p[$p['id_hps_detail']]	= $p['price_unit'];
						}
						foreach($data['penawaran_vendor'] as $k => $v) {
							$price_unit				= isset($_p[$v['id']]) ? $_p[$v['id']] : 0;
							$data['penawaran_vendor'][$k]['price_unit'] 	= $price_unit;
							$data['penawaran_vendor'][$k]['total_value'] 	= $price_unit * $v['quantity'];
						}
					} else {
						$data['penawaran_vendor']	= [];
					}
				}

				$data['counter']	= 0;
				if($data['selesai_sesi'] != '0000-00-00 00:00:00') {
					$data['counter']	= strtotime($data['selesai_sesi']) - strtotime(date('Y-m-d H:i:s'));
				}

				if($data['current_sesi'] > 0) {
					$last_lelang		= get_data('tbl_klarifikasi_lelang',[
						'select'		=> 'count(id) AS jml',
						'where'			=> [
							'id_klarifikasi'	=> $data['id'],
							'sesi'				=> $data['current_sesi'],
							'penawaran >'		=> 0
						]
					])->row();
					$data['lanjut_sesi']	= $last_lelang->jml ? true : false;
				} else {
					$data['lanjut_sesi']	= true;
				}

				if($data['tipe_pengadaan'] == 'Jasa Langsung') {
					$vendor_lolos	= get_data('tbl_aanwijzing_vendor',[
						'select'	=> 'COUNT(id) AS jml',
						'where'		=> [
							'nomor_pengadaan'	=> $data['nomor_pengadaan'],
							'lolos_penawaran'	=> 1
						]
					])->row();
					if($vendor_lolos->jml == 1) $data['sisa_vendor'] = 0;
				}

				if(!isset($data['sisa_vendor'])) {
					$vendor_ots	= get_data('tbl_aanwijzing_vendor',[
						'select'	=> 'COUNT(id) AS jml',
						'where'		=> [
							'nomor_pengadaan'	=> $data['nomor_pengadaan'],
							'lolos_penawaran'	=> 1,
							'status_peninjauan'	=> 0
						]
					])->row();
					$data['sisa_vendor']		= $vendor_ots->jml;
				}

				$data['vendor_terendah']	= get_data('tbl_klarifikasi_vendor',[
					'where'					=> [
						'id_klarifikasi'	=> $data['id']
					],
					'sort_by'				=> 'penawaran_terakhir'
				])->row();

				render($data);
			} else render('404');
		} else render('404');
	}

	function satu_rekanan() {
		$post 	= post();
		$data['id_klarifikasi']		= $post['id_klarifikasi'];
		if(post('penawaran_akhir')) {
			$data['penawaran_terakhir']	= 1;
		}
		$vendor 	= get_data('tbl_klarifikasi_vendor','id_klarifikasi',$data['id_klarifikasi'])->row();
		$user 		= [];
		if(isset($vendor->id_vendor)) {
			$user 	= get_data('tbl_user','id_vendor',$vendor->id_vendor)->row_array();
		}
		$save 		= insert_data('tbl_klarifikasi_negosiasi',$data);
		if($save && isset($user['id'])) {

			$k 				= get_data('tbl_klarifikasi','id',$data['id_klarifikasi'])->row();
			$pengajuan 		= get_data('tbl_pengajuan','nomor_pengajuan',$k->nomor_pengajuan)->row();
			$hps 			= get_data('tbl_m_hps','nomor_hps',$pengajuan->no_hps)->row();
			$price_unit 	= post('price_unit');

			if(is_array($price_unit) && count($price_unit)) {
				$total 	= 0;
				foreach($price_unit as $id_hps_detail => $p) {
					$dt 	= [
						'id_klarifikasi'	=> $data['id_klarifikasi'],
						'tipe'				=> 'negosiasi',
						'id_detail'			=> $save,
						'tipe_detail'		=> 'panitia',
						'id_hps_detail'		=> $id_hps_detail,
						'price_unit'		=> str_replace('.', '', $p)
					];

					$s = insert_data('tbl_klarifikasi_detail',$dt);
					if($s) {
						$d_hps 	= get_data('tbl_hps_detail','id',$id_hps_detail)->row();
						if(isset($d_hps->id)) {
							$total += $dt['price_unit'] * $d_hps->quantity;
						}
					}
				}
				if($total) {
					update_data('tbl_klarifikasi_negosiasi',['penawaran_panitia'=>$total],'id',$save);
				}
			}


			$jml 			= get_data('tbl_klarifikasi_negosiasi',[
				'select'	=> 'COUNT(id) AS jml',
				'where'	 	=> [
					'id_klarifikasi'	=> $data['id_klarifikasi']
				]
			])->row();
			$link				= base_url('pengadaan_v/klarifikasi_negosiasi_v/ref/'.encode_id($data['id_klarifikasi']));
			$desctiption 		= 'Panitia Pengadaan <strong>'.$vendor->nomor_pengadaan.'</strong> mengirimkan penawaran.';
			$data_notifikasi 	= [
				'title'			=> 'Negosiasi',
				'description'	=> $desctiption,
				'notif_link'	=> $link,
				'notif_date'	=> date('Y-m-d H:i:s'),
				'notif_type'	=> 'info',
				'notif_icon'	=> 'fa-comments-dollar',
				'id_user'		=> $user['id'],
				'transaksi'		=> 'negosiasi',
				'id_transaksi'	=> $data['id_klarifikasi']
			];
			insert_data('tbl_notifikasi',$data_notifikasi);

			if($user['email'] && setting('email_notification')) {
				send_mail([
					'subject'				=> 'Negosiasi #'.$vendor->nomor_pengadaan.' ('.$jml->jml.')',
					'to'					=> $user['email'],
					'nama_pengadaan'		=> $vendor->nama_pengadaan,
					'url'					=> $link
				]);
			}
		}
		render([
			'status'	=> 'success',
			'message'	=> lang('penawaran_berhasil_dikirim')
		],'json');
	}

	function tutup_negosiasi() {
		$id_klarifikasi 	= post('id');
		$negosiasi 			= get_data('tbl_klarifikasi_negosiasi',[
			'where'			=> [
				'id_klarifikasi'		=> $id_klarifikasi,
				'penawaran_vendor >'	=> 0
			],
			'sort_by'		=> 'id',
			'sort'			=> 'desc'
		])->row();
		$penawaran_terakhir			= 0;
		if(isset($negosiasi->penawaran_vendor)) {
			$penawaran_terakhir		= $negosiasi->penawaran_vendor;
		} else {
			$vendor 		= get_data('tbl_klarifikasi_vendor','id_klarifikasi',$id_klarifikasi)->row();
			if(isset($vendor->nilai_total_penawaran)) {
				$penawaran_terakhir	= $vendor->nilai_total_penawaran;
			}
		}
		update_data('tbl_klarifikasi',['status_klarifikasi'=>'CLOSE'],'id',$id_klarifikasi);
		update_data('tbl_klarifikasi_vendor',['status_klarifikasi'=>'CLOSE','penawaran_terakhir'=>$penawaran_terakhir],'id_klarifikasi',$id_klarifikasi);
		render([
			'status'	=> 'success',
			'message'	=> lang('negosiasi_berhasil_ditutup')
		],'json');
	}

	function history_negosiasi($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) > 0) {
			$data 				= get_data('tbl_klarifikasi','id',$id[0])->row_array();
			$data['rekanan']	= get_data('tbl_klarifikasi_vendor','id_klarifikasi',$id[0])->row_array();
			$data['negosiasi'] 	= get_data('tbl_klarifikasi_negosiasi',[
				'where'			=> [
					'id_klarifikasi'	=> $id[0]
				],
				'sort_by'		=> 'id',
				'sort'			=> 'ASC'
			])->result_array();
			render($data,'layout:false');
		} else echo lang('data_tidak_ada');
	}

	function detail_negosiasi() {
		$id_detail 		= get('i');
		$tipe_detail	= get('t');

		$data['detail'] = get_data('tbl_klarifikasi_detail a',[
			'select'	=> 'b.*, a.price_unit AS pu',
			'join'		=> 'tbl_hps_detail b ON a.id_hps_detail = b.id TYPE LEFT',
			'where'		=> [
				'a.id_detail'	=> $id_detail,
				'a.tipe_detail'	=> $tipe_detail,
				'a.tipe'		=> 'negosiasi'
			],
			'sort_by'	=> 'a.id',
			'sort'		=> 'asc'
		])->result_array();
		render($data,'layout:false');
	}

	function monitoring_penawaran($encode_id='') {
		$id 	= decode_id($encode_id);
		if(count($id) == 2) {
			$data['vendor']	= get_data('tbl_klarifikasi_vendor',[
				'where'	=> [
					'id_klarifikasi'	=> $id[0]
				],
				'sort_by'	=> 'nilai_total_penawaran'
			])->result_array();
			$klarifikasi 	= get_data('tbl_klarifikasi','id',$id[0])->row();
			$data['max_sesi']	= $klarifikasi->current_sesi;
			if($data['max_sesi']) {
				for($i=1;$i<=$data['max_sesi'];$i++) {
					$lelang 	= get_data('tbl_klarifikasi_lelang',[
						'where'	=> [
							'id_klarifikasi'	=> $id[0],
							'sesi'				=> $i
						]
					])->result();
					$data['lelang'][$i]	= [];
					$data['lelang'][$i]['min']	= 0;
					foreach($lelang as $l) {
						if($data['lelang'][$i]['min'] == 0 || $data['lelang'][$i]['min'] > $l->penawaran) {
							$data['lelang'][$i]['min'] 	= $l->penawaran;
						}
						$data['lelang'][$i][$l->id_vendor] = $l->penawaran;
						$data['lelang'][$i]['id_detail'][$l->id_vendor] = $l->id;
					}
				}
			}
			render($data,'layout:false');
		} else echo lang('tidak_ada_data');
	}

	function detail_monitoring() {
		$id_detail 		= get('i');

		$data['detail'] = get_data('tbl_klarifikasi_detail a',[
			'select'	=> 'b.*, a.price_unit AS pu',
			'join'		=> 'tbl_hps_detail b ON a.id_hps_detail = b.id TYPE LEFT',
			'where'		=> [
				'a.id_detail'	=> $id_detail,
				'a.tipe'		=> 'lelang'
			],
			'sort_by'	=> 'a.id',
			'sort'		=> 'asc'
		])->result_array();
		render($data,'layout:false');
	}

	function init_lelang() {
		$data 	= post();
		unset($data['id_klarifikasi']);
		update_data('tbl_klarifikasi',$data,'id',post('id_klarifikasi'));
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function mulai_sesi() {
		$id 			= post('id');
		$klarifikasi 	= get_data('tbl_klarifikasi','id',$id)->row();
		$data['current_sesi']	= $klarifikasi->current_sesi + 1;
		$data['selesai_sesi']	= date('Y-m-d H:i:s',strtotime('+'.$klarifikasi->lama_sesi.' minutes',strtotime(date('Y-m-d H:i:s'))));

		update_data('tbl_klarifikasi',$data,'id',$id);

		$vendor 				= get_data('tbl_klarifikasi_vendor','id_klarifikasi',$id)->result();
		$id_vendor 				= [-1];
		foreach($vendor as $v) {
			$id_vendor[]		= $v->id_vendor;
		}
		$user 					= get_data('tbl_user','id_vendor',$id_vendor)->result();
		$id_user 				= $email_user = [];
		foreach($user as $u) {
			$id_user[] 			= $u->id;
			$email_user[]		= $u->email;
		}
		if(count($id_user) > 0) {
			$link				= base_url().'pengadaan_v/klarifikasi_negosiasi_v/ref/'.encode_id([$id,rand()]);
			$desctiption 		= 'Sesi '.$data['current_sesi'].' Penawaran Pengadaan <strong>'.$klarifikasi->nomor_pengadaan.'</strong> sudah dimulai.';
			foreach($id_user as $iu) {
				$data_notifikasi 	= [
					'title'			=> 'Negosiasi',
					'description'	=> $desctiption,
					'notif_link'	=> $link,
					'notif_date'	=> date('Y-m-d H:i:s'),
					'notif_type'	=> 'info',
					'notif_icon'	=> 'fa-comments-dollar',
					'id_user'		=> $iu,
					'transaksi'		=> 'klarifikasi',
					'id_transaksi'	=> $id
				];
				insert_data('tbl_notifikasi',$data_notifikasi);
			}

			if(count($email_user) > 0 && setting('email_notification')) {
				send_mail([
					'subject'				=> 'Negosiasi #'.$klarifikasi->nomor_pengadaan.' Sesi '.$data['current_sesi'],
					'bcc'					=> $email_user,
					'description'			=> $desctiption,
					'url'					=> $link
				]);
			}
		}
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function tutup_lelang() {
		$id_klarifikasi 	= post('id');
		update_data('tbl_klarifikasi',['status_klarifikasi'=>'CLOSE'],'id',$id_klarifikasi);
		$vendor 			= get_data('tbl_klarifikasi_vendor','id_klarifikasi',$id_klarifikasi)->result();
		foreach($vendor as $v) {
			$lelang 		= get_data('tbl_klarifikasi_lelang',[
				'where'		=> [
					'id_klarifikasi'	=> $id_klarifikasi,
					'id_vendor'			=> $v->id_vendor,
					'penawaran >'		=> 0
				],
				'sort_by'	=> 'penawaran',
				'sort'		=> 'ASC',
				'limit'		=> 1
			])->row();
			$penawaran 		= isset($lelang->penawaran) ? $lelang->penawaran : $v->nilai_total_penawaran;
			update_data('tbl_klarifikasi_vendor',['status_klarifikasi'=>'CLOSE','penawaran_terakhir'=>$penawaran],'id',$v->id);
		}
		render([
			'status'	=> 'success',
			'message'	=> lang('negosiasi_berhasil_ditutup')
		],'json');
	}

	function detail_vendor($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) > 0) {
			include_lang('manajemen_rekanan');
			$data 	= get_data('tbl_vendor','id',$id[0])->row_array();
			render($data,'layout:false');
		} else echo lang('data_tidak_ada');
	}

	function save_berita_acara() {
		$data 			= post();
		unset($data['_id']);
		$klarifikasi 	= get_data('tbl_klarifikasi','id',post('_id'))->row();
		if(!$klarifikasi->nomor_berita_acara) {
			$data['nomor_berita_acara']	= generate_code('tbl_klarifikasi','nomor_berita_acara',$data);
		}
		$vendor = get_data('tbl_klarifikasi_vendor a',[
			'select'	=> 'a.nama_vendor,b.nama_cp',
			'join'		=> 'tbl_vendor b ON a.id_vendor = b.id TYPE LEFT',
			'where'		=> [
				'id_klarifikasi' => $klarifikasi->id
			]
		])->result();
		$peserta	= [];
		foreach($vendor as $v) {
			$peserta[] 	= [
				'vendor'			=> $v->nama_vendor,
				'nama_perwakilan'	=> $v->nama_cp
			];
		}
		$data['peserta_berita_acara']	= json_encode($peserta);
		$id 	= post('_id');
		$save 	= update_data('tbl_klarifikasi',$data,'id',$id);
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function berita_acara($encode_id='') {
		$decode = decode_id($encode_id);
		if(count($decode) == 2) {
			$data 	= get_data('tbl_klarifikasi','id',$decode[0])->row_array();
			if(isset($data['id'])) {
				$data['vendor']				= get_data('tbl_klarifikasi_vendor a',[
					'select'				=> 'a.*,b.nama_cp',
					'join'					=> 'tbl_vendor b ON a.id_vendor = b.id TYPE LEFT',
					'where'					=> 'id_klarifikasi = '.$decode[0]
				])->result_array();
				$data['pemenang']			= get_data('tbl_klarifikasi_vendor',[
					'where'	=> [
						'id_klarifikasi'	=> $decode[0]
					],
					'sort_by'				=> 'penawaran_terakhir',
					'sort'					=> 'ASC'
				])->row_array();
				$data['panitia']			= get_data('tbl_panitia_pelaksana',[
					'where'					=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan']
					],
					'sort_by'				=> 'id'
				])->result_array();

				$creator 					= get_data('tbl_user','id',$data['id_creator'])->row();
				$data['nama_creator']		= isset($creator->id) ? $creator->nama : 'Creator';
				$user_pengadaan 			= get_data('tbl_alur_persetujuan',[
					'where'					=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan'],
						'jenis_approval'	=> 'PERMINTAAN'
					]
				])->result();
				foreach($user_pengadaan as $k => $u) {
					if(strpos(strtolower($u->nama_persetujuan),'direktur') !== false ) {
						unset($user_pengadaan[$k]);
					}
				}
				$data['user_pengadaan']		= $user_pengadaan;

				render($data,'pdf');
			} else render('404');
		} else render('404');
	}

	function save_rks() {
		$data 							= post();

		$data['syarat_umum'] 			= post('syarat_umum','html');
		$data['syarat_khusus'] 			= post('syarat_khusus','html');
		$data['syarat_teknis'] 			= post('syarat_teknis','html');
		$data['pola_pembayaran'] 		= post('pola_pembayaran','html');
		$data['sanggahan_peserta'] 		= post('sanggahan_peserta','html');

		$data['latar_belakang'] 		= post('latar_belakang','html');
		$data['spesifikasi'] 			= post('spesifikasi','html');
		$data['ruang_lingkup'] 			= post('ruang_lingkup','html');
		$data['distribusi_kebutuhan'] 	= post('distribusi_kebutuhan','html');
		$data['jangka_waktu'] 			= post('jangka_waktu','html');
		$data['jumlah_kebutuhan'] 		= post('jumlah_kebutuhan','html');
		$data['lain_lain'] 				= post('lain_lain','html');

		$data['tipe_rks']				= 'klarifikasi';

		$rks_aanwijzing 				= get_data('tbl_rks','nomor_pengajuan = "'.$data['nomor_pengajuan'].'" AND tipe_rks = "aanwijzing"')->row_array();
		if(isset($rks_aanwijzing['id'])) {
			$data['id_panitia']				= $rks_aanwijzing['id_panitia'];
			$data['nama_panitia']			= $rks_aanwijzing['nama_panitia'];
			$data['metode_pengadaan']		= $rks_aanwijzing['metode_pengadaan'];
			$data['jenis_pengadaan']		= $rks_aanwijzing['jenis_pengadaan'];
			$data['bobot_harga']			= $rks_aanwijzing['bobot_harga'];
			$data['bobot_teknis']			= $rks_aanwijzing['bobot_teknis'];
			$data['nama_tanda_tangan']		= $rks_aanwijzing['nama_tanda_tangan'];
			$data['jabatan_tanda_tangan']	= $rks_aanwijzing['jabatan_tanda_tangan'];
		}

		$last_file 		= [];
		if($data['id']) {
			$dt 		= get_data('tbl_rks','id',$data['id'])->row();
			if(isset($dt->id)) {
				$lf 	= json_decode($dt->file,true);
				foreach($lf as $l) {
					$last_file[$l] = $l;
				}
			}
		}		
		$file 				= post('file');
		$keterangan_file 	= post('keterangan_file');
		$filename 			= [];
		$dir 				= '';
		if(isset($file) && is_array($file)) {
			foreach($file as $k => $f) {
				if(strpos($f,'exist:') !== false) {
					$orig_file = str_replace('exist:','',$f);
					if(isset($last_file[$orig_file])) {
						unset($last_file[$orig_file]);
						$filename[$keterangan_file[$k]]	= $orig_file;
					}
				} else {
					if(file_exists($f)) {
						if(@copy($f, FCPATH . 'assets/uploads/rks/'.basename($f))) {
							$filename[$keterangan_file[$k]]	= basename($f);
							if(!$dir) $dir = str_replace(basename($f),'',$f);
						}
					}
				}
			}
		}
		foreach($last_file as $lf) {
			@unlink(FCPATH . 'assets/uploads/rks/' . $lf);
		}
		$data['file']					= json_encode($filename);
		if($dir) {
			delete_dir(FCPATH . $dir);
		}

		$response 	= save_data('tbl_rks',$data,post(':validation'),menu()['access_additional']);
		render($response,'json');		
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

	function cetak_rks($encode_id=''){
		$decode = decode_id($encode_id);
		if(count($decode) == 2) {
			$id 						= $decode[0];
			$record						= get_data('tbl_rks','id',$id)->row_array();
			$record['bobot_harga']		= c_percent($record['bobot_harga']);
			$record['bobot_teknis']		= c_percent($record['bobot_teknis']);
			$tanggal_rks				= $record['tanggal_rks'];

			$aanwijzing					= get_data('tbl_jadwal_pengadaan',[
				'where'					=> [
					'nomor_pengajuan'	=> $record['nomor_pengajuan'],
					'kata_kunci'		=> 'aanwijzing'
				]
			])->row();
			$pemasukan_dokumen			= get_data('tbl_jadwal_pengadaan',[
				'where'					=> [
					'nomor_pengajuan'	=> $record['nomor_pengajuan'],
					'kata_kunci'		=> 'pemasukan_dokumen'
				]
			])->row();
			$pembukaan_dokumen			= get_data('tbl_jadwal_pengadaan',[
				'where'					=> [
					'nomor_pengajuan'	=> $record['nomor_pengajuan'],
					'kata_kunci'		=> 'pembukaan_dokumen'
				]
			])->row();

			$record['hari_aanwijzing']		= !isset($aanwijzing->id) ? '-' : hari($aanwijzing->tanggal_awal);
			$record['tanggal_aanwijzing']	= !isset($aanwijzing->id) ? '-' : date_indo($aanwijzing->tanggal_awal, false);
			$record['jam_aanwijzing']		= !isset($aanwijzing->id) ? '-' : date('H:i',strtotime($aanwijzing->tanggal_awal)).' '.$aanwijzing->zona_waktu.' s/d Selesai';

			$record['tanggal_mulai_pemasukan_dokumen']		= !isset($pemasukan_dokumen->id) ? '-' : hari($pemasukan_dokumen->tanggal_awal).', '.date_indo($pemasukan_dokumen->tanggal_awal).' '.$pemasukan_dokumen->zona_waktu;
			$record['tanggal_selesai_pemasukan_dokumen']	= !isset($pemasukan_dokumen->id) ? '-' : hari($pemasukan_dokumen->tanggal_akhir).', '.date_indo($pemasukan_dokumen->tanggal_akhir).' '.$pemasukan_dokumen->zona_waktu;

			$record['hari_pembukaan_dokumen']				= !isset($pembukaan_dokumen->id) ? '-' : hari($pembukaan_dokumen->tanggal_awal);
			$record['tanggal_pembukaan_dokumen']			= !isset($pembukaan_dokumen->id) ? '-' : date_indo($pembukaan_dokumen->tanggal_awal, false);
			$record['jam_pembukaan_dokumen']				= !isset($pembukaan_dokumen->id) ? '-' : date('H:i',strtotime($pembukaan_dokumen->tanggal_awal)).' '.$pembukaan_dokumen->zona_waktu;

			$record['nama_pemberi_tugas']	= $record['nama_tanda_tangan'];
			$record['jabatan_pemberi_tugas']= $record['jabatan_tanda_tangan'];

			$inisiasi 						= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$record['nomor_pengajuan'])->row();
			$record['minimal_rekanan_mengikuti']	= '-';
			$record['minimal_rekanan_sah']			= '-';
			if(isset($inisiasi->id)) {
				if($inisiasi->tipe_pengadaan == 'Lelang') {
					$record['minimal_rekanan_mengikuti']	= setting('min_memasukan_lelang').' ('.terbilang(setting('min_memasukan_lelang')).')';
					$record['minimal_rekanan_sah']			= setting('min_pengadaan_lelang').' ('.terbilang(setting('min_pengadaan_lelang')).')';
				} elseif($inisiasi->tipe_pengadaan == 'Pemilihan Langsung') {
					$record['minimal_rekanan_mengikuti']	= setting('min_memasukan_pemilihan_langsung').' ('.terbilang(setting('min_memasukan_pemilihan_langsung')).')';
					$record['minimal_rekanan_sah']			= setting('min_pengadaan_pemilihan_langsung').' ('.terbilang(setting('min_pengadaan_pemilihan_langsung')).')';
				} elseif($inisiasi->tipe_pengadaan == 'Penunjukan Langsung') {
					$record['minimal_rekanan_mengikuti']	= setting('min_memasukan_penunjukan_langsung').' ('.terbilang(setting('min_memasukan_penunjukan_langsung')).')';
					$record['minimal_rekanan_sah']			= setting('min_pengadaan_penunjukan_langsung').' ('.terbilang(setting('min_pengadaan_penunjukan_langsung')).')';
				}
			}

			$jaminan						= get_data('tbl_m_definisi_pasal','is_active',1)->result_array();
			$r['jaminan']					= [];
			foreach($jaminan as $j)	$r['jaminan'][$j['kata_kunci']] = $j['deskripsi'];
			$r['jaminan_penawaran']			= $record['jaminan_penawaran'];
			$r['jaminan_pelaksanaan']		= $record['jaminan_pelaksanaan'];
			$r['jaminan_pemeliharaan']		= $record['jaminan_pemeliharaan'];

			$r_sanggah['deskripsi']			= '';
			$r_sanggah['active']			= $record['sanggahan_peserta'];
			if(isset($r['jaminan']['sanggah'])) $r_sanggah['deskripsi']	= $r['jaminan']['sanggah'];

			$record['jaminan_pengadaan']	= include_view('pengadaan/rks/view_jaminan',$r);
			$record['sanggahan_peserta']	= include_view('pengadaan/rks/view_sanggah',$r_sanggah);
			$record['batas_hps_atas']		= c_percent($record['batas_hps_atas']);
			$record['batas_hps_bawah']		= c_percent($record['batas_hps_bawah']);
			$record['tanggal_rks']			= date_indo($record['tanggal_rks']);
			$data['html']					= template_pdf($record,'rks_klarifikasi',$tanggal_rks);
			render($data,'pdf');
		} else {
			render('404');
		}
	}

	function kembali_peninjauan() {
		$id 			= post('id');
		$klarifikasi 	= get_data('tbl_klarifikasi','id',$id)->row();

		update_data('tbl_klarifikasi',['stat_pengadaan'=>'BATAL'],'id',$id);
		update_data('tbl_aanwijzing',['stat_pengadaan'=>'PENINJAUAN','status_aanwijzing'=>'PENINJAUAN'],'nomor_pengadaan',$klarifikasi->nomor_pengadaan);
		update_data('tbl_aanwijzing_vendor',['status_aanwijzing'=>'PENINJAUAN'],'nomor_pengadaan',$klarifikasi->nomor_pengadaan);
		update_data('tbl_aanwijzing_vendor',['close_penugasan'=>1,'status_peninjauan'=>9],[
			'nomor_pengadaan'		=> $klarifikasi->nomor_pengadaan,
			'status_peninjauan >'	=> 0
		]);
		update_data('tbl_chat_key',['is_active'=>0],'id',$klarifikasi->id_chat);
		$status_desc 	= 'Peninjuan Lapangan (On The Spot)';
		update_data('tbl_pengajuan',['status_desc'=>$status_desc],'nomor_pengajuan',$klarifikasi->nomor_pengajuan);

		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function negosiasi_kandidat_lain() {
		$id 			= post('id');
		$klarifikasi 	= get_data('tbl_klarifikasi','id',$id)->row();

		update_data('tbl_klarifikasi',['stat_pengadaan'=>'BATAL'],'id',$id);
		update_data('tbl_aanwijzing_vendor',['close_penugasan'=>1,'status_peninjauan'=>9],[
			'nomor_pengadaan'		=> $klarifikasi->nomor_pengadaan,
			'status_peninjauan >'	=> 0
		]);
		update_data('tbl_chat_key',['is_active'=>0],'id',$klarifikasi->id_chat);

		$aanwijzing 		= get_data('tbl_aanwijzing','nomor_pengadaan',$klarifikasi->nomor_pengadaan)->row();
		$awz 				= get_data('tbl_aanwijzing','nomor_pengadaan',$aanwijzing->nomor_pengadaan)->row_array();
		$field_klarifikasi	= get_field('tbl_klarifikasi','name');
		$new_data 			= [];
		foreach($field_klarifikasi as $f) {
			if(isset($awz[$f]) && !in_array($f,['id','status_rks','id_chat','nomor_berita_acara','tanggal_berita_acara','peserta_berita_acara','zona_waktu','lokasi_berita_acara','peserta_lain'])) {
				$new_data[$f]	= $awz[$f];
			}
		}
		$vendor 			= get_data('tbl_aanwijzing_vendor',[
			'where'	=> [
				'nomor_pengadaan'	=> $aanwijzing->nomor_pengadaan,
				'lolos_penawaran'	=> 1,
				'status_peninjauan'	=> 0
			],
			'sort_by'	=> 'rank_evaluasi',
			'sort'		=> 'ASC',
			'limit'		=> 1
		])->result_array();
		$keterangan_chat 	= '';
		if(isset($vendor[0])) {
			$new_data['keterangan']	= 'Klarifikasi dan Negosiasi dengan '.$vendor[0]['nama_vendor'];
			$keterangan_chat 	= ' @'.$vendor[0]['nama_vendor'];
		}

		$save_klarifikasi	= insert_data('tbl_klarifikasi',$new_data);
		$field_vendor 		= get_field('tbl_klarifikasi_vendor','name');
		foreach($vendor as $v) {
			$row_data 		= ['id_klarifikasi'=>$save_klarifikasi];
			foreach($field_vendor as $f) {
				if(isset($v[$f]) && $f != 'id') {
					$row_data[$f]	= $v[$f];
				}
			}
			insert_data('tbl_klarifikasi_vendor',$row_data);
			update_data('tbl_aanwijzing_vendor',['status_peninjauan'=>1],'id',$v['id']);
		}

		$id_anggota	= $anggota_chat = $id_user = $email_user = [];
		$panitia 	= get_data('tbl_panitia_pelaksana',[
			'where'	=> [
				'id_m_panitia'		=> $aanwijzing->id_panitia,
				'nomor_pengajuan'	=> $aanwijzing->nomor_pengajuan
			]
		])->result();

		foreach($panitia as $p) {
			$id_anggota[] 	= $p->userid;
			$anggota_chat[]	= $p->nama_panitia;
		}

		$creator 				= get_data('tbl_user','id',$aanwijzing->id_creator)->row();
		$id_anggota[]			= $creator->id;
		$anggota_chat[]			= $creator->nama;
		$id_user[] 				= $creator->id;
		$email_user[]			= $creator->email;

		$id_vendor		= [-1];
		foreach($vendor as $v) {
			$id_vendor[]	= $v['id_vendor'];
		}

		$user_vendor	= get_data('tbl_user','id_vendor',$id_vendor)->result();
		foreach($user_vendor as $u) {
			$id_anggota[] 	= $u->id;
			$anggota_chat[]	= $u->nama;
			$id_user[] 		= $u->id;
			$email_user[] 	= $u->email;
		}

		$jadwal 				= get_data('tbl_jadwal_pengadaan','kata_kunci = "klarifikasi_negosiasi" AND nomor_pengajuan = "'.$aanwijzing->nomor_pengajuan.'"')->row();

		$data_chat 		= [
			'nama'		=> 'Klarifikasi dan Negosiasi '.$aanwijzing->nomor_pengadaan.$keterangan_chat,
			'anggota'	=> implode(', ', $anggota_chat),
			'is_group'	=> 1,
			'is_active'	=> 1
		];
		if(isset($jadwal->id)) {
			$data_chat['aktif_mulai']	= $jadwal->tanggal_awal;
			$data_chat['aktif_selesai']	= $jadwal->tanggal_akhir;
		}

		$chat 			= insert_data('tbl_chat_key',$data_chat);

		foreach($id_anggota as $i) {
			insert_data('tbl_chat_anggota',[
				'key_id'	=> $chat,
				'id_user'	=> $i,
				'is_read'	=> 1
			]);
		}
		update_data('tbl_klarifikasi',['id_chat'=>$chat],'id',$save_klarifikasi);

		if(count($id_user) > 0) {
			$link				= base_url('auth');
			$desctiption 		= 'Proses Evaluasi Pengadaan <strong>'.$aanwijzing->nomor_pengadaan.'</strong> telah selesai.';
			foreach($id_user as $iu) {
				$data_notifikasi 	= [
					'title'			=> 'Evaluasi Penawaran',
					'description'	=> $desctiption,
					'notif_link'	=> $link,
					'notif_date'	=> date('Y-m-d H:i:s'),
					'notif_type'	=> 'info',
					'notif_icon'	=> 'fa-file-alt',
					'id_user'		=> $iu,
					'transaksi'		=> 'evaluasi',
					'id_transaksi'	=> $aanwijzing->id
				];
				insert_data('tbl_notifikasi',$data_notifikasi);
			}

			if(count($email_user) > 0 && setting('email_notification')) {
				send_mail([
					'subject'				=> 'Evaluasi Penawaran #'.$aanwijzing->nomor_pengadaan,
					'bcc'					=> $email_user,
					'nama_pengadaan'		=> $aanwijzing->nama_pengadaan,
					'metode_negosiasi'		=> $aanwijzing->metode_negosiasi,
					'vendor'				=> get_data('tbl_aanwijzing_vendor',[
						'where'	=> [
							'nomor_pengadaan'	=> $aanwijzing->nomor_pengadaan,
							'status_peninjauan'	=> 1
						]
					])->result_array(),
					'url'					=> $link
				]);
			}
		}

		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');		
	}

	function batal_pengadaan() {
		$id 			= post('id');
		$klarifikasi 	= get_data('tbl_klarifikasi','id',$id)->row();

		update_data('tbl_klarifikasi',['stat_pengadaan'=>'BATAL','last_pos'=>'KLARIFIKASI'],'id',$id);
		update_data('tbl_chat_key',['is_active'=>0],'id',$klarifikasi->id_chat);
		$status_desc 	= 'Dibatalkan, Tidak mencapai kesepakatan saat negosiasi';
		update_data('tbl_pengajuan',['status_desc'=>$status_desc],'nomor_pengajuan',$klarifikasi->nomor_pengajuan);

		$pengajuan 	= get_data('tbl_pengajuan','nomor_pengajuan',$klarifikasi->nomor_pengajuan)->row();
		if(isset($pengajuan->id)) {
			update_data('sap_detail',['is_deleted'=>'1','deleted_date'=>date('Y-m-d H:i:s')],'purchase_req_item',$pengajuan->purchase_req_item);
		}

		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function resume_lelang($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_klarifikasi','id',$id[0])->row_array();
			$data['vendor']	= get_data('tbl_klarifikasi_vendor','id_klarifikasi',$id[0])->result_array();
			$min 			= 0;
			foreach($data['vendor'] as $k => $v) {
				if($min == 0 || $min > $v['nilai_total_penawaran']) {
					$min 	= $v['nilai_total_penawaran'];
				}
			}
			$data['min_awal'] 	= $min;
			$data['max_sesi']	= $data['current_sesi'];
			if($data['max_sesi']) {
				for($i=1;$i<=$data['max_sesi'];$i++) {
					$lelang 	= get_data('tbl_klarifikasi_lelang',[
						'where'	=> [
							'id_klarifikasi'	=> $id[0],
							'sesi'				=> $i
						]
					])->result();
					$data['lelang'][$i]	= [];
					$data['lelang'][$i]['min']	= 0;
					foreach($lelang as $l) {
						if($data['lelang'][$i]['min'] == 0 || $data['lelang'][$i]['min'] > $l->penawaran) {
							$data['lelang'][$i]['min'] 	= $l->penawaran;
						}
						$data['lelang'][$i][$l->id_vendor] = $l->penawaran;
					}
				}
			}
			render($data,'pdf');
		} else render('404');
	}

	function proses() {
		$id 				= post('id');
		$klarifikasi 		= get_data('tbl_klarifikasi','id',$id)->row_array();
		$pemenang 			= get_data('tbl_klarifikasi_vendor',[
			'where'			=> [
				'id_klarifikasi'	=> $id
			],
			'sort_by'		=> 'penawaran_terakhir',
			'limit'			=> 1
		])->row();
		$penawaran_terakhir	= $pemenang->penawaran_terakhir;
		$get_limit			= get_data('tbl_m_penyetuju_penetapan',[
			'where'			=> [
				'is_active'				=> 1,
				'id_unit_kerja'			=> $klarifikasi['id_unit_kerja2'],
				'limit_persetujuan >='	=> $penawaran_terakhir
			],
			'sort_by'		=> 'limit_persetujuan',
			'sort'			=> 'ASC',
			'limit'			=> 1
		])->row();

		// untuk detil penawaran
		$identifikasi_pajak	= get_data('tbl_m_identifikasi_pajak','id',$klarifikasi['id_identifikasi_pajak'])->row();
		$kode_pajak 		= isset($identifikasi_pajak->id) ? $identifikasi_pajak->kode : '';
		$detail_penawaran 	= [];
		if($klarifikasi['metode_negosiasi'] == 'Negosiasi Satu Rekanan') {
			$negosiasi 		= get_data('tbl_klarifikasi_negosiasi',[
				'where'		=> [
					'id_klarifikasi'	=> $klarifikasi['id'],
					'penawaran_vendor'	=> $penawaran_terakhir
				],
				'sort_by'	=> 'id',
				'sort'		=> 'desc'
			])->row();
			if(isset($negosiasi->id)) {
				$detail_penawaran	= get_data('tbl_klarifikasi_detail a',[
					'select'	=> 'b.*, a.price_unit AS pu',
					'join'		=> 'tbl_hps_detail b ON a.id_hps_detail = b.id TYPE LEFT',
					'where'		=> [
						'a.id_detail'	=> $negosiasi->id,
						'a.tipe_detail'	=> 'vendor',
						'a.tipe'		=> 'negosiasi'
					],
					'sort_by'	=> 'a.id',
					'sort'		=> 'asc'
				])->result_array();
			}
		} else {
			$negosiasi 		= get_data('tbl_klarifikasi_lelang',[
				'where'		=> [
					'id_klarifikasi'	=> $klarifikasi['id'],
					'id_vendor'			=> $pemenang->id_vendor,
					'penawaran'			=> $penawaran_terakhir
				],
				'sort_by'	=> 'id',
				'sort'		=> 'desc'
			])->row();
			if(isset($negosiasi->id)) {
				$detail_penawaran	= get_data('tbl_klarifikasi_detail a',[
					'select'	=> 'b.*, a.price_unit AS pu',
					'join'		=> 'tbl_hps_detail b ON a.id_hps_detail = b.id TYPE LEFT',
					'where'		=> [
						'a.id_detail'	=> $negosiasi->id,
						'a.tipe'		=> 'lelang'
					],
					'sort_by'	=> 'a.id',
					'sort'		=> 'asc'
				])->result_array();
			}
		}

		$persetujuan 		= [];
		if(isset($get_limit->id)) {
			$persetujuan	= get_data('tbl_m_penyetuju_penetapan',[
				'where'		=> [
					'is_active'				=> 1,
					'id_unit_kerja'			=> $klarifikasi['id_unit_kerja2'],
					'limit_persetujuan <='	=> $get_limit->limit_persetujuan
				],
				'sort_by'	=> 'limit_persetujuan',
				'sort'		=> 'ASC'
			])->result();
		}

		if(count($persetujuan) == 0) {
			render([
				'status'	=> 'info',
				'message'	=> lang('alur_persetujuan_tidak_ditemukan')
			],'json');
		} else {
			delete_data('tbl_alur_persetujuan','nomor_pengadaan',$klarifikasi['nomor_pengadaan']);            
			$i = 1;
			foreach($persetujuan as $m) {
				$data_p = [
					'nomor_pengajuan'	=> $klarifikasi['nomor_pengajuan'],
					'nomor_pengadaan'	=> $klarifikasi['nomor_pengadaan'],
					'jenis_approval'	=> 'PEMENANG',
					'level_persetujuan'	=> $i,
					'nama_persetujuan'	=> $m->nama_persetujuan,
					'id_user'			=> $m->id_user,
					'username'			=> $m->username,
					'nama_user'			=> $m->nama_lengkap
				];

				insert_data('tbl_alur_persetujuan',$data_p);
				$i++;
			}
			$field_pemenang 	= get_field('tbl_pemenang_pengadaan','name');
			$data_pemenang 		= [];
			foreach($field_pemenang as $p) {
				if(isset($klarifikasi[$p])) {
					$data_pemenang[$p] 	= $klarifikasi[$p];
				}
			}
			if(count($data_pemenang) > 0) {
				unset($data_pemenang['id']);
				$data_pemenang['id_vendor']				= $pemenang->id_vendor;
				$data_pemenang['nama_vendor']			= $pemenang->nama_vendor;
				$data_pemenang['alamat_vendor']			= $pemenang->alamat_vendor;
				$data_pemenang['penawaran_awal']		= $pemenang->nilai_total_penawaran;
				$data_pemenang['penawaran_terakhir']	= $pemenang->penawaran_terakhir;
			}
			$save 	= insert_data('tbl_pemenang_pengadaan',$data_pemenang);
			if($save) {
				foreach($detail_penawaran as $dp) {
					$dt = $dp;
					unset($dt['id']);
					unset($dt['id_hps']);
					unset($dt['pu']);
					$dt['price_unit']				= $dp['pu'];
					$dt['total_value']				= $dp['quantity'] * $dp['pu'];
					$dt['id_pemenang_pengadaan']	= $save;
					$dt['kode_pajak']				= $kode_pajak;
					insert_data('tbl_pemenang_pengadaan_detail',$dt);
				}
			}
			update_data('tbl_klarifikasi',['stat_pengadaan'=>'PENETAPAN'],'id',$id);

			$next_tabel_persetujuan 		= get_data('tbl_alur_persetujuan',[
				'where'						=> [
					'nomor_pengadaan'		=> $klarifikasi['nomor_pengadaan'],
					'tanggal_persetujuan'	=> '0000-00-00 00:00:00',
					'jenis_approval'		=> 'PEMENANG'
				],
				'sort_by'					=> 'level_persetujuan',
				'sort'						=> 'ASC'
			])->row();

			if(isset($next_tabel_persetujuan->id)){
				update_data('tbl_pemenang_pengadaan',[
					'posisi_persetujuan'	=> $next_tabel_persetujuan->id_user,
					'nama_persetujuan' 		=> $next_tabel_persetujuan->nama_persetujuan,
					'tanggal_persetujuan'   => date('Y-m-d H:i:s')
				],'nomor_pengadaan',$klarifikasi['nomor_pengadaan']);

				update_data('tbl_pengajuan',[
					'status_desc'			=> 'Persetujuan Pemenang (Menunggu : '.$next_tabel_persetujuan->nama_user.')'
				],'nomor_pengajuan',$klarifikasi['nomor_pengajuan']);

				// kirim notifikasi ke approver
				$usr 						= get_data('tbl_user','id',$next_tabel_persetujuan->id_user)->row();
				if(isset($usr->id)) {
					$link				= base_url().'pengadaan/persetujuan_pemenang?i='.encode_id([$save,rand()]);
					$description 		= 'Penetapan pemenang pengadaan dengan no. <strong>'.$klarifikasi['nomor_pengadaan'].'</strong> membutuhkan persetujuan anda';
					$data_notifikasi 	= [
						'title'			=> 'Penetapan Pemenang',
						'description'	=> $description,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'info',
						'notif_icon'	=> 'fa-check-circle',
						'id_user'		=> $usr->id,
						'transaksi'		=> 'persetujuan_pemenang',
						'id_transaksi'	=> $save
					];
					insert_data('tbl_notifikasi',$data_notifikasi);

					if(setting('email_notification') && $usr->email) {
						send_mail([
							'subject'		=> 'Penetapan Pemenang #'.$klarifikasi['nomor_pengadaan'],
							'to'			=> $usr->email,
							'nama_user'		=> $usr->nama,
							'description'	=> $description,
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
	}

	function inisiasi_ulang() {
		$nomor_pengadaan	= post('nomor_pengadaan');
		$pengadaan 			= get_data('tbl_pengadaan','nomor_pengadaan',$nomor_pengadaan)->row();
		$nomor_pengajuan 	= $pengadaan->nomor_pengajuan;

		update_data('tbl_klarifikasi',['inisiasi_ulang'=>1],'nomor_pengadaan',$nomor_pengadaan);
		update_data('tbl_pengajuan',[
			'approve'			=> 0,
			'is_pos_approve'	=> 0,
			'status_desc'		=> 'Diinisiasi Ulang'
		],'nomor_pengajuan',$nomor_pengajuan);
		update_data('tbl_inisiasi_pengadaan',[
			'status'			=> 0,
			'status_proses'		=> 0
		],'nomor_pengajuan',$nomor_pengajuan);
		update_data('tbl_rks',[
			'status'			=> 0,
			'status_proses'		=> 0
		],'nomor_pengajuan',$nomor_pengajuan);
		update_data('tbl_m_hps',[
			'status'			=> 0,
			'status_proses'		=> 0
		],'nomor_pengajuan',$nomor_pengajuan);

		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

}