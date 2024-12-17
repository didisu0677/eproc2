<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checklist_rekanan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['dokumen'] 		= get_data('tbl_m_dokumen_rekanan','is_active',1)->result();
		$data['id']				= 0;
		if(get('i')) {
			$id 				= decode_id(get('i'));
			if(isset($id[0])) $data['id'] = $id[0];
		}
		render($data);
	}

	function data($status = 0) {
		$config				= [
			'access_view' 	=> false,
			'access_delete'	=> false,
			'access_edit'	=> false,
			'where'			=> [
				'is_pendaftar'			=> 1,
				'verifikasi_dokumen'	=> $status
			]
		];
		$config['button'][]	= button_serverside('btn-info','btn-detail',['fa-search',lang('detil'),true],'detail',['verifikasi_dokumen >'=>0]);
		if(menu()['access_additional']) {
			$config['button'][]	= button_serverside('btn-success','btn-input',['fa-check-square',lang('verifikasi'),true],'verifikasi',['kunjungan'=>0]);
		}
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data	= get_data('tbl_vendor','id',post('id'))->row_array();

		$data['file']		= get_data('tbl_upl_dokumenvendor','id_vendor',post('id'))->result();
		$data['cek']		= get_data('tbl_verifikasi_rekanan',[
			'where'			=> [
				'id_vendor'	=> post('id')
			]
		])->result_array();
		if(isset($data['cek'][0])) {
			$data['ket_hsl_verifikasi']	= $data['cek'][0]['ket_hsl_verifikasi'];
			$data['verifikasi_oleh']	= $data['cek'][0]['verifikasi_oleh'];
			$data['tanggal_verifikasi']	= $data['cek'][0]['tanggal_verifikasi'];
		}

		render($data,'json');
	}

	function save() {
		$id_dok 		= post('id_dok');
		$check 			= post('check');
		$keterangan 	= post('keterangan');
		$vendor 		= get_data('tbl_vendor','id',post('id_vendor'))->row();
		$user 			= get_data('tbl_user','id_vendor',$vendor->id)->row();
		
		$status 		= 1;
		$post 			= post();

		$data 			= [];
		$i 				= 0;
		foreach($id_dok as $k => $v) {
			if($v) {
				$dokumen	= get_data('tbl_m_dokumen_rekanan',[
					'where'		=> [
						'id' => $v
					]
				])->row();

				$data[$i] 	= [
					'id_vendor'				=> post('id_vendor'),
					'nama_vendor'			=> isset($vendor->nama) ? $vendor->nama : '',
					'alamat_vendor'			=> post('alamat'),
					'id_dokumen'			=> $v,
					'kode_dokumen'			=> $dokumen->kode_dokumen,
					'nama_dokumen'			=> $dokumen->nama_dokumen,
					'status_dokumen'		=> $dokumen->status_dokumen,
					'verifikasi'			=> isset($check[$v]) ? 1 : 0,
					'keterangan_tambahan'	=> $keterangan[$v],
					'tanggal_verifikasi' 	=> $post['tanggal_verifikasi'],
					'ket_hsl_verifikasi' 	=> post('ket_hsl_verifikasi'),
					'verifikasi_oleh'		=> post('verifikasi_oleh'),
				];

				if($data[$i]['status_dokumen'] == 'Mandatory' && $data[$i]['verifikasi'] == 0) {
					$status = 9;
				}
				$i++;
			}
		}


		if(count($data)) {
			delete_data('tbl_verifikasi_rekanan',['id_vendor'=>post('id_vendor')]);
			update_data('tbl_vendor',['verifikasi_dokumen'=>$status,'tanggal_verifikasi'=>$post['tanggal_verifikasi']],'id',post('id_vendor'));
			insert_batch('tbl_verifikasi_rekanan',$data);

			$link               = base_url('account/dokumen/');
			$desctiption        = $status == 9 ? 'Dokumen tidak memenuhi persyaratan' : 'Dokumen memenuhi persyarat';
			$data_notifikasi    = [
				'title'         => 'Verifikasi dan Checklist',
				'description'   => $desctiption,
				'notif_link'    => $link,
				'notif_date'    => date('Y-m-d H:i:s'),
				'notif_type'    => $status == 9 ? 'danger' : 'success',
				'notif_icon'    => 'fa-check',
				'id_user'       => $user->id,
				'transaksi'     => 'checklist_verifikasi',
				'id_transaksi'  => $vendor->id
			];
			insert_data('tbl_notifikasi',$data_notifikasi);

			$email_notification = [];
			if($vendor->email) $email_notification[$vendor->email] = $vendor->email;
			if($vendor->email_cp) $email_notification[$vendor->email_cp] = $vendor->email_cp;
			if(setting('email_notification') && count($email_notification) ) {
				send_mail([
					'subject'		=> 'Verifikasi dan Checklist',
					'to'			=> $email_notification,
					'nama'			=> $vendor->nama,
					'description'	=> $desctiption,
					'url'			=> $link,
					'status'		=> $status
				]);
			}
		}

		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function detail($id='') {
		$data				= get_data('tbl_vendor','id',$id)->row_array();
		$data['dokumen'] 	= get_data('tbl_m_dokumen_rekanan','is_active',1)->result();
		$data['keterangan']	= $data['verifikasi_oleh'] = $data['tanggal_verifikasi'] = '';

		foreach($data['dokumen'] as $k => $v) {
			$file			= get_data('tbl_upl_dokumenvendor','id_vendor = '.$id.' AND id_dokumen = '.$v->id)->row();
			$cek			= get_data('tbl_verifikasi_rekanan','id_vendor = '.$id.' AND id_dokumen = '.$v->id)->row();

			$data['dokumen'][$k]->file			= isset($file->file) ? $file->file : '';
			$data['dokumen'][$k]->verifikasi	= isset($cek->verifikasi) ? $cek->verifikasi : '';
			$data['dokumen'][$k]->keterangan	= isset($cek->verifikasi) ? $cek->keterangan_tambahan : '';
			if(isset($cek->id) && !$data['keterangan']) {
				$data['keterangan']			= $cek->ket_hsl_verifikasi;
				$data['verifikasi_oleh']	= $cek->verifikasi_oleh;
				$data['tanggal_verifikasi']	= c_date($cek->tanggal_verifikasi);
			}
		}
		render($data,'layout:false');
	}

}
