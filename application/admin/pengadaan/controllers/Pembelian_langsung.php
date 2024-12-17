<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_langsung extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$config = [
			'access_view'	=> false,
			'where'			=> ['tipe' => 'PL'],
			'button'		=> button_serverside('btn-success','btn-print',['fa-print',lang('cetak'),false],'act-print')
		];
		if(user('is_kanwil')) {
			$config['where']['id_unit_kerja']	= user('id_unit_kerja');
		}
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data 	= get_data('tbl_pengadaan_langsung a',[
			'select'	=> 'a.*,b.nama_vendor,b.alamat_vendor,b.npwp_vendor',
			'join'		=> 'tbl_pengadaan_langsung_header b ON a.id = b.id_pengadaan_langsung TYPE LEFT',
			'where'		=> 'a.id = '.post('id')
		])->row_array();
		$data['detail']	= get_data('tbl_pengadaan_langsung_detail',[
			'where'		=> [
				'id_pengadaan_langsung'	=> post('id')
			],
			'sort_by'	=> 'id'
		])->result_array();
		render($data,'json');
	}

	function save() {
		$data 					= post();
		$data['tipe']			= 'PL';
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
			$id_header = insert_data('tbl_pengadaan_langsung_header',[
				'id_pengadaan_langsung'	=> $response['id'],
				'nama_vendor'			=> post('nama_vendor'),
				'alamat_vendor'			=> post('alamat_vendor'),
				'npwp_vendor'			=> post('npwp_vendor')
			]);

			$deskripsi	= post('deskripsi');
			$satuan		= post('satuan');
			$harga 		= post('harga');
			$jumlah 	= post('jumlah');

			$total 		= 0;
			foreach($deskripsi as $k => $v) {
				$_total 	= $jumlah[$k] * str_replace('.', '', $harga[$k]);
				$total 		+= $_total;
				insert_data('tbl_pengadaan_langsung_detail',[
					'id_pengadaan_langsung'	=> $response['id'],
					'id_header'				=> $id_header,
					'deskripsi'				=> $deskripsi[$k],
					'satuan'				=> $satuan[$k],
					'harga'					=> str_replace('.', '', $harga[$k]),
					'jumlah'				=> $jumlah[$k],
					'total'					=> $_total
				]);
			}
			update_data('tbl_pengadaan_langsung',['total_pengadaan'=>$total],'id',$response['id']);
			update_data('tbl_pengadaan_langsung_header',['total'=>$total],'id',$id_header);
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
		$data 	= get_data('tbl_pengadaan_langsung a',[
			'select'	=> 'a.*,b.nama_vendor,b.alamat_vendor,b.npwp_vendor',
			'join'		=> 'tbl_pengadaan_langsung_header b ON a.id = b.id_pengadaan_langsung TYPE LEFT',
			'where'		=> 'a.id = '.$id
		])->row_array();
		$data['detail']	= get_data('tbl_pengadaan_langsung_detail',[
			'where'		=> [
				'id_pengadaan_langsung'	=> $id
			],
			'sort_by'	=> 'id'
		])->result_array();
		render($data,'pdf');
	}

}