<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rks extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['jadwal']			= get_data('tbl_m_penjadwalan','is_active',1)->result_array();
		$data['mandatori']		= ['spph','pendaftaran_pengadaan','aanwijzing','pemasukan_dokumen','pembukaan_dokumen'];
		render($data);
	}

	function data() {
		$config				= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false,
			'where'			=> [
				'tipe_rks'	=> 'pengadaan'
			]
		];
		if(user('id_group') > 2) {
			$id_panitia 	= [0];
			$panitia 		= get_data('tbl_panitia_pelaksana','userid',user('id'))->result();
			foreach($panitia as $ci) {
				$id_panitia[]	= $ci->id_m_panitia;
			}
			$config['where_in']['id_panitia']	= $id_panitia;
		}
		if(menu()['access_additional']) {
			$config['button'][]	= button_serverside('btn-success','btn-print',['fa-print',lang('cetak_rks'),true],'act-print');
		}
		if(menu()['access_edit']) {
			$config['button'][]	= button_serverside('btn-warning','btn-input',['fa-edit',lang('ubah'),true],'edit',['status_proses'=>[0,8]]);
		}
		if(menu()['access_delete']) {
			$config['button'][]	= button_serverside('btn-danger','btn-delete',['fa-trash-alt',lang('hapus'),true],'delete',['status_proses'=>[0,8]]);
		}
		$data 					= data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data					= get_data('tbl_rks a','id',post('id'))->row_array();
		$data['file'] 			= json_decode($data['file'],true);
		$data['detail']			= get_data('tbl_jadwal_pengadaan','nomor_pengajuan',$data['nomor_pengajuan'])->result_array();
		render($data,'json');
	}

	function save() {
		$data 					= post();
		$last 					= get_data('tbl_rks','nomor_pengajuan',$data['nomor_pengajuan'])->row();
		if(isset($last->id)) {
			$data['id']	= $last->id;
		}

		$data['syarat_umum'] 			= post('syarat_umum','html');
		$data['syarat_khusus'] 			= post('syarat_khusus','html');
		$data['syarat_teknis'] 			= post('syarat_teknis','html');
		$data['pola_pembayaran'] 		= post('pola_pembayaran','html');

		$data['latar_belakang'] 		= post('latar_belakang','html');
		$data['spesifikasi'] 			= post('spesifikasi','html');
		$data['ruang_lingkup'] 			= post('ruang_lingkup','html');
		$data['distribusi_kebutuhan'] 	= post('distribusi_kebutuhan','html');
		$data['jangka_waktu'] 			= post('jangka_waktu','html');
		$data['jumlah_kebutuhan'] 		= post('jumlah_kebutuhan','html');
		$data['lain_lain'] 				= post('lain_lain','html');

		$data['tipe_rks']				= 'pengadaan';

		$pengajuan 						= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
		if(isset($pengajuan->id)) {
			$delegasi 					= get_data('tbl_delegasi_pengadaan','nomor_delegasi',$pengajuan->nomor_delegasi)->row();
			if(isset($delegasi->id)) {
				$data['id_panitia']		= $delegasi->id_m_panitia;
				$data['nama_panitia']	= $delegasi->nama_panitia;
			}
			$inisiasi_pengadaan 		= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$pengajuan->nomor_pengajuan)->row();
			if(isset($inisiasi_pengadaan->id)) {
				$data['metode_pengadaan']	= $inisiasi_pengadaan->metode_pengadaan;
				$data['jenis_pengadaan']	= $inisiasi_pengadaan->jenis_pengadaan;
				$data['bobot_harga']		= $inisiasi_pengadaan->bobot_harga;
				$data['bobot_teknis']		= $inisiasi_pengadaan->bobot_teknis;
			}
		}


	    $jadwal 		= post('jadwal');
		$lokasi 		= post('lokasi');
		$tanggal_awal	= post('tanggal_awal');
		$tanggal_akhir	= post('tanggal_akhir');
		$zona_waktu		= post('zona_waktu');
		$pengajuan 		= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row();

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

		$response 	= save_data('tbl_rks',$data,post(':validation'));
		if($response['status'] == 'success') {
			$dt_rks = get_data('tbl_rks','id',$response['id'])->row();
			update_data('tbl_pengajuan',[
				'nomor_rks'	=> $dt_rks->nomor_rks,
			],'nomor_pengajuan',$dt_rks->nomor_pengajuan);

			if(isset($pengajuan->id) && $pengajuan->nomor_delegasi) {
				update_data('tbl_delegasi_pengadaan',['status_proses'=>1],'nomor_delegasi',$pengajuan->nomor_delegasi);
			}

			delete_data('tbl_jadwal_pengadaan','nomor_pengajuan',$data['nomor_pengajuan']);
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

			if($pengajuan->approve == 0 && $pengajuan->id_user_persetujuan == 0) {
				$this->load->helper('pengadaan');
				cek_approval($pengajuan->nomor_pengajuan);
			}
		}
		render($response,'json');
	}

	function delete() {
		$dt_rks 	= get_data('tbl_rks','id',post('id'))->row();
		$inisiasi 	= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$dt_rks->nomor_pengajuan)->row();

		$response 	= destroy_data('tbl_rks','id',post('id'));
		if($response['status'] == 'success') {
		    update_data('tbl_pengajuan',[
		        'nomor_rks'	=> '',
		    ],'nomor_pengajuan',$dt_rks->nomor_pengajuan);
		    if(!isset($inisiasi->id)) {
		    	delete_data('tbl_jadwal_pengadaan','nomor_pengajuan',$data['nomor_pengajuan']);
		    }
		    if(isset($dt_rks->file) && $dt_rks->file) {
				$file = json_decode($dt_rks->file,true);
				foreach($file as $f) {
					@unlink(FCPATH . 'assets/uploads/rks/'.$f);
				}
			}
			$this->load->helper('pengadaan');
			cek_approval($dt_rks->nomor_pengajuan);
		}
		render($response,'json');
	}

	function get_combo(){
		$a 			= array('0000');
		$pengadaan 	= get_data('tbl_panitia_pelaksana','userid',user('id'))->result();
		foreach($pengadaan as $ci) {
			$a[]	= $ci->nomor_pengajuan;
		}
		$pengajuan 	= get_data('tbl_delegasi_pengadaan a',[
			'select'	=> 'a.nomor_pengajuan, a.nama_pengadaan, a.tanggal_pengadaan, b.nama_divisi, a.mata_anggaran, a.besar_anggaran, a.usulan_hps, b.pemberi_tugas, b.latar_belakang, b.spesifikasi, b.jumlah_kebutuhan, b.distribusi_kebutuhan, b.jangka_waktu, b.ruang_lingkup, b.lain_lain, d.total_hps_pembulatan AS hps_panitia, c.metode_pengadaan, c.jenis_pengadaan',
			'join'		=> [
				'tbl_pengajuan b ON a.nomor_pengajuan = b.nomor_pengajuan type LEFT',
				'tbl_inisiasi_pengadaan c ON a.nomor_pengajuan = c.nomor_pengajuan type LEFT',
				'tbl_m_hps d ON a.nomor_pengajuan = d.nomor_pengajuan type LEFT'
			],
			'where' => [
				'a.nomor_pengajuan' => $a,
				'b.nomor_rks'		=> ''
			]
		])->result();
		$data['pengajuan']	= $data['jadwal'] = [];
		foreach($pengajuan as $p) {
			$data['pengajuan'][$p->nomor_pengajuan] = $p;
			$data['jadwal'][$p->nomor_pengajuan]	= get_data('tbl_jadwal_pengadaan','nomor_pengajuan',$p->nomor_pengajuan)->result();
		}

		render($data,'json');
	}

	function cetak($encode_id=''){
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
			$data['html']					= template_pdf($record,'rks',$tanggal_rks);
			render($data,'pdf');
		} else {
			render('404');
		}
	}
}
