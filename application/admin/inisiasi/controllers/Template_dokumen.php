<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_dokumen extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['grup_dokumen']			= [
			'persyaratan_peserta'		=> lang('dokumen_hal_yang_menggugurkan'),
			'dokumen_administrasi'		=> lang('dokumen_administrasi'),
			'dokumen_teknis'			=> lang('dokumen_teknis'),
			'dokumen_penawaran_harga'	=> lang('dokumen_penawaran_harga')
		];
		$data['deskripsi']				= [];
		foreach($data['grup_dokumen'] as $k => $v) {
			$data['deskripsi'][$k][0]	= get_data('tbl_template_dokumen',[
				'where'					=> [
					'grup'				=> $k,
					'parent_id'			=> 0
				]
			])->result_array();
			$data['mandatori'][$k]		= 0;
			if(isset($data['deskripsi'][$k][0][0])) $data['mandatori'][$k]	= $data['deskripsi'][$k][0][0]['mandatori'];
			foreach ($data['deskripsi'][$k][0] as $key => $value) {
				$data['deskripsi'][$k][$value['id']]	= get_data('tbl_template_dokumen',[
					'where'					=> [
						'grup'				=> $k,
						'parent_id'			=> $value['id']
					]
				])->result_array();
			}
		}
		render($data);
	}

	function save() {
		$grup = [
			'persyaratan_peserta','dokumen_administrasi','dokumen_teknis','dokumen_penawaran_harga'
		];
		$mandatori	= post('mandatori');
		$deskripsi	= post('deskripsi');
		delete_data('tbl_template_dokumen');
		foreach($grup as $g) {
			if(isset($deskripsi[$g])) {
				foreach ($deskripsi[$g][0] as $key => $value) {
					$data 	= [
						'parent_id'	=> 0,
						'grup'		=> $g,
						'mandatori'	=> isset($mandatori[$g]) ? $mandatori[$g] : 0,
						'deskripsi'	=> $value
					];
					$save_parent	= insert_data('tbl_template_dokumen',$data);
					if($save_parent && isset($deskripsi[$g][$key])) {
						foreach ($deskripsi[$g][$key] as $key2 => $value2) {
							$data 	= [
								'parent_id'	=> $save_parent,
								'grup'		=> $g,
								'mandatori'	=> isset($mandatori[$g]) ? $mandatori[$g] : 0,
								'deskripsi'	=> $value2
							];
							insert_data('tbl_template_dokumen',$data);
						}
					}
				}
			}
		}

		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

}