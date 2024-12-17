<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekaf_nilai_rekanan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['kanwil']	= get_data('tbl_m_unit','is_active',1)->result_array();
		   for($i=1;$i <= 12;$i++) {
		   		$data['bulan'][] = $i;
		   }

	//	   $data['periode'] = [];


//	debug($data);die;   
		render($data);
	}

	function view() {
		$tanggal 		= post('::periode');

		$unit			= post('id_unit_daftar');

		$where 				= [
			'is_active ' 	=> 1,
		];

		if($unit != 'all') $where['id']	= $unit;
		$nm_kanwil 			= get_data('tbl_m_unit',[
			'where'		=> $where
		])->row();

		if($unit == 0){
			$nm_unit = 'Semua Kanwil' ;
		}else{
			$nm_unit = $nm_kanwil->unit;
		} 

		$status 		= [
			1 => 'Disarankan',
			9 => 'Tidak Disarankan',

		];


		if($unit) {

			$tanggal1 = date('Y-m-d',strtotime($tanggal[0]));
			$tanggal2 = '2019-12-28';

			foreach($status as $k => $v) {

				$where1 				= [
					'a.hasil_rekomendasi'			=> $k,
					'a.tanggal '	=> $tanggal1,
				];


				if($unit != 'all') $where1['b.id_unit_daftar']	= $unit;
				$data['result'][$v]	= get_data('tbl_evaluasi_vendor a',[
					'select' => 'b.id,b.kode_rekanan,b.nama,a.nama_pengadaan,a.nilai_kontrak,a.keterangan_lain,a.tanggal',
					'join' => ['tbl_vendor b on a.id_vendor = b.id type LEFT',
							   'tbl_pengadaan c on a.nomor_pengadaan = c.nomor_pengadaan type LEFT'	
							  ],
					'where'	=> $where1
				])->result_array();


			}

		//	debug($data['jumlah']);die;

			if(post('tipe') == 'pdf') {
				ini_set('memory_limit', '-1');
				$data['nm_kanwil'] = $nm_unit;	
				$data['tanggal']	= $tanggal1;
				debug($data);die;
				render($data,'pdf:landscape');
			} elseif(post('tipe') == 'excel') {
				$overall 	= [];
				foreach($data['result'] as $k => $v) {
					$overall[] 	= [
						'Status Rekanan'	=> $k,
						'jumlah'			=> count($v)
					];
				}
				$config[]	= [
					'data'		=> $overall,
					'title'		=> 'Rekap Nilai Vendor Rekanan',
					'image'		=> 'assets/images/'.user('id').'_rekaf_nilai_rekanan.png'
				];
				foreach($data['result'] as $k => $v) {
					$config[]	= [
						'data'		=> $v,
						'title'		=> $k,
						'header'	=> [
							'nama'					=> 'Nama Vendor',
							'nama_pengadaan'		=> 'Nama Pengadaan',
							'nilai_kontrak'			=> 'Nilai Kontrak',
							'keterangan_lain'		=> 'Keterangan Untuk Vendor',
						],
					];
				}
				$this->load->library('simpleexcel',$config);
				$this->simpleexcel->header([
					'status'		=> post('status'),
				]);
				$this->simpleexcel->export();
			} else {
				render($data,'json');
			}
		} else {
			$response	= array(
	            'status'	=> 'failed',
	            'message'	=> 'Permission Denied',
	        );
			render($response,'json');
		}
	}

}