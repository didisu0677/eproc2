<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekomendasi_usulan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$config				= [
			'access_view' 	=> false,
			'access_delete'	=> false,
			'access_edit'	=> false,
			'where'			=> [
				'is_pendaftar'			=> 1,
				'laporan_kunjungan'		=> 1,
			],
			'sort_by'		=> 'tanggal_kunjungan',
			'sort'			=> 'desc'
		];
		if(menu()['access_additional']) {
			$config['button'][]	= button_serverside('btn-success','btn-input',['fa-edit',lang('input'),true],'btn-input',['status_drm'=>0]);
		}
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_vendor a',[
			'select'	=> 'a.id AS i_vendor,a.nama AS nm_vendor,a.alamat AS alamat_vendor ,a.nama_kelurahan,a.nama_kecamatan,a.nama_kota,a.nama_provinsi,a.kode_pos,b.*',
			'join'		=> 'tbl_m_rekomendasi_vendor b ON a.id = b.id_vendor TYPE LEFT',
			'where'		=> ['a.id'=>post('id')]
		])->row_array();
		render($data,'json');
	}

	function save() {
		$response 	= save_data('tbl_m_rekomendasi_vendor',post(),post(':validation'),true);
		if($response['status'] == 'success') {
			$dt 	= get_data('tbl_m_rekomendasi_vendor','id',$response['id'])->row();
			update_data('tbl_vendor',['rekomendasi'=>post('usulan_rekomendasi'),'nomor_rekomendasi'=>$dt->nomor_rekomendasi],'id',post('id_vendor'));		    
		}
		render($response,'json');
	}

}