<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sanggah extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data($status=1) {
		$anggota_panitia				= get_data('tbl_anggota_panitia','userid',user('id'))->result();
		$id_panitia						= [0];
		foreach($anggota_panitia as $a) $id_panitia[] = $a->id_m_panitia;
		$config['where']['id_panitia']			= $id_panitia;
		$config['where']['status_penetapan']	= 1;
		$config['where']['lama_sanggah >']		= 0;
		$config['access_edit'] 					= false;
		$config['access_delete'] 				= false;
		$config['access_view'] 					= false;
		$config['button'][]						= button_serverside('btn-info',base_url('pengadaan/sanggah/detail/'),['fa-search',lang('detil'),true],'btn-detail');

		if($status == 1) $config['where']['status_sanggah']	= '0';
		else {
			$config['sort_by'] 	= 'id';
			$config['sort']		= 'DESC';
			$config['where']['status_sanggah !=']			= '0';
		}

		$data 	= data_serverside($config);
		render($data,'json');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_pemenang_pengadaan','id = '.$id[0])->row_array();
			if(isset($data['id'])) {
				$data['title']	= $data['nomor_pengadaan'];
				$data['vendor']	= get_data('tbl_peserta_sanggah','id_pemenang_pengadaan',$id[0])->result();
				$data['close_sanggah']	= $data['sudah_dijawab'] = false;
				if(strtotime($data['tanggal_selesai_sanggah']) < strtotime(date('Y-m-d'))) $data['close_sanggah'] = true;
				$jumlah_pesan 	= get_data('tbl_peserta_sanggah',[
					'select'	=> 'count(id) AS jml',
					'where'		=> [
						'id_pemenang_pengadaan'	=> $id[0],
						'pesan !='				=> ''
					]
				])->row();
				$jumlah_jawab 	= get_data('tbl_peserta_sanggah',[
					'select'	=> 'count(id) AS jml',
					'where'		=> [
						'id_pemenang_pengadaan'	=> $id[0],
						'jawaban !='			=> ''
					]
				])->row();
				if($jumlah_jawab == $jumlah_pesan) $data['sudah_dijawab'] = true;
				$_detail 		= get_data('tbl_pemenang_pengadaan_detail','id_pemenang_pengadaan',$data['id'])->row();
				$data['pr_type']			= $_detail->pr_type;
				$data['chk_kontrak']		= $data['pr_type'] == setting('pr_biaya') ? 'disabled' : '';
				$data['kelompok_pembelian']	= get_data('tbl_m_kelompok_pembelian')->result_array();
				render($data);
			} else render('404');
		} else render('404');
	}

	function get_data() {
		$id 	= post('id');
		$data 	= get_data('tbl_peserta_sanggah','id',$id)->row_array();
		render($data,'json');
	}

	function save_jawab() {
		$id 				= post('id');
		$data['jawaban'] 	= post('jawaban');
		$file 				= post('file_jawaban');
		$filename 			= 'file_jawaban_'.encode_id($id).'.zip';
		$new_file			= FCPATH . 'assets/uploads/sanggah/'.$filename;

		$dt 				= get_data('tbl_peserta_sanggah','id',$id)->row();
		if($dt->file_jawaban) {
			@unlink(FCPATH . 'assets/uploads/sanggah/'.$dt->file_jawaban);
		}

		if(file_exists($file)) {
			if(@copy($file, $new_file)) {
				$data['file_jawaban']	= $filename;
				$dir = str_replace(basename($file),'',$file);
				if($dir) {
					delete_dir(FCPATH . $dir);
				}
			}
		}
		update_data('tbl_peserta_sanggah',$data,'id',$id);
		$vendor 		= get_data('tbl_peserta_sanggah a',[
			'join'		=> 'tbl_user b ON a.id_vendor = b.id_vendor TYPE LEFT',
			'select'	=> 'b.id,b.email,a.nomor_pengadaan',
			'where'		=> 'a.id = '.$id
		])->row();
		if(isset($vendor->id)) {
			$link				= base_url().'pengadaan_v/sanggah_v/detail/'.encode_id([$id,rand()]);
			$description 		= 'Panitia pengadaan menjawab sanggahan anda untuk pengadaan <strong>'.$vendor->nomor_pengadaan.'</strong>';
			$data_notifikasi 	= [
				'title'			=> 'Jawaban Sanggah',
				'description'	=> $description,
				'notif_link'	=> $link,
				'notif_date'	=> date('Y-m-d H:i:s'),
				'notif_type'	=> 'info',
				'notif_icon'	=> 'fa-comment-alt',
				'id_user'		=> $vendor->id,
				'transaksi'		=> 'sanggah',
				'id_transaksi'	=> $id
			];
			insert_data('tbl_notifikasi',$data_notifikasi);

			if(setting('email_notification') && $vendor->email) {
				send_mail([
					'subject'		=> 'Jawaban Sanggah #'.$vendor->nomor_pengadaan,
					'to'			=> $vendor->email,
					'nama_user'		=> '',
					'description'	=> $description,
					'url'			=> $link
				]);
			}
		}
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function proses() {
		$post	= post();
		$id 	= post('id');
		$dt 	= get_data('tbl_pemenang_pengadaan','id',$id)->row();
		$gt 	= get_data('tbl_aanwijzing_vendor',[
			'where'	=> [
				'nomor_pengadaan'	=> $dt->nomor_pengadaan,
				'id_vendor'			=> $dt->id_vendor
			]
		])->row();
		$nomor_penawaran	= isset($gt->nomor_penawaran) ? $gt->nomor_penawaran : '';
		$tanggal_penawaran	= isset($gt->tanggal_penawaran) ? $gt->tanggal_penawaran : date('Y-m-d');
		$data 				= [
			'status_sanggah'			=> 1,
			'nomor_penawaran'			=> $nomor_penawaran,
			'tanggal_penawaran'			=> $tanggal_penawaran,
			'nomor_spk'					=> generate_code('tbl_pemenang_pengadaan','nomor_spk',[]),
			'tanggal_spk'				=> $post['tanggal_spk'],
			'tanggal_jatuh_tempo_spk'	=> $post['tanggal_jatuh_tempo_spk'],
			'kelompok_pembelian'		=> $post['kelompok_pembelian'],
			'doc_type'					=> isset($post['is_kontrak']) && $post['is_kontrak'] ? 'OA' : 'PO',
			'tanggal_po'				=> $post['tanggal_po'],
			'nomor_penunjukan'			=> generate_code('tbl_pemenang_pengadaan','nomor_penunjukan',[]),
			'tanggal_penunjukan'		=> date('Y-m-d')
		];
		$_detail 		= get_data('tbl_pemenang_pengadaan_detail','id_pemenang_pengadaan',$dt->id)->row();
		if(isset($_detail->id)) {
			if($_detail->pr_type == setting('pr_biaya')) $data['doc_type'] = 'OA';
			if($data['doc_type'] == 'PO') {
				if($_detail->pr_type == setting('pr_persediaan')) $data['tipe_dokumen']	= setting('po_persediaan');
				else if($_detail->pr_type == setting('pr_jasa')) $data['tipe_dokumen']	= setting('po_jasa');
				else if($_detail->pr_type == setting('pr_biaya')) $data['tipe_dokumen'] = setting('po_biaya');
				else if($_detail->pr_type == setting('pr_asset')) $data['tipe_dokumen'] = setting('po_asset');
			} else {
				if($_detail->pr_type == setting('pr_persediaan')) $data['tipe_dokumen']	= setting('oa_persediaan');
				else if($_detail->pr_type == setting('pr_jasa')) $data['tipe_dokumen']	= setting('oa_jasa');
				else if($_detail->pr_type == setting('pr_asset')) $data['tipe_dokumen'] = setting('oa_asset');
			}
		}
		$vendor 	= get_data('tbl_vendor','id',$dt->id_vendor)->row();
		if(isset($vendor->id)) {
			$data['alamat_vendor']		= $vendor->alamat.', '.$vendor->nama_kelurahan.', '.$vendor->nama_kecamatan.', '.$vendor->nama_kota.', '.$vendor->nama_provinsi;
			$data['kode_vendor']		= $vendor->kode_rekanan;
			$data['kode_sap_vendor']	= $vendor->kode_sap;
		}

		$tanda_tangan 	= get_data('tbl_tanda_tangan a',[
			'join'		=> 'tbl_user b ON a.id_user = b.id TYPE LEFT',
			'select'	=> 'b.nama,b.jabatan',
			'where'		=> '_key = "spk"'
		])->row();
		if(isset($tanda_tangan->nama)) {
			$data['ttd_spk']			= $tanda_tangan->nama;
			$data['jabatan_ttd_spk']	= $tanda_tangan->jabatan;
		}
		$tanda_tangan 	= get_data('tbl_tanda_tangan a',[
			'join'		=> 'tbl_user b ON a.id_user = b.id TYPE LEFT',
			'select'	=> 'b.nama,b.jabatan',
			'where'		=> '_key = "surat_penunjukan"'
		])->row();
		if(isset($tanda_tangan->nama)) {
			$data['ttd_penunjukan']			= $tanda_tangan->nama;
			$data['jabatan_ttd_penunjukan']	= $tanda_tangan->jabatan;
		}
		update_data('tbl_peserta_sanggah',['status_sanggah'=>1],'id_pemenang_pengadaan',$id);
		update_data('tbl_pemenang_pengadaan',$data,'id',$id);
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function pembatalan() {
		$id 	= post('id');
		$dt 	= get_data('tbl_pemenang_pengadaan','id',$id)->row();
		$data 				= [
			'status_sanggah'	=> 9,
		];
		update_data('tbl_peserta_sanggah',['status_sanggah'=>9],'id_pemenang_pengadaan',$id);
		update_data('tbl_pemenang_pengadaan',$data,'id',$id);
		update_data('tbl_pengajuan',['status_desc'=>'Dibatalkan pada proses Sanggah karena sanggah salah satu peserta pengadaan diterima'],'nomor_pengajuan',$dt->nomor_pengadaan);

		$pengajuan 	= get_data('tbl_pengajuan','nomor_pengajuan',$dt->nomor_pengajuan)->row();
		if(isset($pengajuan->id)) {
			update_data('sap_detail',['is_deleted'=>'1','deleted_date'=>date('Y-m-d H:i:s')],'purchase_req_item',$pengajuan->purchase_req_item);
		}

		$vendor 	= get_data('tbl_aanwijzing_vendor',[
			'where'	=> [
				'nomor_pengadaan'	=> $dt->nomor_pengadaan
			]
		])->result();
		$id_vendor 	= [-1];
		foreach($vendor as $v) {
			$id_vendor[] = $v->id_vendor;
		}

		$user 		= get_data('tbl_user','id_vendor',$id_vendor)->result();
		$id_user 	= $email_user = [];
		foreach($user as $u) {
			$id_user[] 		= $u->id;
			$email_user[] 	= $u->email;
		}

		if(count($id_user) > 0) {
			$link				= base_url();
			$desctiption 		= 'Proses Pengadaan <strong>'.$dt->nomor_pengadaan.'</strong> dibatalkan, dikarenakan sanggahan salahsatu peserta pengadaan diterima.';
			foreach($id_user as $iu) {
				$data_notifikasi 	= [
					'title'			=> 'Pengadaan',
					'description'	=> $desctiption,
					'notif_link'	=> $link,
					'notif_date'	=> date('Y-m-d H:i:s'),
					'notif_type'	=> 'danger',
					'notif_icon'	=> 'fa-times-circle',
					'id_user'		=> $iu,
					'transaksi'		=> 'sanggah',
					'id_transaksi'	=> $dt->id
				];
				insert_data('tbl_notifikasi',$data_notifikasi);
			}

			if(setting('email_notification') && count($email_user) > 0) {
				send_mail([
					'subject'		=> 'Pembatalan Pengadaan #'.$pengadaan->nomor_pengadaan,
					'bcc'			=> $email_user,
					'nama_user'		=> '',
					'description'	=> $desctiption,
					'url'			=> $link
				]);
			}
		}

		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function inisiasi_ulang() {
		$nomor_pengadaan	= post('nomor_pengadaan');
		$pengadaan 			= get_data('tbl_pengadaan','nomor_pengadaan',$nomor_pengadaan)->row();
		$nomor_pengajuan 	= $pengadaan->nomor_pengajuan;

		update_data('tbl_pemenang_pengadaan',['inisiasi_ulang'=>1,'status_penetapan'=>9],'nomor_pengadaan',$nomor_pengadaan);
		update_data('tbl_pengajuan',[
			'approve'			=> 0,
			'is_pos_approve'	=> 0,
			'status_desc'		=> 'Diinisiasi Ulang'
		],'nomor_pengajuan',$nomor_pengajuan);
		update_data('tbl_inisiasi_pengadaan',[
			'status'			=> 0,
			'status_proses'		=> 0
		],'nomor_pengajuan',$nomor_pengajuan);
		update_data('tbl_rks',[
			'status'			=> 0,
			'status_proses'		=> 0
		],'nomor_pengajuan',$nomor_pengajuan);
		update_data('tbl_m_hps',[
			'status'			=> 0,
			'status_proses'		=> 0
		],'nomor_pengajuan',$nomor_pengajuan);

		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

}