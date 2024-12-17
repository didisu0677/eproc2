<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aanwijzing_v extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		include_lang('pengadaan');
		render();
	}

	function data($status=1) {
		$config	= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false,
			'button'		=> [
				button_serverside('btn-info',base_url('pengadaan_v/aanwijzing_v/detail/'),['fa-search',lang('detil'),true],'btn-detail'),
			],
			'where'			=> [
				'id_vendor'	=> user('id_vendor')
			]
		];
		if($status == 1) $config['where']['status_aanwijzing']	= 'AANWIJZING';
		else {
			$config['sort_by'] 	= 'id';
			$config['sort']		= 'DESC';
			$config['where']['status_aanwijzing !=']	= 'AANWIJZING';
		}
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$aanwijzing_vendor 					= get_data('tbl_aanwijzing_vendor','id = '.$id[0].' AND id_vendor = '.user('id_vendor'))->row_array();
			$data 								= [];
			if(isset($aanwijzing_vendor['id'])) {
				$data 							= get_data('tbl_aanwijzing','nomor_aanwijzing',$aanwijzing_vendor['nomor_aanwijzing'])->row_array();
			}
			if(isset($data['id'])) {
				$this->load->helper('pengadaan');
				$data['tanggal_berita_acara']	= $data['tanggal_berita_acara'] != '0000-00-00' ? c_date($data['tanggal_berita_acara']) : '';
				$data['peserta_berita_acara']	= $data['peserta_berita_acara'] ? json_decode($data['peserta_berita_acara'],true) : [];
				$data['title']				= $data['nomor_aanwijzing'];
				$data['id_rks_pengadaan']	= id_by_nomor($data['nomor_pengajuan'],'rks');
				$data['id_rks_aanwijzing']	= id_by_nomor($data['nomor_pengajuan'],'rks','aanwijzing');
				include_lang('pengadaan');
				render($data);
			} else render('404');
		} else render('404');
	}

	function dokumen_rks($encode_id='') {
		$id 	= decode_id($encode_id);
		if(count($id) == 2) {
			$rks 	= get_data('tbl_rks','id',$id[0])->row();
			if(isset($rks->id) && $rks->file) {
				$data['file']	= json_decode($rks->file,true);
				if(count($data['file'])) render($data,'layout:false');
				else echo lang('tidak_ada_data');
			} else echo lang('tidak_ada_data');
		} else echo lang('tidak_ada_data');
	}

}