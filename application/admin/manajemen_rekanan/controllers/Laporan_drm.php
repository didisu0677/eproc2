<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_drm extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['kanwil']	= get_data('tbl_m_unit','is_active',1)->result_array();

		render($data);
	}

	function view() {
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

		$where 				= [
				'is_active ' 	=> 1,
			];

		if($unit != 'all') $where['id']	= $unit;
		$dept 			= get_data('tbl_m_unit',[
			'where'		=> $where

		])->result();
		$kanwil 		= [];
		foreach($dept as $d) {
			$kanwil[$d->id]	= $d->unit;
		}


		if($unit) {
			$jumlah = 0;

			$z = 0;
			$zid = '';
			$data['jumlah'][] = 0;
			foreach($kanwil as $k => $v) {	
				$zid = $v;	
				$where 				= [
					'is_active ' 	=> 1,
					'id_unit_daftar' => $k
				];

				if($unit != 'all') $where['id_unit_daftar']	= $unit;

				$data['result'][$v]	= get_data('tbl_vendor',[
					'select' => '*',
					'where'	=> $where
				])->result_array();

				$z = 0;
				$data['jumlah'][$zid] = 0;
				foreach($data['result'][$v] as $n => $x) {
					if($x['id_unit_daftar'] == $k) {
						$z++;
						$data['jumlah'][$zid]	= $z;
					}
					$data['result'][$v][$n]['nama']	= $x['kode_rekanan'] .' - '. $x['nama'];
					$data['result'][$v][$n]['alamat']	= $x['alamat'] ;
					$data['result'][$v][$n]['jenis']	= $x['jenis_rekanan']==1 ? 'Badan Usaha' : 'Perorangan';
					$data['result'][$v][$n]['kategori']	= $x['kategori_rekanan'];

				}

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
					'image'		=> 'assets/images/'.user('id').'_laporan_drm.png'
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