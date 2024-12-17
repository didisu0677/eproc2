<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_pemenang extends BE_Controller {

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
		$access 			= get_access('persetujuan_pemenang');
		$config['where'] 	= [
			'posisi_persetujuan' 	=> user('id'),
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
		$data 	= get_data('tbl_pemenang_pengadaan','id',post('id'))->row_array();
		render($data,'json');
	}
	
	function detail($id=0) {
		$nomor_pengadaan 	= get('nomor_pengadaan');
		$data				= get_data('tbl_pemenang_pengadaan','id = "'.$id.'" OR nomor_pengadaan = "'.$nomor_pengadaan.'"')->row_array();
		if(isset($data['id'])) {
			$data['klarifikasi']	= get_data('tbl_klarifikasi','nomor_pengadaan',$data['nomor_pengadaan'])->row_array(); 
			render($data,'layout:false access:true');
		} else render(lang('tidak_ada_data'));
	}

	function save_persetujuan() {
		$pemenang 					= get_data('tbl_pemenang_pengadaan','id',post('id'))->row_array();
		$awz 						= get_data('tbl_aanwijzing','nomor_pengadaan',$pemenang['nomor_pengadaan'])->row_array();
		$id_panitia 				= isset($awz['id']) ? $awz['id_panitia'] : 0;
		$panitia 					= get_data('tbl_panitia_pelaksana a',[
			'join'					=> 'tbl_user b ON a.userid = b.id TYPE LEFT',
			'select'				=> 'a.userid,b.email',
			'where'					=> [
				'id_m_panitia'		=> $id_panitia,
				'nomor_pengajuan'	=> $pemenang['nomor_pengajuan']
			]
		])->result();
		$id_user 					= $email_user = [];
		foreach($panitia as $p) {
			$id_user[] 				= $p->userid;
			$email_user 			= $p->email;
		}
		$last_update 				= date('Y-m-d H:i:s');
		$check_curent_persetujuan	= get_data('tbl_alur_persetujuan',[
			'where'	=> [
				'nomor_pengadaan'	=> $pemenang['nomor_pengadaan'],
				'id_user' 			=> user('id'),
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

		if(post('value') == 1) {
			$check_next_persetujuan  = get_data('tbl_alur_persetujuan',[
				'where'	=> [
					'nomor_pengadaan'		=> $pemenang['nomor_pengadaan'],
					'status_persetujuan' 	=> '0',
					'jenis_approval'		=> 'PEMENANG'
				],
				'sort_by'=>'level_persetujuan','sort'=>'ASC'
			])->row();

			if(isset($check_next_persetujuan->id_user)){ 
				update_data('tbl_pemenang_pengadaan',[
					'posisi_persetujuan'	=> $check_next_persetujuan->id_user,
					'nama_persetujuan' 		=> $check_next_persetujuan->nama_persetujuan,
					'tanggal_persetujuan'	=> date('Y-m-d H:i:s')
				],'id',post('id'));

				update_data('tbl_pengajuan',[
					'status_desc'			=> 'Persetujuan Pemenang (Menunggu : '.$check_next_persetujuan->nama_user.')'
				],'nomor_pengajuan',$pemenang['nomor_pengajuan']);

				// notifikasi untuk approval selanjutnya				
				$usr 					= get_data('tbl_user','id',$check_next_persetujuan->id_user)->row();
				if(isset($usr->id)) {
					$link				= base_url().'pengadaan/persetujuan_pemenang?i='.encode_id([$pemenang['id'],rand()]);
					$description 		= 'Penetapan pemenang pengadaan dengan no. <strong>'.$pemenang['nomor_pengadaan'].'</strong> membutuhkan persetujuan anda';
					$data_notifikasi 	= [
						'title'			=> 'Penetapan Pemenang',
						'description'	=> $description,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'info',
						'notif_icon'	=> 'fa-check-circle',
						'id_user'		=> $usr->id,
						'transaksi'		=> 'persetujuan_pemenang',
						'id_transaksi'	=> $pemenang['id']
					];
					insert_data('tbl_notifikasi',$data_notifikasi);

					if(setting('email_notification') && $usr->email) {
						send_mail([
							'subject'		=> 'Penetapan Pemenang #'.$pemenang['nomor_pengadaan'],
							'to'			=> $usr->email,
							'nama_user'		=> $usr->nama,
							'description'	=> $description,
							'url'			=> $link
						]);
					}
				}				
			}else{
				update_data('tbl_pemenang_pengadaan',[
					'approve'				=> post('value'),
					'posisi_persetujuan'	=> '',
					'nama_persetujuan' 		=> '',
					'tanggal_persetujuan'	=> date('Y-m-d H:i:s'),
					'nomor_persetujuan'		=> generate_code('tbl_pemenang_pengadaan','nomor_persetujuan',[])
				],'id',post('id'));

				if($awz['tipe_pengadaan'] == 'Lelang') {
					update_data('tbl_pengajuan',[
						'status_desc'			=> 'Proses Masa Sanggah'
					],'nomor_pengajuan',$pemenang['nomor_pengajuan']);
				} else {
					update_data('tbl_pengajuan',[
						'status_desc'			=> 'Penunjukan Pemenang'
					],'nomor_pengajuan',$pemenang['nomor_pengajuan']);
				}

				if(count($id_user)) {
					$link				= base_url().'pengadaan/penetapan_pemenang/detail/'.encode_id([$pemenang['id'],rand()]);
					$description 		= 'Penetapan pemenang pengadaan dengan no. <strong>'.$pemenang['nomor_pengadaan'].'</strong> telah disetujui';
					foreach($id_user as $iu) {
						$data_notifikasi 	= [
							'title'			=> 'Penetapan Pemenang',
							'description'	=> $description,
							'notif_link'	=> $link,
							'notif_date'	=> date('Y-m-d H:i:s'),
							'notif_type'	=> 'success',
							'notif_icon'	=> 'fa-check-circle',
							'id_user'		=> $iu,
							'transaksi'		=> 'persetujuan_pemenang',
							'id_transaksi'	=> $pemenang['id']
						];
						insert_data('tbl_notifikasi',$data_notifikasi);
					}

					if(setting('email_notification') && $email_user) {
						send_mail([
							'subject'		=> 'Penetapan Pemenang #'.$pemenang['nomor_pengadaan'],
							'to'			=> $email_user,
							'nama_user'		=> '',
							'description'	=> $description,
							'url'			=> $link
						]);
					}
				}
			}
		} else {
			update_data('tbl_pemenang_pengadaan',[
				'alasan_ditolak'		=> post('alasan'),
				'approve'				=> post('value'),
				'posisi_persetujuan'	=> '',
				'nama_persetujuan' 		=> '',
			],'id',post('id'));

			update_data('tbl_pengajuan',[
				'status_desc'			=> 'Persetujuan Pemenang (Ditolak : '.user('nama').')'
			],'nomor_pengajuan',$pemenang['nomor_pengajuan']);

			$pengajuan 	= get_data('tbl_pengajuan','nomor_pengajuan',$pemenang['nomor_pengajuan'])->row();
			if(isset($pengajuan->id)) {
				update_data('sap_detail',['is_deleted'=>'1','deleted_date'=>date('Y-m-d H:i:s')],'purchase_req_item',$pengajuan->purchase_req_item);
			}

			if(count($id_user)) {
				$link				= base_url().'pengadaan/penetapan_pemenang/detail/'.encode_id([$pemenang['id'],rand()]);
				$description 		= 'Penetapan pemenang pengadaan dengan no. <strong>'.$pemenang['nomor_pengadaan'].'</strong> ditolak oleh '.user('nama');
				foreach($id_user as $iu) {
					$data_notifikasi 	= [
						'title'			=> 'Penetapan Pemenang',
						'description'	=> $description,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'danger',
						'notif_icon'	=> 'fa-times-circle',
						'id_user'		=> $iu,
						'transaksi'		=> 'persetujuan_pemenang',
						'id_transaksi'	=> $pemenang['id']
					];
					insert_data('tbl_notifikasi',$data_notifikasi);
				}

				if(setting('email_notification') && $email_user) {
					send_mail([
						'subject'		=> 'Penetapan Pemenang #'.$pemenang['nomor_pengadaan'],
						'to'			=> $email_user,
						'nama_user'		=> '',
						'description'	=> $description.'. Dengan alasan '.post('alasan'),
						'url'			=> $link
					]);
				}
			}

		}
		echo lang('data_berhasil_disimpan');
	}
}