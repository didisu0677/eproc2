<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitor_pengadaan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		if(user('id_group') == 3) {
			$config['where'] 	= [
				'tbl_pengajuan.nomor_pengajuan !='	=> '',
				'tbl_pengajuan.kode_divisi'			=> user('kode_divisi')
			];
		}else{
			$config['where'] 	= [
				'tbl_pengajuan.nomor_pengajuan !='	=> ''
			];
		}
		$config['sort_by']		= 'id';
		$config['sort']			= 'desc';
		$config['access_edit'] 	= false;
		$config['access_view'] 	= false;
		$config['access_delete'] = false;

		if(user('is_kanwil')==1){
			$config['where']['tbl_pengajuan.id_unit_kerja']	= user('id_unit_kerja');
		}

		$data 	= data_serverside($config);		
		render($data,'json');
	}

	function detail($id=0) {
		$no_pengajuan 	= get('no_pengajuan');
		$data			= get_data('tbl_pengajuan a',[
			'select'	=> 'a.*, b.metode_pengadaan, c.nama_vendor, c.nomor_spk, c.tanggal_spk, c.penawaran_terakhir',
			'join'		=> [
				'tbl_inisiasi_pengadaan b ON a.nomor_inisiasi = b.nomor_inisiasi TYPE LEFT',
				'tbl_pemenang_pengadaan c ON a.nomor_pengajuan = c.nomor_pengajuan TYPE LEFT',
			],
			'where'		=> [
				'a.id' 					=> $id,
				'or a.nomor_pengajuan'	=> $no_pengajuan
			]
		])->row_array();
		$data['hps']	= get_data('tbl_m_hps','nomor_pengajuan = "'.$no_pengajuan.'"')->row_array();
		$data['rks']	= get_data('tbl_rks','nomor_pengajuan = "'.$no_pengajuan.'" AND tipe_rks = "pengadaan"')->row_array();
		if(isset($data['id'])) {
			render($data,'layout:false access:true');
		} else render(lang('tidak_ada_data'));
	}

}