<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengalaman extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		if(user('id_vendor')) {
			$data['title']	= lang('pengalaman');
			$data['vendor']	= get_data('tbl_vendor','id',user('id_vendor'))->row_array();
			$data['pengalaman_vendor']	= get_data('tbl_pengalaman_vendor','id_vendor',user('id_vendor'))->result_array();
			render($data);
		} else render('404');
	}

	function get_data() {
		$data['pengalaman_vendor']	= get_data('tbl_pengalaman_vendor',[
			'where' => [
			'id_vendor'=>user('id_vendor')
		],
		'sort_by' => 'id'
		])->result_array();
		render($data,'json');
	}

	function save() {
		$pengalaman = post('pengalaman');
		$deskripsi = post('deskripsi');
		if(user('id_vendor')) {		
			delete_data('tbl_pengalaman_vendor','id_vendor',user('id_vendor'));
			$vendor 			= get_data('tbl_vendor','id',user('id_vendor'))->row();

			$pengalaman = post('pengalaman');
			$isi 	= post('deskripsi','html');

			$d 		= [];

			foreach($pengalaman as $k => $v) {
			//	debug($v);die;
				if(isset($v) && $v != '') {
					$d= [
						'id_vendor'		=> $vendor->id,
						'kode_vendor'	=> $vendor->kode_rekanan,
						'nama_vendor'	=> $vendor->nama,
						'pengalaman'	=> $pengalaman[$k],
						'deskripsi'		=> $deskripsi[$k],
					];
					$response = insert_data('tbl_pengalaman_vendor',$d);
				}
			}

			render([
				'status'	=> 'success',
				'message'	=> lang('data_berhasil_disimpan')
			],'json');
		} else render('404');	
	}
}
