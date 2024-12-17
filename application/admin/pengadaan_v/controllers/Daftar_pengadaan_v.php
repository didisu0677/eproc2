<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_pengadaan_v extends BE_Controller {

	function __construct() {
		parent::__construct();
		include_lang('pengadaan');
	}

	function index() {
		render();
	}

	function data() {
		$vendor_kategori		= get_data('tbl_vendor_kategori','id_vendor',user('id_vendor'))->result();
		$id_kategori_rekanan	= [0];
		foreach($vendor_kategori as $v) {
			$id_kategori_rekanan[] = $v->id_kategori_rekanan;
		}
		$config	= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false,
			'group_by'		=> 'nomor_pengadaan',
			'where'			=> [
				'tipe_pengadaan'		=> 'Lelang',
				'status_pengadaan'		=> 'BIDDING',
				'id_kategori_rekanan'	=> $id_kategori_rekanan
			],
			'button'		=> button_serverside('btn-info',base_url('pengadaan_v/daftar_pengadaan_v/detail/'),['fa-search',lang('detil'),true])
		];
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function ref($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) == 2) {
			$pengadaan 			= get_data('tbl_pengadaan','id',$id[0])->row();
			$pengadaan_detail	= get_data('tbl_pengadaan_detail','nomor_pengadaan',$pengadaan->nomor_pengadaan)->row();
			redirect('pengadaan_v/daftar_pengadaan_v/detail/'.encode_id([$pengadaan_detail->id,rand()]));
		} else render('404');
	}

	function detail($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) == 2) {
			$data					= get_data('tbl_pengadaan_detail','id',$id[0])->row_array();
			$pengadaan_kategori 	= get_data('tbl_pengadaan_detail','nomor_pengadaan',$data['nomor_pengadaan'])->result();
			$vendor_kategori		= get_data('tbl_vendor_kategori','id_vendor',user('id_vendor'))->result();
			$is_valid				= false;
			$tanggal_pendaftaran	= get_data('tbl_jadwal_pengadaan','kata_kunci = "pendaftaran_pengadaan" AND nomor_pengajuan = "'.$data['nomor_pengajuan'].'"')->row();
			$data['open_pendaftaran']	= false;
			if(isset($tanggal_pendaftaran->id)) {
				if(strtotime($tanggal_pendaftaran->tanggal_awal) <= strtotime('now') && strtotime($tanggal_pendaftaran->tanggal_akhir) >= strtotime('now')) {
					$data['open_pendaftaran'] 	= true;
				}
				$data['tanggal_pendaftaran']	= c_date($tanggal_pendaftaran->tanggal_awal).' - '.c_date($tanggal_pendaftaran->tanggal_akhir);
			}
			foreach($vendor_kategori as $v) {
				foreach($pengadaan_kategori as $p) {
					if($v->id_kategori_rekanan == $p->id_kategori_rekanan) $is_valid = true;
				}
			}
			if($is_valid && $data['status_pengadaan'] == 'BIDDING') {
				$this->load->helper('pengadaan');
				$pengadaan 				= get_data('tbl_pengadaan','nomor_pengadaan',$data['nomor_pengadaan'])->row();
				$data['id_pengadaan']	= isset($pengadaan->id) ? $pengadaan->id : 0;
				$data['title']			= $data['nomor_pengadaan'];
				$data['id_pengajuan']	= id_by_nomor($data['nomor_pengajuan'],'pengajuan');
				$data['id_rks']			= id_by_nomor($data['nomor_pengajuan'],'rks');
				$data['id_hps']			= id_by_nomor($data['no_hps'],'hps');
				$data['bidding']		= get_data('tbl_pengadaan_bidder',[
					'where'		=> [
						'id_vendor'			=> user('id_vendor'),
						'nomor_pengadaan'	=> $data['nomor_pengadaan']
					]
				])->row();
				render($data);
			} else render('404');
		} else render('404');
	}

	function dokumen($encode_id='') {
		$id 	= decode_id($encode_id);
		if(count($id) == 2) {
			$pengajuan 	= get_data('tbl_rks','id',$id[0])->row();
			if(isset($pengajuan->id) && $pengajuan->file) {
				$data['file']	= json_decode($pengajuan->file,true);
				if(count($data['file'])) render($data,'layout:false');
				else echo lang('tidak_ada_data');
			} else echo lang('tidak_ada_data');
		} else echo lang('tidak_ada_data');
	}

	function save() {
		$data 		= get_data('tbl_pengadaan','nomor_pengadaan',post('nomor_pengadaan'))->row_array();
		$response 	= [
			'status'	=> 'failed',
			'message'	=> lang('data_gagal_disimpan')
		];
		if(isset($data['id'])) {
			$id 	= $data['id'];
			unset($data['id']);
			unset($data['file_persyaratan']);
			$file_persyaratan 	= post('file_persyaratan');
			$full_path 			= FCPATH . $file_persyaratan;
			if(file_exists($full_path)) {
				$zip 			= new ZipArchive;
				if ($zip->open($full_path) === TRUE) {

					$_temp 		= FCPATH . 'assets/uploads/temp/dokumen_persyaratan_'.user('id_vendor').'_'.$id;
					if(!is_dir($_temp)){
						$oldmask = umask(0);
						mkdir($_temp,0777);
						umask($oldmask);
					}

					$zip->extractTo($_temp);
					$zip->close();

					$full_path_save		= FCPATH . 'assets/uploads/dokumen_rekanan/dokumen_persyaratan_'.user('id_vendor').'_'.$id.'.zip';
					$zip 				= new ZipArchive;
					$res 				= $zip->open($full_path_save, ZipArchive::CREATE | ZipArchive::OVERWRITE);
					$password 			= 'undefined';
					if ($res === TRUE) {
						$password 		= user('id_vendor').$id.rand(10000,99999);
						foreach(c_scandir($_temp) as $f) {
							if(!is_dir($f)) {
								$new_filename = substr($f,strrpos($f,'/') + 1);
								$zip->addFile($f,$new_filename);
								$zip->setEncryptionName($new_filename, ZipArchive::EM_AES_256, $password);
							}
						}
						$zip->close();
					}
					$basename_file = str_replace(basename($file_persyaratan),'',$file_persyaratan);
					delete_dir($_temp);
					delete_dir($basename_file);
					$arr_file					= [$password => 'dokumen_persyaratan_'.user('id_vendor').'_'.$id.'.zip'];
					$data['file_persyaratan']	= json_encode($arr_file);
				}
				$data['pesan']		= post('pesan');
				$data['id_vendor']	= user('id_vendor');
				$data['nama_vendor']= user('nama');
				$data['is_submit']	= 1;
				$response 			= save_data('tbl_pengadaan_bidder',$data,[],true);
			} else {
				$response 		= [
					'message'	=> lang('dokumen_persyaratan_tidak_valid'),
					'status'	=> 'failed'
				];
			}
		}
		render($response,'json');
	}

	function delete() {
		delete_data('tbl_pengadaan_bidder','id',post('id'));
		echo lang('pembatalan_pendaftaran_berhasil');
	}
}