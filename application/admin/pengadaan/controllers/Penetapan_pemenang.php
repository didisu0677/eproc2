<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penetapan_pemenang extends BE_Controller {

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
		$config['where']['id_panitia']	= $id_panitia;
		$config['access_edit'] 			= false;
		$config['access_delete'] 		= false;
		$config['access_view'] 			= false;
		$config['button'][]				= button_serverside('btn-info',base_url('pengadaan/penetapan_pemenang/detail/'),['fa-search',lang('detil'),true],'btn-detail');

		if($status == 1) $config['where']['status_penetapan']	= '0';
		else {
			$config['sort_by'] 	= 'id';
			$config['sort']		= 'DESC';
			$config['where']['status_penetapan !=']				= '0';
		}

		$data 	= data_serverside($config);
		render($data,'json');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_pemenang_pengadaan','id = '.$id[0])->row_array();
			if(isset($data['id'])) {
				$_detail 		= get_data('tbl_pemenang_pengadaan_detail','id_pemenang_pengadaan',$data['id'])->row();
				$data['pr_type']			= $_detail->pr_type;
				$data['chk_kontrak']		= $data['pr_type'] == setting('pr_biaya') ? 'disabled' : '';
				$data['kelompok_pembelian']	= get_data('tbl_m_kelompok_pembelian')->result_array();
				$data['title']	= $data['nomor_pengadaan'];
				render($data);
			} else render('404');
		} else render('404');
	}

	function save() {
		$data 			= post();
		$data['status_penetapan']	= 1;
		$data['tanggal_pengumuman']	= date('Y-m-d');
		$data['nomor_pengumuman']	= generate_code('tbl_pemenang_pengadaan','nomor_pengumuman',$data);
		$tanda_tangan 	= get_data('tbl_tanda_tangan a',[
			'join'		=> 'tbl_user b ON a.id_user = b.id TYPE LEFT',
			'select'	=> 'b.nama,b.jabatan',
			'where'		=> '_key = "penetapan_pemenang"'
		])->row();
		if(isset($tanda_tangan->nama)) {
			$data['tanda_tangan']			= $tanda_tangan->nama;
			$data['jabatan_tanda_tangan']	= $tanda_tangan->jabatan;
		}
		update_data('tbl_pemenang_pengadaan',$data,'id',$data['id']);
		$pemenang_pengadaan 	= get_data('tbl_pemenang_pengadaan','id',$data['id'])->row();
		$vendor 				= get_data('tbl_aanwijzing_vendor','nomor_pengadaan',$pemenang_pengadaan->nomor_pengadaan)->result();
		$data_vendor 			= [];
		$id_vendor 				= [-1];
		foreach($vendor as $v) {
			if($v->id_vendor != $pemenang_pengadaan->id_vendor) {
				$data_vendor[] 		= [
					'id_pemenang_pengadaan'		=> $data['id'],
					'nomor_pengajuan'			=> $pemenang_pengadaan->nomor_pengajuan,
					'nomor_pengadaan'			=> $pemenang_pengadaan->nomor_pengadaan,
					'tanggal_pengadaan'			=> $pemenang_pengadaan->tanggal_pengadaan,
					'nama_pengadaan'			=> $pemenang_pengadaan->nama_pengadaan,
					'keterangan_pengadaan'		=> $pemenang_pengadaan->keterangan_pengadaan,
					'id_vendor'					=> $v->id_vendor,
					'nama_vendor'				=> $v->nama_vendor
				];
			}
			$id_vendor[]		= $v->id_vendor;
		}
		if(count($data_vendor)) {
			insert_batch('tbl_peserta_sanggah',$data_vendor);
		}
		$id_user 				= $email_user = [];
		$user 					= get_data('tbl_user','id_vendor',$id_vendor)->result();
		foreach($user as $u) {
			$id_user[] 			= $u->id;
			$email_user[]		= $u->email;
		}
		if(count($id_user)) {
			$link				= base_url().'pengadaan/penetapan_pemenang/cetak/'.encode_id([$data['id'],rand()]);
			$description 		= 'Pengumuman penetapan pemenang pengadaan dengan no. <strong>'.$pemenang_pengadaan->nomor_pengadaan.'</strong>';
			foreach($id_user as $iu) {
				$data_notifikasi 	= [
					'title'			=> 'Penetapan Pemenang',
					'description'	=> $description,
					'notif_link'	=> $link,
					'notif_date'	=> date('Y-m-d H:i:s'),
					'notif_type'	=> 'info',
					'notif_icon'	=> 'fa-file-alt',
					'id_user'		=> $iu,
					'transaksi'		=> 'persetujuan_pemenang',
					'id_transaksi'	=> $pemenang_pengadaan->id
				];
				insert_data('tbl_notifikasi',$data_notifikasi);
			}

			if(setting('email_notification') && count($email_user)) {
				send_mail([
					'subject'		=> 'Penetapan Pemenang #'.$pemenang_pengadaan->nomor_pengadaan,
					'bcc'			=> $email_user,
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

	function surat_persetujuan($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_pemenang_pengadaan','id = '.$id[0].' AND approve = 1')->row_array();
			if(isset($data['id'])) {
				$data['aanwijzing']		= get_data('tbl_aanwijzing','nomor_pengadaan',$data['nomor_pengadaan'])->row();
				$data['klarifikasi']	= get_data('tbl_klarifikasi','nomor_pengadaan',$data['nomor_pengadaan'])->row();
				$data['rks']			= get_data('tbl_rks',[
					'where'				=> [
						'nomor_pengadaan'	=> $data['nomor_pengadaan'],
						'tipe_rks'			=> 'klarifikasi'
					]
				])->row();
				$data['persetujuan']	= get_data('tbl_alur_persetujuan',[
					'where'				=> [
						'nomor_pengadaan'	=> $data['nomor_pengadaan'],
						'jenis_approval'	=> 'PEMENANG'
					],
					'sort_by'			=> 'level_persetujuan'
				])->result();
				render($data,'pdf');
			} else render('404');
		} else render('404');
	}

	function cetak($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_pemenang_pengadaan','id = '.$id[0].' AND lama_sanggah > 0')->row_array();
			if(isset($data['id'])) {
				$data['inisiasi']	= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
				$data['vendor']		= get_data('tbl_aanwijzing_vendor','nomor_pengadaan',$data['nomor_pengadaan'])->result_array();
				render($data,'pdf');
			} else render('404');
		} else render('404');
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

	function proses_spk() {
		$post 				= post();
		$nomor_pengadaan 	= post('nomor_pengadaan');
		$dt 				= get_data('tbl_pemenang_pengadaan','nomor_pengadaan',$nomor_pengadaan)->row();
		$gt 				= get_data('tbl_aanwijzing_vendor',[
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

		$pejabat_ttd 		= get_data('tbl_alur_persetujuan',[
			'where'	=> [
				'nomor_pengadaan'		=> $nomor_pengadaan,
				'jenis_approval'		=> 'PEMENANG'
			],
			'sort_by'=>'level_persetujuan',
			'sort'=>'DESC'
		])->row();
		if(isset($pejabat_ttd->id)) {
			$data['ttd_penunjukan']			= $pejabat_ttd->nama_user;
			$data['jabatan_ttd_penunjukan']	= $pejabat_ttd->nama_persetujuan;
			$data['ttd_spk']				= $pejabat_ttd->nama_user;
			$data['jabatan_ttd_spk']		= $pejabat_ttd->nama_persetujuan;
		} else {
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
		}
		$data['status_penetapan']	= 1;
		
		$data['ttd_spk']			= post('ttd_spk');
		$data['jabatan_ttd_spk']	= post('jabatan_ttd_spk');
		$data['lokasi_ttd_spk']		= post('lokasi_ttd_spk');

		debug($dt);die;
		debug($data);die;
		update_data('tbl_pemenang_pengadaan',$data,'id',$dt->id);
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}
}