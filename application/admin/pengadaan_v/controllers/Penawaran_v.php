<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penawaran_v extends BE_Controller {

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
				button_serverside('btn-info',base_url('pengadaan_v/penawaran_v/detail/'),['fa-search',lang('detil'),true],'btn-detail'),
			],
			'where'			=> [
				'id_vendor'	=> user('id_vendor')
			]
		];
		if($status == 1) $config['where']['status_aanwijzing']	= 'PENAWARAN';
		else {
			$config['sort_by'] 	= 'id';
			$config['sort']		= 'DESC';
			$config['where']['status_aanwijzing !=']	= ['AANWIJZING','PENAWARAN'];
		}
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function ref($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$aanwijzing 	= get_data('tbl_aanwijzing','id',$id[0])->row();
			if(isset($aanwijzing->id)) {
				if(user('id_vendor')) {
					$aanwijzing_vendor 	= get_data('tbl_aanwijzing_vendor','nomor_aanwijzing = "'.$aanwijzing->nomor_aanwijzing.'" AND id_vendor = '.user('id_vendor'))->row();
					if(isset($aanwijzing_vendor->id)) {
						redirect('pengadaan_v/penawaran_v/detail/'.encode_id([$aanwijzing_vendor->id,rand()]));
					} else render('404');
				} else redirect('pengadaan/penawaran/detail/'.$encode_id);
			} else render('404');
		} else render('404');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_aanwijzing_vendor','id = '.$id[0].' AND id_vendor = '.user('id_vendor'))->row_array();
			if(isset($data['id'])) {
				$this->load->helper('pengadaan');
				$data['title']				= $data['nomor_pengadaan'];
				$data['id_rks_aanwijzing']	= id_by_nomor($data['nomor_pengajuan'],'rks','aanwijzing');
				$data['aanwijzing']			= get_data('tbl_aanwijzing','nomor_pengadaan',$data['nomor_pengadaan'])->row();
				$tanggal_penawaran			= get_data('tbl_jadwal_pengadaan','kata_kunci = "pemasukan_dokumen" AND nomor_pengajuan = "'.$data['nomor_pengajuan'].'"')->row();
				$data['open_penawaran']	= false;
				if(isset($tanggal_penawaran->id)) {
					if(strtotime($tanggal_penawaran->tanggal_awal) <= strtotime('now') && strtotime($tanggal_penawaran->tanggal_akhir) >= strtotime('now')) {
						$data['open_penawaran'] 	= true;
					}
					$data['tanggal_penawaran']	= c_date($tanggal_penawaran->tanggal_awal).' - '.c_date($tanggal_penawaran->tanggal_akhir);
				}
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

	function save() {
		$post 				= post();
		$aanwijzing 		= get_data('tbl_aanwijzing_vendor','nomor_pengadaan = "'.post('nomor_pengadaan').'" AND id_vendor = '.user('id_vendor'))->row();
		$data 				= [
			'id'						=> $aanwijzing->id,
			'nomor_penawaran'			=> $post['nomor_penawaran'],
			'pesan_penawaran'			=> $post['pesan_penawaran'],
			'nilai_total_penawaran'		=> $post['nilai_total_penawaran'],
			'nilai_jaminan_penawaran'	=> $post['nilai_jaminan_penawaran'],
			'jumlah_edit'				=> $aanwijzing->jumlah_edit + 1,
			'tanggal_penawaran'			=> date('Y-m-d H:i:s')
		];
		$dokumen 			= [
			'administrasi'		=> post('file_administrasi'),
			'teknis'			=> post('file_teknis'),
			'penawaran_harga'	=> post('file_penawaran_harga')
		];
		$file_penawaran 	= [];
		foreach($dokumen as $k => $v) {
			$full_path 			= FCPATH . $v;
			if(file_exists($full_path)) {
				$zip 			= new ZipArchive;
				if ($zip->open($full_path) === TRUE) {
					$_temp 		= FCPATH . 'assets/uploads/temp/dokumen_'.$k.'_'.user('id_vendor').'_'.$aanwijzing->id;
					if(!is_dir($_temp)){
						$oldmask = umask(0);
						mkdir($_temp,0777);
						umask($oldmask);
					}

					$zip->extractTo($_temp);
					$zip->close();

					$full_path_save		= FCPATH . 'assets/uploads/dokumen_rekanan/dokumen_'.$k.'_'.user('id_vendor').'_'.$aanwijzing->id.'.zip';
					$zip 				= new ZipArchive;
					$res 				= $zip->open($full_path_save, ZipArchive::CREATE | ZipArchive::OVERWRITE);
					if ($res === TRUE) {
						$password 		= user('id_vendor').$aanwijzing->id.rand(10000,99999);
						foreach(c_scandir($_temp) as $f) {
							if(!is_dir($f)) {
								$new_filename = substr($f,strrpos($f,'/') + 1);
								$zip->addFile($f,$new_filename);
								$zip->setEncryptionName($new_filename, ZipArchive::EM_AES_256, $password);
							}
						}
						$zip->close();
					}
					$basename_file = str_replace(basename($v),'',$v);
					delete_dir($_temp);
					delete_dir($basename_file);
					$file_penawaran[$k] 		= [
						'file'					=> 'dokumen_'.$k.'_'.user('id_vendor').'_'.$aanwijzing->id.'.zip',
						'password'				=> $password
					];
				}
			}
		} 
		if(count($file_penawaran) == 3) {
			$data['file_penawaran']	= json_encode($file_penawaran);
			$response		= save_data('tbl_aanwijzing_vendor',$data,[],true);
		} else {
			$response 		= [
				'message'	=> lang('dokumen_penawaran_tidak_valid'),
				'status'	=> 'failed'
			];
		}
		render($response,'json');
	}

}