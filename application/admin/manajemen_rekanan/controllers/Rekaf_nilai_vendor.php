<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekaf_nilai_vendor1 extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['kanwil']	= get_data('tbl_m_unit','is_active',1)->result_array();

		render($data);
	}

	function view() {
		$unit			= post('id_unit_daftar');

		$status 		= [
			1 => 'Disarankan',
			9 => 'Tidak disarankan',

		];


		if($unit) {
			$jumlah = 0;


			foreach($status as $k => $v) {
				
				$where 				= [
					'b.hasil_rekomendasi'			=> $k,
				];

				if($unit != 'all') $where['a.id_unit_daftar']	= $unit;
				$data['result'][$v]	= get_data('tbl_vendor a',[
					'select' => 'a.* ',
					'join' => 'tbl_evaluasi_vendor b on a.id = b.id_vendor type LEFT',
					'where'	=> $where
				])->result_array();


			}


			

		//	debug($data['jumlah']);die;

			if(post('tipe') == 'pdf') {
				ini_set('memory_limit', '-1');
				$data['id_unit_daftar']		= $unit;
				$data['nm_kanwil'] = $nm_unit;

				render($data,'pdf:landscape');
			} elseif(post('tipe') == 'excel') {
				$overall 	= [];
				foreach($data['result'] as $k => $v) {
					$overall[] 	= [
						'Kantor Wilayah'	=> $k,
						'jumlah'			=> count($v)
					];
				}
				$config[]	= [
					'data'		=> $overall,
					'title'		=> 'Daftar Rekanan Mampu',
					'image'		=> 'assets/images/'.user('id').'_rekaf_nilai_vendor.png'
				];
				foreach($data['result'] as $k => $v) {
					$config[]	= [
						'data'		=> $v,
						'title'		=> $k,
						'header'	=> [
							'nama'					=> 'Nama Vendor',
							'alamat'				=> 'Alamat',
							'jenis'					=> 'Jenis',
							'kategori'				=> 'Kategori',
						],
					];
				}
				$this->load->library('simpleexcel',$config);
				$this->simpleexcel->header([
					'Kanwil'		=> $nm_unit,
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