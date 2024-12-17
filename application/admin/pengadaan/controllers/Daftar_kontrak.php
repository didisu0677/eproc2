<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_kontrak extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data($type='belum_kadaluarsa') {
		$config	= [];
		$access = menu();
		
		$config['join'][] = 'tbl_pengadaan ON tbl_kontrak.nomor_pengadaan = tbl_pengadaan.nomor_pengadaan TYPE LEFT';

		if($type == 'belum_kadaluarsa') {
		    $config['where']['tbl_kontrak.tanggal_selesai_kontrak >']	= date("Y-m-d");
		}else{
		    $config['where']['tbl_kontrak.tanggal_selesai_kontrak <=']	= date("Y-m-d");
		}

		if(user('is_kanwil')==1){
			$config['where']['tbl_pengadaan.id_unit_kerja']	= user('id_unit_kerja');
		}

		$config['where']['tbl_pengadaan.id_divisi']	= user('id_divisi');

		$data = data_serverside($config);
		render($data,'json');
	}

	function notifikasi_email() {
		$kadaluarsa = get_data('tbl_kontrak a', [
			'select' => 'a.* , b.email',
			'join' => 'tbl_vendor b on a.id_vendor =b.id type LEFT',
			'where' => [
				'tanggal_selesai_kontrak <=' =>  date("Y-m-d")
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
			'id_group'	=> id_group_access('daftar_kontrak')
			]
		])->result();

		$ccemail			= [];
		foreach($cc as $a) $ccemail[] = $a->email;


		$link				= base_url().'pengadaan/Daftar_kontrak';
		
		foreach($vendor as $m){
			$tbl_kontrak = get_data('tbl_kontrak a',[
				'where' => [
					'id_vendor' => $m->id,
					'tanggal_selesai_kontrak <=' => date("Y-m-d"),
				],
			])->result(); 	


			if($m->email_cp !='') {
				send_mail([
					'subject'				=> 'Pemberitahuan waktu kontrak kadaluarsa',
					'to'					=> $m->email_cp,
					'bcc'					=> $ccemail,
					'dokumen'				=> get_data('tbl_kontrak a',[
						'select' => 'a.*, b.nama as nama_perusahaan',
						'join'	 => 'tbl_vendor b on a.id_vendor = b.id',
						'where'	=> [
							'id_vendor'	=> $m->id,
							'tanggal_selesai_kontrak <='	=> date("Y-m-d")
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

	function detail($id=0) {
		$data			= get_data('tbl_kontrak a',[
			'select'	=> 'a.*, b.divisi,c.unit',
			'join'		=> ['tbl_m_divisi b ON a.id_divisi = b.id type LEFT',
							'tbl_m_unit c on a.id_unit_kerja = c.id type LEFT'
							],
			'where'		=> 'a.id = "'.$id.'"'
		])->row_array();

		if(isset($data['id'])) {
			render($data,'layout:false access:true');
		} else render(lang('tidak_ada_data'));
	}

	function export($filter='') {
		ini_set('memory_limit', '-1');
	
		if($filter == 'belum_kadaluarsa') {		
			$f = 'AKTIF';
			$where 				= [
		    	'a.tanggal_selesai_kontrak >'	=> date("Y-m-d"),
			];		
		}else{
			$where 				= [
		    	'a.tanggal_selesai_kontrak <='	=> date("Y-m-d"),
			];		
			$f = 'KADALUARSA';
		}

		if(user('is_kanwil')==1){
			$where 				= [
		    	'b.id_unit_kerja'	=>user('id_unit_kerja'),
			];		
		}

		$where 				= [
		   	'b.id_divisi'	=>user('id_divisi'),
		];		

		$arr = ['' => 'no','nomor_kontrak' => 'nomor_kontrak','nomor_spk' => 'nomor_spk','nomor_pengadaan' => 'nomor_pengadaan','nama_pengadaan' => 'nama_pengadaan','nilai_pengadaan' => 'nilai_pengadaan','nama_vendor' => 'nama_vendor','tanggal_mulai_kontrak' => 'tanggal_mulai_kontrak','tanggal_selesai_kontrak' => 'tanggal_selesai_kontrak'];
		
		

		$data	= get_data('tbl_kontrak a',[
			'select' => '"",a.nomor_kontrak,a.nomor_spk,a.nomor_pengadaan,a.nama_pengadaan,a.nilai_pengadaan,a.nama_vendor,a.tanggal_mulai_kontrak,a.tanggal_selesai_kontrak',
			'join' => 'tbl_pengadaan b on a.nomor_pengadaan = b.nomor_pengadaan type LEFT',
			'where'	=> $where,
		])->result_array();

		
		
		$config = [
			'title' => 'DAFTAR_KONTRAK ' .'_'. $f ,
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}