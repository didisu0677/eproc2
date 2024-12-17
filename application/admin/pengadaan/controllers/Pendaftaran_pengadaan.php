<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendaftaran_pengadaan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data($status=1) {
		$anggota_panitia	= get_data('tbl_anggota_panitia','userid',user('id'))->result();
		$id_panitia			= [0];
		foreach($anggota_panitia as $a) $id_panitia[] = $a->id_m_panitia;
		$config				= [
			'access_view' 	=> false,
			'access_delete'	=> false,
			'access_edit'	=> false,
			'where'			=> [
				'id_panitia'		=> $id_panitia
			]
		];
		if($status == 1) {
			$config['where']['status_pengadaan']	= 'BIDDING';
			$config['button']	= button_serverside('btn-info',base_url('pengadaan/pendaftaran_pengadaan/detail/'),['fa-search',lang('detil'),true],'btn-detail',['status_pengadaan'=>'BIDDING']);
		} else {
			$config['where']['status_pengadaan !=']	= 'BIDDING';
			$config['sort_by'] 	= 'id';
			$config['sort']		= 'DESC';
			$config['button']	= button_serverside('btn-info',base_url('pengadaan/pendaftaran_pengadaan/detail/'),['fa-search',lang('detil'),true],'btn-detail',['status_pengadaan'=>['AANWIJZING','BATAL']]);
		}
		$data = data_serverside($config);
		render($data,'json');
	}

	function detail($encode_id='') {
		$id 	= decode_id($encode_id);
		if(count($id) > 0) {
			$anggota_panitia	= get_data('tbl_anggota_panitia','userid',user('id'))->result();
			$id_panitia			= [0];
			foreach($anggota_panitia as $a) $id_panitia[] = $a->id_m_panitia;

			$data	= get_data('tbl_pengadaan','id = '.$id[0].' AND status_pengadaan IN ("BIDDING","BATAL","AANWIJZING")')->row_array();
			if(isset($data['id']) && in_array($data['id_panitia'],$id_panitia)) {
				$this->load->helper('pengadaan');
				$data['title']			= $data['nomor_pengadaan'];
				$data['id_pengajuan']	= id_by_nomor($data['nomor_pengajuan'],'pengajuan');
				$data['id_rks']			= id_by_nomor($data['nomor_pengajuan'],'rks');
				$data['bidder']			= get_data('tbl_pengadaan_bidder','nomor_pengadaan',$data['nomor_pengadaan'])->result();
				$aanwijzing 			= get_data('tbl_aanwijzing','nomor_pengadaan',$data['nomor_pengadaan'])->row();
				$peserta_aanwijzing		= get_data('tbl_aanwijzing_vendor','nomor_pengadaan',$data['nomor_pengadaan'])->result();
				$data['vendor_peserta']= [];
				foreach($peserta_aanwijzing as $p) {
					$data['vendor_peserta'][$p->id_vendor]	= $p->id_vendor;
				}
				$tanggal_pendaftaran	= get_data('tbl_jadwal_pengadaan','kata_kunci = "pendaftaran_pengadaan" AND nomor_pengajuan = "'.$data['nomor_pengajuan'].'"')->row();
				$data['open_pendaftaran']	= false;
				if(isset($tanggal_pendaftaran->id)) {
					if(strtotime($tanggal_pendaftaran->tanggal_awal) <= strtotime('now')) {
						$data['open_pendaftaran'] 	= true;
					}
					$data['tanggal_pendaftaran']	= c_date($tanggal_pendaftaran->tanggal_awal).' - '.c_date($tanggal_pendaftaran->tanggal_akhir);
				}
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
				$data['peserta_lain']	= [];
				if(isset($aanwijzing->id)) {
					$data['peserta_lain']	= json_decode($aanwijzing->peserta_lain,true);
				}
				$i = 0;
				$data['id_peserta_lain'] = $data['nama_peserta_lain'] = '';
				foreach($data['peserta_lain'] as $k => $v) {
					if($i == 0) {
						$data['id_peserta_lain'] 	= $k;
						$data['nama_peserta_lain']	= $v;
						unset($data['peserta_lain'][$k]);
					}
					$i++;
				}
				$data['panitia_pelaksana']	= get_data('tbl_panitia_pelaksana','nomor_pengajuan',$data['nomor_pengajuan'])->result();
				$pengajuan 					= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
				$data['nama_creator']		= isset($pengajuan->id) ? $pengajuan->create_by : '-';
				$data['nama_panitia']		= isset($pengajuan->id) ? $pengajuan->nama_panitia : 'Panitia';

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

	function save() {
		$id_vendor 		= post('id_vendor');
		$id_pengadaan	= post('id_pengadaan');
		$_id_anggota	= post('id_anggota');
		$_nm_anggota 	= post('anggota');
		if(is_array($id_vendor) && count($id_vendor) > 0) {
			$data 				= $pengadaan = get_data('tbl_pengadaan','id',$id_pengadaan)->row_array();
			$jadwal_aanwijzing	= get_data('tbl_jadwal_pengadaan','kata_kunci = "aanwijzing" AND nomor_pengajuan = "'.$data['nomor_pengajuan'].'"')->row();
			$tanggal_aanwijzing	= isset($jadwal_aanwijzing->id) ? c_date($jadwal_aanwijzing->tanggal_awal).' - '.c_date($jadwal_aanwijzing->tanggal_akhir) : 'sekarang';
			unset($data['id']);
			$peserta_lain	= [];
			if(is_array($_id_anggota) && count($_id_anggota) > 0) {
				foreach($_id_anggota as $k => $v) {
					if($v) {
						$peserta_lain[$v]	= $_nm_anggota[$k];
					}
				}
			}
			$aanwijzing 	= get_data('tbl_aanwijzing','nomor_pengadaan',$data['nomor_pengadaan'])->row();
			if(isset($aanwijzing->id)) {
				$data 		= [
					'id'	=> $aanwijzing->id
				];
			}
			$data['status_aanwijzing']	= 'AANWIJZING';
			$data['peserta_lain']		= json_encode($peserta_lain);
			$response 		= save_data('tbl_aanwijzing',$data,[],true);
			if($response['status'] == 'success') {
				if(isset($data['id']) && $data['id']) {
					delete_data('tbl_aanwijzing_vendor','nomor_pengadaan',$pengadaan['nomor_pengadaan']);
				}
				$aanwijzing 			= get_data('tbl_aanwijzing','id',$response['id'])->row_array();
				$aanwijzing_chat_id		= $aanwijzing['id_chat'];
				$field_aanwijzing_vendor= get_field('tbl_aanwijzing_vendor','name');
				$anggota_chat 	= $id_anggota = $id_user_notifikasi = $email_user_notifikasi = [];
				foreach($id_vendor as $i) {
					$vendor 	= get_data('tbl_vendor','id',$i)->row();
					$aanwijzing['id_vendor']	= $i;
					$aanwijzing['nama_vendor']	= $vendor->nama;
					$user_vendor				= get_data('tbl_user','id_vendor',$i)->row();
					$id_anggota[] 				= $user_vendor->id;
					$anggota_chat[]				= $user_vendor->nama;
					$id_user_notifikasi[] 		= $user_vendor->id;
					$email_user_notifikasi[]	= $user_vendor->email;
					$data_vendor				= [];
					foreach($field_aanwijzing_vendor as $f) {
						if(isset($aanwijzing[$f]) && $f != 'id') {
							$data_vendor[$f]		= $aanwijzing[$f];
						}
					}
					insert_data('tbl_aanwijzing_vendor',$data_vendor);
				}
				$pendaftar_tidak_lolos	= get_data('tbl_pengadaan_bidder',[
					'where'	=> [
						'nomor_pengadaan'	=> $pengadaan['nomor_pengadaan'],
						'id_vendor !='		=> $id_vendor
					]
				])->result();
				if(count($pendaftar_tidak_lolos) > 0) {
					foreach($pendaftar_tidak_lolos as $ptl) {
						$user_vendor				= get_data('tbl_user','id_vendor',$ptl->id_vendor)->row();
						$email_user_notifikasi[]	= $user_vendor->email;
					}
				}
				foreach($peserta_lain as $i => $n) {
					$peserta 					= get_data('tbl_user','id',$i)->row();
					$id_anggota[] 				= $peserta->id;
					$anggota_chat[]				= $peserta->nama;
					$id_user_notifikasi[] 		= $peserta->id;
					$email_user_notifikasi[]	= $peserta->email;					
				}

				update_data('tbl_pengadaan',['status_pengadaan'=>'AANWIJZING'],'nomor_pengadaan',$pengadaan['nomor_pengadaan']);
				update_data('tbl_pengadaan_detail',['status_pengadaan'=>'AANWIJZING'],'nomor_pengadaan',$pengadaan['nomor_pengadaan']);
				update_data('tbl_pengadaan_bidder',['status_pengadaan'=>'AANWIJZING'],'nomor_pengadaan',$pengadaan['nomor_pengadaan']);

				update_data('tbl_pengajuan',['status_desc'=>'Aanwijzing'],'nomor_pengajuan',$pengadaan['nomor_pengajuan']);

				$panitia 	= get_data('tbl_panitia_pelaksana',[
					'where'	=> [
						'id_m_panitia'		=> $pengadaan['id_panitia'],
						'nomor_pengajuan'	=> $pengadaan['nomor_pengajuan']
					]
				])->result();

				foreach($panitia as $p) {
					$id_anggota[] 	= $p->userid;
					$anggota_chat[]	= $p->nama_panitia;
				}

				$creator 				= get_data('tbl_user','id',$pengadaan['id_creator'])->row();
				$id_anggota[]			= $creator->id;
				$anggota_chat[]			= $creator->nama;
				$id_user_notifikasi[]	= $creator->id;
				$email_user_notifikasi[]= $creator->email;

				$user_pengadaan 		= get_data('tbl_alur_persetujuan',[
					'where'				=> [
						'nomor_pengajuan'	=> $pengadaan['nomor_pengajuan'],
						'jenis_approval'	=> 'PERMINTAAN'
					]
				])->result();
				foreach($user_pengadaan as $k => $u) {
					if(strpos(strtolower($u->nama_persetujuan),'direktur') === false ) {
						$id_anggota[]	= $u->id_user;
						$anggota_chat[]	= $u->nama_user;
					}
				}

				if(!isset($data['id'])) {
					$data_chat 		= [
						'nama'		=> 'Aanwijzing '.$pengadaan['nomor_pengadaan'],
						'anggota'	=> implode(', ', $anggota_chat),
						'is_group'	=> 1,
						'is_active'	=> 1
					];
					if(isset($jadwal_aanwijzing->id)) {
						$data_chat['aktif_mulai']	= $jadwal_aanwijzing->tanggal_awal;
						$data_chat['aktif_selesai']	= $jadwal_aanwijzing->tanggal_akhir;
					}
					$chat 			= insert_data('tbl_chat_key',$data_chat);

					foreach($id_anggota as $i) {
						insert_data('tbl_chat_anggota',[
							'key_id'	=> $chat,
							'id_user'	=> $i,
							'is_read'	=> 1
						]);
					}
					update_data('tbl_aanwijzing',['id_chat' => $chat],'id',$response['id']);
				} else {
					$chat 			= update_data('tbl_chat_key',[
						'anggota'	=> implode(', ', $anggota_chat)
					],'id',$aanwijzing_chat_id);
					delete_data('tbl_chat_anggota','key_id',$aanwijzing_chat_id);
					foreach($id_anggota as $i) {
						insert_data('tbl_chat_anggota',[
							'key_id'	=> $aanwijzing_chat_id,
							'id_user'	=> $i,
							'is_read'	=> 1
						]);
					}
				}

				// kirim notifikasi ke pengguna dan vendor yg diterima bahwa chat aanwijzing sudah tergenerate
				if(count($id_user_notifikasi) > 0 && !isset($data['id'])) {
					$link				= base_url().'pengadaan/notif_aanwijzing/ref/'.encode_id([$response['id'],rand()]);
					$desctiption 		= 'Proses Pengadaan <strong>'.$aanwijzing['nomor_pengadaan'].'</strong> memasuki tahap aanwijzing. Grup Chat Aanwijzing akan aktif pada '.$tanggal_aanwijzing.'.';
					foreach($id_user_notifikasi as $iu) {
						$data_notifikasi 	= [
							'title'			=> 'Aanwijzing',
							'description'	=> $desctiption,
							'notif_link'	=> $link,
							'notif_date'	=> date('Y-m-d H:i:s'),
							'notif_type'	=> 'info',
							'notif_icon'	=> 'fa-chalkboard-teacher',
							'id_user'		=> $iu,
							'transaksi'		=> 'approval_pengadaan',
							'id_transaksi'	=> post('id')
						];
						insert_data('tbl_notifikasi',$data_notifikasi);
					}

					if(setting('email_notification') && count($email_user_notifikasi) > 0) {
						send_mail([
							'subject'				=> 'Pendaftaran Pengadaan #'.$pengadaan['nomor_pengadaan'],
							'bcc'					=> $email_user_notifikasi,
							'tanggal_aanwijzing'	=> $tanggal_aanwijzing,
							'nama_pengadaan'		=> $pengadaan['nama_pengadaan'],
							'vendor'				=> get_data('tbl_aanwijzing_vendor','nomor_pengadaan',$pengadaan['nomor_pengadaan'])->result_array(),
							'url'					=> $link
						]);
					}
				}
			}
		} else {
			$response = [
				'status'	=> 'failed',
				'message'	=> lang('aanwijzing_harus_diikuti_rekanan')
			];
		}
		render($response,'json');
	}

	function dokumen($encode_id='') {
		$id 	= decode_id($encode_id);
		if(count($id) == 2) {
			$pengajuan 	= get_data('tbl_rks','id',$id[0])->row();
			if(isset($pengajuan->id) && $pengajuan->file) {
				$data['file']	= json_decode($pengajuan->file,true);
				if(count($data['file'])) render($data,'layout:false');
				else echo lang('tidak_ada_data');
			} else echo lang('tidak_ada_data');
		} else echo lang('tidak_ada_data');
	}

	function get_user($id='') {
		$pengadaan				= get_data('tbl_pengadaan','id = '.$id.' AND status_pengadaan IN ("BIDDING","AANWIJZING")')->row_array();
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

	function pembatalan() {
		$nomor_pengadaan	= post('nomor_pengadaan');
		$pengadaan 			= get_data('tbl_pengadaan','nomor_pengadaan',$nomor_pengadaan)->row();
		$nomor_pengajuan 	= $pengadaan->nomor_pengajuan;

		$save 	= update_data('tbl_pengadaan',['status_pengadaan'=>'BATAL'],'nomor_pengadaan',$nomor_pengadaan);
		update_data('tbl_pengadaan_bidder',['status_pengadaan'=>'BATAL'],'nomor_pengadaan',$nomor_pengadaan);
		update_data('tbl_pengadaan_detail',['status_pengadaan'=>'BATAL'],'nomor_pengadaan',$nomor_pengadaan);
		update_data('tbl_pengajuan',['status_desc'=>'Dibatalkan karena jumlah pendaftaran pengadaan tidak memenuhi persyaratan'],'nomor_pengajuan',$nomor_pengajuan);

		if($save) {
			$p_vendor 	= get_data('tbl_pengadaan_bidder','nomor_pengadaan',$nomor_pengadaan)->result();
			$id_vendor 	= [-1];
			foreach($p_vendor as $p) {
				$id_vendor[]	= $p->id_vendor;
			}

			$pengajuan 	= get_data('tbl_pengajuan','nomor_pengajuan',$nomor_pengajuan)->row();
			if(isset($pengajuan->id)) {
				update_data('sap_detail',['is_deleted'=>'1','deleted_date'=>date('Y-m-d H:i:s')],'purchase_req_item',$pengajuan->purchase_req_item);
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
				$desctiption 		= 'Proses Pengadaan <strong>'.$pengadaan->nomor_pengadaan.'</strong> dibatalkan, dikarenakan jumlah pendaftar pengadaan tidak memenuhi persyaratan.';
				foreach($id_user as $iu) {
					$data_notifikasi 	= [
						'title'			=> 'Pendaftaran',
						'description'	=> $desctiption,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'danger',
						'notif_icon'	=> 'fa-times-circle',
						'id_user'		=> $iu,
						'transaksi'		=> 'pengadaan',
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

	function inisiasi_ulang() {
		$nomor_pengadaan	= post('nomor_pengadaan');
		$pengadaan 			= get_data('tbl_pengadaan','nomor_pengadaan',$nomor_pengadaan)->row();
		$nomor_pengajuan 	= $pengadaan->nomor_pengajuan;

		update_data('tbl_pengadaan',['status_pengadaan'=>'RE-INISIASI','inisiasi_ulang'=>1],'nomor_pengadaan',$nomor_pengadaan);
		update_data('tbl_pengadaan_bidder',['status_pengadaan'=>'RE-INISIASI'],'nomor_pengadaan',$nomor_pengadaan);
		update_data('tbl_pengadaan_detail',['status_pengadaan'=>'RE-INISIASI'],'nomor_pengadaan',$nomor_pengadaan);
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