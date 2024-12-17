<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spk extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$anggota_panitia				= get_data('tbl_anggota_panitia','userid',user('id'))->result();
		$id_panitia						= [0];
		foreach($anggota_panitia as $a) $id_panitia[] = $a->id_m_panitia;
		if(count($id_panitia) > 1) {
			$config['where']['id_panitia']		= $id_panitia;
		} else {
			$config['where']['id_creator']		= user('id');
		}
		$config['access_edit'] 					= false;
		$config['access_delete'] 				= false;
		$config['access_view'] 					= false;
		$config['sort_by'] 						= 'id';
		$config['sort']							= 'DESC';
		$config['where']['status_sanggah']		= '1';
		$config['button'][]						= button_serverside('btn-info',base_url('pengadaan/spk/detail/'),['fa-search',lang('detil'),true],'btn-detail');

		$data 	= data_serverside($config);
		render($data,'json');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_pemenang_pengadaan','id = '.$id[0])->row_array();
			if(isset($data['id'])) {
				$data['title']	= $data['nomor_pengadaan'];
				render($data);
			} else render('404');
		} else render('404');
	}

	function cetak($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_pemenang_pengadaan','id = '.$id[0].' AND status_sanggah = 1')->row_array();
			if(isset($data['id'])) {
				$data['aanwijzing']		= get_data('tbl_aanwijzing','nomor_pengadaan',$data['nomor_pengadaan'])->row();
				$data['klarifikasi']	= get_data('tbl_klarifikasi','nomor_pengadaan',$data['nomor_pengadaan'])->row();
				$data['rks']			= get_data('tbl_rks',[
					'where'				=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan'],
						'tipe_rks'			=> 'klarifikasi'
					],
					'sort_by'	=> 'id',
					'sort'		=> 'desc',
					'limit'		=> 1
				])->row();
				$jaminan 				= get_data('tbl_m_definisi_pasal')->result();
				$data['jaminan']		= [];
				foreach($jaminan as $j) {
					$data['jaminan'][$j->kata_kunci]	= $j->deskripsi;
				}
				render($data,'pdf');
			} else render('404');
		} else render('404');
	}

	function surat_penunjukan($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_pemenang_pengadaan','id = '.$id[0].' AND status_sanggah = 1')->row_array();
			if(isset($data['id'])) {
				$data['rks']			= get_data('tbl_rks',[
					'where'				=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan'],
						'tipe_rks'			=> 'klarifikasi'
					],
					'sort_by'	=> 'id',
					'sort'		=> 'desc',
					'limit'		=> 1
				])->row();
				render($data,'pdf');
			} else render('404');
		} else render('404');
	}
}