<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Approval_permintaan extends BE_Controller {

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
			'status'				=> 1,
			'id_user_persetujuan' 	=> user('id'),
			'approve_user'			=> 0
		];
		$config['button'][]		= button_serverside('btn-success','btn-print',['fa-print',lang('cetak_tor'),true]);
		if($access['access_additional']) {
			$config['button'][]	= button_serverside('btn-info','btn-act-view',['fa-check-circle',lang('persetujuan'),true],'act-detail',['approve_user'=>0]);
		}
		$config['access_edit'] 		= false;
		$config['access_delete'] 	= false;
		$config['access_view'] 		= false;
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_pengajuan','id',post('id'))->row_array();
		render($data,'json');
	}

	function save_persetujuan() {
		$pengajuan 					= get_data('tbl_pengajuan','id',post('id'))->row_array();
		$last_update 				= date('Y-m-d H:i:s');
		$check_curent_persetujuan	= get_data('tbl_alur_persetujuan',[
			'where'	=> [
				'id_pengajuan'		=> post('id'),
				'id_user' 			=> user('id'),
				'jenis_approval' 	=> 'PERMINTAAN'
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
				'approve_user'			=> 9,
				'status'				=> 0,
				'id_user_persetujuan'	=> '',
				'nama_persetujuan' 		=> '',
				'alasan_ditolak'		=> post('alasan'),
				'status_desc'			=> 'Persetujuan Pengajuan (Ditolak : '.user('nama').')'
			],'id',post('id'));

			// kirim notifikasi ke creator bahwa permintaan ditolak
			$usr 				= get_data('tbl_user','id',$pengajuan['id_user_creator'])->row();
			if(isset($usr->id)) {
				$link				= base_url().'pengadaan/pengajuan?i='.encode_id([post('id'),rand()]);
				$desctiption 		= 'Pengajuan pengadaan dengan no. <strong>'.$pengajuan['nomor_pengajuan'].'</strong> telah ditolak oleh '.user('nama');
				$data_notifikasi 	= [
					'title'			=> 'Pengajuan Pengadaan',
					'description'	=> $desctiption,
					'notif_link'	=> $link,
					'notif_date'	=> date('Y-m-d H:i:s'),
					'notif_type'	=> 'danger',
					'notif_icon'	=> 'fa-times',
					'id_user'		=> $usr->id,
					'transaksi'		=> 'approval_permintaan',
					'id_transaksi'	=> post('id')
				];
				insert_data('tbl_notifikasi',$data_notifikasi);

				if(setting('email_notification') && $usr->email) {
					send_mail([
						'subject'		=> 'Pengajuan Pengadaan #'.$pengajuan['nomor_pengajuan'],
						'to'			=> $usr->email,
						'nama_user'		=> $usr->nama,
						'description'	=> $desctiption.', dengan alasan '.post('alasan'),
						'url'			=> $link
					]);
				}
			}
		} elseif(post('value') == 8) {
			update_data('tbl_pengajuan',[
				'approve_user'			=> 8,
				'status'				=> 0,
				'id_user_persetujuan'	=> '',
				'nama_persetujuan' 		=> '',
				'alasan_ditolak'		=> post('alasan'),
				'status_desc'			=> 'Persetujuan Pengajuan (Dikembalikan oleh '.user('nama').')'
			],'id',post('id'));

			delete_data('tbl_alur_persetujuan','nomor_pengajuan',$pengajuan['nomor_pengajuan']);

			// kirim notifikasi ke creator bahwa permintaan ditolak
			$usr 				= get_data('tbl_user','id',$pengajuan['id_user_creator'])->row();
			if(isset($usr->id)) {
				$link				= base_url().'pengadaan/pengajuan?i='.encode_id([post('id'),rand()]);
				$desctiption 		= 'Pengajuan pengadaan dengan no. <strong>'.$pengajuan['nomor_pengajuan'].'</strong> dikembalikan oleh '.user('nama').' untuk dilakukan revisi';
				$data_notifikasi 	= [
					'title'			=> 'Pengajuan Pengadaan',
					'description'	=> $desctiption,
					'notif_link'	=> $link,
					'notif_date'	=> date('Y-m-d H:i:s'),
					'notif_type'	=> 'warning',
					'notif_icon'	=> 'fa-share',
					'id_user'		=> $usr->id,
					'transaksi'		=> 'approval_permintaan',
					'id_transaksi'	=> post('id')
				];
				insert_data('tbl_notifikasi',$data_notifikasi);

				if(setting('email_notification') && $usr->email) {
					send_mail([
						'subject'		=> 'Pengajuan Pengadaan #'.$pengajuan['nomor_pengajuan'],
						'to'			=> $usr->email,
						'nama_user'		=> $usr->nama,
						'description'	=> $desctiption.', dengan alasan '.post('alasan'),
						'url'			=> $link
					]);
				}
			}
		} else {
			$check_next_persetujuan_permintaan  = get_data('tbl_alur_persetujuan',[
				'where'	=> [
					'id_pengajuan'			=> post('id'),
					'status_persetujuan' 	=> '0',
					'jenis_approval'		=> 'PERMINTAAN'
				],
				'sort_by'=>'level_persetujuan','sort'=>'ASC'    
			])->row();

			if(!isset($check_next_persetujuan_permintaan->id_user)){ 
				// notifikasi untuk user yg memiliki akses disposisi
				$grup_approval = get_data('tbl_master_group_persetujuan',[
					'where'	=> [
						'kode_divisi'	=> $pengajuan['kode_divisi'],
						'id_unit_kerja'	=> $pengajuan['id_unit_kerja']
					]
				])->row_array();
				$id_unit_kerja2 		= $pengajuan['id_unit_kerja'];
				if(isset($grup_approval['id'])) {
					$check_unit 		= get_data('tbl_m_unit','id',$pengajuan['id_unit_kerja'])->row();
					$id_user_disposisi	= $grup_approval['id_user_disposisi'];
					if(isset($check_unit) && $check_unit->max_pengadaan < $pengajuan['usulan_hps']) {
						$id_unit_kerja2	= 1;
						$id_user_disposisi = $grup_approval['id_user_disposisi2'];
					}
					$usr 				= get_data('tbl_user','id',$id_user_disposisi)->row();
				}
				if(isset($usr->id)) {
					$link				= base_url().'pengadaan/disposisi';
					$desctiption 		= 'Pengajuan pengadaan dengan no. <strong>'.$pengajuan['nomor_pengajuan'].'</strong> telah disetujui dan perlu dilakukan disposisi';
					$data_notifikasi 	= [
						'title'			=> 'Disposisi Pengajuan Pengadaan',
						'description'	=> $desctiption,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'info',
						'notif_icon'	=> 'fa-exchange-alt',
						'id_user'		=> $usr->id,
						'transaksi'		=> 'approval_permintaan',
						'id_transaksi'	=> post('id')
					];
					insert_data('tbl_notifikasi',$data_notifikasi);	

					if(setting('email_notification') && $usr->email ) {
						send_mail([
							'subject'		=> 'Disposisi Pengajuan Pengadaan #'.$pengajuan['nomor_pengajuan'],
							'to'			=> $usr->email,
							'nama_user'		=> $usr->nama,
							'description'	=> $desctiption,
							'url'			=> $link
						]);
					}
				}
				$user_disposisi 			= isset($usr->id) ? $usr->nama : '';
				update_data('tbl_pengajuan',[
					'approve_user'			=> post('value'),
					'id_user_disposisi'		=> $usr->id,
					'id_user_persetujuan'	=> '',
					'id_unit_kerja2'		=> $id_unit_kerja2,
					'nama_persetujuan' 		=> '',
					'status_desc'			=> 'Disposisi (Menunggu : '.$user_disposisi.')'
				],'id',post('id'));
			}else{
				update_data('tbl_pengajuan',[
					'id_user_persetujuan'	=> $check_next_persetujuan_permintaan->id_user,
					'nama_persetujuan' 		=> $check_next_persetujuan_permintaan->nama_persetujuan,
					'status_desc'			=> 'Persetujuan Pengajuan (Menunggu : '.$check_next_persetujuan_permintaan->nama_user.')'
				],'id',post('id'));

				// notifikasi untuk approval selanjutnya
				$usr 				= get_data('tbl_user','id',$check_next_persetujuan_permintaan->id_user)->row();
				if(isset($usr->id)) {
					$link				= base_url().'pengadaan/approval_permintaan?i='.encode_id([post('id'),rand()]);
					$desctiption 		= 'Pengajuan pengadaan dengan no. <strong>'.$pengajuan['nomor_pengajuan'].'</strong> membutuhkan persetujuan anda';
					$data_notifikasi 	= [
						'title'			=> 'Pengajuan Pengadaan',
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
							'subject'		=> 'Pengajuan Pengadaan #'.$pengajuan['nomor_pengajuan'],
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