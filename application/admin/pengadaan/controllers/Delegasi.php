<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delegasi extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
	    $data['panitia'] = get_data('tbl_m_panitia_pengadaan','is_active=1')->result_array();
	    render($data);
	}

	function data() {
		$config	= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false
		];
		$config['button'][]		= button_serverside('btn-info','btn-act-view',['fa-search',lang('detil'),true],'view');
	    if(menu()['access_edit']) {
	        $config['button'][]	= button_serverside('btn-warning','btn-input',['fa-edit',lang('ubah'),true],'edit',['status_proses' => 0]);
	    }
	    if(menu()['access_delete']) {
	        $config['button'][]	= button_serverside('btn-danger','btn-delete',['fa-trash-alt',lang('hapus'),true],'delete',['status_proses' => 0]);
	    }
		if(user('id_group') > 2) {
			$config['where']['id_delegator']	= user('id');
		}
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_delegasi_pengadaan','id',post('id'))->row_array();
		$cb_nopengadaan  = get_data('tbl_pengajuan a',[
		    'select'	=> 'a.nomor_pengajuan,nama_pengadaan,tanggal_pengadaan,nama_divisi,a.mata_anggaran,a.besar_anggaran,a.usulan_hps,a.unit_kerja,a.id_unit_kerja2',
		    'join'		=> 'tbl_disposisi b ON a.nomor_disposisi = b.nomor_disposisi type LEFT',
		    'where'		=> 'a.nomor_delegasi ="'.$data['nomor_delegasi'].'" AND b.id_user = '.user('id').''
		])->result();

		$data['nomor_pengajuan1']    = '';
		foreach($cb_nopengadaan as $d) {
		    $data['nomor_pengajuan1'] .= '<option value="'.$d->nomor_pengajuan.'"
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
		$data						= post();
		$pengajuan 					= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row_array();
		$data['id_pengajuan']		= $pengajuan['id'];
		$data['nama_pengadaan']		= $pengajuan['nama_pengadaan'];
		$data['tanggal_pengadaan']	= $pengajuan['tanggal_pengadaan'];
		$data['mata_anggaran']		= $pengajuan['mata_anggaran'];
		$data['besar_anggaran']		= $pengajuan['besar_anggaran'];
		$data['usulan_hps']			= $pengajuan['usulan_hps'];
	    $last_update    			= date('Y-m-d H:i:s');
		$a							= [];
		$m_panitia 					= get_data('tbl_m_panitia_pengadaan','id',$data['id_m_panitia'])->row();
		$data['nama_panitia']		= $m_panitia->deskripsi;
	    $panitia 					= get_data('tbl_anggota_panitia',[
	    	'where' 	=> ['id_m_panitia'=>$data['id_m_panitia']],
	    	'sort_by'	=> 'id'
	    ])->result();
	    foreach($panitia as $ci) {
	        $a[]					= $ci->userid;
	    }
		$data['panitia_pelaksana']	= json_encode($a);
		$data['id_delegator']		= user('id');
		$response 					= [];
		if(count($a)) {
			$response 					= save_data('tbl_delegasi_pengadaan',$data,post(':validation'));
			if($response['status'] == 'success') {
				$dt_delegasi = get_data('tbl_delegasi_pengadaan','id',$response['id'])->row();
				update_data('tbl_pengajuan',[
					'nomor_delegasi'	=> $dt_delegasi->nomor_delegasi,
					'id_panitia'		=> $data['id_m_panitia'],
					'nama_panitia'		=> $data['nama_panitia'],
					'status_desc'		=> 'Inisiasi Pengadaan (Menunggu : '.$data['nama_panitia'].')'
				],['nomor_pengajuan'	=> $dt_delegasi->nomor_pengajuan]);

				update_data('tbl_disposisi',[
					'status_delegasi'	=> 1,
				],['nomor_disposisi'	=> $pengajuan['nomor_disposisi']]);

				delete_data('tbl_panitia_pelaksana','nomor_pengajuan',$data['nomor_pengajuan']);
				if($panitia){
					foreach($panitia as $ci) {
						$data_p = array(
							'nomor_pengajuan'	=> $data['nomor_pengajuan'],
							'nomor_delegasi'	=> $dt_delegasi->nomor_delegasi,
							'id_m_panitia'		=> $data['id_m_panitia'],
							'userid'			=> $ci->userid,
							'nama_panitia'		=> $ci->nama_panitia,
							'jabatan_panitia'	=> $ci->jabatan,
							'posisi_panitia'	=> $ci->posisi_panitia,
							'update_at'			=> $last_update,
							'update_by'			=> user('nama'),
						);
						insert_data('tbl_panitia_pelaksana',$data_p);
					}
				}
				
				// kirim notifikasi ke anggota panitia
				$user		= get_data('tbl_user','id',$a)->result();
				$user_id	= $user_email = [];
				foreach($user as $u) {
					$user_id[] 		= $u->id;
					$user_email[]	= $u->email;
				}
				if(count($user_id)) {
					$link				= base_url().'pengadaan';
					$desctiption 		= user('nama').' mendelegasikan pengadaan dengan no. pengajuan <strong>'.$pengajuan['nomor_pengajuan'].'</strong> kepada panitia '.$data['nama_panitia'].' yang dimana anda sebagai salah satu bagian dari panitia tersebut';
					foreach($user_id as $i) {
						$data_notifikasi 	= [
							'title'			=> 'Delegasi Pengadaan',
							'description'	=> $desctiption,
							'notif_link'	=> $link,
							'notif_date'	=> date('Y-m-d H:i:s'),
							'notif_type'	=> 'info',
							'notif_icon'	=> 'fa-exchange-alt',
							'id_user'		=> $i,
							'transaksi'		=> 'delegasi_pengadaan',
							'id_transaksi'	=> post('id')
						];
						insert_data('tbl_notifikasi',$data_notifikasi);	
					}

					if(setting('email_notification') && count($user_email) ) {
						send_mail([
							'subject'		=> 'Delegasi Pengajuan Pengadaan #'.$pengajuan['nomor_pengajuan'],
							'bcc'			=> $user_email,
							'nama_user'		=> '',
							'description'	=> $desctiption,
							'url'			=> $link
						]);
					}
				}
			}
		} else {
			$response = [
				'status'	=> 'failed',
				'message'	=> lang('panitia_ini_tidak_memiliki_anggota')
			];
		}
		render($response,'json');
	}

	function delete() {
	    $dt_delegasi 	= get_data('tbl_delegasi_pengadaan','id',post('id'))->row();
		$response 		= destroy_data('tbl_delegasi_pengadaan','nomor_delegasi',$dt_delegasi->nomor_delegasi,[
			'nomor_delegasi'	=> 'tbl_panitia_pelaksana'
		]);
		if($response['status'] == 'success') {
		    update_data('tbl_pengajuan',[
		        'nomor_delegasi'	=> '',
		    ],['id'=>$dt_delegasi->id_pengajuan]);
		}
		render($response,'json');
	}

	function get_combo(){
	    $cb_nopengadaan  			= get_data('tbl_pengajuan a',[
	        'select'				=> 'a.nomor_pengajuan,nama_pengadaan,tanggal_pengadaan,nama_divisi,a.mata_anggaran,a.besar_anggaran,a.usulan_hps,a.unit_kerja,a.id_unit_kerja2',
	        'join'					=> 'tbl_disposisi b ON a.nomor_disposisi = b.nomor_disposisi type LEFT',
	        'where'					=> 'a.nomor_delegasi = "" AND b.id_user = '.user('id')
	    ])->result();
	    $data['nomor_pengajuan']    = '<option value=""></option>';
	    foreach($cb_nopengadaan as $d) {
	        $data['nomor_pengajuan'].= '<option value="'.$d->nomor_pengajuan.'"  
            data-nama_pengadaan="'.$d->nama_pengadaan.'"
            data-tanggal_pengadaan="'.c_date($d->tanggal_pengadaan).'"
            data-unit_kerja="'.$d->unit_kerja.'"
            data-unit="'.$d->id_unit_kerja2.'"
            data-divisi="'.$d->nama_divisi.'"
            data-mata_anggaran="'.$d->mata_anggaran.'"
            data-besar_anggaran="'.custom_format($d->besar_anggaran).'"
            data-usulan_hps="'.custom_format($d->usulan_hps).'"
            >'.$d->nomor_pengajuan.'  |  '.$d->nama_pengadaan.'</option>';
	    }
	
	    render($data,'json');
	}
	
	function detail($id=0) {
		$no_delegasi 	= get('no_delegasi');
		$data			= get_data('tbl_delegasi_pengadaan a',[
			'select'	=> 'a.tanggal_delegasi, a.nama_panitia, b.*',
			'join'		=> 'tbl_pengajuan b ON a.id_pengajuan = b.id',
			'where'		=> 'a.id = "'.$id.'" OR a.nomor_delegasi = "'.$no_delegasi.'"'
		])->row_array();
		if(isset($data['id'])) {
			$data['anggota']	= get_data('tbl_panitia_pelaksana',[
				'where'		=> ['nomor_delegasi' => $data['nomor_delegasi']],
				'sort_by'	=> 'id'
			])->result_array();
			render($data,'layout:false access:true');
		} else render(lang('tidak_ada_data'));
	}
}