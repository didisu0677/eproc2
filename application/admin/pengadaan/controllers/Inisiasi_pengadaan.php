<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inisiasi_pengadaan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['id']					= 0;
		$id							= decode_id(get('i'));
		if(is_array($id) && isset($id[0])) $data['id'] = $id[0];
		$data['bidang_usaha']		= get_data('tbl_m_bidang_usaha','is_active = 1 AND parent_id = 0')->result_array();
		foreach($data['bidang_usaha'] as $b) {
			$data['subbidang_usaha'][$b['id']]	= get_data('tbl_m_bidang_usaha','is_active = 1 AND parent_id = '.$b['id'])->result_array();
		}
		$data['kategori_rekanan']	= get_data('tbl_m_kategori_rekanan','is_active=1')->result_array();
		$data['jenis_pengadaan']	= get_data('tbl_jenis_pengadaan a',[
			'select'				=> 'a.id,a.jenis_pengadaan,b.bobot_harga,b.bobot_teknis',
			'join'					=> 'tbl_m_bobot_evaluasi b ON a.id = b.id_jenis_pengadaan type LEFT',
			'where'					=> [
				'a.is_active'		=> 1
			]
		])->result_array();
		$data['identifikasi_pajak']	= get_data('tbl_m_identifikasi_pajak','is_active',1)->result_array();
		$data['jadwal']				= get_data('tbl_m_penjadwalan','is_active',1)->result_array();
		$data['mandatori']			= ['spph','pendaftaran_pengadaan','aanwijzing'];
		$data['grup_dokumen']			= [
			'persyaratan_peserta'		=> lang('dokumen_hal_yang_menggugurkan'),
			'dokumen_administrasi'		=> lang('dokumen_administrasi'),
			'dokumen_teknis'			=> lang('dokumen_teknis'),
			'dokumen_penawaran_harga'	=> lang('dokumen_penawaran_harga')
		];
		render($data);
	}

	function data() {
		$config				= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false
		];
		if(user('id_group') > 2) {
			$id_panitia 	= [0];
			$panitia 		= get_data('tbl_panitia_pelaksana','userid',user('id'))->result();
			foreach($panitia as $ci) {
				$id_panitia[]	= $ci->id_m_panitia;
			}
			$config['where_in']['id_panitia']	= $id_panitia;
		}
		$config['button'][]	= button_serverside('btn-info','btn-act-view',['fa-search',lang('detil'),true],'act-view');
		if(menu()['access_edit']) {
			$config['button'][]	= button_serverside('btn-warning','btn-input',['fa-edit',lang('ubah'),true],'edit',['status_proses'=>[0,8]]);
		}
		if(menu()['access_delete']) {
			$config['button'][]	= button_serverside('btn-danger','btn-delete',['fa-trash-alt',lang('hapus'),true],'delete',['status_proses'=>[0,8]]);
		}
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data 						= get_data('tbl_inisiasi_pengadaan','id',post('id'))->row_array();
		$data['file'] 				= json_decode($data['file'],true);
		$data['id_vendor']			= json_decode($data['id_vendor'],true);
		$data['id_bidang_usaha']	= json_decode($data['id_bidang_usaha'],true);
		$data['id_kategori_rekanan']= json_decode($data['id_kategori_rekanan'],true);

		$data['detail']			= get_data('tbl_jadwal_pengadaan','nomor_pengajuan',$data['nomor_pengajuan'])->result_array();

		$data['metode_pengadaan']	= [];
		if($data['hps_panitia']) {
			$data['metode_pengadaan'] = get_data('tbl_metode_pengadaan',[
				'select'	=> 'id,metode_pengadaan,tipe',
				'where'		=> [
					'limit_bawah_pengadaan <='	=> $data['hps_panitia'],
					'limit_atas_pengadaan >='	=> $data['hps_panitia'],
					'is_active'					=> 1
				]
			])->result_array();
		}

		$data['vendor'] 		= get_data('tbl_vendor a',[
			'select'	=> 'DISTINCT(a.id) AS id, a.nama',
			'join'		=> 'tbl_vendor_kategori b ON a.id = b.id_vendor TYPE LEFT',
			'where'		=> [
				'b.id_kategori_rekanan'	=> $data['id_kategori_rekanan']
			]
		])->result_array();


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

	function save() {
		$data 			= post();
		$last 			= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
		if(isset($last->id)) {
			$data['id']	= $last->id;
		}
		$jadwal 		= post('jadwal');
		$lokasi 		= post('lokasi');
		$tanggal_awal	= post('tanggal_awal');
		$tanggal_akhir	= post('tanggal_akhir');
		$zona_waktu		= post('zona_waktu');
		$pengajuan 		= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
		if(isset($pengajuan->id)) {
			$delegasi 					= get_data('tbl_delegasi_pengadaan','nomor_delegasi',$pengajuan->nomor_delegasi)->row();
			if(isset($delegasi->id)) {
				$data['id_panitia']		= $delegasi->id_m_panitia;
				$data['nama_panitia']	= $delegasi->nama_panitia;
			}
		}

		$last_file 		= [];
		if($data['id']) {
			$dt 		= get_data('tbl_inisiasi_pengadaan','id',$data['id'])->row();
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
						if(@copy($f, FCPATH . 'assets/uploads/inisiasi_pengadaan/'.basename($f))) {
							$filename[$keterangan_file[$k]]	= basename($f);
							if(!$dir) $dir = str_replace(basename($f),'',$f);
						}
					}
				}
			}
		}
		if($dir) {
			delete_dir(FCPATH . $dir);
		}
		foreach($last_file as $lf) {
			@unlink(FCPATH . 'assets/uploads/inisiasi_pengadaan/' . $lf);
		}
		$data['file']					= json_encode($filename);
		if($dir) {
			delete_dir(FCPATH . $dir);
		}

		$dt_metode						= get_data('tbl_metode_pengadaan','id',$data['id_metode_pengadaan'])->row();
		if (isset($dt_metode->id)) {
			$data['metode_pengadaan']	= $dt_metode->metode_pengadaan;
			$data['tipe_pengadaan']		= $dt_metode->tipe;
		}
		$dt_jenis_pengadaan				= get_data('tbl_jenis_pengadaan','id',$data['id_jenis_pengadaan'])->row();
		if (isset($dt_jenis_pengadaan->id)){
			$data['jenis_pengadaan']	= $dt_jenis_pengadaan->jenis_pengadaan;
		}
		$dt_identifikasi_pajak			= get_data('tbl_m_identifikasi_pajak','id',$data['id_identifikasi_pajak'])->row();
		if (isset($dt_identifikasi_pajak->id)){
			$data['identifikasi_pajak']	= $dt_identifikasi_pajak->kategori;
		}
		$dt_unit						= get_data('tbl_m_unit','id',$pengajuan->id_unit_kerja)->row();
		if(isset($dt_unit->id)) {
			$data['id_unit_kerja']		= $dt_unit->id;
			$data['unit_kerja']			= $dt_unit->unit;
			$data['kode_unit_kerja']	= $dt_unit->kode;
		}
		if(is_array(post('id_bidang_usaha'))) {
			$data['id_bidang_usaha']	= json_encode(post('id_bidang_usaha'));
			if(count(post('id_bidang_usaha')) > 0) {
				$bidang_usaha 			= get_data('tbl_m_bidang_usaha','id',post('id_bidang_usaha'))->result();
				$_bu 					= [];
				foreach($bidang_usaha as $b) {
					$parent 			= get_data('tbl_m_bidang_usaha','id',$b->parent_id)->row();
					$_bu[]				= [
						'bidang_usaha'		=> $parent->bidang_usaha,
						'subbidang_usaha'	=> $b->bidang_usaha
					];
				}
				$data['bidang_usaha']	= json_encode($_bu);
			}
		}

		if(is_array(post('id_kategori_rekanan'))) {
			$data['id_kategori_rekanan']	= json_encode(post('id_kategori_rekanan'));
			if(count(post('id_kategori_rekanan')) > 0) {
				$kategori_rekanan 			= get_data('tbl_m_kategori_rekanan','id',post('id_kategori_rekanan'))->result();
				$_bu 						= [];
				foreach($kategori_rekanan as $b) {
					$_bu[]					= $b->kategori;
				}
				$data['kategori_rekanan']	= implode(', ', $_bu);
			}
		}

		if(is_array(post('id_vendor'))) {
			$data['id_vendor']			= json_encode(post('id_vendor'));
			if(count(post('id_vendor')) > 0) {
				$vendor 				= get_data('tbl_vendor','id',post('id_vendor'))->result();
				$_v 					= [];
				foreach($vendor as $b) {
					$_v[]				= $b->nama;
				}
				$data['vendor']			= implode(', ', $_v);
			}
		}

		$response = save_data('tbl_inisiasi_pengadaan',$data,post(':validation'));

		if($response['status'] == 'success') {
			$dt_inisiasi 				= get_data('tbl_inisiasi_pengadaan','id',$response['id'])->row();
			update_data('tbl_pengajuan',[
				'nomor_inisiasi'	=> $dt_inisiasi->nomor_inisiasi,
			],['nomor_pengajuan'	=> $dt_inisiasi->nomor_pengajuan]);

			update_data('tbl_rks',[
				'metode_pengadaan'	=> $dt_inisiasi->metode_pengadaan,
				'jenis_pengadaan'	=> $dt_inisiasi->jenis_pengadaan,
				'bobot_teknis'		=> $dt_inisiasi->bobot_teknis,
				'bobot_harga'		=> $dt_inisiasi->bobot_harga
			],'nomor_pengajuan',$dt_inisiasi->nomor_pengajuan);

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
						'nomor_pengajuan'	=> $dt_inisiasi->nomor_pengajuan,
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

			if(is_array($idx)) {
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

			if(in_array($pengajuan->approve,[0,8]) && $pengajuan->id_user_persetujuan == 0 && post('ajukan')) {
				$this->load->helper('pengadaan');
				cek_approval($pengajuan->nomor_pengajuan);
			}
		}
		render($response,'json');
	}

	function delete() {
		$dt_inisiasi	= get_data('tbl_inisiasi_pengadaan','id',post('id'))->row();
		$rks 			= get_data('tbl_rks','nomor_pengajuan',$dt_inisiasi->nomor_pengajuan)->row();
		$response		= destroy_data('tbl_inisiasi_pengadaan','id',post('id'));
		if($response['status'] == 'success') {
			update_data('tbl_pengajuan',[
				'nomor_inisiasi'	=> '',
			],['nomor_pengajuan'=>$dt_inisiasi->nomor_pengajuan]);

			if(!isset($rks->id)) {
				delete_data('tbl_jadwal_pengadaan','nomor_pengajuan',$dt_inisiasi->nomor_pengajuan);
			}

			delete_data('tbl_dokumen_persyaratan','nomor_pengajuan',$dt_inisiasi->nomor_pengajuan);

			if(isset($dt_inisiasi->file) && $dt_inisiasi->file) {
				$file = json_decode($dt_inisiasi->file,true);
				foreach($file as $f) {
					@unlink(FCPATH . 'assets/uploads/inisiasi_pengadaan/'.$f);
				}
			}
			$this->load->helper('pengadaan');
			cek_approval($dt_inisiasi->nomor_pengajuan);
		}

		render($response,'json');
	}

	function get_combo(){
		$a 				= array('0000');
		$pengadaan 		= get_data('tbl_panitia_pelaksana','userid',user('id'))->result();
		foreach($pengadaan as $ci) {
			$a[]		= $ci->nomor_pengajuan;
		}
		$cb_nopengadaan	= get_data('tbl_delegasi_pengadaan a',[
			'select'	=> 'a.nomor_pengajuan, a.nama_pengadaan, a.tanggal_pengadaan, b.nama_divisi, a.mata_anggaran, a.besar_anggaran, b.unit_kerja, a.usulan_hps, c.total_hps_pembulatan AS hps_panitia',
			'join'		=> [
				'tbl_pengajuan b ON a.nomor_pengajuan = b.nomor_pengajuan type LEFT',
				'tbl_m_hps c ON a.nomor_pengajuan = c.nomor_pengajuan type LEFT'
			],
			'where' 	=> [
				'a.nomor_pengajuan' => $a,
				'b.nomor_inisiasi'	=> ''
			]
		])->result();

		$data['pengajuan']				= $data['metode_pengadaan']	= $data['jadwal'] = [];
		foreach($cb_nopengadaan as $d) {
			$data['pengajuan'][$d->nomor_pengajuan] 			= $d;
			$data['jadwal'][$d->nomor_pengajuan]				= get_data('tbl_jadwal_pengadaan','nomor_pengajuan',$d->nomor_pengajuan)->result();

			$data['metode_pengadaan'][$d->nomor_pengajuan]		= [];
			if($d->hps_panitia) {
				$data['metode_pengadaan'][$d->nomor_pengajuan]	= get_data('tbl_metode_pengadaan',[
					'select'	=> 'id,metode_pengadaan,tipe',
					'where'		=> [
						'limit_bawah_pengadaan <='	=> $d->hps_panitia,
						'limit_atas_pengadaan >='	=> $d->hps_panitia,
						'is_active'					=> 1
					]
				])->result_array();
				foreach($data['metode_pengadaan'][$d->nomor_pengajuan] as $km => $vm) {
					if($vm['tipe'] == 'Lelang') {
						$data['metode_pengadaan'][$d->nomor_pengajuan][$km]['limit'] = setting('min_memasukan_lelang');
					} elseif($vm['tipe'] == 'Pemilihan Langsung') {
						$data['metode_pengadaan'][$d->nomor_pengajuan][$km]['limit'] = setting('min_memasukan_pemilihan_langsung');
					} elseif($vm['tipe'] == 'Penunjukan Langsung') {
						$data['metode_pengadaan'][$d->nomor_pengajuan][$km]['limit'] = setting('min_memasukan_penunjukan_langsung');
					} else {
						$data['metode_pengadaan'][$d->nomor_pengajuan][$km]['limit'] = setting('min_memasukan_jasa_langsung');
					}
				}
			}
		}

		$grup_dokumen					= [
			'persyaratan_peserta'		=> lang('persyaratan_peserta'),
			'dokumen_administrasi'		=> lang('dokumen_administrasi'),
			'dokumen_teknis'			=> lang('dokumen_teknis'),
			'dokumen_penawaran_harga'	=> lang('dokumen_penawaran_harga')
		];
		$data['dokumen_persyaratan']	= [];
		$data['mandatori']				= [];
		foreach($grup_dokumen as $k => $v) {
			$data['dokumen_persyaratan'][$k][0]	= get_data('tbl_template_dokumen',[
				'where'					=> [
					'grup'				=> $k,
					'parent_id'			=> 0
				]
			])->result_array();
			$data['mandatori'][$k]		= 0;
			if(isset($data['dokumen_persyaratan'][$k][0][0])) $data['mandatori'][$k]	= $data['dokumen_persyaratan'][$k][0][0]['mandatori'];
			foreach ($data['dokumen_persyaratan'][$k][0] as $key => $value) {
				$data['dokumen_persyaratan'][$k][$value['id']]	= get_data('tbl_template_dokumen',[
					'where'					=> [
						'grup'				=> $k,
						'parent_id'			=> $value['id']
					]
				])->result_array();
			}
		}
		render($data,'json');
	}

	function detail($id=0) {
		$data 				= get_data('tbl_inisiasi_pengadaan','id',$id)->row_array();
		$data['pengajuan']	= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row_array();
		$data['detail']		= get_data('tbl_jadwal_pengadaan',[
			'where'		=> ['nomor_pengajuan' => $data['nomor_pengajuan']],
			'sort_by'	=> 'id'
		])->result_array();

		$grup_dokumen					= $data['grup_dokumen'] = [
			'persyaratan_peserta'		=> lang('dokumen_hal_yang_menggugurkan'),
			'dokumen_administrasi'		=> lang('dokumen_administrasi'),
			'dokumen_teknis'			=> lang('dokumen_teknis'),
			'dokumen_penawaran_harga'	=> lang('dokumen_penawaran_harga')
		];
		$data['dokumen_persyaratan']	= [];
		foreach($grup_dokumen as $k => $v) {
			$data['dokumen_persyaratan'][$k][0]	= get_data('tbl_dokumen_persyaratan',[
				'where'					=> [
					'grup'				=> $k,
					'parent_id'			=> 0,
					'nomor_pengajuan'	=> $data['nomor_pengajuan']
				],
				'sort_by'				=> 'id'
			])->result_array();
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

		$data['pembobotan'][0]			= get_data('tbl_pembobotan',[
			'where'	=> [
				'nomor_pengajuan'	=> $data['nomor_pengajuan'],
				'parent_id' 		=> 0
			],
			'sort_by'	=> 'id'
		])->result_array();
		foreach($data['pembobotan'][0] as $p) {
			$data['pembobotan'][$p['id']]	= get_data('tbl_pembobotan',[
				'where'				=> [
					'nomor_pengajuan'	=> $data['nomor_pengajuan'],
					'parent_id'			=> $p['id']
				],
				'sort_by'			=> 'id'
			])->result_array();
		}

		render($data,'layout:false');
	}

	function get_vendor() {
		$id_kategori 	= post('id_kategori');
		$vendor 		= get_data('tbl_vendor a',[
			'select'	=> 'DISTINCT(a.id) AS id, a.nama',
			'join'		=> 'tbl_vendor_kategori b ON a.id = b.id_vendor TYPE LEFT',
			'where'		=> [
				'a.status_drm'			=> 1,
				'a.is_active'			=> 1,
				'b.id_kategori_rekanan'	=> $id_kategori
			]
		])->result_array();
		render($vendor,'json');
	}
}