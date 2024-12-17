<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_peninjauan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['template1']	= get_data('tbl_template_peninjauan',[
			'where'		=> 'grup = "aspek_peninjauan"',
			'sort_by'	=> 'id'
		])->result_array();
		$data['template']	= get_data('tbl_template_peninjauan',[
			'where'		=> 'grup = "data_pendukung"',
			'sort_by'	=> 'id'
		])->result_array();
		render($data);
	}

	function save() {
		$deskripsi1 = post('deskripsi1');
		$deskripsi 	= post('deskripsi');
		$nomor 		= post('nomor');
		$pilihan 	= post('pilihan');

		$data 		= [];
		foreach($deskripsi1 as $v) {
			if($v) {
				$data[] 	= [
					'deskripsi'		=> $v,
					'nomor'			=> 0,
					'pilihan'		=> '',
					'grup'			=> 'aspek_peninjauan'
				];
			}
		}
		foreach($deskripsi as $k => $v) {
			if($v) {
				$_nomor		= isset($nomor[$k]) && $nomor[$k] ? 1 : 0;
				$_pilihan 	= !$_nomor && $pilihan[$k] ? explode(',', $pilihan[$k]) : [];
				$data[] 	= [
					'deskripsi'		=> $v,
					'nomor'			=> $_nomor,
					'pilihan'		=> json_encode($_pilihan),
					'grup'			=> 'data_pendukung'
				];
			}
		}

		delete_data('tbl_template_peninjauan');
		insert_batch('tbl_template_peninjauan',$data);
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

}