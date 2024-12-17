<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kontrak extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$config				= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false
		];
		if(user('is_kanwil')) {
			$config['where']['id_unit_kerja']	= user('id_unit_kerja');
		}
		$config['button'][]	= button_serverside('btn-success','btn-print',['fa-print',lang('cetak'),true],'act-print');
		$config['button'][]	= button_serverside('btn-info','btn-act-view',['fa-search',lang('detil'),true],'act-view');
		if(menu()['access_edit']) {
			$config['button'][]	= button_serverside('btn-warning','btn-input',['fa-edit',lang('ubah'),true],'edit');
		}
		if(menu()['access_delete']) {
			$config['button'][]	= button_serverside('btn-danger','btn-delete',['fa-trash-alt',lang('hapus'),true],'delete');
		}
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data 			= get_data('tbl_kontrak','id',post('id'))->row_array();
		$data['detail']	= get_data('tbl_kontrak_detail','id_kontrak',post('id'))->result_array();
		render($data,'json');
	}

	function get_spk() {
		$detail = user('is_kanwil') ? ' AND id_unit_kerja = "'.user('id_unit_kerja').'"' : '';
		$data 	= get_data('tbl_pemenang_pengadaan','status_sanggah = 1 AND id_kontrak = 0 AND doc_type = "OA" '.$detail)->result_array();
		render($data,'json');
	}

	function save() {
		$data 		= post();
		$spk 		= get_data('tbl_pemenang_pengadaan','nomor_spk',$data['nomor_spk'])->row();
		if(isset($spk->id)) {
			$data['id_divisi']				= $spk->id_divisi;
			$data['id_unit_kerja']			= $spk->id_unit_kerja;
			$data['nomor_pengadaan']		= $spk->nomor_pengadaan;
			$data['nama_pengadaan']			= $spk->nama_pengadaan;
			$data['keterangan_pengadaan']	= $spk->keterangan_pengadaan;
			$data['id_vendor']				= $spk->id_vendor;
			$data['nama_vendor']			= $spk->nama_vendor;
			$data['nilai_pengadaan']		= $spk->penawaran_terakhir;
		}
		$response 	= save_data('tbl_kontrak',$data,post(':validation'));
		if($response['status'] == 'success') {
			delete_data('tbl_kontrak_detail','id_kontrak',$response['id']);

			$pasal 	= post('pasal');
			$judul	= post('judul_pasal');
			$isi 	= post('isi_pasal','html');

			$d 		= [];
			foreach($pasal as $k => $v) {
				$d[]= [
					'id_kontrak'	=> $response['id'],
					'pasal'			=> $pasal[$k],
					'judul_pasal'	=> $judul[$k],
					'isi_pasal'		=> $isi[$k]
				];
			}
			insert_batch('tbl_kontrak_detail',$d);

			$data_update 	= [
				'id_kontrak'					=> $response['id'],
				'tanggal_mulai_kontrak'			=> $data['tanggal_mulai_kontrak'],
				'tanggal_selesai_kontrak'		=> $data['tanggal_selesai_kontrak'],
				'tanggal_dikeluarkan_kontrak'	=> $data['tanggal_dikeluarkan'],
				'target_value'					=> $data['target_value']
			];

			if(!$data['id']) {
				$data_update['tanggal_input_kontrak']	= date('Y-m-d');
			}

			update_data('tbl_pemenang_pengadaan',$data_update,'nomor_spk',$data['nomor_spk']);

		}
		render($response,'json');
	}

	function delete() {
		$data 		= get_data('tbl_kontrak','id',post('id'))->row();
		$response 	= destroy_data('tbl_kontrak','id',post('id'));
		if($response['status'] == 'success' && isset($data->id)) {
			delete_data('tbl_kontrak_detail','id_kontrak',post('id'));
			update_data('tbl_pemenang_pengadaan',['id_kontrak'=>0],'nomor_spk',$data->nomor_spk);
		}
		render($response,'json');
	}

	function detail($id = 0) {
		$data 	= get_data('tbl_kontrak','id',$id)->row_array();
		if(isset($data['id'])) {
			render($data,'layout:false');
		} else {
			echo lang('tidak_ada_data');
		}
	}

	function cetak($encode_id=''){
		$decode = decode_id($encode_id);
		if(count($decode) == 2) {
			$id 								= $decode[0];
			$record								= get_data('tbl_kontrak','id',$id)->row_array();
			$tanggal_dikeluarkan				= $record['tanggal_dikeluarkan'];
			$record['tanggal_mulai_kontrak']	= date_indo($record['tanggal_mulai_kontrak']);
			$record['tanggal_selesai_kontrak']	= date_indo($record['tanggal_selesai_kontrak']);
			$record['tanggal_dikeluarkan']		= date_indo($record['tanggal_dikeluarkan']);

			$r['pasal'] 					= get_data('tbl_kontrak_detail',[
				'where'						=> 'id_kontrak = '.$id,
				'sort_by'					=> 'id',
				'sort'						=> 'ASC'
			])->result_array();
			$record['isi_kontrak']			= include_view('manajemen_kontrak/kontrak/isi_kontrak',$r);
			$data['html']					= template_pdf($record,'kontrak',$tanggal_dikeluarkan);
			render($data,'pdf');
		} else {
			render('404');
		}
	}
}