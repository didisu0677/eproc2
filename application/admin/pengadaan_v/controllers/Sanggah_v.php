<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sanggah_v extends BE_Controller {

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
				button_serverside('btn-info',base_url('pengadaan_v/sanggah_v/detail/'),['fa-search',lang('detil'),true],'btn-detail'),
			],
			'where'			=> [
				'id_vendor'	=> user('id_vendor')
			]
		];
		if($status == 1) $config['where']['status_sanggah']	= 0;
		else {
			$config['sort_by'] 	= 'id';
			$config['sort']		= 'DESC';
			$config['where']['status_sanggah !=']	= 0;
		}
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_peserta_sanggah','id = '.$id[0].' AND id_vendor = '.user('id_vendor'))->row_array();
			if(isset($data['id'])) {
				$this->load->helper('pengadaan');
				$data['title']				= $data['nomor_pengadaan'];
				$data['pemenang']			= get_data('tbl_pemenang_pengadaan','id',$data['id_pemenang_pengadaan'])->row();
				$data['open_sanggah']		= false;
				if(strtotime($data['pemenang']->tanggal_mulai_sanggah) <= strtotime(date('Y-m-d')) && strtotime(date('Y-m-d')) <= strtotime($data['pemenang']->tanggal_selesai_sanggah)) {
					$data['open_sanggah']	= true;
				}

				include_lang('pengadaan');
				render($data);
			} else render('404');
		} else render('404');
	}

	function save() {
		$id 				= post('id');
		$data['pesan'] 		= post('pesan');
		$file 				= post('file_pendukung');
		$filename 			= 'file_sanggah_'.encode_id($id).'.zip';
		$new_file			= FCPATH . 'assets/uploads/sanggah/'.$filename;

		$dt 				= get_data('tbl_peserta_sanggah','id',$id)->row();
		if($dt->file_pendukung) {
			@unlink(FCPATH . 'assets/uploads/sanggah/'.$dt->file_pendukung);
		}

		if(file_exists($file)) {
			if(@copy($file, $new_file)) {
				$data['file_pendukung']	= $filename;
				$dir = str_replace(basename($file),'',$file);
				if($dir) {
					delete_dir(FCPATH . $dir);
				}
			}
		}
		update_data('tbl_peserta_sanggah',$data,'id',$id);
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

}