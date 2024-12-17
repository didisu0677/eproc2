<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pemeriksaan_dokumen extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data($type='belum_kadaluarsa') {
		$config	= [];
		$access = menu();
		
		if($type == 'belum_kadaluarsa') {
		    $config['where']['tanggal_kadaluarsa >']	= date("Y-m-d");
		}else{
		    $config['where']['tanggal_kadaluarsa <=']	= date("Y-m-d");
		}
		
		if(user('is_kanwil')==1){
			$kanwil	= get_data('tbl_upl_dokumenvendor a',[
				'select' => 'a.id_vendor',
				'join' => 'tbl_vendor b on a.id_vendor=b.id',
				'where' => [
				'b.id_unit_daftar' => user('id_unit_kerja'),
			],
			])->result();
			
			$id_vendor			= [0];
			foreach($kanwil as $a) $id_vendor[] = $a->id_vendor;

			$config['where']['id_vendor']	= $id_vendor;
		}

		$data 	= data_serverside($config);
		render($data,'json');
	}

	function notifikasi_email() {
		$kadaluarsa = get_data('tbl_upl_dokumenvendor a', [
			'select' => 'a.* , b.email',
			'join' => 'tbl_vendor b on a.id_vendor =b.id type LEFT',
			'where' => [
				'a.tanggal_kadaluarsa <=' =>  date("Y-m-d")
			],
		])->result();

		$id_vendor			= [0];
		foreach($kadaluarsa as $a) $id_vendor[] = $a->id_vendor;

		$vendor = get_data('tbl_vendor', [
			'where' => [
				'id' => $id_vendor
			]
		])->result();


		$cc = get_data('tbl_user', [
			'where' => [
			'id_group'	=> id_group_access('Pemeriksaan_dokumen')
			]
		])->result();

		$ccemail			= [];
		foreach($cc as $a) $ccemail[] = $a->email;

		$ccemail = implode(',',$ccemail);


	//	debug($vendor);die;

		$link				= base_url().'account/dokumen';
		foreach($vendor as $m){
			$dokumen = get_data('tbl_upl_dokumenvendor a',[
				'where' => [
					'id_vendor' => $m->id,
					'tanggal_kadaluarsa <=' => date("Y-m-d"),
				],
			])->result(); 	


			if($m->email_cp !='') {
				send_mail([
					'subject'				=> 'Pemberitahuan dokumen kadaluarsa',
					'to'					=> $m->email_cp,
					'bcc'					=> $ccemail,
					'dokumen'				=> get_data('tbl_upl_dokumenvendor a',[
						'select' => 'a.*, b.nama as nama_perusahaan',
						'join'	 => 'tbl_vendor b on a.id_vendor = b.id',
						'where'	=> [
							'id_vendor'	=> $m->id,
							'tanggal_kadaluarsa <='	=> date("Y-m-d")
						]
					])->result_array(),
					'url'					=> $link
				]);
			}

		}
		
		render([
			'status'	=> 'success',
			'message'	=> 'Email notifikasi terikirim'
		],'json');

	}


	function get_data() {
		$data = get_data('tbl_upl_dokumenvendor','id',post('id'))->row_array();
		render($data,'json');
	}

	function detail($id=0) {
	    $data 				= get_data('tbl_upl_dokumenvendor a',[
	        'select'	=> 'a.id_vendor,a.kode_rekanan,a.kode_dokumen,a.tanggal_kadaluarsa,a.nama_dokumen, a.file,b.nama as nama_rekanan',
	        'join'		=> 'tbl_vendor b ON a.id_vendor = b.id TYPE LEFT',
	        'where'     => [
	            'a.id'=> $id,
	            ],
	    ])->row_array();
	    
	    render($data,'layout:false');
	}
}