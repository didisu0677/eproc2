<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Peninjauan_lapangan extends BE_Controller {

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
			'where'			=> [
				'tipe_pengadaan'	=> 'Lelang'
			]
		];
		if(menu()['access_additional']) {
			$anggota_panitia	= get_data('tbl_anggota_panitia','userid',user('id'))->result();
			$id_panitia			= [0];
			foreach($anggota_panitia as $a) $id_panitia[] = $a->id_m_panitia;
			$config['where']['id_panitia']	= $id_panitia;
			$config['button'][]	= button_serverside('btn-success',base_url('pengadaan/peninjauan_lapangan/penugasan/'),['fa-file-alt',lang('penugasan'),true],'btn-penugasan',['status_aanwijzing'=>'PENINJAUAN']);
		} elseif(menu()['access_input']) {
			$config['button'][]	= button_serverside('btn-success',base_url('pengadaan/peninjauan_lapangan/penugasan/'),['fa-file-alt',lang('penugasan'),true],'btn-penugasan',['status_aanwijzing'=>'PENINJAUAN']);
		} else {
			$config['where']['id_creator']	= user('id');
		}
		$config['button'][]	= button_serverside('btn-info',base_url('pengadaan/peninjauan_lapangan/detail/'),['fa-search',lang('detil'),true],'btn-detail');
		if($status == 1) $config['where']['status_aanwijzing']	= 'PENINJAUAN';
		else {
			$config['sort_by'] 	= 'id';
			$config['sort']		= 'DESC';
			$config['where']['status_aanwijzing !=']	= ['AANWIJZING','PENAWARAN','BATAL_PENAWARAN','EVALUASI','PENINJAUAN'];
		}
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function penugasan($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2 && menu()['access_input']) {
			$data 			= get_data('tbl_aanwijzing','id = '.$id[0])->row_array();
			if(isset($data['id'])) {
				$this->load->helper('pengadaan');
				$data['title']				= lang('penugasan').' '.$data['nomor_pengadaan'];
				$data['id_rks_aanwijzing']	= id_by_nomor($data['nomor_pengajuan'],'rks','aanwijzing');
				$data['rekanan']			= get_data('tbl_aanwijzing_vendor',[
					'where'	=> [
						'nomor_pengadaan'	=> $data['nomor_pengadaan'],
						'lolos_penawaran'	=> 1
					],
					'sort_by'				=> 'rank_evaluasi'
				])->result_array();
				$data['create_tugas']		= true;
				$data['cur_id_tugas']		= 0;
				if($data['metode_negosiasi'] == 'Negosiasi Satu Rekanan') {
					$has_tugas 				= get_data('tbl_aanwijzing_vendor',[
						'where'	=> [
							'nomor_pengadaan'		=> $data['nomor_pengadaan'],
							'lolos_penawaran'		=> 1,
							'tanggal_peninjauan !='	=> '0000-00-00',
							'status_peninjauan <'	=> 2
						]
					])->row();
					if(isset($has_tugas->id)) {
						$data['create_tugas'] 	= false;
						$data['cur_id_tugas']	= $has_tugas->id;
					}
				}

				render($data);
			} else render('404');
		} else render('404');
	}

	function detail_vendor($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) > 0) {
			include_lang('manajemen_rekanan');
			$data 	= get_data('tbl_vendor','id',$id[0])->row_array();
			render($data,'layout:false');
		} else echo lang('data_tidak_ada');
	}

	function init_penugasan() {
		$id 	= post('id');
		$awz 	= get_data('tbl_aanwijzing_vendor','id',$id)->row();
		$data 	= [
			'nama_rekanan'			=> '',
			'alamat_rekanan'		=> '',
			'nomor_surat_tugas'		=> '',
			'tanggal_peninjauan'	=> '',
			'nama_pemberi_tugas'	=> '',
			'jabatan_pemberi_tugas'	=> '',
			'detail'				=> []
		];
		if(isset($awz->id)) {
			$vendor 	= get_data('tbl_vendor','id',$awz->id_vendor)->row();
			if(isset($vendor->id)) {
				$data['nama_rekanan']			= $vendor->nama;
				$data['alamat_rekanan']			= $vendor->alamat.', '.$vendor->nama_kelurahan.', '.$vendor->nama_kecamatan.', '.$vendor->nama_kota.', '.$vendor->nama_provinsi;
				$data['nomor_surat_tugas']		= $awz->nomor_surat_tugas;
				$data['tanggal_peninjauan']		= c_date($awz->tanggal_peninjauan);
				$data['nama_pemberi_tugas']		= $awz->nama_pemberi_tugas;
				$data['jabatan_pemberi_tugas']	= $awz->jabatan_pemberi_tugas;
			}
			$data['detail']					= get_data('tbl_aanwijzing_peninjauan',[
				'where'		=> [
					'id_aanwijzing_vendor'	=> $id
				],
				'sort_by'	=> 'posisi',
				'sort'		=> 'desc'
			])->result();
		}
		render($data,'json');
	}

	function get_tim_peninjauan($id_aanwijzing='') {
		$data['suggestions']	= [];

		$aanwijzing 	= get_data('tbl_aanwijzing','id',$id_aanwijzing)->row();
		if(isset($aanwijzing->id)) {
			$panitia 	= get_data('tbl_panitia_pelaksana',[
				'where'	=> [
					'nomor_pengajuan'	=> $aanwijzing->nomor_pengajuan,
					'id_m_panitia'		=> $aanwijzing->id_panitia
				],
				'like'	=> [
					'nama_panitia'		=> get('query')
				]
			])->result();
			foreach($panitia as $u) {
				$data['suggestions'][] = [
					'value'	=> $u->nama_panitia . ' | Panitia Pengadaan',
					'data'	=> $u->userid
				];
			}

			$user 		= get_data('tbl_user a',[
				'select'	=> 'a.id,a.nama,b.unit AS unit_kerja',
				'join'		=> 'tbl_m_unit b ON a.id_unit_kerja = b.id TYPE LEFT',
				'where'		=> [
					'a.is_active'			=> 1,
					'a.id_divisi'			=> $aanwijzing->id_divisi,
					'a.id_unit_kerja'		=> $aanwijzing->id_unit_kerja2
				],
				'like'	=> [
					'nama'	=> get('query')
				],
				'sort_by'	=> 'a.id_unit_kerja'
			])->result();
			foreach($user as $u) {
				$data['suggestions'][] = [
					'value'	=> $u->nama . ' | ' . $u->unit_kerja,
					'data'	=> $u->id
				];
			}
		}
		render($data,'json');
	}

	function save_penugasan() {
		$id 		= post('id');
		$detail 	= get_data('tbl_aanwijzing_vendor','id',$id)->row();
		$data 		= post();
		$has_save	= true;
		if(!$detail->nomor_surat_tugas) {
			$has_save 						= false;
			$data['nomor_surat_tugas'] 		= generate_code('tbl_aanwijzing_vendor','nomor_surat_tugas',$data);
			$data['tanggal_surat_tugas']	= date('Y-m-d');
		}
		$save 		= update_data('tbl_aanwijzing_vendor',$data,'id',$id);
		if($save) {
			$id_anggota		= post('id_anggota');
			$ketua 			= $id_anggota[0];
			$awz 			= get_data('tbl_aanwijzing_vendor','id',$id)->row();
			$awz_parent 	= get_data('tbl_aanwijzing','nomor_pengadaan',$awz->nomor_pengadaan)->row();
			if(is_array($id_anggota) && count($id_anggota) > 0) {
				$user 	= get_data('tbl_user a',[
					'select'	=> 'a.id,a.nama,a.jabatan,b.unit AS unit_kerja,a.email',
					'join'		=> 'tbl_m_unit b ON a.id_unit_kerja = b.id TYPE LEFT',
					'where'		=> [
						'a.id'	=> $id_anggota
					]
				])->result();

				$id_user 	= $email_user = [];
				delete_data('tbl_aanwijzing_peninjauan','id_aanwijzing_vendor',$id);
				$data_save	= [];
				foreach($user as $u) {
					$id_user[] 		= $u->id;
					$email_user[] 	= $u->email;
					$data_save[] 	= [
						'id_aanwijzing_vendor'	=> $id,
						'nomor_pengadaan'		=> $awz->nomor_pengadaan,
						'nama_pengadaan'		=> $awz->nama_pengadaan,
						'nama_vendor'			=> $awz->nama_vendor,
						'nomor_surat_tugas'		=> $awz->nomor_surat_tugas,
						'tanggal_peninjauan'	=> $awz->tanggal_peninjauan,
						'id_user'				=> $u->id,
						'nama_user'				=> $u->nama,
						'jabatan_user'			=> $u->jabatan,
						'unit_kerja_user'		=> $u->unit_kerja,
						'posisi'				=> $ketua == $u->id ? 'Ketua' : 'Anggota'
					];
				}
				if(count($data_save) > 0) {
					insert_batch('tbl_aanwijzing_peninjauan',$data_save);
				}

				$vendor 	= get_data('tbl_user','id_vendor',$awz->id_vendor)->row();
				if(isset($vendor->id)) {
					$email_user[]	= $vendor->email;
				}

				if(!$has_save && count($id_user) > 0) {
					$link				= base_url().'pengadaan/laporan_peninjauan/detail/'.encode_id($id);
					$desctiption 		= 'Tugas Peninjauan Lapangan untuk pengadaan <strong>'.$awz->nomor_pengadaan.'</strong>';
					foreach($id_user as $iu) {
						$data_notifikasi 	= [
							'title'			=> 'Peninjauan Lapangan',
							'description'	=> $desctiption,
							'notif_link'	=> $link,
							'notif_date'	=> date('Y-m-d H:i:s'),
							'notif_type'	=> 'info',
							'notif_icon'	=> 'fa-map-marker-alt',
							'id_user'		=> $iu,
							'transaksi'		=> 'peninjauan_lapangan',
							'id_transaksi'	=> $awz->id
						];
						insert_data('tbl_notifikasi',$data_notifikasi);
					}

					if(count($email_user) > 0 && setting('email_notification')) {
						send_mail([
							'subject'		=> 'Peninjauan Lapangan #'.$awz->nomor_pengadaan,
							'bcc'			=> $email_user,
							'detail'		=> $awz,
							'peninjau'		=> get_data('tbl_aanwijzing_peninjauan',[
								'where'		=> [
									'id_aanwijzing_vendor'	=> $id
								],
								'sort_by'	=> 'posisi',
								'sort'		=> 'desc'
							])->result(),
							'url'			=> $link
						]);
					}
				}
			}
		}
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function surat_tugas($encode_id='') {
		$decode = decode_id($encode_id);
		if(count($decode) == 2) {
			$data 	= get_data('tbl_aanwijzing_vendor','id',$decode[0])->row_array();
			if(isset($data['id'])) {
				$data['peninjau']	= get_data('tbl_aanwijzing_peninjauan',[
					'where'		=> [
						'id_aanwijzing_vendor'	=> $data['id']
					],
					'sort_by'	=> 'posisi',
					'sort'		=> 'desc'
				])->result();
				render($data,'pdf');
			} else render('404');
		} else render('404');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_aanwijzing','id = '.$id[0])->row_array();
			if(isset($data['id'])) {
				$this->load->helper('pengadaan');
				$data['title']				= lang('penugasan').' '.$data['nomor_pengadaan'];
				$data['id_rks_aanwijzing']	= id_by_nomor($data['nomor_pengajuan'],'rks','aanwijzing');
				$data['rekanan']			= get_data('tbl_aanwijzing_vendor',[
					'where'	=> [
						'nomor_pengadaan'	=> $data['nomor_pengadaan'],
						'lolos_penawaran'	=> 1
					],
					'sort_by'				=> 'rank_evaluasi'
				])->result_array();

				$data['jml_penugasan']		= 0;
				$data['jml_laporan']		= 0;
				$data['jml_layak']			= 0;
				foreach($data['rekanan'] as $d) {
					if($d['tanggal_peninjauan'] != '0000-00-00') $data['jml_penugasan']++;
					if($d['status_peninjauan']) $data['jml_laporan']++;
					if($d['status_peninjauan'] == 1) $data['jml_layak']++;
				}

				$penyetuju			= get_data('tbl_alur_persetujuan',[
					'where'			=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan'],
						'jenis_approval'	=> 'PENGADAAN'
					],
					'sort_by'		=> 'id'
				])->result();
				foreach($penyetuju as $p) {
					$data['peserta_lain'][$p->id_user] = $p->nama_user;
				}
				$i = 0;
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

				render($data);
			} else render('404');
		} else render('404');
	}

	function data_pendukung($encode_id='') {
		$id = decode_id($encode_id);
		$id = isset($id[0]) ? $id[0] : 0;
		$data 		= get_data('tbl_aanwijzing_vendor','id',$id)->row_array();
		if(isset($data['id'])) {
			$ketua 		= get_data('tbl_aanwijzing_peninjauan',[
				'where'		=> [
					'posisi'				=> 'Ketua',
					'id_aanwijzing_vendor'	=> $id
				]
			])->row();
			$data['ketua']		= isset($ketua->nama_user) ? $ketua->nama_user : '';
			$data['result']		= json_decode($data['data_pendukung'],true);
			$data['lain']		= $data['result']['lain'];
			unset($data['result']['lain']);
			render($data,'pdf');
		} else render('404');
	}

	function laporan_peninjauan($encode_id='') {
		$id = decode_id($encode_id);
		$id = isset($id[0]) ? $id[0] : 0;
		$data 		= get_data('tbl_aanwijzing_vendor','id',$id)->row_array();
		if(isset($data['id'])) {
			$ketua 		= get_data('tbl_aanwijzing_peninjauan',[
				'where'		=> [
					'posisi'				=> 'Ketua',
					'id_aanwijzing_vendor'	=> $id
				]
			])->row();
			$data['ketua']		= isset($ketua->nama_user) ? $ketua->nama_user : '';
			$data['result']		= json_decode($data['hasil_peninjauan'],true);
			$data['lain']		= $data['result']['lain'];
			unset($data['result']['lain']);
			render($data,'pdf');
		} else render('404');
	}

	function pembatalan() {
		$pengadaan 	= get_data('tbl_aanwijzing','nomor_pengadaan',post('nomor_pengadaan'))->row();
		$save 	= update_data('tbl_aanwijzing',['status_aanwijzing'=>'BATAL_PENINJAUAN','stat_pengadaan'=>'BATAL','last_pos'=>'PENINJAUAN'],'nomor_pengadaan',post('nomor_pengadaan'));
		update_data('tbl_aanwijzing_vendor',['status_aanwijzing'=>'BATAL'],'nomor_pengadaan',post('nomor_pengadaan'));
		update_data('tbl_pengadaan',['status_pengadaan'=>'BATAL'],'nomor_pengadaan',post('nomor_pengadaan'));
		update_data('tbl_pengadaan_bidder',['status_pengadaan'=>'BATAL'],'nomor_pengadaan',post('nomor_pengadaan'));
		update_data('tbl_pengadaan_detail',['status_pengadaan'=>'BATAL'],'nomor_pengadaan',post('nomor_pengadaan'));
		update_data('tbl_pengajuan',['status_desc'=>'Dibatalkan pada proses Peninjauan Lapangan karena jumlah peserta pengadaan yang layak tidak memenuhi persyaratan'],'nomor_pengajuan',$pengadaan->nomor_pengajuan);

		if($save) {
			$pengajuan 	= get_data('tbl_pengajuan','nomor_pengajuan',$pengadaan->nomor_pengajuan)->row();
			if(isset($pengajuan->id)) {
				update_data('sap_detail',['is_deleted'=>'1','deleted_date'=>date('Y-m-d H:i:s')],'purchase_req_item',$pengajuan->purchase_req_item);

			}
			$p_vendor 	= get_data('tbl_aanwijzing_vendor',[
				'where'	=> [
					'nomor_pengadaan'	=> post('nomor_pengadaan'),
					'lolos_penawaran'	=> 1
				]
			])->result();
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
				$desctiption 		= 'Proses Pengadaan <strong>'.$pengadaan->nomor_pengadaan.'</strong> dibatalkan, dikarenakan jumlah peserta pengadaan yang layak tidak memenuhi persyaratan.';
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

	function save() {
		$data['stat_pengadaan'] 	= 'KLARIFIKASI';
		$data['nomor_pengadaan']	= post('nomor_pengadaan');
		$aanwijzing 				= get_data('tbl_aanwijzing','nomor_pengadaan',$data['nomor_pengadaan'])->row();
		$data['status_aanwijzing']	= 'KLARIFIKASI';

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
		$vendor 			= get_data('tbl_aanwijzing_vendor',[
			'where'	=> [
				'nomor_pengadaan'	=> $aanwijzing->nomor_pengadaan,
				'status_peninjauan'	=> 1
			]
		])->result_array();
		$keterangan_chat 	= '';
		if(isset($vendor[0]) && $aanwijzing->metode_negosiasi == 'Negosiasi Satu Rekanan') {
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