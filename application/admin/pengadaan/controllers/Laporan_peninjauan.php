<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_peninjauan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data($status=0) {
		$config	= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false,
			'where'			=> [
				'id_user'	=> user('id')
			]
		];
		$config['button'][]	= button_serverside('btn-info',base_url('pengadaan/laporan_peninjauan/ref/'),['fa-search',lang('detil'),true],'btn-detail');
		if($status == 0) $config['where']['status_peninjauan']	= '0';
		else {
			$config['where']['status_peninjauan !=']	= '0';
			$config['sort_by']							= 'id';
			$config['sort']								= 'DESC';
		}
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function ref($encode_id='') {
		$id 	= decode_id($encode_id);
		if(count($id) == 2) {
			$dt 	= get_data('tbl_aanwijzing_peninjauan','id',$id[0])->row();
			if(isset($dt->id)) {
				redirect('pengadaan/laporan_peninjauan/detail/'.encode_id($dt->id_aanwijzing_vendor));
			} else render('404');
		} else render('404');
	}

	function detail($encode_id='') {
		$id 	= decode_id($encode_id);
		if(count($id) == 2) {
			$data 	= get_data('tbl_aanwijzing_vendor','id',$id[0])->row_array();
			if(isset($data['id'])) {
				$awz 			= get_data('tbl_aanwijzing','nomor_aanwijzing',$data['nomor_aanwijzing'])->row_array();
				$data['stat_pengadaan']	= $awz['stat_pengadaan'];
				$data['title']	= lang('detil');
				$data['tim']	= get_data('tbl_aanwijzing_peninjauan',[
					'where'	=> [
						'id_aanwijzing_vendor'	=> $id[0]
					],
					'sort_by'	=> 'posisi',
					'sort'		=> 'DESC'
				])->result();
				$data['template1']	= get_data('tbl_template_peninjauan',[
					'where'		=> 'grup = "aspek_peninjauan"',
					'sort_by'	=> 'id'
				])->result_array();
				$data['template2']	= get_data('tbl_template_peninjauan',[
					'where'		=> 'grup = "data_pendukung"',
					'sort_by'	=> 'id'
				])->result_array();
				render($data);
			} else render('404');
		} else render('404');		
	}

	function save() {
		$deskripsi 				= post('deskripsi');
		$nomor 					= post('nomor');
		$detil 					= post('detil');
		$kelengkapan			= post('kelengkapan');
		$keterangan				= post('keterangan');
		$deskripsi_lain 		= post('deskripsi_lain');
		$detil_lain 			= post('detil_lain');
		$kelengkapan_lain		= post('kelengkapan_lain');
		$keterangan_lain		= post('keterangan_lain');

		$deskripsi1				= post('deskripsi1');
		$kondisi				= post('kondisi');
		$keterangan1			= post('keterangan1');
		$deskripsi1_lain		= post('deskripsi1_lain');
		$kondisi_lain			= post('kondisi_lain');
		$keterangan1_lain		= post('keterangan1_lain');

		$data 					= post();
		$awz 					= get_data('tbl_aanwijzing_vendor','id',$data['id'])->row();
		if(isset($awz->id)) {
			$vendor 			= get_data('tbl_vendor','id',$awz->id_vendor)->row();
			$data['kota_peninjauan']	= $vendor->id ? trim(str_replace(['Kota','Kabupaten','Kab.'], '', $vendor->nama_kota)) : 'Jakarta';
		}

		$data_pendukung			= $hasil_peninjauan = [];
		foreach($deskripsi as $k => $v) {
			$data_pendukung[$k]	= [
				'deskripsi'		=> $deskripsi[$k],
				'detil'			=> '',
				'kelengkapan'	=> $kelengkapan[$k],
				'keterangan'	=> $keterangan[$k]
			];
			if(isset($nomor[$k])) {
				$data_pendukung[$k]['detil']	= 'Nomor '.$nomor[$k];
			} elseif(isset($detil[$k])) {
				$data_pendukung[$k]['detil']	= $detil[$k];
			}
		}
		$data_pendukung['lain']	= [];
		if(is_array($deskripsi_lain) && count($deskripsi_lain) > 0) {
			foreach($deskripsi_lain as $k => $v) {
				$data_pendukung['lain'][$k]	= [
					'deskripsi'		=> $deskripsi_lain[$k],
					'detil'			=> $detil_lain[$k],
					'kelengkapan'	=> $kelengkapan_lain[$k],
					'keterangan'	=> $keterangan_lain[$k]
				];
			}
		}

		foreach ($deskripsi1 as $k => $v) {
			$hasil_peninjauan[$k]	= [
				'deskripsi'		=> $deskripsi1[$k],
				'kondisi'		=> $kondisi[$k],
				'keterangan'	=> $keterangan1[$k]
			];
		}
		$hasil_peninjauan['lain']	= [];
		if(is_array($deskripsi1_lain) && count($deskripsi1_lain) > 0) {
			foreach($deskripsi1_lain as $k => $v) {
				$hasil_peninjauan['lain'][$k]	= [
					'deskripsi'		=> $deskripsi1_lain[$k],
					'kondisi'		=> $kondisi_lain[$k],
					'keterangan'	=> $keterangan1_lain[$k]
				];
			}
		}

		$data['data_pendukung']		= json_encode($data_pendukung);
		$data['hasil_peninjauan']	= json_encode($hasil_peninjauan);

		$response 		= save_data('tbl_aanwijzing_vendor',$data,[],true);
		if($response['status'] == 'success') {
			update_data('tbl_aanwijzing_peninjauan',['status_peninjauan'=>post('status_peninjauan')],'id_aanwijzing_vendor',post('id'));
		}
		render($response,'json');
	}

	function template_data_pendukung($encode_id='') {
		$id = decode_id($encode_id);
		$id = isset($id[0]) ? $id[0] : 0;
		$data['detil'] 	= get_data('tbl_aanwijzing_vendor','id',$id)->row_array();
		$ketua 			= get_data('tbl_aanwijzing_peninjauan',[
			'where'		=> [
				'posisi'				=> 'Ketua',
				'id_aanwijzing_vendor'	=> $id
			]
		])->row();
		$data['detil']['ketua']	= isset($ketua->nama_user) ? $ketua->nama_user : '';
		$data['template']	= get_data('tbl_template_peninjauan',[
			'where'		=> 'grup = "data_pendukung"',
			'sort_by'	=> 'id'
		])->result_array();
		render($data,'pdf');
	}

	function template_laporan_peninjauan($encode_id='') {
		$id = decode_id($encode_id);
		$id = isset($id[0]) ? $id[0] : 0;
		$data['detil'] 	= get_data('tbl_aanwijzing_vendor','id',$id)->row_array();
		$ketua 			= get_data('tbl_aanwijzing_peninjauan',[
			'where'		=> [
				'posisi'				=> 'Ketua',
				'id_aanwijzing_vendor'	=> $id
			]
		])->row();
		$data['detil']['ketua']	= isset($ketua->nama_user) ? $ketua->nama_user : '';
		$data['template']	= get_data('tbl_template_peninjauan',[
			'where'		=> 'grup = "aspek_peninjauan"',
			'sort_by'	=> 'id'
		])->result_array();
		render($data,'pdf');
	}

}