<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_broadcast extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['vendor']		= get_data('tbl_vendor',[
			'where'			=> [
				'status_drm'	=> 1,
				'is_active'		=> 1
			]
		])->result_array();
		render($data);
	}

	function data() {
		$data = data_serverside();
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_email_broadcast','id',post('id'))->row_array();
		render($data,'json');
	}

	function save() {
		$subjek 			= post('subjek');
		$id_vendor 			= post('id_user_penerima');
		$pesan 				= post('konten_html');

		$vendor 			= get_data('tbl_vendor','id',$id_vendor)->result();
		$email 				= $nama = [];
		foreach($vendor as $p) {
			if($p->email_cp) {
				$email[] 	= $p->email_cp;
			}
			$nama[] 		= '['.$p->nama.']';
		}

		$data 				= [
			'subjek'				=> $subjek,
			'konten_html'			=> $pesan,
			'konten'				=> strip_tags($pesan),
			'tanggal'				=> date('Y-m-d H:i:s'),
			'id_vendor'				=> json_encode($id_vendor),
			'email_vendor'			=> json_encode($email),
			'vendor'				=> implode(', ', $nama)
		];
		if(count($email)) {
			$kirim 	= send_mail(['to'=>'info@pegadaian.co.id','bcc'=>$email,'subject'=>$subjek,'pesan'=>$pesan]);
			if($kirim['status'] == 'success') {
				insert_data('tbl_email_broadcast',$data);
			}
			render($kirim,'json');
		} else {
			render([
				'status'	=> 'failed',
				'message'	=> 'Tidak ditemukan data email dari pengguna yang dipilih'
			],'json');
		}
	}

}