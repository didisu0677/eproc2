<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Swakelola extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$config = [
			'access_view'	=> false,
			'where'			=> ['tipe' => 'S'],
			'button'		=> button_serverside('btn-success','btn-print',['fa-print',lang('cetak'),false],'act-print')
		];
		if(user('is_kanwil')) {
			$config['where']['id_unit_kerja']	= user('id_unit_kerja');
		}
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_pengadaan_langsung','id',post('id'))->row_array();
		$data['header']	= get_data('tbl_pengadaan_langsung_header',[
			'where'		=> ['id_pengadaan_langsung'=>post('id')],
			'sort_by'	=> 'id'
		])->result_array();
		$data['detail']	= [];
		foreach($data['header'] as $h) {
			$data['detail'][$h['id']] = get_data('tbl_pengadaan_langsung_detail',[
				'where'		=> ['id_header'=>$h['id']],
				'sort_by'	=> 'id'
			])->result_array();
		}
		render($data,'json');
	}

	function save() {
		$nama_grup				= post('nama_grup');
		$nama_vendor			= post('nama_vendor');
		$alamat_vendor 			= post('alamat_vendor');
		$npwp_vendor			= post('npwp_vendor');

		$deskripsi				= post('deskripsi');
		$satuan					= post('satuan');
		$harga 					= post('harga');
		$jumlah 				= post('jumlah');

		$data 					= post();
		$data['tipe']			= 'S';
		$data['id_creator']		= user('id');
		$data['nama_creator']	= user('nama');
		$data['id_unit_kerja']	= user('id_unit_kerja');
		$unit_kerja 			= get_data('tbl_m_unit','id',$data['id_unit_kerja'])->row();
		if(isset($unit_kerja->id)) {
			$data['kode_unit_kerja']	= $unit_kerja->kode;
			$data['unit_kerja']			= $unit_kerja->unit;
		}
		$response 				= save_data('tbl_pengadaan_langsung',$data,post(':validation'));
		if($response['status'] == 'success') {
			delete_data('tbl_pengadaan_langsung_header','id_pengadaan_langsung',$response['id']);
			delete_data('tbl_pengadaan_langsung_detail','id_pengadaan_langsung',$response['id']);

			$total_all 			= 0;
			if(isset($nama_grup) && is_array($nama_grup)) {
				foreach($nama_grup as $k => $v) {
					$id_header = insert_data('tbl_pengadaan_langsung_header',[
						'id_pengadaan_langsung'	=> $response['id'],
						'nama_grup'				=> $nama_grup[$k],
						'nama_vendor'			=> $nama_vendor[$k],
						'alamat_vendor'			=> $alamat_vendor[$k],
						'npwp_vendor'			=> $npwp_vendor[$k]
					]);

					$total 		= 0;
					foreach($deskripsi[$k] as $x => $y) {
						$_total 	= $jumlah[$k][$x] * str_replace('.', '', $harga[$k][$x]);
						$total 		+= $_total;
						insert_data('tbl_pengadaan_langsung_detail',[
							'id_pengadaan_langsung'	=> $response['id'],
							'id_header'				=> $id_header,
							'deskripsi'				=> $deskripsi[$k][$x],
							'satuan'				=> $satuan[$k][$x],
							'harga'					=> str_replace('.', '', $harga[$k][$x]),
							'jumlah'				=> $jumlah[$k][$x],
							'total'					=> $_total
						]);
					}
					update_data('tbl_pengadaan_langsung_header',['total'=>$total],'id',$id_header);
					$total_all += $total;
				}
			}
			update_data('tbl_pengadaan_langsung',['total_pengadaan'=>$total_all],'id',$response['id']);
		}
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_pengadaan_langsung','id',post('id'),[
			'id_pengadaan_langsung'	=> [
				'tbl_pengadaan_langsung_header',
				'tbl_pengadaan_langsung_detail'
			]
		]);
		render($response,'json');
	}

	function print($ids='') {
		$decode_id = decode_id($ids);
		$id = isset($decode_id[1]) ? $decode_id[0] : 0;
		$data = get_data('tbl_pengadaan_langsung','id',$id)->row_array();
		$data['header']	= get_data('tbl_pengadaan_langsung_header',[
			'where'		=> ['id_pengadaan_langsung'=>$id],
			'sort_by'	=> 'id'
		])->result_array();
		$data['detail']	= [];
		foreach($data['header'] as $h) {
			$data['detail'][$h['id']] = get_data('tbl_pengadaan_langsung_detail',[
				'where'		=> ['id_header'=>$h['id']],
				'sort_by'	=> 'id'
			])->result_array();
		}
		render($data,'pdf');
	}


}