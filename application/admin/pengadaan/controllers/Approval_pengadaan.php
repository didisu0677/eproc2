<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Approval_pengadaan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['id']	= 0;
		$id			= decode_id(get('i'));
		if(is_array($id) && isset($id[0])) $data['id'] = $id[0];
		render($data);
	}

	function data() {
		$access 			= get_access('approval_permintaan');
		$config['where'] 	= [
			'is_pos_approve'		=> 1,
			'id_user_persetujuan' 	=> user('id'),
			'approve'				=> 0
		];
		if($access['access_additional']) {
			$config['button'][]	= button_serverside('btn-info','btn-act-view',['fa-check-circle',lang('persetujuan'),true],'act-detail',['approve'=>0]);
		}
		$config['access_edit'] 		= false;
		$config['access_delete'] 	= false;
		$config['access_view'] 		= false;
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data 	= get_data('tbl_pengajuan','id',post('id'))->row_array();
		$rks	= get_data('tbl_rks','nomor_rks',$data['nomor_rks'])->row_array();
		$hps	= get_data('tbl_m_hps','nomor_hps',$data['no_hps'])->row_array();
		$data['hps_panitia']		= $hps['total_hps_pembulatan'];
		$data['id_hps']				= $hps['id'];
		$data['metode_pengadaan']	= $rks['metode_pengadaan'];
		$data['id_rks']				= $rks['id'];
		render($data,'json');
	}

	function save_persetujuan() {
		$pengajuan 					= get_data('tbl_pengajuan','id',post('id'))->row_array();
		$last_update 				= date('Y-m-d H:i:s');
		$check_curent_persetujuan	= get_data('tbl_alur_persetujuan',[
			'where'	=> [
				'id_pengajuan'		=> post('id'),
				'id_user' 			=> user('id'),
				'jenis_approval' 	=> 'PENGADAAN'
			]
		])->row();

		if($check_curent_persetujuan){
			update_data('tbl_alur_persetujuan',[
				'tanggal_persetujuan'	=> $last_update,
				'update_at'				=> $last_update,
				'update_by'				=> user('nama'),
				'status_persetujuan'	=> post('value')
			],'id',$check_curent_persetujuan->id);
		}

		if(post('value') == 9) {
			update_data('tbl_pengajuan',[
				'approve'				=> 9,
				'status'				=> 0,
				'id_user_persetujuan'	=> '',
				'nama_persetujuan' 		=> '',
				'alasan_ditolak'		=> post('alasan'),
				'status_desc'			=> 'Persetujuan Pengadaan (Ditolak : '.user('nama').')'
			],'id',post('id'));

			$_arr = [
				'status'		=> 9,
				'status_proses'	=> 9
			];

			update_data('tbl_m_hps',$_arr,'nomor_pengajuan',$pengajuan['nomor_pengajuan']);
			update_data('tbl_inisiasi_pengadaan',$_arr,'nomor_pengajuan',$pengajuan['nomor_pengajuan']);
			update_data('tbl_rks',$_arr,'nomor_pengajuan',$pengajuan['nomor_pengajuan']);

			// kirim notifikasi ke creator dan panitia bahwa permintaan ditolak
			$usr 				= get_data('tbl_user','id',$pengajuan['id_user_creator'])->row();
			$id_user 			= $email_user = [];
			if(isset($usr->id)) {
				$id_user[]		= $usr->id;
				$email_user[]	= $usr->email;
			}
			$panitia 			= get_data('tbl_panitia_pelaksana a',[
				'select'		=> 'b.id,b.email',
				'join'			=> 'tbl_user b ON a.userid = b.id TYPE LEFT',
				'where'			=> 'a.nomor_pengajuan = "'.$pengajuan['nomor_pengajuan'].'"'
			])->result();
			foreach($panitia as $p) {
				$id_user[]		= $p->id;
				$email_user[]	= $p->email;				
			}
			if(count($id_user) > 0) {
				$link				= base_url().'pengadaan/monitor_pengadaan';
				$desctiption 		= 'Pengajuan pengadaan dengan no. <strong>'.$pengajuan['nomor_pengajuan'].'</strong> telah ditolak oleh '.user('nama');
				foreach($id_user as $iu) {
					$data_notifikasi 	= [
						'title'			=> 'Persetujuan Pengadaan',
						'description'	=> $desctiption,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'danger',
						'notif_icon'	=> 'fa-times',
						'id_user'		=> $iu,
						'transaksi'		=> 'approval_pengadaan',
						'id_transaksi'	=> post('id')
					];
					insert_data('tbl_notifikasi',$data_notifikasi);
				}

				if(setting('email_notification') && count($email_user) > 0) {
					send_mail([
						'subject'		=> 'Persetujuan Pengadaan #'.$pengajuan['nomor_pengajuan'],
						'bcc'			=> $email_user,
						'nama_user'		=> '',
						'description'	=> $desctiption.', dengan alasan '.post('alasan'),
						'url'			=> $link
					]);
				}
			}
		} else if(post('value') == 8) {
			update_data('tbl_pengajuan',[
				'approve'				=> 8,
				'status'				=> 0,
				'is_pos_approve'		=> 0,
				'id_user_persetujuan'	=> '',
				'nama_persetujuan' 		=> '',
				'alasan_ditolak'		=> post('alasan'),
				'status_desc'			=> 'Persetujuan Pengadaan (Dikembalikan oleh '.user('nama').')'
			],'id',post('id'));

			$_arr = [
				'status'		=> 8,
				'status_proses'	=> 8
			];

			update_data('tbl_m_hps',$_arr,'nomor_pengajuan',$pengajuan['nomor_pengajuan']);
			update_data('tbl_inisiasi_pengadaan',$_arr,'nomor_pengajuan',$pengajuan['nomor_pengajuan']);
			update_data('tbl_rks',$_arr,'nomor_pengajuan',$pengajuan['nomor_pengajuan']);

			// kirim notifikasi ke panitia bahwa permintaan dikembalikan
			$id_user 			= $email_user = [];
			$panitia 			= get_data('tbl_panitia_pelaksana a',[
				'select'		=> 'b.id,b.email',
				'join'			=> 'tbl_user b ON a.userid = b.id TYPE LEFT',
				'where'			=> 'a.nomor_pengajuan = "'.$pengajuan['nomor_pengajuan'].'"'
			])->result();
			foreach($panitia as $p) {
				$id_user[]		= $p->id;
				$email_user[]	= $p->email;				
			}
			if(count($id_user) > 0) {
				$link				= base_url().'pengadaan/monitor_pengadaan';
				$desctiption 		= 'Pengajuan pengadaan dengan no. <strong>'.$pengajuan['nomor_pengajuan'].'</strong> dikembalikan oleh '.user('nama').' untuk dilakukan revisi';
				foreach($id_user as $iu) {
					$data_notifikasi 	= [
						'title'			=> 'Persetujuan Pengadaan',
						'description'	=> $desctiption,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'warning',
						'notif_icon'	=> 'fa-share',
						'id_user'		=> $iu,
						'transaksi'		=> 'approval_pengadaan',
						'id_transaksi'	=> post('id')
					];
					insert_data('tbl_notifikasi',$data_notifikasi);
				}

				if(setting('email_notification') && count($email_user) > 0) {
					send_mail([
						'subject'		=> 'Persetujuan Pengadaan #'.$pengajuan['nomor_pengajuan'],
						'bcc'			=> $email_user,
						'nama_user'		=> '',
						'description'	=> $desctiption.', dengan alasan '.post('alasan'),
						'url'			=> $link
					]);
				}
			}
		} else {
			$check_next_persetujuan  = get_data('tbl_alur_persetujuan',[
				'where'	=> [
					'id_pengajuan'			=> post('id'),
					'status_persetujuan' 	=> '0',
					'jenis_approval'		=> 'PENGADAAN'
				],
				'sort_by'=>'level_persetujuan','sort'=>'ASC'    
			])->row();

			if(!isset($check_next_persetujuan->id_user)){ 
				
				// ambil data inisiasi pengadaan, untuk mendapatkan id_panitia dan jenis pengadaannya "lelang / non lelang"
				$ip 	= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$pengajuan['nomor_pengajuan'])->row_array();

				// copy data di tbl_pengajuan ke tbl_pengadaan 
				$dt_pengadaan = [
					'id_divisi' 			=> $pengajuan['id_divisi'],
					'kode_divisi' 			=> $pengajuan['kode_divisi'],
					'nama_divisi' 			=> $pengajuan['nama_divisi'],
					'nama_pengadaan' 		=> $pengajuan['nama_pengadaan'],
					'nomor_pengajuan' 		=> $pengajuan['nomor_pengajuan'],
					'pemberi_tugas' 		=> $pengajuan['pemberi_tugas'],
					'id_mata_anggaran' 		=> $pengajuan['id_mata_anggaran'],
					'mata_anggaran' 		=> $pengajuan['mata_anggaran'],
					'besar_anggaran' 		=> $pengajuan['besar_anggaran'],
					'no_hps' 				=> $pengajuan['no_hps'],
					'nomor_disposisi' 		=> $pengajuan['nomor_disposisi'],
					'nomor_delegasi' 		=> $pengajuan['nomor_delegasi'],
					'nomor_inisiasi' 		=> $pengajuan['nomor_inisiasi'],
					'nomor_rks' 			=> $pengajuan['nomor_rks'],
					'id_creator' 			=> $pengajuan['id_user_creator'],
					'id_unit_kerja2'		=> $pengajuan['id_unit_kerja2'],
					'tanggal_pengadaan' 	=> $ip['tanggal_pengadaan'],
					'hps' 					=> $ip['hps_panitia'],
					'id_panitia' 			=> $ip['id_panitia'],
					'id_metode_pengadaan' 	=> $ip['id_metode_pengadaan'],
					'metode_pengadaan' 		=> $ip['metode_pengadaan'],
					'tipe_pengadaan' 		=> $ip['tipe_pengadaan'],
					'id_unit_kerja'			=> $ip['id_unit_kerja'],
					'kode_unit_kerja'		=> $ip['kode_unit_kerja'],
					'unit_kerja'			=> $ip['unit_kerja'],
					'keterangan_pengadaan'	=> $ip['keterangan_pengadaan'],
					'id_identifikasi_pajak'	=> $ip['id_identifikasi_pajak'],
					'identifikasi_pajak'	=> $ip['identifikasi_pajak'],
					'id_kategori_rekanan'	=> $ip['id_kategori_rekanan'],
					'kategori_rekanan'		=> $ip['kategori_rekanan'],
					'id_bidang_usaha'		=> $ip['id_bidang_usaha'],
					'bidang_usaha'			=> $ip['bidang_usaha'],
					'status_pengadaan'		=> 'BIDDING'
				];

				$save_pengadaan 	= save_data('tbl_pengadaan',$dt_pengadaan,[],true);
				if($save_pengadaan['status'] == 'success') {
					$_arr = [
						'status'		=> 2,
						'status_proses'	=> 1
					];

					update_data('tbl_m_hps',$_arr,'nomor_pengajuan',$pengajuan['nomor_pengajuan']);
					update_data('tbl_inisiasi_pengadaan',$_arr,'nomor_pengajuan',$pengajuan['nomor_pengajuan']);
					update_data('tbl_rks',$_arr,'nomor_pengajuan',$pengajuan['nomor_pengajuan']);

					$pengadaan 		= get_data('tbl_pengadaan','id',$save_pengadaan['id'])->row_array();

					// simpan data pengadaan ke tbl_pengadaan_detail untuk memasukan id_kategori_rekanan ini untuk mempermudah query untuk list di dashboard vendor
					$dt_detail						= $dt_pengadaan;
					unset($dt_detail['id_kategori_rekanan']);
					unset($dt_detail['kategori_rekanan']);
					$dt_detail['nomor_pengadaan']	= $pengadaan['nomor_pengadaan'];
					$id_kategori_rekanan 			= json_decode($ip['id_kategori_rekanan'],true);
					foreach($id_kategori_rekanan as $ib) {
						$dt_detail['id_kategori_rekanan']	= $ib;
						save_data('tbl_pengadaan_detail',$dt_detail,[],true);
					}

					// kirim notifikasi ke creator pengaju pengadaan dan ke panitia yg melakukan proses (hanya kirim notifikasi tombol lonceng saja, karena di klo ngirim email 2x dalam 1 method, email yg ke 2nya tidak terkirim, antisipasi saja karena emailnya lebih baik untuk kirim notifikasi ke vendor saja)

					$panitia_pelaksana		= get_data('tbl_panitia_pelaksana','nomor_pengajuan',$pengajuan['nomor_pengajuan'])->result();

					$id_user 				= [$pengajuan['id_user_creator']];
					foreach($panitia_pelaksana as $p) {
						$id_user[] 			= $p->userid;
					}

					if(count($id_user) > 0) {
						$link				= base_url().'pengadaan/monitor_pengadaan';
						$desctiption 		= 'Pengajuan dengan no. <strong>'.$pengajuan['nomor_pengajuan'].'</strong> telah disetujui dengan no. pengadaan <strong>'.$pengadaan['nomor_pengadaan'].'</strong>.';
						foreach($id_user as $iu) {
							$data_notifikasi 	= [
								'title'			=> 'Persetujuan Pengadaan',
								'description'	=> $desctiption,
								'notif_link'	=> $link,
								'notif_date'	=> date('Y-m-d H:i:s'),
								'notif_type'	=> 'success',
								'notif_icon'	=> 'fa-check',
								'id_user'		=> $iu,
								'transaksi'		=> 'approval_pengadaan',
								'id_transaksi'	=> post('id')
							];
							insert_data('tbl_notifikasi',$data_notifikasi);
						}
					}

					if($ip['tipe_pengadaan'] == 'Lelang') {
						// jika metode pengadaannya dilakukan secara LELANG maka berikan notifikasi ke vendor yg sesuai dengan bidang usaha agar bisa secepatnya dilakukan bidding (dikhawatirkan vendor pegadaiaan mencapai ribuan, maka untuk mempercepat proses query dan kirim email maka notifikasi dilakukan secara acak sebanyak 30 vendor saja)

						$vendor 						= get_data('tbl_vendor_kategori',[
							'select'					=> 'id_user, email',
							'where'						=> [
								'id_kategori_rekanan'	=> $id_kategori_rekanan,
								'is_active'				=> 1,
								'status_drm'			=> 1
							],
							'group_by'					=> 'id_vendor',
							'sort_by'					=> 'id',
							'sort'						=> 'RANDOM',
							'limit'						=> 30
						])->result();
						$id_user_vendor	= $email_vendor = [];
						foreach($vendor as $uv) {
							$id_user_vendor[]	= $uv->id_user;
							$email_vendor[]		= $uv->email;
						}

						if(count($id_user_vendor) > 0) {
							$link				= base_url().'pengadaan_v/daftar_pengadaan_v/ref/'.encode_id([$pengadaan['id'],rand()]);
							$desctiption 		= 'Pengadaan dengan no. <strong>'.$pengadaan['nomor_pengadaan'].'</strong> sesuai dengan bidang usaha perusahaan anda.';
							foreach($id_user_vendor as $iu) {
								$data_notifikasi 	= [
									'title'			=> 'Pengadaan Baru',
									'description'	=> $desctiption,
									'notif_link'	=> $link,
									'notif_date'	=> date('Y-m-d H:i:s'),
									'notif_type'	=> 'info',
									'notif_icon'	=> 'fa-boxes',
									'id_user'		=> $iu,
									'transaksi'		=> 'approval_pengadaan',
									'id_transaksi'	=> post('id')
								];
								insert_data('tbl_notifikasi',$data_notifikasi);
							}

							if(setting('email_notification') && count($email_vendor) > 0) {
								send_mail([
									'subject'		=> 'Pengadaan #'.$pengadaan['nomor_pengadaan'],
									'bcc'			=> $email_vendor,
									'nama_user'		=> '',
									'description'	=> $desctiption.'. Jika anda berminat silahkan konfimasi melalui sistem '.setting('title').' '.setting('company'),
									'url'			=> $link
								]);
							}
						}
					} else {
						// jika NON LELANG simpan data pengadaan ke tbl_pengadaan_bidder untuk mempermudah query untuk list di dashboard vendor
						$dt_bidder 						= $dt_pengadaan;
						$dt_bidder['nomor_pengadaan']	= $pengadaan['nomor_pengadaan'];
						$dt_bidder['is_invite']			= 1;
						$id_vendor 						= json_decode($ip['id_vendor'],true);
						$nama_vendor					= explode(', ', $ip['vendor']);
						foreach($id_vendor as $k => $v) {
							$_vendor 					= get_data('tbl_vendor','id',$v)->row();
							$dt_bidder['id_vendor']		= $v;
							$dt_bidder['nama_vendor']	= isset($_vendor->nama) ? $_vendor->nama : '';
							save_data('tbl_pengadaan_bidder',$dt_bidder,[],true);
						}

						// jika NON LELANG maka berikan notifikasi ke vendor yg di undang saja
						$usr_vendor		= get_data('tbl_user','id_vendor',$id_vendor)->result();
						$id_user_vendor	= $email_vendor = [];
						foreach($usr_vendor as $uv) {
							$id_user_vendor[]	= $uv->id;
							$email_vendor[]		= $uv->email;
						}

						if(count($id_user_vendor) > 0) {
							$link				= base_url().'pengadaan_v/undangan_pengadaan/ref/'.encode_id([$pengadaan['id'],rand()]);
							$desctiption 		= 'Anda diundang untuk mengikuti tahapan pengadaan dengan no. <strong>'.$pengadaan['nomor_pengadaan'].'</strong>';
							foreach($id_user_vendor as $iu) {
								$data_notifikasi 	= [
									'title'			=> 'Undangan Pengadaan',
									'description'	=> $desctiption,
									'notif_link'	=> $link,
									'notif_date'	=> date('Y-m-d H:i:s'),
									'notif_type'	=> 'info',
									'notif_icon'	=> 'fa-envelope',
									'id_user'		=> $iu,
									'transaksi'		=> 'approval_pengadaan',
									'id_transaksi'	=> post('id')
								];
								insert_data('tbl_notifikasi',$data_notifikasi);
							}

							if(setting('email_notification') && count($email_vendor) > 0) {
								send_mail([
									'subject'		=> 'Undangan Pengadaan #'.$pengadaan['nomor_pengadaan'],
									'bcc'			=> $email_vendor,
									'nama_user'		=> '',
									'description'	=> $desctiption.'. Jika anda berminat silahkan konfimasi melalui sistem '.setting('title').' '.setting('company'),
									'url'			=> $link
								]);
							}
						}
					}
					update_data('tbl_pengajuan',[
						'approve'				=> post('value'),
						'nomor_pengadaan'		=> $pengadaan['nomor_pengadaan'],
						'id_user_persetujuan'	=> '',
						'nama_persetujuan' 		=> '',
						'status_desc'			=> 'Proses Pendaftaran Rekanan'
					],'id',post('id'));
				}
			}else{
				update_data('tbl_pengajuan',[
					'id_user_persetujuan'	=> $check_next_persetujuan->id_user,
					'nama_persetujuan' 		=> $check_next_persetujuan->nama_persetujuan,
					'status_desc'			=> 'Persetujuan Pengadaan (Menunggu : '.$check_next_persetujuan->nama_user.')'
				],'id',post('id'));

				// notifikasi untuk approval selanjutnya
				$usr 					= get_data('tbl_user','id',$check_next_persetujuan->id_user)->row();
				if(isset($usr->id)) {
					$link				= base_url().'pengadaan/approval_pengadaan?i='.encode_id([post('id'),rand()]);
					$desctiption 		= 'Pengajuan pengadaan dengan no. <strong>'.$pengajuan['nomor_pengajuan'].'</strong> membutuhkan persetujuan anda';
					$data_notifikasi 	= [
						'title'			=> 'Persetujuan Pengadaan',
						'description'	=> $desctiption,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'info',
						'notif_icon'	=> 'fa-file-alt',
						'id_user'		=> $usr->id,
						'transaksi'		=> 'approval_permintaan',
						'id_transaksi'	=> post('id')
					];
					insert_data('tbl_notifikasi',$data_notifikasi);

					if(setting('email_notification') && $usr->email) {
						send_mail([
							'subject'		=> 'Persetujuan Pengadaan #'.$pengajuan['nomor_pengajuan'],
							'to'			=> $usr->email,
							'nama_user'		=> $usr->nama,
							'description'	=> $desctiption,
							'url'			=> $link
						]);
					}
				}
			}
		}
		echo lang('data_berhasil_disimpan');
	}
}