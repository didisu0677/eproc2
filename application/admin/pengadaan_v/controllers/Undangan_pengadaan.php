<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Undangan_pengadaan extends BE_Controller {

	function __construct() {
		parent::__construct();
		include_lang('pengadaan');
	}

	function index() {
		render();
	}

	function data() {
		$config	= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false,
			'where'			=> [
				'status_pengadaan'	=> 'BIDDING',
				'is_invite'			=> 1,
				'id_vendor'			=> user('id_vendor')
			],
			'button'		=> button_serverside('btn-info',base_url('pengadaan_v/undangan_pengadaan/detail/'),['fa-search',lang('detil'),true])
		];
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function ref($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) == 2) {
			$pengadaan 			= get_data('tbl_pengadaan','id',$id[0])->row();
			$pengadaan_bidder	= get_data('tbl_pengadaan_bidder',[
				'where'			=> [
					'nomor_pengadaan'	=> $pengadaan->nomor_pengadaan,
					'id_vendor'			=> user('id_vendor')
				]
			])->row();
			if(isset($pengadaan_bidder->id))
				redirect('pengadaan_v/undangan_pengadaan/detail/'.encode_id([$pengadaan_bidder->id,rand()]));
			else render('404');
		} else render('404');
	}

	function detail($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) == 2) {
			$data				= get_data('tbl_pengadaan_bidder',[
				'where'			=> [
					'id'		=> $id[0],
					'id_vendor'	=> user('id_vendor')
				]
			])->row_array();
			if(isset($data['id']) && $data['status_pengadaan'] == 'BIDDING') {
				$this->load->helper('pengadaan');
				$tanggal_pendaftaran		= get_data('tbl_jadwal_pengadaan','kata_kunci = "pendaftaran_pengadaan" AND nomor_pengajuan = "'.$data['nomor_pengajuan'].'"')->row();
				$data['open_pendaftaran']	= false;
				if(isset($tanggal_pendaftaran->id)) {
					if(strtotime($tanggal_pendaftaran->tanggal_awal) <= strtotime('now') && strtotime($tanggal_pendaftaran->tanggal_akhir) >= strtotime('now')) {
						$data['open_pendaftaran'] 	= true;
					}
					$data['tanggal_pendaftaran']	= c_date($tanggal_pendaftaran->tanggal_awal).' - '.c_date($tanggal_pendaftaran->tanggal_akhir);
				}

				$pengadaan 				= get_data('tbl_pengadaan','nomor_pengadaan',$data['nomor_pengadaan'])->row();
				$data['id_pengadaan']	= isset($pengadaan->id) ? $pengadaan->id : 0;
				$data['title']			= $data['nomor_pengadaan'];
				$data['id_pengajuan']	= id_by_nomor($data['nomor_pengajuan'],'pengajuan');
				$data['id_rks']			= id_by_nomor($data['nomor_pengajuan'],'rks');
				$data['id_hps']			= id_by_nomor($data['no_hps'],'hps');
				$data['bidder']			= get_data('tbl_pengadaan_bidder','nomor_pengadaan',$data['nomor_pengadaan'])->result();
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

	function spph($encode_id='') {
		$id 	= decode_id($encode_id);
		if(count($id) == 2) {
			$data 	= get_data('tbl_pengadaan_bidder','id',$id[0])->row_array();
			if(isset($data['id'])) {
				$jadwal_spph		= get_data('tbl_jadwal_pengadaan','kata_kunci="spph" AND nomor_pengajuan="'.$data['nomor_pengajuan'].'"')->row();
				$jadwal_aanwijzing	= get_data('tbl_jadwal_pengadaan','kata_kunci="aanwijzing" AND nomor_pengajuan="'.$data['nomor_pengajuan'].'"')->row();
				$jadwal_peninjauan	= get_data('tbl_jadwal_pengadaan','kata_kunci="peninjauan_lapangan" AND nomor_pengajuan="'.$data['nomor_pengajuan'].'"')->row();

				$data['tanggal_spph']		= isset($jadwal_spph->tanggal_awal) ? $jadwal_spph->tanggal_awal : date('Y-m-d');
				$data['tanggal_aanwijzing']	= isset($jadwal_aanwijzing->tanggal_awal) ? $jadwal_aanwijzing->tanggal_awal : '';
				$data['zona_aanwijzing']	= isset($jadwal_aanwijzing->zona_waktu) ? $jadwal_aanwijzing->zona_waktu : '';
				$data['tempat_aanwijzing']	= isset($jadwal_aanwijzing->lokasi) ? $jadwal_aanwijzing->lokasi : '';

				$data['tanggal_peninjauan']	= isset($jadwal_peninjauan->tanggal_awal) ? $jadwal_peninjauan->tanggal_awal : '';
				$data['zona_peninjauan']	= isset($jadwal_peninjauan->zona_waktu) ? $jadwal_peninjauan->zona_waktu : '';
				$data['tempat_peninjauan']	= isset($jadwal_peninjauan->lokasi) ? $jadwal_peninjauan->lokasi : '';

				$inisiasi 						= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
				$data['nama_tanda_tangan']		= $inisiasi->nama_tanda_tangan;
				$data['jabatan_tanda_tangan']	= $inisiasi->jabatan_tanda_tangan;

				$data['vendor']			= get_data('tbl_vendor','id',$data['id_vendor'])->row();
				render($data,'pdf');
			} else echo lang('tidak_ada_data');
		} else echo lang('tidak_ada_data');
	}

	function save() {
		$data 				= post();
		$data['is_submit']	= 1;
		$file_persyaratan 	= post('file_persyaratan');
		$full_path 			= FCPATH . $file_persyaratan;
		if(file_exists($full_path)) {
			$id 			= post('id');
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
			$response 			= save_data('tbl_pengadaan_bidder',$data,[],true);
		} else {
			$response 		= [
				'message'	=> lang('dokumen_persyaratan_tidak_valid'),
				'status'	=> 'failed'
			];
		}
		render($response,'json');
	}

	function unreg() {
		update_data('tbl_pengadaan_bidder',['is_submit'=>0,'pesan'=>''],'id',post('id'));
		echo lang('pembatalan_pendaftaran_berhasil');
	}

}