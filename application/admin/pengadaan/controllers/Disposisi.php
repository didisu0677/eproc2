<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Disposisi extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
	    $data['delegator'] = get_data('tbl_user',[
	        'where'=> [
	            'is_active'	=> 1,
	            'id_group'	=> id_group_access('delegasi')
	        ],
	        'sort_by'=>'nama','sort'=>'ASC'
	    ])->result_array();
	    
	    render($data);
	}

	function data() {
		$config	= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false
		];
		if(user('id_group') > 2) {
			$config['where']['id_user_disposisi']	= user('id');
		}
		$config['button'][]						= button_serverside('btn-info','btn-act-view',['fa-search',lang('detil'),true],'view');
	    if(menu()['access_edit']) {
	        $config['button'][]	= button_serverside('btn-warning','btn-input',['fa-edit',lang('ubah'),true],'edit',['status_delegasi'	=> 0]);
	    }
	    if(menu()['access_delete']) {
	        $config['button'][]	= button_serverside('btn-danger','btn-delete',['fa-trash-alt',lang('hapus'),true],'delete',['status_delegasi'	=> 0]);
	    }
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data 				= get_data('tbl_disposisi','id',post('id'))->row_array();
		$cb_nopengajuan		= get_data('tbl_pengajuan',[
		    'where'			=> [
		        'nomor_disposisi'	=> $data['nomor_disposisi'],
		    ],
		    'sort_by'=>'nomor_pengajuan','sort'=>'ASC'
		])->result();
		$data['nomor_pengajuan']    = '<option value=""></option>';
		foreach($cb_nopengajuan as $d) {
		    $data['nomor_pengajuan'] = '<option value="'.$d->nomor_pengajuan.'"
            data-nama_pengadaan="'.$d->nama_pengadaan.'"
            data-tanggal_pengadaan="'.c_date($d->tanggal_pengadaan).'"
            data-divisi="'.$d->nama_divisi.'"
            data-unit_kerja="'.$d->unit_kerja.'"
            data-unit="'.$d->id_unit_kerja2.'"
            data-mata_anggaran="'.$d->mata_anggaran.'"
            data-besar_anggaran="'.custom_format($d->besar_anggaran).'"
            data-usulan_hps="'.custom_format($d->usulan_hps).'"
            >'.$d->nomor_pengajuan.'  |  '.$d->nama_pengadaan.'</option>';
		}
		render($data,'json');
	}

	function save() {
		$data 						= post();
		$pengajuan 					= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
		$data['id_pengajuan']		= isset($pengajuan->id) ? $pengajuan->id : 0;
		$data['id_divisi']			= isset($pengajuan->id) ? $pengajuan->id_divisi : 0;
		$data['id_user_disposisi']	= isset($pengajuan->id) ? $pengajuan->id_user_disposisi : 0;
		$user 						= get_data('tbl_user','id',$data['id_user'])->row();
		if(isset($user->id)) {
			$data['kode_user']	= $user->kode;
			$data['nama_user']	= $user->nama;
		}
		$response 				= save_data('tbl_disposisi',$data,post(':validation'));
		if($response['status'] == 'success') {
		    $dt_disposisi 		= get_data('tbl_disposisi','id',$response['id'])->row();
		    update_data('tbl_pengajuan',[
		        'nomor_disposisi'	=> $dt_disposisi->nomor_disposisi,
		        'status_desc'		=> 'Delegasi Pengadaan (Menunggu : '.$user->nama.')'
		    ],['nomor_pengajuan'	=> $dt_disposisi->nomor_pengajuan]);
			
			// kasih notifikasi ke delegator
			$usr 				= $user;
			if(isset($usr->id)) {
				$link				= base_url().'pengadaan/delegasi';
				$desctiption 		= 'Anda ditunjuk menjadi delegator untuk pengajuan pengadaan dengan no. <strong>'.$pengajuan->nomor_pengajuan.'</strong>';
				$data_notifikasi 	= [
					'title'			=> 'Delegasi Pengadaan',
					'description'	=> $desctiption,
					'notif_link'	=> $link,
					'notif_date'	=> date('Y-m-d H:i:s'),
					'notif_type'	=> 'info',
					'notif_icon'	=> 'fa-sync',
					'id_user'		=> $usr->id,
					'transaksi'		=> 'disposisi',
					'id_transaksi'	=> $response['id']
				];
				insert_data('tbl_notifikasi',$data_notifikasi);

				if(setting('email_notification') && $usr->email) {
					send_mail([
						'subject'		=> 'Delegasi Pengadaan #'.$pengajuan->nomor_pengajuan,
						'to'			=> $usr->email,
						'nama_user'		=> $usr->nama,
						'description'	=> $desctiption,
						'url'			=> $link
					]);
				}
			}
		}
		render($response,'json');
	}

	function delete() {
	    $dt_disposisi 			= get_data('tbl_disposisi','id',post('id'))->row();
	    $response 				= destroy_data('tbl_disposisi','id',post('id'));
		if($response['status'] == 'success') {
		    update_data('tbl_pengajuan',[
		        'nomor_disposisi'	=> '',
		    ],['nomor_pengajuan'=>$dt_disposisi->nomor_pengajuan]);
		}
		render($response,'json');
	}

	function get_combo(){
	    $cb_nopengajuan  = get_data('tbl_pengajuan',[
	        'where'	=> [
	            'nomor_disposisi'	=> '',
	            'approve_user'		=> 1,
	            'id_user_disposisi'	=> user('id')
	        ],
	        'sort_by'=>'nomor_pengajuan','sort'=>'ASC'
		])->result();
	    $data['nomor_pengajuan']    = '<option value=""></option>';
	    foreach($cb_nopengajuan as $d) {
	        $data['nomor_pengajuan'] .= '<option value="'.$d->nomor_pengajuan.'"
            data-nama_pengadaan="'.$d->nama_pengadaan.'"
            data-tanggal_pengadaan="'.c_date($d->tanggal_pengadaan).'"
            data-divisi="'.$d->nama_divisi.'"
            data-unit_kerja="'.$d->unit_kerja.'"
            data-unit="'.$d->id_unit_kerja2.'"
            data-mata_anggaran="'.$d->mata_anggaran.'"
            data-besar_anggaran="'.custom_format($d->besar_anggaran).'"
            data-usulan_hps="'.custom_format($d->usulan_hps).'"
            >'.$d->nomor_pengajuan.'  |  '.$d->nama_pengadaan.'</option>';
	    }
	    render($data,'json');
	}
	
	function detail($id=0) {
		$no_disposisi 	= get('no_disposisi');
		$data			= get_data('tbl_disposisi a',[
			'select'	=> 'a.tanggal_disposisi, a.nama_user AS delegator,a.catatan, b.*',
			'join'		=> 'tbl_pengajuan b ON a.id_pengajuan = b.id',
			'where'		=> 'a.id = "'.$id.'" OR a.nomor_disposisi = "'.$no_disposisi.'"'
		])->row_array();
		// debug($data);die;
		if(isset($data['id'])) {
			render($data,'layout:false access:true');
		} else render(lang('tidak_ada_data'));
	}

}