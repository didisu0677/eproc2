<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aanwijzing extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data($status=1) {
		$config	= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false,
			'button'		=> [
				button_serverside('btn-info',base_url('pengadaan/aanwijzing/detail/'),['fa-search',lang('detil'),true],'btn-detail'),
			]
		];
		if(menu()['access_additional']) {
			$anggota_panitia	= get_data('tbl_anggota_panitia','userid',user('id'))->result();
			$id_panitia			= [0];
			foreach($anggota_panitia as $a) $id_panitia[] = $a->id_m_panitia;
			$config['where']['id_panitia']	= $id_panitia;
		} else {
			$config['where']['id_creator']	= user('id');
		}
		if($status == 1) $config['where']['status_aanwijzing']	= 'AANWIJZING';
		else {
			$config['sort_by'] 	= 'id';
			$config['sort']		= 'DESC';
			$config['where']['status_aanwijzing !=']	= 'AANWIJZING';
		}
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function detail_vendor($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) > 0) {
			include_lang('manajemen_rekanan');
			$data 	= get_data('tbl_vendor','id',$id[0])->row_array();
			render($data,'layout:false');
		} else echo lang('data_tidak_ada');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 							= get_data('tbl_aanwijzing','id = '.$id[0])->row_array();
			if(isset($data['id'])) {
				$this->load->helper('pengadaan');
				$data['tanggal_berita_acara']	= $data['tanggal_berita_acara'] != '0000-00-00' ? c_date($data['tanggal_berita_acara']) : '';
				$data['peserta_berita_acara']	= $data['peserta_berita_acara'] ? json_decode($data['peserta_berita_acara'],true) : [];
				$data['title']				= $data['nomor_aanwijzing'];
				$data['id_rks_pengadaan']	= id_by_nomor($data['nomor_pengajuan'],'rks');
				$data['id_rks_aanwijzing']	= id_by_nomor($data['nomor_pengajuan'],'rks','aanwijzing');
				$data['rks']				= get_data('tbl_rks','nomor_pengajuan = "'.$data['nomor_pengajuan'].'" AND tipe_rks = "aanwijzing"')->row_array();
				if(!isset($data['rks']['id'])) {
					$data['rks']			= get_data('tbl_rks',[
						'where'	=> [
							'nomor_pengajuan'	=> $data['nomor_pengajuan'],
							'tipe_rks'			=> "pengadaan"
						],
						'sort_by'	=> 'id',
						'sort'		=> 'DESC'
					])->row_array();
					$data['rks']['id']			= 0;
					$data['rks']['file']		= '[]';
					$data['rks']['nomor_rks']	= '';
					$data['rks']['tanggal_rks']	= '';
				} else {
					$data['rks']['tanggal_rks']	= c_date($data['rks']['tanggal_rks']);
				}
				$data['jadwal']				= get_data('tbl_m_penjadwalan',[
					'where'					=> [
						'is_active'			=> 1,
						'kata_kunci'		=> ['pemasukan_dokumen','pembukaan_dokumen','evaluasi_penawaran','peninjauan_lapangan','klarifikasi_negosiasi']
					]
				])->result_array();
				foreach($data['jadwal'] as $k => $v) {
					$jadwal 				= get_data('tbl_jadwal_pengadaan',[
						'where'				=> [
							'nomor_pengajuan'	=> $data['nomor_pengajuan'],
							'kata_kunci'		=> $v['kata_kunci']
						]
					])->row();
					$data['jadwal'][$k]['lokasi']			= isset($jadwal->id) ? $jadwal->lokasi : '';
					$data['jadwal'][$k]['tanggal_awal']		= isset($jadwal->id) ? c_date($jadwal->tanggal_awal) : '';
					$data['jadwal'][$k]['tanggal_akhir']	= isset($jadwal->id) ? c_date($jadwal->tanggal_akhir) : '';
					$data['jadwal'][$k]['zona_waktu']		= isset($jadwal->id) ? $jadwal->zona_waktu : '';
				}
				$data['mandatori']			= ['pemasukan_dokumen','pembukaan_dokumen','evaluasi_penawaran','peninjauan_lapangan','klarifikasi_negosiasi'];
				$data['aanwijzing_vendor']	= get_data('tbl_aanwijzing_vendor',[
					'where'		=> [
						'nomor_aanwijzing'	=> $data['nomor_aanwijzing']
					],
					'sort_by'				=> 'id'
				])->result_array();
				$id_vendor					= [0];
				foreach($data['aanwijzing_vendor'] as $a) {
					$id_vendor[]			= $a['id_vendor'];
				}
				$data['vendor']				= get_data('tbl_vendor','id',$id_vendor)->result_array();
				$tanggal_aanwijzing			= get_data('tbl_jadwal_pengadaan','kata_kunci = "aanwijzing" AND nomor_pengajuan = "'.$data['nomor_pengajuan'].'"')->row();
				$data['open_aanwijzing']	= false;
				if(isset($tanggal_aanwijzing->id)) {
					if(strtotime($tanggal_aanwijzing->tanggal_awal) <= strtotime('now')) {
						$data['open_aanwijzing'] 	= true;
					}
					$data['tanggal_aanwijzing']	= c_date($tanggal_aanwijzing->tanggal_awal).' - '.c_date($tanggal_aanwijzing->tanggal_akhir);
				}

				$data['grup_dokumen']			= [
					'persyaratan_peserta'		=> lang('dokumen_hal_yang_menggugurkan'),
					'dokumen_administrasi'		=> lang('dokumen_administrasi'),
					'dokumen_teknis'			=> lang('dokumen_teknis'),
					'dokumen_penawaran_harga'	=> lang('dokumen_penawaran_harga')
				];
				$data['jenis_pengadaan']	= get_data('tbl_jenis_pengadaan a',[
					'select'				=> 'a.id,a.jenis_pengadaan,b.bobot_harga,b.bobot_teknis',
					'join'					=> 'tbl_m_bobot_evaluasi b ON a.id = b.id_jenis_pengadaan type LEFT',
					'where'					=> [
						'a.is_active'		=> 1
					]
				])->result_array();

				$data['panitia_pelaksana']	= get_data('tbl_panitia_pelaksana','nomor_pengajuan',$data['nomor_pengajuan'])->result();
				$pengajuan 					= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
				$data['nama_creator']		= isset($pengajuan->id) ? $pengajuan->create_by : '-';
				$data['nama_panitia']		= isset($pengajuan->id) ? $pengajuan->nama_panitia : 'Panitia';
				$hps 						= get_data('tbl_m_hps','nomor_hps',$pengajuan->no_hps)->row();
				$data['id_hps']				= isset($hps->id) ? $hps->id : 0;

				$user_pengadaan 		= get_data('tbl_alur_persetujuan',[
					'where'				=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan'],
						'jenis_approval'	=> 'PERMINTAAN'
					]
				])->result();
				foreach($user_pengadaan as $k => $u) {
					if(strpos(strtolower($u->nama_persetujuan),'direktur') !== false || strpos(strtolower($u->nama_persetujuan),'direksi') !== false) {
						unset($user_pengadaan[$k]);
					}
				}
				$data['user_pengadaan']	= $user_pengadaan;

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

	function save_rks() {
		$data 							= post();

		$data['syarat_umum'] 			= post('syarat_umum','html');
		$data['syarat_khusus'] 			= post('syarat_khusus','html');
		$data['syarat_teknis'] 			= post('syarat_teknis','html');
		$data['pola_pembayaran'] 		= post('pola_pembayaran','html');
		$data['sanggahan_peserta'] 		= post('sanggahan_peserta','html');

		$data['latar_belakang'] 		= post('latar_belakang','html');
		$data['spesifikasi'] 			= post('spesifikasi','html');
		$data['ruang_lingkup'] 			= post('ruang_lingkup','html');
		$data['distribusi_kebutuhan'] 	= post('distribusi_kebutuhan','html');
		$data['jangka_waktu'] 			= post('jangka_waktu','html');
		$data['jumlah_kebutuhan'] 		= post('jumlah_kebutuhan','html');
		$data['lain_lain'] 				= post('lain_lain','html');

		$data['tipe_rks']				= 'aanwijzing';

		$rks_pengadaan 					= get_data('tbl_rks','nomor_pengajuan = "'.$data['nomor_pengajuan'].'" AND tipe_rks = "pengadaan"')->row_array();
		if(isset($rks_pengadaan['id'])) {
			$data['id_panitia']				= $rks_pengadaan['id_panitia'];
			$data['nama_panitia']			= $rks_pengadaan['nama_panitia'];
			$data['metode_pengadaan']		= $rks_pengadaan['metode_pengadaan'];
			$data['jenis_pengadaan']		= $rks_pengadaan['jenis_pengadaan'];
			$data['bobot_harga']			= $rks_pengadaan['bobot_harga'];
			$data['bobot_teknis']			= $rks_pengadaan['bobot_teknis'];
			$data['nama_tanda_tangan']		= $rks_pengadaan['nama_tanda_tangan'];
			$data['jabatan_tanda_tangan']	= $rks_pengadaan['jabatan_tanda_tangan'];
		}

	    $jadwal 		= post('jadwal');
		$lokasi 		= post('lokasi');
		$tanggal_awal	= post('tanggal_awal');
		$tanggal_akhir	= post('tanggal_akhir');
		$zona_waktu		= post('zona_waktu');

		$last_file 		= [];
		if($data['id']) {
			$dt 		= get_data('tbl_rks','id',$data['id'])->row();
			if(isset($dt->id)) {
				$lf 	= json_decode($dt->file,true);
				foreach($lf as $l) {
					$last_file[$l] = $l;
				}
			}
		}		
		$file 				= post('file');
		$keterangan_file 	= post('keterangan_file');
		$filename 			= [];
		$dir 				= '';
		if(isset($file) && is_array($file)) {
			foreach($file as $k => $f) {
				if(strpos($f,'exist:') !== false) {
					$orig_file = str_replace('exist:','',$f);
					if(isset($last_file[$orig_file])) {
						unset($last_file[$orig_file]);
						$filename[$keterangan_file[$k]]	= $orig_file;
					}
				} else {
					if(file_exists($f)) {
						if(@copy($f, FCPATH . 'assets/uploads/rks/'.basename($f))) {
							$filename[$keterangan_file[$k]]	= basename($f);
							if(!$dir) $dir = str_replace(basename($f),'',$f);
						}
					}
				}
			}
		}
		foreach($last_file as $lf) {
			@unlink(FCPATH . 'assets/uploads/rks/' . $lf);
		}
		$data['file']					= json_encode($filename);
		if($dir) {
			delete_dir(FCPATH . $dir);
		}

		$response 	= save_data('tbl_rks',$data,post(':validation'),menu()['access_additional']);
		if($response['status'] == 'success') {
			$dt_rks = get_data('tbl_rks','id',$response['id'])->row();

			delete_data('tbl_jadwal_pengadaan',[
				'nomor_pengajuan'	=> $data['nomor_pengajuan'],
				'kata_kunci'		=> ['pemasukan_dokumen','pembukaan_dokumen','evaluasi_penawaran','on_the_spot','klarifikasi_negosiasi']
			]);
			$c = array();
			foreach($jadwal as $k => $v) {
				if($tanggal_awal[$k] && $tanggal_akhir[$k]) {
					$dt_jadwal		= get_data('tbl_m_penjadwalan','id',$jadwal[$k])->row();
					$tawal 			= date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $tanggal_awal[$k])));
					$takhir 		= date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $tanggal_akhir[$k])));
					$c[]			= array(
						'nomor_pengajuan'	=> $dt_rks->nomor_pengajuan,
						'id_m_penjadwalan'	=> $jadwal[$k],
						'kata_kunci'		=> isset($dt_jadwal->id) ? $dt_jadwal->kata_kunci : '',
						'kode_jadwal'		=> isset($dt_jadwal->id) ? $dt_jadwal->kode : '',
						'nama_jadwal'		=> isset($dt_jadwal->id) ? $dt_jadwal->jadwal : '',
						'lokasi'			=> $lokasi[$k],
						'tanggal_awal'		=> $tawal,
						'tanggal_akhir'		=> $takhir,
						'zona_waktu'		=> $zona_waktu[$k]
					);
				}
			}
			if(count($c))
				insert_batch('tbl_jadwal_pengadaan',$c);

			$awz 	= get_data('tbl_aanwijzing',[
				'where'	=> [
					'nomor_pengajuan'		=> $data['nomor_pengajuan'],
					'status_aanwijzing'		=> 'AANWIJZING'
				]
			])->row();

			if(isset($awz->nomor_pengadaan)) {
				update_data('tbl_aanwijzing',['status_rks'=>1],'nomor_pengadaan',$awz->nomor_pengadaan);
				update_data('tbl_aanwijzing_vendor',['status_rks'=>1],'nomor_pengadaan',$awz->nomor_pengadaan);
			}
		}
		render($response,'json');		
	}

	function save_berita_acara() {
		$post 	= post();
		$id 	= post('id_aanwijzing');
		$aanwijzing 					= get_data('tbl_aanwijzing','id',$id)->row();
		$data['id']						= $id;
		$data['tanggal_berita_acara']	= $post['tanggal_berita_acara'];
		$data['lokasi_berita_acara']	= $post['lokasi_berita_acara'];
		$data['zona_waktu']				= $post['zona_waktu'];
		if(!$aanwijzing->nomor_berita_acara) {
			$data['nomor_berita_acara']	= generate_code('tbl_aanwijzing','nomor_berita_acara',$post);
		}
		$id_vendor_ba					= post('id_vendor_ba');
		$vendor_ba 						= post('vendor_ba');
		$hadir 							= post('hadir');
		$nama_perwakilan 				= post('nama_perwakilan_ba');
		$dt 							= [];
		foreach($id_vendor_ba as $v) {
			if(isset($hadir[$v]) && $hadir[$v]) {
				$dt[$v]	= [
					'vendor'			=> $vendor_ba[$v],
					'nama_perwakilan'	=> $nama_perwakilan[$v]
				];
			}
		}
		$data['peserta_berita_acara']	= json_encode($dt);
		$data['status_berita_acara']	= 1;
		$save 							= update_data('tbl_aanwijzing',$data,'id',$id);
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function proses() {
		$aanwijzing 	= get_data('tbl_aanwijzing','id',post('id_awz'))->row();
		if($aanwijzing->status_berita_acara && $aanwijzing->status_rks) {
			$_id_anggota	= post('id_anggota');
			$_nm_anggota 	= post('anggota');

			$peserta_lain	= [];
			if(is_array($_id_anggota) && count($_id_anggota) > 0) {
				foreach($_id_anggota as $k => $v) {
					if($v) {
						$peserta_lain[$v]	= $_nm_anggota[$k];
					}
				}
			}
			$_peserta_lain 	= json_encode($peserta_lain);
			$kadiv_ppat 	= get_data('tbl_user',[
				'where'	=> [
					'is_active'	=> 1,
					'jabatan'	=> ['kadiv PPAT','ketua PPAT','ketua divisi PPAT','kepala PPAT','kepala divisi PPAT']
				]
			])->row();
			$cur_kadiv_ppat	= isset($kadiv_ppat->id) ? $kadiv_ppat->nama : '';

			update_data('tbl_aanwijzing',['status_aanwijzing'=>'PENAWARAN','peserta_lain_penawaran'=>$_peserta_lain,'cur_kadiv_ppat'=>$cur_kadiv_ppat],'nomor_aanwijzing',$aanwijzing->nomor_aanwijzing);
			update_data('tbl_aanwijzing_vendor',['status_aanwijzing'=>'PENAWARAN'],'nomor_aanwijzing',$aanwijzing->nomor_aanwijzing);
			update_data('tbl_chat_key',['is_active'=>0],'id',$aanwijzing->id_chat);

			update_data('tbl_pengadaan',['status_pengadaan'=>'PENAWARAN'],'nomor_pengadaan',$aanwijzing->nomor_pengadaan);
			update_data('tbl_pengadaan_detail',['status_pengadaan'=>'PENAWARAN'],'nomor_pengadaan',$aanwijzing->nomor_pengadaan);
			update_data('tbl_pengadaan_bidder',['status_pengadaan'=>'PENAWARAN'],'nomor_pengadaan',$aanwijzing->nomor_pengadaan);

			update_data('tbl_pengajuan',['status_desc'=>'Penawaran'],'nomor_pengajuan',$aanwijzing->nomor_pengajuan);

			$aanwijzing_vendor 	= get_data('tbl_aanwijzing_vendor','nomor_aanwijzing',$aanwijzing->nomor_aanwijzing)->result();
			$id_vendor 			= [];
			foreach($aanwijzing_vendor as $a) {
				$id_vendor[] 	= $a->id_vendor;
			}

			$user_id 			= $user_email = $user_nama = [];
			if(count($id_vendor)) {
				$user 				= get_data('tbl_user','id_vendor',$id_vendor)->result();
				foreach($user as $u) {
					$user_id[] 		= $u->id;
					$user_email[] 	= $u->email;
					$user_nama[] 	= $u->nama;
				}
			}

			if(count($user_id)) {
				$link				= base_url().'pengadaan_v/penawaran_v/ref/'.encode_id([$aanwijzing->id,rand()]);
				$desctiption 		= 'Pengadaan dengan nomor '.$aanwijzing->nomor_pengadaan.' membutuhkan file penawaran dari anda';
				foreach($user_id as $i) {
					$data_notifikasi 	= [
						'title'			=> 'Penawaran Pengadaan',
						'description'	=> $desctiption,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'info',
						'notif_icon'	=> 'fa-file-alt',
						'id_user'		=> $i,
						'transaksi'		=> 'aanwijzing',
						'id_transaksi'	=> post('id')
					];
					insert_data('tbl_notifikasi',$data_notifikasi);	
				}

				if(setting('email_notification') && count($user_email) ) {
					send_mail([
						'subject'		=> 'Pembukaan Dokumen Pengadaan #'.$aanwijzing->nomor_pengadaan,
						'bcc'			=> $user_email,
						'nama_user'		=> '',
						'description'	=> $desctiption,
						'url'			=> $link
					]);
				}
			}

			// create grup chat penawaran
			$id_anggota	= $anggota_chat = [];
			$panitia 	= get_data('tbl_panitia_pelaksana',[
				'where'	=> [
					'id_m_panitia'		=> $aanwijzing->id_panitia,
					'nomor_pengajuan'	=> $aanwijzing->nomor_pengajuan
				]
			])->result();

			foreach($panitia as $p) {
				$id_anggota[] 	= $p->userid;
				$anggota_chat[]	= $p->nama_panitia;
			}

			foreach($peserta_lain as $i => $n) {
				$id_anggota[] 				= $i;
				$anggota_chat[]				= $n;
			}

			$creator 				= get_data('tbl_user','id',$aanwijzing->id_creator)->row();
			$id_anggota[]			= $creator->id;
			$anggota_chat[]			= $creator->nama;

			$user_pengadaan 		= get_data('tbl_alur_persetujuan',[
				'where'				=> [
					'nomor_pengajuan'	=> $aanwijzing->nomor_pengajuan,
					'jenis_approval'	=> 'PERMINTAAN'
				]
			])->result();
			foreach($user_pengadaan as $k => $u) {
				if(strpos(strtolower($u->nama_persetujuan),'direktur') === false || strpos(strtolower($u->nama_persetujuan),'direksi') === false) {
					$id_anggota[]	= $u->id_user;
					$anggota_chat[]	= $u->nama_user;
				}
			}

			if($aanwijzing->tipe_pengadaan == 'Tender') {
				foreach($user_id as $i) $id_anggota[] 	= $i;
				foreach($user_nama as $u) $anggota_chat[] = $u;
			}

			$jadwal 				= get_data('tbl_jadwal_pengadaan','kata_kunci = "pembukaan_dokumen" AND nomor_pengajuan = "'.$aanwijzing->nomor_pengajuan.'"')->row();

			$data_chat 		= [
				'nama'		=> 'Pembukaan Dokumen Penawaran '.$aanwijzing->nomor_pengadaan,
				'anggota'	=> implode(', ', $anggota_chat),
				'is_group'	=> 1,
				'is_active'	=> 1
			];
			if(isset($jadwal->id)) {
				$data_chat['aktif_mulai']	= $jadwal->tanggal_awal;
				$data_chat['aktif_selesai']	= $jadwal->tanggal_akhir;
			}

			$chat 			= insert_data('tbl_chat_key',$data_chat);

			foreach($id_anggota as $i) {
				insert_data('tbl_chat_anggota',[
					'key_id'	=> $chat,
					'id_user'	=> $i,
					'is_read'	=> 1
				]);
			}
			update_data('tbl_aanwijzing',['id_chat_penawaran'=>$chat],'id',$aanwijzing->id);

			render([
				'status' => 'success',
				'message' => lang('data_berhasil_disimpan')
			],'json');
		} else {
			render([
				'status' => 'failed',
				'message' => lang('data_gagal_disimpan')
			],'json');
		}
	}

	function cetak_rks($encode_id=''){
		$decode = decode_id($encode_id);
		if(count($decode) == 2) {
			$id 						= $decode[0];
			$record						= get_data('tbl_rks','id',$id)->row_array();
			$record['bobot_harga']		= c_percent($record['bobot_harga']);
			$record['bobot_teknis']		= c_percent($record['bobot_teknis']);
			$tanggal_rks				= $record['tanggal_rks'];

			$aanwijzing					= get_data('tbl_jadwal_pengadaan',[
				'where'					=> [
					'nomor_pengajuan'	=> $record['nomor_pengajuan'],
					'kata_kunci'		=> 'aanwijzing'
				]
			])->row();
			$pemasukan_dokumen			= get_data('tbl_jadwal_pengadaan',[
				'where'					=> [
					'nomor_pengajuan'	=> $record['nomor_pengajuan'],
					'kata_kunci'		=> 'pemasukan_dokumen'
				]
			])->row();
			$pembukaan_dokumen			= get_data('tbl_jadwal_pengadaan',[
				'where'					=> [
					'nomor_pengajuan'	=> $record['nomor_pengajuan'],
					'kata_kunci'		=> 'pembukaan_dokumen'
				]
			])->row();

			$record['hari_aanwijzing']		= !isset($aanwijzing->id) ? '-' : hari($aanwijzing->tanggal_awal);
			$record['tanggal_aanwijzing']	= !isset($aanwijzing->id) ? '-' : date_indo($aanwijzing->tanggal_awal, false);
			$record['jam_aanwijzing']		= !isset($aanwijzing->id) ? '-' : date('H:i',strtotime($aanwijzing->tanggal_awal)).' '.$aanwijzing->zona_waktu.' s/d Selesai';

			$record['tanggal_mulai_pemasukan_dokumen']		= !isset($pemasukan_dokumen->id) ? '-' : hari($pemasukan_dokumen->tanggal_awal).', '.date_indo($pemasukan_dokumen->tanggal_awal).' '.$pemasukan_dokumen->zona_waktu;
			$record['tanggal_selesai_pemasukan_dokumen']	= !isset($pemasukan_dokumen->id) ? '-' : hari($pemasukan_dokumen->tanggal_akhir).', '.date_indo($pemasukan_dokumen->tanggal_akhir).' '.$pemasukan_dokumen->zona_waktu;

			$record['hari_pembukaan_dokumen']				= !isset($pembukaan_dokumen->id) ? '-' : hari($pembukaan_dokumen->tanggal_awal);
			$record['tanggal_pembukaan_dokumen']			= !isset($pembukaan_dokumen->id) ? '-' : date_indo($pembukaan_dokumen->tanggal_awal, false);
			$record['jam_pembukaan_dokumen']				= !isset($pembukaan_dokumen->id) ? '-' : date('H:i',strtotime($pembukaan_dokumen->tanggal_awal)).' '.$pembukaan_dokumen->zona_waktu;

			$record['nama_pemberi_tugas']	= $record['nama_tanda_tangan'];
			$record['jabatan_pemberi_tugas']= $record['jabatan_tanda_tangan'];

			$inisiasi 						= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$record['nomor_pengajuan'])->row();
			$record['minimal_rekanan_mengikuti']	= '-';
			$record['minimal_rekanan_sah']			= '-';
			if(isset($inisiasi->id)) {
				if($inisiasi->tipe_pengadaan == 'Lelang') {
					$record['minimal_rekanan_mengikuti']	= setting('min_memasukan_lelang').' ('.terbilang(setting('min_memasukan_lelang')).')';
					$record['minimal_rekanan_sah']			= setting('min_pengadaan_lelang').' ('.terbilang(setting('min_pengadaan_lelang')).')';
				} elseif($inisiasi->tipe_pengadaan == 'Pemilihan Langsung') {
					$record['minimal_rekanan_mengikuti']	= setting('min_memasukan_pemilihan_langsung').' ('.terbilang(setting('min_memasukan_pemilihan_langsung')).')';
					$record['minimal_rekanan_sah']			= setting('min_pengadaan_pemilihan_langsung').' ('.terbilang(setting('min_pengadaan_pemilihan_langsung')).')';
				} elseif($inisiasi->tipe_pengadaan == 'Penunjukan Langsung') {
					$record['minimal_rekanan_mengikuti']	= setting('min_memasukan_penunjukan_langsung').' ('.terbilang(setting('min_memasukan_penunjukan_langsung')).')';
					$record['minimal_rekanan_sah']			= setting('min_pengadaan_penunjukan_langsung').' ('.terbilang(setting('min_pengadaan_penunjukan_langsung')).')';
				}
			}

			$jaminan						= get_data('tbl_m_definisi_pasal','is_active',1)->result_array();
			$r['jaminan']					= [];
			foreach($jaminan as $j)	$r['jaminan'][$j['kata_kunci']] = $j['deskripsi'];
			$r['jaminan_penawaran']			= $record['jaminan_penawaran'];
			$r['jaminan_pelaksanaan']		= $record['jaminan_pelaksanaan'];
			$r['jaminan_pemeliharaan']		= $record['jaminan_pemeliharaan'];

			$r_sanggah['deskripsi']			= '';
			$r_sanggah['active']			= $record['sanggahan_peserta'];
			if(isset($r['jaminan']['sanggah'])) $r_sanggah['deskripsi']	= $r['jaminan']['sanggah'];

			$record['jaminan_pengadaan']	= include_view('pengadaan/rks/view_jaminan',$r);
			$record['sanggahan_peserta']	= include_view('pengadaan/rks/view_sanggah',$r_sanggah);
			$record['batas_hps_atas']		= c_percent($record['batas_hps_atas']);
			$record['batas_hps_bawah']		= c_percent($record['batas_hps_bawah']);
			$record['tanggal_rks']			= date_indo($record['tanggal_rks']);
			$data['html']					= template_pdf($record,'rks_aanwijzing',$tanggal_rks);
			render($data,'pdf');
		} else {
			render('404');
		}
	}

	function cetak_berita_acara($encode_id='') {
		$decode = decode_id($encode_id);
		if(count($decode) == 2) {
			$data 	= get_data('tbl_aanwijzing','id',$decode[0])->row_array();
			if(isset($data['id'])) {
				$pengumuman 	= get_data('tbl_jadwal_pengadaan',[
					'where'	=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan'],
						'kata_kunci'		=> 'spph'
					]
				])->row();
				$data['tanggal_pengumuman']	= isset($pengumuman->id) ? $pengumuman->tanggal_awal : date('Y-m-d H:i:s');
				$data['panitia']			= get_data('tbl_panitia_pelaksana',[
					'where'					=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan']
					],
					'sort_by'				=> 'id'
				])->result_array();
				$creator 	= get_data('tbl_user','id',$data['id_creator'])->row();
				$data['nama_creator']		= isset($creator->id) ? $creator->nama : 'Creator';
				$user_pengadaan 		= get_data('tbl_alur_persetujuan',[
					'where'				=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan'],
						'jenis_approval'	=> 'PERMINTAAN'
					]
				])->result();
				foreach($user_pengadaan as $k => $u) {
					if(strpos(strtolower($u->nama_persetujuan),'direktur') !== false || strpos(strtolower($u->nama_persetujuan),'direksi') !== false ) {
						unset($user_pengadaan[$k]);
					}
				}
				$data['user_pengadaan']	= $user_pengadaan;
				render($data,'pdf');
			} else render('404');
		} else render('404');
	}

	function get_user($id='') {
		$aanwijzing				= get_data('tbl_aanwijzing','id = '.$id.'')->row_array();
		$user_auto_terdaftar 	= '';
		if(isset($aanwijzing['id'])) {
			$panitia 			= get_data('tbl_panitia_pelaksana','nomor_pengajuan',$aanwijzing['nomor_pengajuan'])->result();
			$userid 			= [$aanwijzing['id_creator']];
			foreach($panitia as $p) {
				$userid[]		= $p->userid;
			}
			$user_pengadaan 		= get_data('tbl_alur_persetujuan',[
				'where'				=> [
					'nomor_pengajuan'	=> $aanwijzing['nomor_pengajuan'],
					'jenis_approval'	=> 'PERMINTAAN'
				]
			])->result();
			foreach($user_pengadaan as $k => $u) {
				if(strpos(strtolower($u->nama_persetujuan),'direktur') === false ) {
					$userid[]	= $u->id_user;
				}
			}

			$user_auto_terdaftar= implode(',', $userid);
		}
		$query 	= get('query');
		if($user_auto_terdaftar) {
			$user 	= get_data('tbl_user','is_active=1 AND nama LIKE "%'.$query.'%" AND id_vendor = 0 AND id NOT IN ('.$user_auto_terdaftar.')')->result();
		} else {
			$user 	= get_data('tbl_user','is_active=1 AND nama LIKE "%'.$query.'%" AND id_vendor = 0')->result();
		}
		$data['suggestions'] = [];
		foreach($user as $u) {
			$data['suggestions'][] = [
				'value'	=> $u->nama,
				'data'	=> $u->id
			];
		}
		render($data,'json');
	}

	function get_inisiasi() {
		$data = get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',post('nomor_pengajuan'))->row_array();
		$grup_dokumen					= [
			'persyaratan_peserta'		=> lang('dokumen_hal_yang_menggugurkan'),
			'dokumen_administrasi'		=> lang('dokumen_administrasi'),
			'dokumen_teknis'			=> lang('dokumen_teknis'),
			'dokumen_penawaran_harga'	=> lang('dokumen_penawaran_harga')
		];
		$data['dokumen_persyaratan']	= [];
		$data['mandatori']				= [];
		foreach($grup_dokumen as $k => $v) {
			$data['dokumen_persyaratan'][$k][0]	= get_data('tbl_dokumen_persyaratan',[
				'where'					=> [
					'grup'				=> $k,
					'parent_id'			=> 0,
					'nomor_pengajuan'	=> $data['nomor_pengajuan']
				],
				'sort_by'				=> 'id'
			])->result_array();
			$data['mandatori'][$k]		= 0;
			if(isset($data['dokumen_persyaratan'][$k][0][0])) $data['mandatori'][$k]	= $data['dokumen_persyaratan'][$k][0][0]['mandatori'];
			foreach ($data['dokumen_persyaratan'][$k][0] as $key => $value) {
				$data['dokumen_persyaratan'][$k][$value['id']]	= get_data('tbl_dokumen_persyaratan',[
					'where'					=> [
						'grup'				=> $k,
						'parent_id'			=> $value['id'],
						'nomor_pengajuan'	=> $data['nomor_pengajuan']
					],
					'sort_by'				=> 'id'
				])->result_array();
			}
		}

		$data['pembobotan'][0]			= get_data('tbl_pembobotan','nomor_pengajuan = "'.$data['nomor_pengajuan'].'" AND parent_id = 0')->result_array();
		foreach($data['pembobotan'][0] as $p) {
			$data['pembobotan'][$p['id']]	= get_data('tbl_pembobotan',[
				'where'				=> [
					'nomor_pengajuan'	=> $data['nomor_pengajuan'],
					'parent_id'			=> $p['id']
				],
				'sort_by'			=> 'id'
			])->result_array();
		}
		render($data,'json');
	}

	function save_inisiasi() {
		$data = [
			'nomor_pengajuan'			=> post('awz_nomor_pengajuan'),
			'ketentuan_bank_garansi'	=> str_replace(',', '.', post('ketentuan_bank_garansi')),
			'id_jenis_pengadaan'		=> post('id_jenis_pengadaan'),
			'bobot_harga'				=> str_replace(',', '.', post('bobot_harga')),
			'bobot_teknis'				=> str_replace(',', '.', post('bobot_teknis'))
		];
		$dt_jenis_pengadaan				= get_data('tbl_jenis_pengadaan','id',$data['id_jenis_pengadaan'])->row();
		if (isset($dt_jenis_pengadaan->id)){
			$data['jenis_pengadaan']	= $dt_jenis_pengadaan->jenis_pengadaan;
		}
		$save 	= update_data('tbl_inisiasi_pengadaan',$data,'nomor_pengajuan',$data['nomor_pengajuan']);
		if($save) {
			delete_data('tbl_dokumen_persyaratan','nomor_pengajuan',$data['nomor_pengajuan']);
			delete_data('tbl_pembobotan','nomor_pengajuan',$data['nomor_pengajuan']);
			$grup = [
				'persyaratan_peserta','dokumen_administrasi','dokumen_teknis','dokumen_penawaran_harga'
			];

			$nomor_pengajuan	= $data['nomor_pengajuan'];
			$mandatori			= post('mandatori');
			$deskripsi			= post('deskripsi');
			$save_id 			= [];
			foreach($grup as $g) {
				if(isset($deskripsi[$g])) {
					foreach ($deskripsi[$g][0] as $key => $value) {
						$data 	= [
							'parent_id'			=> 0,
							'grup'				=> $g,
							'mandatori'			=> isset($mandatori[$g]) ? $mandatori[$g] : 0,
							'deskripsi'			=> $value,
							'nomor_pengajuan'	=> $nomor_pengajuan
						];
						$save_parent	= insert_data('tbl_dokumen_persyaratan',$data);
						$save_id[$key]	= $save_parent;
						if($save_parent && isset($deskripsi[$g][$key])) {
							foreach ($deskripsi[$g][$key] as $key2 => $value2) {
								$data 	= [
									'parent_id'			=> $save_parent,
									'grup'				=> $g,
									'mandatori'			=> isset($mandatori[$g]) ? $mandatori[$g] : 0,
									'deskripsi'			=> $value2,
									'nomor_pengajuan'	=> $nomor_pengajuan
								];
								insert_data('tbl_dokumen_persyaratan',$data);
							}
						}
					}
				}
			}
			$idx 				= post('idx');
			$detil_deskripsi	= post('detil_bobot_keterangan');
			$tipe_rumus 		= post('cara_perhitungan');
			$detil_bobot 		= post('detail_bobot');

			$child_deskripsi 	= post('child_deskripsi');
			$child_batas_bawah	= post('child_batas_bawah');
			$child_batas_atas 	= post('child_batas_atas');
			$child_bobot 		= post('child_bobot');

			foreach($idx as $key => $value) {
				$id_persyaratan 		= $save_id[$value];
				$data 	= [
					'nomor_pengajuan'	=> $nomor_pengajuan,
					'id_persyaratan'	=> $id_persyaratan,
					'deskripsi'			=> $detil_deskripsi[$key],
					'tipe_rumus'		=> $tipe_rumus[$key],
					'bobot'				=> str_replace(',', '.', $detil_bobot[$key])
				];
				$save_bobot 	= insert_data('tbl_pembobotan',$data);

				foreach($child_bobot[$value] as $key2 => $value2) {
					$data 	= [
						'nomor_pengajuan'	=> $nomor_pengajuan,
						'id_persyaratan'	=> $id_persyaratan,
						'parent_id'			=> $save_bobot,
						'bobot'				=> $child_bobot[$value][$key2]
					];
					if($tipe_rumus[$key] == 'range') {
						$data['batas_bawah']	= $child_batas_bawah[$value][$key2];
						$data['batas_atas']		= $child_batas_atas[$value][$key2];
					} else {
						$data['deskripsi']	= $child_deskripsi[$value][$key2];
					}
					insert_data('tbl_pembobotan',$data);
				}
			}
		}
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

}