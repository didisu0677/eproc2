<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penawaran extends BE_Controller {

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
				button_serverside('btn-info',base_url('pengadaan/penawaran/detail/'),['fa-search',lang('detil'),true],'btn-detail'),
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
		if($status == 1) $config['where']['status_aanwijzing']	= 'PENAWARAN';
		else {
			$config['sort_by'] 	= 'id';
			$config['sort']		= 'DESC';
			$config['where']['status_aanwijzing !=']	= ['AANWIJZING','PENAWARAN'];
		}
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_aanwijzing','id = '.$id[0])->row_array();
			if(isset($data['id'])) {
				$this->load->helper('pengadaan');
				$data['title']				= $data['nomor_pengadaan'];
				$data['id_rks_aanwijzing']	= id_by_nomor($data['nomor_pengajuan'],'rks','aanwijzing');
				$data['penawaran']			= get_data('tbl_aanwijzing_vendor',[
					'where'	=> [
						'nomor_pengadaan'	=> $data['nomor_pengadaan']
					],
					'sort_by'	=> 'id'
				])->result_array();
				$tanggal_penawaran			= get_data('tbl_jadwal_pengadaan','kata_kunci = "pembukaan_dokumen" AND nomor_pengajuan = "'.$data['nomor_pengajuan'].'"')->row();
				$data['open_penawaran']	= false;
				if(isset($tanggal_penawaran->id)) {
					if(strtotime($tanggal_penawaran->tanggal_awal) <= strtotime('now')) {
						$data['open_penawaran'] 	= true;
					}
					$data['tanggal_penawaran']	= c_date($tanggal_penawaran->tanggal_awal).' - '.c_date($tanggal_penawaran->tanggal_akhir);
				}

				$grup_dokumen					= $data['grup_dokumen'] = [
					'persyaratan_peserta'		=> lang('dokumen_hal_yang_menggugurkan'),
					'dokumen_administrasi'		=> lang('dokumen_administrasi'),
					'dokumen_teknis'			=> lang('dokumen_teknis'),
					'dokumen_penawaran_harga'	=> lang('dokumen_penawaran_harga')
				];
				$data['dokumen_persyaratan']	= [];
				foreach($grup_dokumen as $k => $v) {
					$data['dokumen_persyaratan'][$k][0]	= get_data('tbl_dokumen_persyaratan',[
						'where'					=> [
							'grup'				=> $k,
							'parent_id'			=> 0,
							'nomor_pengajuan'	=> $data['nomor_pengajuan']
						],
						'sort_by'				=> 'id'
					])->result_array();
					foreach ($data['dokumen_persyaratan'][$k][0] as $key => $value) {
						$data['dokumen_persyaratan'][$k][$value['id']]	= get_data('tbl_dokumen_persyaratan',[
							'where'					=> [
								'grup'				=> $k,
								'parent_id'			=> $value['id'],
								'nomor_pengajuan'	=> $data['nomor_pengajuan']
							],
							'sort_by'				=> 'id'
						])->result_array();
					}
				}

				$user_pengadaan 		= get_data('tbl_alur_persetujuan',[
					'where'				=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan'],
						'jenis_approval'	=> 'PERMINTAAN'
					]
				])->result();
				foreach($user_pengadaan as $k => $u) {
					if(strpos(strtolower($u->nama_persetujuan),'direktur') !== false || strpos(strtolower($u->nama_persetujuan),'direksi') !== false ) {
						unset($user_pengadaan[$k]);
					}
				}
				$data['user_pengadaan']	= $user_pengadaan;


				$data['panitia_pelaksana']	= get_data('tbl_panitia_pelaksana','nomor_pengajuan',$data['nomor_pengajuan'])->result();
				$pengajuan 					= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
				$data['nama_creator']		= isset($pengajuan->id) ? $pengajuan->create_by : '-';
				$data['nama_panitia']		= isset($pengajuan->id) ? $pengajuan->nama_panitia : 'Panitia';
				$hps 						= get_data('tbl_m_hps','nomor_hps',$pengajuan->no_hps)->row();
				$data['id_hps']				= isset($hps->id) ? $hps->id : 0;

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

	function save() {
		$aanwijzing 				= get_data('tbl_aanwijzing','nomor_pengadaan',post('nomor_pengadaan'))->row();
		$jml_lolos 					= get_data('tbl_aanwijzing_vendor',[
			'select'				=> 'COUNT(*) AS jml',
			'where'					=> [
				'nomor_aanwijzing'	=> $aanwijzing->nomor_aanwijzing,
				'lolos_penawaran'	=> 1
			]
		])->row();
		if($aanwijzing->tipe_pengadaan == 'Jasa Langsung' && $jml_lolos->jml == 1) {
			$data['stat_pengadaan'] 	= 'KLARIFIKASI';
			$data['nomor_pengadaan']	= post('nomor_pengadaan');
			$data['status_aanwijzing']	= 'KLARIFIKASI';
			$data['metode_negosiasi']	= 'Negosiasi Satu Rekanan';

			$_id_anggota	= post('id_anggota');
			$_nm_anggota 	= post('anggota');

			$peserta_lain	= [];
			if(is_array($_id_anggota) && count($_id_anggota) > 0) {
				foreach($_id_anggota as $k => $v) {
					if($v) {
						$peserta_lain[$v]	= $_nm_anggota[$k];
					}
				}
			}

			update_data('tbl_aanwijzing',$data,'id',$aanwijzing->id);
			update_data('tbl_aanwijzing_vendor',['status_aanwijzing'=>'KLARIFIKASI'],'nomor_pengadaan',$data['nomor_pengadaan']);
			$status_desc 			= 'Klarifikasi dan Negosiasi';
			update_data('tbl_chat_key',['is_active'=>0],'id',$aanwijzing->id_chat_penawaran);
			update_data('tbl_pengajuan',['status_desc'=>$status_desc],'nomor_pengajuan',$aanwijzing->nomor_pengajuan);

			$awz 				= get_data('tbl_aanwijzing','nomor_pengadaan',$data['nomor_pengadaan'])->row_array();
			$field_klarifikasi	= get_field('tbl_klarifikasi','name');
			$new_data 			= [];
			foreach($field_klarifikasi as $f) {
				if(isset($awz[$f]) && !in_array($f,['id','status_rks','id_chat','nomor_berita_acara','tanggal_berita_acara','peserta_berita_acara','zona_waktu','lokasi_berita_acara','peserta_lain'])) {
					$new_data[$f]	= $awz[$f];
				}
			}
			$new_data['peserta_lain']	= json_encode($peserta_lain);
			$vendor 					= get_data('tbl_aanwijzing_vendor',[
				'where'					=> [
					'nomor_aanwijzing'	=> $aanwijzing->nomor_aanwijzing,
					'lolos_penawaran'	=> 1
				]
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

			foreach($peserta_lain as $i => $n) {
				$id_anggota[] 				= $i;
				$anggota_chat[]				= $n;
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

			if($aanwijzing->metode_negosiasi != 'Negosiasi Satu Rekanan') {
				$vendor 		= get_data('tbl_aanwijzing_vendor',[
					'where'	=> [
						'nomor_pengadaan'	=> $aanwijzing->nomor_pengadaan,
						'lolos_penawaran'	=> 1
					]
				])->result();
				$id_vendor		= [-1];
				foreach($vendor as $v) {
					$id_vendor[]	= $v->id_vendor;
				}
			}

			$user_vendor	= get_data('tbl_user','id_vendor',$id_vendor)->result();
			foreach($user_vendor as $u) {
				$id_user[]		= $u->id;
				$email_user[]	= $u->email;
			}

			if(count($id_user) > 0) {
				$link				= base_url('auth');
				$desctiption 		= 'Proses Evaluasi dan Peninjauan Lapangan Pengadaan <strong>'.$aanwijzing->nomor_pengadaan.'</strong> telah selesai.';
				foreach($id_user as $iu) {
					$data_notifikasi 	= [
						'title'			=> 'Evaluasi dan Peninjauan Lapangan',
						'description'	=> $desctiption,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'info',
						'notif_icon'	=> 'fa-file-alt',
						'id_user'		=> $iu,
						'transaksi'		=> 'peninjauan_lapangan',
						'id_transaksi'	=> $aanwijzing->id
					];
					insert_data('tbl_notifikasi',$data_notifikasi);
				}

				if(count($email_user) > 0 && setting('email_notification')) {
					send_mail([
						'subject'				=> 'Evaluasi dan Peninjauan Lapangan #'.$aanwijzing->nomor_pengadaan,
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
		} else {
			$data['stat_pengadaan'] 	= 'EVALUASI';
			$data['nomor_pengadaan']	= post('nomor_pengadaan');
			$data['status_aanwijzing']	= 'EVALUASI';

			$_id_anggota	= post('id_anggota');
			$_nm_anggota 	= post('anggota');

			$peserta_lain	= [];
			if(is_array($_id_anggota) && count($_id_anggota) > 0) {
				foreach($_id_anggota as $k => $v) {
					if($v) {
						$peserta_lain[$v]	= $_nm_anggota[$k];
					}
				}
			}

			$data['peserta_lain_evaluasi']	= json_encode($peserta_lain);

			update_data('tbl_aanwijzing',$data,'id',$aanwijzing->id);
			update_data('tbl_aanwijzing_vendor',['status_aanwijzing'=>'EVALUASI'],'nomor_pengadaan',$aanwijzing->nomor_pengadaan);
			update_data('tbl_chat_key',['is_active'=>0],'id',$aanwijzing->id_chat_penawaran);
			$status_desc 			= 'Evaluasi Penawaran';
			update_data('tbl_pengajuan',['status_desc'=>$status_desc],'nomor_pengajuan',$aanwijzing->nomor_pengajuan);

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

			foreach($peserta_lain as $i => $n) {
				$id_anggota[] 				= $i;
				$anggota_chat[]				= $n;
			}

			$user_pengadaan 		= get_data('tbl_alur_persetujuan',[
				'where'				=> [
					'nomor_pengajuan'	=> $aanwijzing->nomor_pengajuan,
					'jenis_approval'	=> 'PERMINTAAN'
				]
			])->result();
			foreach($user_pengadaan as $k => $u) {
				if(strpos(strtolower($u->nama_persetujuan),'direktur') === false || strpos(strtolower($u->nama_persetujuan),'direksi') === false ) {
					$id_anggota[]	= $u->id_user;
					$anggota_chat[]	= $u->nama_user;
				}
			}

			$creator 				= get_data('tbl_user','id',$aanwijzing->id_creator)->row();
			$id_anggota[]			= $creator->id;
			$anggota_chat[]			= $creator->nama;
			$id_user[] 				= $creator->id;
			$email_user[]			= $creator->email;

			$jadwal 				= get_data('tbl_jadwal_pengadaan','kata_kunci = "evaluasi_penawaran" AND nomor_pengajuan = "'.$aanwijzing->nomor_pengajuan.'"')->row();

			$data_chat 		= [
				'nama'		=> 'Evaluasi Dokumen Penawaran '.$aanwijzing->nomor_pengadaan,
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
			update_data('tbl_aanwijzing',['id_chat_evaluasi'=>$chat],'id',$aanwijzing->id);

			$vendor 		= get_data('tbl_aanwijzing_vendor','nomor_pengadaan',$aanwijzing->nomor_pengadaan)->result();
			$id_vendor		= [-1];
			foreach($vendor as $v) {
				$id_vendor[]	= $v->id_vendor;
			}

			$user_vendor	= get_data('tbl_user','id_vendor',$id_vendor)->result();
			foreach($user_vendor as $u) {
				$id_user[]		= $u->id;
				$email_user[]	= $u->email;
			}

			if(count($id_user) > 0) {
				$link				= base_url().'pengadaan_v/penawaran_v/ref/'.encode_id([$aanwijzing->id,rand()]);
				$desctiption 		= 'Proses Pembukaan dan Verifikasi Dokumen Penawaran Harga Pengadaan <strong>'.$aanwijzing->nomor_pengadaan.'</strong> telah selesai.';
				foreach($id_user as $iu) {
					$data_notifikasi 	= [
						'title'			=> 'Pembukaan Dokumen Penawaran',
						'description'	=> $desctiption,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'info',
						'notif_icon'	=> 'fa-file-alt',
						'id_user'		=> $iu,
						'transaksi'		=> 'penawaran',
						'id_transaksi'	=> $aanwijzing->id
					];
					insert_data('tbl_notifikasi',$data_notifikasi);
				}

				if(count($email_user) > 0 && setting('email_notification')) {
					send_mail([
						'subject'				=> 'Pembukaan Dokumen Penawaran #'.$aanwijzing->nomor_pengadaan,
						'bcc'					=> $email_user,
						'nama_pengadaan'		=> $aanwijzing->nama_pengadaan,
						'vendor'				=> get_data('tbl_aanwijzing_vendor',[
							'where'	=> [
								'nomor_pengadaan'	=> $aanwijzing->nomor_pengadaan,
								'lolos_penawaran'	=> 1
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
		$data 	= post();
		unset($data['id_aanwijzing']);
		$awz 	= get_data('tbl_aanwijzing','id',post('id_aanwijzing'))->row();
		$data['peserta_ba_pembukaan']	= $awz->peserta_berita_acara;
		$id 	= post('id_aanwijzing');
		$save 	= update_data('tbl_aanwijzing',$data,'id',$id);
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function berita_acara($encode_id='') {
		$decode = decode_id($encode_id);
		if(count($decode) == 2) {
			$data 	= get_data('tbl_aanwijzing','id',$decode[0])->row_array();
			if(isset($data['id'])) {
				$data['panitia']			= get_data('tbl_panitia_pelaksana',[
					'where'					=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan']
					],
					'sort_by'				=> 'id'
				])->result_array();
				$vendor 		= get_data('tbl_aanwijzing_vendor',[
					'select'	=> 'count(id) AS jml',
					'where'		=> [
						'nomor_pengadaan'	=> $data['nomor_pengadaan']
					]
				])->row();
				$kirim_penawaran = get_data('tbl_aanwijzing_vendor',[
					'select'	=> 'count(id) AS jml',
					'where'		=> [
						'nomor_pengadaan'	=> $data['nomor_pengadaan'],
						'file_penawaran !='	=> ''
					]
				])->row();
				$sah 			=  get_data('tbl_aanwijzing_vendor',[
					'select'	=> 'count(id) AS jml',
					'where'		=> [
						'nomor_pengadaan'	=> $data['nomor_pengadaan'],
						'lolos_penawaran'	=> 1
					]
				])->row();
				$data['jumlah_vendor']	= $vendor->jml;
				$data['jumlah_penawar']	= $kirim_penawaran->jml;
				$data['jumlah_sah']		= $sah->jml;

				$creator 					= get_data('tbl_user','id',$data['id_creator'])->row();
				$data['nama_creator']		= isset($creator->id) ? $creator->nama : 'Creator';
				$user_pengadaan 		= get_data('tbl_alur_persetujuan',[
					'where'				=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan'],
						'jenis_approval'	=> 'PERMINTAAN'
					]
				])->result();
				foreach($user_pengadaan as $k => $u) {
					if(strpos(strtolower($u->nama_persetujuan),'direktur') !== false || strpos(strtolower($u->nama_persetujuan),'direksi') !== false) {
						unset($user_pengadaan[$k]);
					}
				}
				$data['user_pengadaan']	= $user_pengadaan;

				render($data,'pdf');
			} else render('404');
		} else render('404');
	}

	function get_penilaian() {
		$id_vendor 			= post('id_vendor');
		$nomor_pengadaan 	= post('nomor_pengadaan');

		$vendor 			= get_data('tbl_vendor','id',$id_vendor)->row();
		$data['nama_vendor']	= $vendor->nama;
		$data['alamat_vendor']	= $vendor->alamat.', '.$vendor->nama_kelurahan.', '.$vendor->nama_kecamatan.', '.$vendor->nama_kota.'<br />'.$vendor->nama_provinsi.' - '.$vendor->kode_pos;

		$data['dok_persyaratan']	= $data['dok_administrasi'] = $data['dok_teknis'] = $data['dok_penawaran'] = '#';
		$data['pass_persyaratan']	= $data['pass_administrasi'] = $data['pass_teknis'] = $data['pass_penawaran'] = '*******';
		$pengadaan 			= get_data('tbl_pengadaan_bidder',[
			'where'			=> [
				'id_vendor'			=> $id_vendor,
				'nomor_pengadaan'	=> $nomor_pengadaan
			]
		])->row();
		$aanwijzing 		= get_data('tbl_aanwijzing_vendor',[
			'where'			=> [
				'id_vendor'			=> $id_vendor,
				'nomor_pengadaan'	=> $nomor_pengadaan
			]
		])->row();
		foreach(json_decode($pengadaan->file_persyaratan,true) as $pass => $file) {
			$data['dok_persyaratan']	= base_url(dir_upload('dokumen_rekanan').$file);
			$data['pass_persyaratan']	= $pass;
		}
		foreach(json_decode($aanwijzing->file_penawaran,true) as $k => $v) {
			if($k == 'penawaran_harga') {
				$data['dok_penawaran']	= base_url(dir_upload('dokumen_rekanan').$v['file']);
				$data['pass_penawaran']	= $v['password'];
			} else {
				$data['dok_'.$k]	= base_url(dir_upload('dokumen_rekanan').$v['file']);
				$data['pass_'.$k]	= $v['password'];
			}
		}
		$data['nilai_total_penawaran']		= custom_format($aanwijzing->nilai_total_penawaran);
		$data['nilai_jaminan_penawaran']	= custom_format($aanwijzing->nilai_jaminan_penawaran);

		$data['persyaratan']				= get_data('tbl_persyaratan_vendor',[
			'where'		=> [
				'id_vendor'			=> $id_vendor,
				'nomor_pengadaan'	=> $nomor_pengadaan
			]
		])->result_array();
		render($data,'json');
	}

	function save_penilaian() {
		$nomor_pengajuan				= post('nomor_pengajuan');
		$nomor_pengadaan 				= post('_nomor_pengadaan');
		$id_vendor 						= post('id_vendor');
		$nilai_total_penawaran			= str_replace('.', '', post('nilai_total_penawaran'));
		$nilai_jaminan_penawaran		= str_replace('.', '', post('nilai_jaminan_penawaran'));
		$id_persyaratan 				= post('id_persyaratan');
		$sah 							= post('sah');

		$grup_dokumen					= [
			'persyaratan_peserta'		=> lang('dokumen_hal_yang_menggugurkan'),
			'dokumen_administrasi'		=> lang('dokumen_administrasi'),
			'dokumen_teknis'			=> lang('dokumen_teknis'),
			'dokumen_penawaran_harga'	=> lang('dokumen_penawaran_harga')
		];
		$data['dokumen_persyaratan']	= [];

		$lolos_penawaran				= 9;
		delete_data('tbl_persyaratan_vendor',[
			'id_vendor'			=> $id_vendor,
			'nomor_pengadaan'	=> $nomor_pengadaan
		]);
		foreach($grup_dokumen as $k => $v) {
			$dokumen_persyaratan[$k][0]	= get_data('tbl_dokumen_persyaratan',[
				'where'					=> [
					'grup'				=> $k,
					'parent_id'			=> 0,
					'nomor_pengajuan'	=> $nomor_pengajuan
				],
				'sort_by'				=> 'id'
			])->result_array();
			foreach ($dokumen_persyaratan[$k][0] as $key => $value) {
				$_data 					= [
					'id_persyaratan'	=> $value['id'],
					'parent_id'			=> $value['parent_id'],
					'deskripsi'			=> $value['deskripsi'],
					'grup'				=> $value['grup'],
					'nomor_pengajuan'	=> $value['nomor_pengajuan'],
					'nomor_pengadaan'	=> $nomor_pengadaan,
					'mandatori'			=> $value['mandatori'],
					'id_vendor'			=> $id_vendor,
					'sah'				=> isset($sah[$value['id']]) ? 1 : 0
				];
				$dokumen_persyaratan[$k][$value['id']]	= get_data('tbl_dokumen_persyaratan',[
					'where'					=> [
						'grup'				=> $k,
						'parent_id'			=> $value['id'],
						'nomor_pengajuan'	=> $nomor_pengajuan
					],
					'sort_by'				=> 'id'
				])->result_array();
				if(count($dokumen_persyaratan[$k][$value['id']]) > 0) {
					$_data['sah']		= 1;
					foreach($dokumen_persyaratan[$k][$value['id']] as $key2 => $value2) {
						$__data 				= [
							'id_persyaratan'	=> $value2['id'],
							'parent_id'			=> $value2['parent_id'],
							'deskripsi'			=> $value2['deskripsi'],
							'grup'				=> $value2['grup'],
							'nomor_pengajuan'	=> $value2['nomor_pengajuan'],
							'nomor_pengadaan'	=> $nomor_pengadaan,
							'mandatori'			=> $value2['mandatori'],
							'id_vendor'			=> $id_vendor,
							'sah'				=> isset($sah[$value2['id']]) ? 1 : 0
						];
						if(!$__data['sah']) $_data['sah'] = 0;
						insert_data('tbl_persyaratan_vendor',$__data);
					}
				}

				insert_data('tbl_persyaratan_vendor',$_data);
			}
		}

		$sah 	= 1;
		$persyaratan 	= get_data('tbl_persyaratan_vendor',[
			'where'		=> [
				'id_vendor'			=> $id_vendor,
				'nomor_pengadaan'	=> $nomor_pengadaan
			]
		])->result();

		foreach($persyaratan as $p) {
			if($p->mandatori == 1 && $p->sah == 0) $sah = 9;
		}

		$inisiasi 					= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$nomor_pengajuan)->row();
		$rks 						= get_data('tbl_rks',[
			'where'					=> [
				'nomor_pengajuan'	=> $nomor_pengajuan,
				'tipe_rks'			=> 'aanwijzing'
			],
			'sort_by'	=> 'id',
			'sort'		=> 'DESC'
		])->row();
		$nilai_jaminan_seharusnya 	= ($inisiasi->ketentuan_bank_garansi / 100) * $nilai_total_penawaran;
		$persentase_total 			= ($nilai_total_penawaran / $inisiasi->hps_panitia) * 100;
		$persentase_jaminan			= ($nilai_jaminan_penawaran / $nilai_total_penawaran) * 100;

		if($persentase_jaminan < $inisiasi->ketentuan_bank_garansi) $sah = 9;
		if($persentase_total > $rks->batas_hps_atas || $persentase_total < $rks->batas_hps_bawah) $sah = 9;

		update_data('tbl_aanwijzing_vendor',[
			'nilai_total_penawaran'		=> $nilai_total_penawaran,
			'nilai_jaminan_penawaran'	=> $nilai_jaminan_penawaran,
			'nilai_jaminan_seharusnya'	=> $nilai_jaminan_seharusnya,
			'persentase_total'			=> $persentase_total,
			'persentase_jaminan'		=> $persentase_jaminan,
			'lolos_penawaran'			=> $sah
		],[
			'id_vendor'					=> $id_vendor,
			'nomor_pengajuan'			=> $nomor_pengajuan
		]);

		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function resume($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) == 2) {
			$aanwijzing 				= get_data('tbl_aanwijzing','id',$id[0])->row();
			$inisiasi 					= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$aanwijzing->nomor_pengajuan)->row();
			$rks 						= get_data('tbl_rks',[
				'where'					=> [
					'nomor_pengajuan'	=> $aanwijzing->nomor_pengajuan,
					'tipe_rks'			=> 'aanwijzing'
				],
				'sort_by'				=> 'id',
				'sort'					=> 'DESC'
			])->row();

			$data['nama_pengadaan']			= $aanwijzing->nama_pengadaan;
			$data['hps_pengadaan']			= $aanwijzing->hps;
			$data['ketentuan_bank_garansi']	= $inisiasi->ketentuan_bank_garansi;
			$data['batas_hps_bawah']		= $rks->batas_hps_bawah;
			$data['batas_hps_atas']			= $rks->batas_hps_atas;

			$grup_dokumen					= $data['grup_dokumen'] = [
				'persyaratan_peserta'		=> lang('dokumen_hal_yang_menggugurkan'),
				'dokumen_administrasi'		=> lang('dokumen_administrasi'),
				'dokumen_teknis'			=> lang('dokumen_teknis'),
				'dokumen_penawaran_harga'	=> lang('dokumen_penawaran_harga')
			];
			$data['dokumen_persyaratan']	= [];
			foreach($grup_dokumen as $k => $v) {
				$data['dokumen_persyaratan'][$k][0]	= get_data('tbl_dokumen_persyaratan',[
					'where'					=> [
						'grup'				=> $k,
						'parent_id'			=> 0,
						'nomor_pengajuan'	=> $aanwijzing->nomor_pengajuan
					],
					'sort_by'				=> 'id'
				])->result_array();
				foreach ($data['dokumen_persyaratan'][$k][0] as $key => $value) {
					$data['dokumen_persyaratan'][$k][$value['id']]	= get_data('tbl_dokumen_persyaratan',[
						'where'					=> [
							'grup'				=> $k,
							'parent_id'			=> $value['id'],
							'nomor_pengajuan'	=> $aanwijzing->nomor_pengajuan
						],
						'sort_by'				=> 'id'
					])->result_array();
				}
			}
			$data['mandatori']			= [];

			$vendor				= get_data('tbl_aanwijzing_vendor',[
				'where'		=> 'nomor_pengadaan = "'.$aanwijzing->nomor_pengadaan.'" AND jumlah_edit > 0',
				'sort_by'	=> 'id'
			])->result_array();
			foreach($vendor as $v) {
				$persyaratan_vendor	= get_data('tbl_persyaratan_vendor',[
					'where'		=> [
						'nomor_pengadaan'	=> $aanwijzing->nomor_pengadaan,
						'id_vendor'			=> $v['id_vendor']
					]
				])->result_array();
				$data['ceklis_vendor'][$v['id_vendor']] = [];
				foreach($persyaratan_vendor as $pv) {
					$data['ceklis_vendor'][$v['id_vendor']][$pv['id_persyaratan']] = $pv['sah'];
					$data['mandatori'][$pv['grup']]	= $pv['mandatori'];
				}
				$data['status_dokumen'][$v['id_vendor']] = [];
				foreach($grup_dokumen as $k1 => $v1) {
					$check 	= get_data('tbl_persyaratan_vendor',[
						'select'	=> 'COUNT(id) AS jml',
						'where'		=> [
							'nomor_pengadaan'	=> $aanwijzing->nomor_pengadaan,
							'id_vendor'			=> $v['id_vendor'],
							'grup'				=> $k1,
							'sah'				=> 0
						]
					])->row();
					$data['status_dokumen'][$v['id_vendor']][$k1] = $check->jml ? 0 : 1;
				}
			}

			$sub 	= 3;
			$bagi 	= ceil(count($vendor) / $sub);
			$data['vendor'] = [];
			for($i=0; $i<$bagi; $i++) {
				$x 	= $i * $sub;
				$y 	= $x + $sub;
				for($j = $x; $j < $y; $j++) {
					if(isset($vendor[$j])) {
						$data['vendor'][$i][] = $vendor[$j];
					}
				}
			}

			$data['peringkat_vendor']	= get_data('tbl_aanwijzing_vendor',[
				'where'	=> [
					'nomor_pengadaan'	=> $aanwijzing->nomor_pengadaan,
					'jumlah_edit >'		=> 0
				],
				'sort_by'	=> 'nilai_total_penawaran'
			])->result_array();

			render($data,'pdf');
		} else render('404');
	}

	function daftar_rekanan($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) == 2) {
			$data 		= get_data('tbl_aanwijzing','id',$id[0])->row_array();
			$vendor 	= get_data('tbl_aanwijzing_vendor',[
				'where'	=> [
					'nomor_pengadaan'		=> $data['nomor_pengadaan'],
					'tanggal_penawaran !='	=> '0000-00-00 00:00:00'
				]
			])->result();
			$id_vendor 	= [0];
			foreach($vendor as $v) {
				$id_vendor[]	= $v->id_vendor;
			}
			$data['vendor']	= get_data('tbl_vendor','id',$id_vendor)->result_array();
			render($data,'pdf');
		} else render('404');
	}

	function pembatalan() {
		$pengadaan 	= get_data('tbl_aanwijzing','nomor_pengadaan',post('nomor_pengadaan'))->row();
		$save 	= update_data('tbl_aanwijzing',['status_aanwijzing'=>'BATAL_PENAWARAN','stat_pengadaan'=>'BATAL','last_pos'=>'PENAWARAN'],'nomor_pengadaan',post('nomor_pengadaan'));
		update_data('tbl_aanwijzing_vendor',['status_aanwijzing'=>'BATAL'],'nomor_pengadaan',post('nomor_pengadaan'));
		update_data('tbl_pengadaan',['status_pengadaan'=>'BATAL'],'nomor_pengadaan',post('nomor_pengadaan'));
		update_data('tbl_pengadaan_bidder',['status_pengadaan'=>'BATAL'],'nomor_pengadaan',post('nomor_pengadaan'));
		update_data('tbl_pengadaan_detail',['status_pengadaan'=>'BATAL'],'nomor_pengadaan',post('nomor_pengadaan'));
		update_data('tbl_pengajuan',['status_desc'=>'Dibatalkan karena jumlah peserta pengadaan yang sah tidak memenuhi persyaratan'],'nomor_pengajuan',$pengadaan->nomor_pengajuan);
		update_data('tbl_chat_key',['is_active'=>0],'id',$pengadaan->id_chat_penawaran);

		if($save) {
			$pengajuan 	= get_data('tbl_pengajuan','nomor_pengajuan',$pengadaan->nomor_pengajuan)->row();
			if(isset($pengajuan->id)) {
				update_data('sap_detail',['is_deleted'=>'1','deleted_date'=>date('Y-m-d H:i:s')],'purchase_req_item',$pengajuan->purchase_req_item);

			}

			$p_vendor 	= get_data('tbl_aanwijzing_vendor','nomor_pengadaan',post('nomor_pengadaan'))->result();
			$id_vendor 	= [-1];
			foreach($p_vendor as $p) {
				$id_vendor[]	= $p->id_vendor;
			}

			$user 		= get_data('tbl_user','id',$pengadaan->id_creator)->row();
			$vendor 	= get_data('tbl_user','id_vendor',$id_vendor)->result();
			$id_user 	= $email_user = [];
			if(isset($user->id)) {
				$id_user[] 		= $user->id;
				$email_user[]	= $user->email;
			}
			foreach($vendor as $v) {
				$id_user[] 		= $v->id;
				$email_user[]	= $v->email;
			}

			if(count($id_user) > 0 && isset($pengadaan->id)) {
				$link				= base_url();
				$desctiption 		= 'Proses Pengadaan <strong>'.$pengadaan->nomor_pengadaan.'</strong> dibatalkan, dikarenakan jumlah peserta pengadaan yang sah tidak memenuhi persyaratan.';
				foreach($id_user as $iu) {
					$data_notifikasi 	= [
						'title'			=> 'Pengadaan',
						'description'	=> $desctiption,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'danger',
						'notif_icon'	=> 'fa-times-circle',
						'id_user'		=> $iu,
						'transaksi'		=> 'penawaran',
						'id_transaksi'	=> post('id')
					];
					insert_data('tbl_notifikasi',$data_notifikasi);
				}

				if(setting('email_notification') && count($email_user) > 0) {
					send_mail([
						'subject'		=> 'Pembatalan Pengadaan #'.$pengadaan->nomor_pengadaan,
						'bcc'			=> $email_user,
						'nama_user'		=> '',
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

	function get_user($id='') {
		$pengadaan				= get_data('tbl_aanwijzing','id = '.$id.'')->row_array();
		$user_auto_terdaftar 	= '';
		if(isset($pengadaan['id'])) {
			$panitia 			= get_data('tbl_panitia_pelaksana','nomor_pengajuan',$pengadaan['nomor_pengajuan'])->result();
			$userid 			= [$pengadaan['id_creator']];
			foreach($panitia as $p) {
				$userid[]		= $p->userid;
			}
			$user_pengadaan 		= get_data('tbl_alur_persetujuan',[
				'where'				=> [
					'nomor_pengajuan'	=> $pengadaan['nomor_pengajuan'],
					'jenis_approval'	=> 'PERMINTAAN'
				]
			])->result();
			foreach($user_pengadaan as $k => $u) {
				if(strpos(strtolower($u->nama_persetujuan),'direktur') === false ) {
					$userid[]	= $u->id_user;
				}
			}
			$user_auto_terdaftar= implode(',', $userid);
		}
		$query 	= get('query');
		if($user_auto_terdaftar) {
			$user 	= get_data('tbl_user','is_active=1 AND nama LIKE "%'.$query.'%" AND id_vendor = 0 AND id NOT IN ('.$user_auto_terdaftar.')')->result();
		} else {
			$user 	= get_data('tbl_user','is_active=1 AND nama LIKE "%'.$query.'%" AND id_vendor = 0')->result();
		}
		$data['suggestions'] = [];
		foreach($user as $u) {
			$data['suggestions'][] = [
				'value'	=> $u->nama,
				'data'	=> $u->id
			];
		}
		render($data,'json');
	}

	function inisiasi_ulang() {
		$nomor_pengadaan	= post('nomor_pengadaan');
		$pengadaan 			= get_data('tbl_pengadaan','nomor_pengadaan',$nomor_pengadaan)->row();
		$nomor_pengajuan 	= $pengadaan->nomor_pengajuan;

		update_data('tbl_aanwijzing',['inisiasi_ulang'=>1],'nomor_pengadaan',$nomor_pengadaan);
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