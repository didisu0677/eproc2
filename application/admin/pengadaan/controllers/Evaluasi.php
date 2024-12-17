<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluasi extends BE_Controller {

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
				button_serverside('btn-info',base_url('pengadaan/evaluasi/detail/'),['fa-search',lang('detil'),true],'btn-detail'),
			]
		];
		// if(menu()['access_additional']) {
		// 	$anggota_panitia	= get_data('tbl_anggota_panitia','userid',user('id'))->result();
		// 	$id_panitia			= [0];
		// 	foreach($anggota_panitia as $a) $id_panitia[] = $a->id_m_panitia;
		// 	$config['where']['id_panitia']	= $id_panitia;
		// } else {
		// 	$config['where']['id_creator']	= user('id');
		// }
		if($status == 1) $config['where']['status_aanwijzing']	= 'EVALUASI';
		else {
			$config['sort_by'] 	= 'id';
			$config['sort']		= 'DESC';
			$config['where']['status_aanwijzing !=']	= ['AANWIJZING','PENAWARAN','BATAL_PENAWARAN','EVALUASI'];
		}
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$data 			= get_data('tbl_aanwijzing','id = '.$id[0])->row_array();
			if(isset($data['id'])) {
				$this->load->helper('pengadaan');
				$data['title']				= $data['nomor_pengadaan'];
				$data['id_rks_aanwijzing']	= id_by_nomor($data['nomor_pengajuan'],'rks','aanwijzing');
				$data['rekanan']			= get_data('tbl_aanwijzing_vendor',[
					'where'	=> [
						'nomor_pengadaan'	=> $data['nomor_pengadaan'],
						'lolos_penawaran'	=> 1
					],
					'sort_by'				=> 'id'
				])->result_array();
				$sudah_input 				= get_data('tbl_aanwijzing_vendor',[
					'select'				=> 'COUNT(id) AS jml',
					'where'	=> [
						'nomor_pengadaan'	=> $data['nomor_pengadaan'],
						'lolos_penawaran'	=> 1,
						'is_penilaian'		=> 1
					]
				])->row();
				$data['jml_evaluasi']		= $sudah_input->jml;
				$data['jml_rekanan']		= count($data['rekanan']);
				$tanggal_evaluasi			= get_data('tbl_jadwal_pengadaan','kata_kunci = "evaluasi_penawaran" AND nomor_pengajuan = "'.$data['nomor_pengajuan'].'"')->row();
				$data['open_evaluasi']	= false;
				if(isset($tanggal_evaluasi->id)) {
					if(strtotime($tanggal_evaluasi->tanggal_awal) <= strtotime('now')) {
						$data['open_evaluasi'] 	= true;
					}
					$data['tanggal_evaluasi']	= c_date($tanggal_evaluasi->tanggal_awal).' - '.c_date($tanggal_evaluasi->tanggal_akhir);
				}

				$data['panitia_pelaksana']	= get_data('tbl_panitia_pelaksana','nomor_pengajuan',$data['nomor_pengajuan'])->result();
				$pengajuan 					= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
				$data['nama_creator']		= isset($pengajuan->id) ? $pengajuan->create_by : '-';
				$data['nama_panitia']		= isset($pengajuan->id) ? $pengajuan->nama_panitia : 'Panitia';

				$data['inisiasi']			= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
				$data['pembobotan'][0]		= get_data('tbl_pembobotan',[
					'where'	=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan'],
						'parent_id' 		=> 0
					],
					'sort_by'				=> 'id'
				])->result_array();
				foreach($data['pembobotan'][0] as $p) {
					$data['pembobotan'][$p['id']]	= get_data('tbl_pembobotan',[
						'where'	=> [
							'nomor_pengajuan'	=> $data['nomor_pengajuan'],
							'parent_id' 		=> $p['id']
						],
						'sort_by'				=> 'id'
					])->result_array();
				}

				render($data);
			} else render('404');
		} else render('404');
	}

	function detail_vendor($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) > 0) {
			include_lang('manajemen_rekanan');
			$data 	= get_data('tbl_vendor','id',$id[0])->row_array();
			render($data,'layout:false');
		} else echo lang('data_tidak_ada');
	}

	function save_pembobotan() {
		$nomor_pengajuan	= post('_nomor_pengajuan');
		$data 				= [
			'bobot_harga'	=> str_replace(',', '.', post('bobot_harga')),
			'bobot_teknis'	=> str_replace(',', '.', post('bobot_teknis')),
		];

		update_data('tbl_inisiasi_pengadaan',$data,'nomor_pengajuan',$nomor_pengajuan);
		delete_data('tbl_pembobotan','nomor_pengajuan',$nomor_pengajuan);
		$idx 				= post('idx');
		$detil_deskripsi	= post('detil_bobot_keterangan');
		$tipe_rumus 		= post('cara_perhitungan');
		$detil_bobot 		= post('detail_bobot');

		$child_deskripsi 	= post('child_deskripsi');
		$child_batas_bawah	= post('child_batas_bawah');
		$child_batas_atas 	= post('child_batas_atas');
		$child_bobot 		= post('child_bobot');

		foreach($idx as $key => $value) {
			$id_persyaratan 		= $value;
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
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function get_penilaian() {
		$id_vendor 			= post('id_vendor');
		$nomor_pengajuan 	= post('nomor_pengajuan');

		$vendor 			= get_data('tbl_vendor','id',$id_vendor)->row();
		$data['nama_vendor']	= $vendor->nama;
		$data['alamat_vendor']	= $vendor->alamat.', '.$vendor->nama_kelurahan.', '.$vendor->nama_kecamatan.', '.$vendor->nama_kota.'<br />'.$vendor->nama_provinsi.' - '.$vendor->kode_pos;

		$data['dok_persyaratan']	= $data['dok_administrasi'] = $data['dok_teknis'] = $data['dok_penawaran'] = '#';
		$data['pass_persyaratan']	= $data['pass_administrasi'] = $data['pass_teknis'] = $data['pass_penawaran'] = '*******';
		$pengadaan 			= get_data('tbl_pengadaan_bidder',[
			'where'			=> [
				'id_vendor'			=> $id_vendor,
				'nomor_pengajuan'	=> $nomor_pengajuan
			]
		])->row();
		$aanwijzing 		= get_data('tbl_aanwijzing_vendor',[
			'where'			=> [
				'id_vendor'			=> $id_vendor,
				'nomor_pengajuan'	=> $nomor_pengajuan
			]
		])->row();
		foreach(json_decode($pengadaan->file_persyaratan,true) as $pass => $file) {
			$data['dok_persyaratan']	= base_url(dir_upload('dokumen_rekanan').$file);
			$data['pass_persyaratan']	= $pass;
		}
		foreach(json_decode($aanwijzing->file_penawaran,true) as $k => $v) {
			if($k == 'penawaran_harga') {
				$data['dok_penawaran']	= base_url(dir_upload('dokumen_rekanan').$v['file']);
				$data['pass_penawaran']	= $v['password'];
			} else {
				$data['dok_'.$k]	= base_url(dir_upload('dokumen_rekanan').$v['file']);
				$data['pass_'.$k]	= $v['password'];
			}
		}
		$data['nilai_total_penawaran']		= custom_format($aanwijzing->nilai_total_penawaran);
		$data['nilai_jaminan_penawaran']	= custom_format($aanwijzing->nilai_jaminan_penawaran);

		$data['bobot']				= get_data('tbl_pembobotan_result',[
			'where'		=> [
				'id_vendor'			=> $id_vendor,
				'nomor_pengajuan'	=> $nomor_pengajuan
			]
		])->result_array();
		render($data,'json');
	}

	function save_penilaian() {
		$nomor_pengajuan 	= post('nomor_pengajuan');
		$nomor_pengadaan 	= post('_nomor_pengadaan');
		$id_vendor 			= post('id_vendor');
		$_value 			= post('v_bobot');

		delete_data('tbl_pembobotan_result',[
			'nomor_pengadaan'	=> $nomor_pengadaan,
			'id_vendor'			=> $id_vendor
		]);
		if(is_array($_value)) {
			foreach($_value as $k => $v) {
				$pembobotan 	= get_data('tbl_pembobotan','id',$k)->row();
				$data 	= [
					'nomor_pengajuan'	=> $pembobotan->nomor_pengajuan,
					'nomor_pengadaan'	=> $nomor_pengadaan,
					'id_persyaratan'	=> $pembobotan->id_persyaratan,
					'id_pembobotan'		=> $pembobotan->id,
					'parent_id'			=> $pembobotan->parent_id,
					'id_vendor'			=> $id_vendor,
					'_value'			=> $v
				];
				insert_data('tbl_pembobotan_result',$data);
			}
		}

		update_data('tbl_aanwijzing_vendor',['is_penilaian'=>1],[
			'nomor_pengadaan'		=> $nomor_pengadaan,
			'id_vendor'				=> $id_vendor
		]);

		$rekanan 						= get_data('tbl_aanwijzing_vendor',[
			'where'					=> [
				'nomor_pengadaan'	=> $nomor_pengadaan,
				'lolos_penawaran'	=> 1
			]
		])->result();
		$sudah_input 				= get_data('tbl_aanwijzing_vendor',[
			'select'				=> 'COUNT(id) AS jml',
			'where'	=> [
				'nomor_pengadaan'	=> $nomor_pengadaan,
				'lolos_penawaran'	=> 1,
				'is_penilaian'		=> 1
			]
		])->row();
		if(count($rekanan) == $sudah_input->jml) {
			$inisiasi 		= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$nomor_pengajuan)->row();
			$pembobotan 	= get_data('tbl_pembobotan',[
				'where'		=> [
					'nomor_pengajuan'	=> $nomor_pengajuan,
					'parent_id'			=> 0
				]
			])->result();
			foreach($pembobotan as $p) {
				if($p->tipe_rumus == 'range') {
					foreach($rekanan as $r) {
						$d_rekanan = get_data('tbl_pembobotan_result',[
							'where'	=> [
								'nomor_pengadaan'	=> $nomor_pengadaan,
								'id_vendor'			=> $r->id_vendor,
								'id_pembobotan'		=> $p->id
							]
						])->row();
						if(isset($d_rekanan->id)) {
							$_val 		= $d_rekanan->_value;
							$q_bobot	= get_data('tbl_pembobotan',[
								'where'	=> [
									'nomor_pengajuan'	=> $nomor_pengajuan,
									'parent_id'			=> $p->id,
									'batas_bawah <='	=> $_val
								],
								'sort_by'	=> 'batas_bawah',
								'sort'		=> 'DESC'
							])->row();
							$bobot 		= isset($q_bobot->bobot) ? $q_bobot->bobot : 0;
							update_data('tbl_pembobotan_result',['bobot'=>$bobot],[
								'nomor_pengadaan'	=> $nomor_pengadaan,
								'id_vendor'			=> $r->id_vendor,
								'id_pembobotan'		=> $p->id
							]);
						}
					}
				} elseif($p->tipe_rumus == 'acuan') {
					foreach($rekanan as $r) {
						$d_rekanan = get_data('tbl_pembobotan_result',[
							'where'	=> [
								'nomor_pengadaan'	=> $nomor_pengadaan,
								'id_vendor'			=> $r->id_vendor,
								'id_pembobotan'		=> $p->id
							]
						])->row();
						if(isset($d_rekanan->id)) {
							$_val 		= $d_rekanan->_value;
							$q_bobot	= get_data('tbl_pembobotan',[
								'where'	=> [
									'nomor_pengajuan'	=> $nomor_pengajuan,
									'parent_id'			=> $p->id,
									'deskripsi'			=> $_val
								]
							])->row();
							$bobot 		= isset($q_bobot->bobot) ? $q_bobot->bobot : 0;
							update_data('tbl_pembobotan_result',['bobot'=>$bobot],[
								'nomor_pengadaan'	=> $nomor_pengadaan,
								'id_vendor'			=> $r->id_vendor,
								'id_pembobotan'		=> $p->id
							]);
						}
					}
				} else {
					$pembobotan2	= get_data('tbl_pembobotan',[
						'where'		=> [
							'nomor_pengajuan'	=> $nomor_pengajuan,
							'parent_id'			=> $p->id
						]
					])->result();
					foreach($pembobotan2 as $p2) {
						$sort 		= $p->tipe_rumus == 'terbanyak' ? 'DESC' : 'ASC';
						$get_ref	= get_data('tbl_pembobotan_result',[
							'select'	=> 'CONVERT(`_value`,UNSIGNED INTEGER) AS `_value`',
							'where'		=> [
								'nomor_pengadaan'	=> $nomor_pengadaan,
								'id_pembobotan'		=> $p2->id
							],
							'sort_by'	=> '_value',
							'sort'		=> $sort
						])->row();
						$ref 		= isset($get_ref->_value) ? $get_ref->_value : 0;
						foreach($rekanan as $r) {
							$d_rekanan = get_data('tbl_pembobotan_result',[
								'where'	=> [
									'nomor_pengadaan'	=> $nomor_pengadaan,
									'id_vendor'			=> $r->id_vendor,
									'id_pembobotan'		=> $p2->id
								]
							])->row();
							if(isset($d_rekanan->id)) {
								$_val 		= $d_rekanan->_value;
								$bobot 		= 0;
								if($p->tipe_rumus == 'terbanyak') {
									if($ref) {
										$bobot 	= ($_val / $ref) * $p2->bobot;
									}
								} else {
									$bobot 	= ($ref / $_val) * $p2->bobot;
								}
								update_data('tbl_pembobotan_result',['bobot'=>$bobot],[
									'nomor_pengadaan'	=> $nomor_pengadaan,
									'id_vendor'			=> $r->id_vendor,
									'id_pembobotan'		=> $p2->id
								]);
							}
						}
					}
				}
			}

			$get_ref 	= get_data('tbl_aanwijzing_vendor',[
				'where'					=> [
					'nomor_pengadaan'	=> $nomor_pengadaan,
					'lolos_penawaran'	=> 1
				],
				'sort_by'				=> 'nilai_total_penawaran',
				'sort'					=> 'ASC'
			])->row();
			$ref 		= isset($get_ref->nilai_total_penawaran) ? $get_ref->nilai_total_penawaran : 0;
			foreach($rekanan as $r) {
				$t_teknis 				= get_data('tbl_pembobotan_result',[
					'select'				=> 'SUM(bobot) AS bobot',
					'where'					=> [
						'nomor_pengadaan'	=> $nomor_pengadaan,
						'id_vendor'			=> $r->id_vendor
					]
				])->row();
				$total_teknis			= $t_teknis->bobot;
				$penilaian_harga		= ($ref / $r->nilai_total_penawaran) * $inisiasi->bobot_harga;
				$penilaian_teknis		= ($inisiasi->bobot_teknis / 100) * $total_teknis;
				$total_penilaian		= $penilaian_harga + $penilaian_teknis;

				update_data('tbl_aanwijzing_vendor',[
					'total_teknis'		=> $total_teknis,
					'penilaian_harga'	=> $penilaian_harga,
					'penilaian_teknis'	=> $penilaian_teknis,
					'total_penilaian'	=> $total_penilaian
				],[
					'nomor_pengadaan'	=> $nomor_pengadaan,
					'id_vendor'			=> $r->id_vendor
				]);
			}

			$r_sort	= get_data('tbl_aanwijzing_vendor',[
				'where'					=> [
					'nomor_pengadaan'	=> $nomor_pengadaan,
					'lolos_penawaran'	=> 1
				],
				'sort_by'	=> 'total_penilaian',
				'sort'		=> 'desc'
			])->result();
			$i = 1;
			foreach($r_sort as $r) {
				update_data('tbl_aanwijzing_vendor',[
					'rank_evaluasi'	=> $i
				],'id',$r->id);
				$i++;
			}
		}

		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function save_berita_acara() {
		$data 	= post();
		unset($data['id_aanwijzing']);
		$awz 	= get_data('tbl_aanwijzing','id',post('id_aanwijzing'))->row();
		$data['peserta_ba_pembukaan']	= $awz->peserta_berita_acara;
		$id 	= post('id_aanwijzing');
		$save 	= update_data('tbl_aanwijzing',$data,'id',$id);
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

	function berita_acara($encode_id='') {
		$decode = decode_id($encode_id);
		if(count($decode) == 2) {
			$data 	= get_data('tbl_aanwijzing','id',$decode[0])->row_array();
			if(isset($data['id'])) {
				$data['panitia']			= get_data('tbl_panitia_pelaksana',[
					'where'					=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan']
					],
					'sort_by'				=> 'id'
				])->result_array();
				$data['vendor'] 		= get_data('tbl_aanwijzing_vendor',[
					'where'		=> [
						'nomor_pengadaan'	=> $data['nomor_pengadaan'],
						'rank_evaluasi <='	=> 3,
						'lolos_penawaran'	=> 1
					],
					'sort_by'	=> 'rank_evaluasi'
				])->result();

				$creator 					= get_data('tbl_user','id',$data['id_creator'])->row();
				$data['nama_creator']		= isset($creator->id) ? $creator->nama : 'Creator';
				$data['penyetuju']			= get_data('tbl_alur_persetujuan',[
					'where'	=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan'],
						'jenis_approval'	=> 'PENGADAAN'
					]
				])->result();

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

				render($data,'pdf');
			} else render('404');
		} else render('404');
	}

	function resume($encode_id='') {
		$id = decode_id($encode_id);
		if(count($id) == 2) {
			$data 					= get_data('tbl_aanwijzing','id',$id[0])->row_array();
			$data['inisiasi']		= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
			$data['pembobotan'][0]	= get_data('tbl_pembobotan',[
				'where'				=> [
					'nomor_pengajuan'	=> $data['nomor_pengajuan'],
					'parent_id'			=> 0
				],
				'sort_by'			=> 'id'
			])->result_array();
			$data['referensi']		= [];
			foreach($data['pembobotan'][0] as $p) {
				$data['pembobotan'][$p['id']]	= get_data('tbl_pembobotan',[
					'where'				=> [
						'nomor_pengajuan'	=> $data['nomor_pengajuan'],
						'parent_id'			=> $p['id']
					],
					'sort_by'			=> 'id'
				])->result_array();
				if($p['tipe_rumus'] == 'terendah' || $p['tipe_rumus'] == 'terbanyak') {
					foreach($data['pembobotan'][$p['id']] as $p2) {
						$sort 		= $p['tipe_rumus'] == 'terbanyak' ? 'DESC' : 'ASC';
						$get_ref	= get_data('tbl_pembobotan_result',[
							'select'	=> 'CONVERT(`_value`,UNSIGNED INTEGER) AS `_value`',
							'where'		=> [
								'nomor_pengadaan'	=> $data['nomor_pengadaan'],
								'id_pembobotan'		=> $p2['id']
							],
							'sort_by'	=> '_value',
							'sort'		=> $sort
						])->row();
						$ref 		= isset($get_ref->_value) ? $get_ref->_value : 0;
						$data['referensi'][$p2['id']] = $ref;
					}
				}
			}
			$vendor					= get_data('tbl_aanwijzing_vendor',[
				'where'				=> [
					'nomor_pengadaan'	=> $data['nomor_pengadaan'],
					'lolos_penawaran'	=> 1
				],
				'sort_by'				=> 'id'
			])->result_array();

			$data['result']			= [];
			foreach($vendor as $v) {
				$res 	= get_data('tbl_pembobotan_result',[
					'where'	=> [
						'nomor_pengadaan'	=> $data['nomor_pengadaan'],
						'id_vendor'			=> $v['id_vendor']
					]
				])->result();
				foreach($res as $r) {
					$data['result'][$v['id_vendor']][$r->id_pembobotan] = [
						'value'		=> $r->_value,
						'bobot'		=> $r->bobot
					];
				}
			}

			$get_ref 	= get_data('tbl_aanwijzing_vendor',[
				'where'					=> [
					'nomor_pengadaan'	=> $data['nomor_pengadaan'],
					'lolos_penawaran'	=> 1
				],
				'sort_by'				=> 'nilai_total_penawaran',
				'sort'					=> 'ASC'
			])->row();
			$data['harga_terendah'] 	= isset($get_ref->nilai_total_penawaran) ? $get_ref->nilai_total_penawaran : 0;

			$sub 	= 3;
			$bagi 	= ceil(count($vendor) / $sub);
			$data['vendor'] = [];
			for($i=0; $i<$bagi; $i++) {
				$x 	= $i * $sub;
				$y 	= $x + $sub;
				for($j = $x; $j < $y; $j++) {
					if(isset($vendor[$j])) {
						$data['vendor'][$i][] = $vendor[$j];
					}
				}
			}

			$data['vendor_rank']  = get_data('tbl_aanwijzing_vendor',[
				'where'		=> [
					'nomor_pengadaan'	=> $data['nomor_pengadaan'],
					'lolos_penawaran'	=> 1
				],
				'sort_by'	=> 'rank_evaluasi'
			])->result_array();

			render($data,'pdf');
		} else render('404');
	}

	function proses() {
		$aanwijzing 		= get_data('tbl_aanwijzing','id',post('id_awz'))->row();
		if($aanwijzing->tipe_pengadaan == 'Lelang') {
			$data 				= [
				'stat_pengadaan'	=> 'PENINJAUAN',
				'status_aanwijzing'	=> 'PENINJAUAN',
				'metode_negosiasi'	=> post('metode_negosiasi')
			];
		} else {
			$data 				= [
				'stat_pengadaan'	=> 'KLARIFIKASI',
				'status_aanwijzing'	=> 'KLARIFIKASI',
				'metode_negosiasi'	=> post('metode_negosiasi')
			];			
		}
		update_data('tbl_aanwijzing',$data,'id',$aanwijzing->id);
		update_data('tbl_aanwijzing_vendor',['status_aanwijzing'=>$data['stat_pengadaan'],'nomor_surat_tugas'=>''],'nomor_pengadaan',$aanwijzing->nomor_pengadaan);
		update_data('tbl_chat_key',['is_active'=>0],'id',$aanwijzing->id_chat_evaluasi);
		if($aanwijzing->tipe_pengadaan == 'Lelang') {
			$status_desc 			= 'Peninjuan Lapangan (On The Spot)';
		} else {
			$status_desc 			= 'Klarifikasi dan Negosiasi';
		}
		update_data('tbl_pengajuan',['status_desc'=>$status_desc],'nomor_pengajuan',$aanwijzing->nomor_pengajuan);

		if($aanwijzing->tipe_pengadaan != 'Lelang') {
			$awz 				= get_data('tbl_aanwijzing','nomor_pengadaan',$aanwijzing->nomor_pengadaan)->row_array();
			$field_klarifikasi	= get_field('tbl_klarifikasi','name');
			$new_data 			= [];
			foreach($field_klarifikasi as $f) {
				if(isset($awz[$f]) && !in_array($f,['id','status_rks','id_chat','nomor_berita_acara','tanggal_berita_acara','peserta_berita_acara','zona_waktu','lokasi_berita_acara','peserta_lain'])) {
					$new_data[$f]	= $awz[$f];
				}
			}
			if(post('metode_negosiasi') == 'Negosiasi Satu Rekanan') {
				$vendor 			= get_data('tbl_aanwijzing_vendor',[
					'where'	=> [
						'nomor_pengadaan'	=> $aanwijzing->nomor_pengadaan,
						'rank_evaluasi'		=> 1
					]
				])->result_array();
			} else {
				$vendor 			= get_data('tbl_aanwijzing_vendor',[
					'where'	=> [
						'nomor_pengadaan'	=> $aanwijzing->nomor_pengadaan,
						'lolos_penawaran'	=> 1
					]
				])->result_array();
			}
			$keterangan_chat 	= '';
			if(isset($vendor[0]) && post('metode_negosiasi') == 'Negosiasi Satu Rekanan') {
				$new_data['keterangan']	= 'Klarifikasi dan Negosiasi dengan '.$vendor[0]['nama_vendor'];
				$keterangan_chat 	= ' @'.$vendor[0]['nama_vendor'];
			}

			$save_klarifikasi	= insert_data('tbl_klarifikasi',$new_data);
			$field_vendor 		= get_field('tbl_klarifikasi_vendor','name');
			foreach($vendor as $v) {
				$row_data 		= ['id_klarifikasi'=>$save_klarifikasi];
				foreach($field_vendor as $f) {
					if(isset($v[$f]) && $f != 'id') {
						$row_data[$f]	= $v[$f];
					}
				}
				insert_data('tbl_klarifikasi_vendor',$row_data);
				update_data('tbl_aanwijzing_vendor',['status_peninjauan'=>1],'id',$v['id']);
			}

			$id_anggota	= $anggota_chat = $id_user = $email_user = [];
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

			$creator 				= get_data('tbl_user','id',$aanwijzing->id_creator)->row();
			$id_anggota[]			= $creator->id;
			$anggota_chat[]			= $creator->nama;
			$id_user[] 				= $creator->id;
			$email_user[]			= $creator->email;

			$id_vendor		= [-1];
			foreach($vendor as $v) {
				$id_vendor[]	= $v['id_vendor'];
			}

			$user_vendor	= get_data('tbl_user','id_vendor',$id_vendor)->result();
			foreach($user_vendor as $u) {
				$id_anggota[] 	= $u->id;
				$anggota_chat[]	= $u->nama;
				$id_user[] 		= $u->id;
				$email_user[] 	= $u->email;
			}

			$jadwal 				= get_data('tbl_jadwal_pengadaan','kata_kunci = "klarifikasi_negosiasi" AND nomor_pengajuan = "'.$aanwijzing->nomor_pengajuan.'"')->row();

			$data_chat 		= [
				'nama'		=> 'Klarifikasi dan Negosiasi '.$aanwijzing->nomor_pengadaan.$keterangan_chat,
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
			update_data('tbl_klarifikasi',['id_chat'=>$chat],'id',$save_klarifikasi);

			if(count($id_user) > 0) {
				$link				= base_url('auth');
				$desctiption 		= 'Proses Evaluasi Pengadaan <strong>'.$aanwijzing->nomor_pengadaan.'</strong> telah selesai.';
				foreach($id_user as $iu) {
					$data_notifikasi 	= [
						'title'			=> 'Evaluasi Penawaran',
						'description'	=> $desctiption,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'info',
						'notif_icon'	=> 'fa-file-alt',
						'id_user'		=> $iu,
						'transaksi'		=> 'evaluasi',
						'id_transaksi'	=> $aanwijzing->id
					];
					insert_data('tbl_notifikasi',$data_notifikasi);
				}

				if(count($email_user) > 0 && setting('email_notification')) {
					send_mail([
						'subject'				=> 'Evaluasi Penawaran #'.$aanwijzing->nomor_pengadaan,
						'bcc'					=> $email_user,
						'nama_pengadaan'		=> $aanwijzing->nama_pengadaan,
						'metode_negosiasi'		=> post('metode_negosiasi'),
						'vendor'				=> get_data('tbl_aanwijzing_vendor',[
							'where'	=> [
								'nomor_pengadaan'	=> $aanwijzing->nomor_pengadaan,
								'status_peninjauan'	=> 1
							]
						])->result_array(),
						'url'					=> $link
					]);
				}
			}
		} else {
			// cari yg mempunyai akses penugasan untuk diberi notifikasi
			$access_penugasan 	= id_group_access('peninjauan_lapangan','input');
			$user_notifikasi 	= get_data('tbl_user',[
				'where'			=> [
					'is_active'	=> 1,
					'id_group'	=> $access_penugasan
				]
			])->result();
			$id_user 			= $email_user = [];
			foreach($user_notifikasi as $u) {
				$id_user[]		= $u->id;
				$email_user[]	= $u->email;
			}

			if(count($id_user) > 0) {
				$link				= base_url().'pengadaan/peninjauan_lapangan/penugasan/'.encode_id([$aanwijzing->id,rand()]);
				$desctiption 		= 'Pengadaan dengan nomor <strong>'.$aanwijzing->nomor_pengadaan.'</strong> diperlukan penugasan untuk peninjauan lapangan.';
				foreach($id_user as $iu) {
					$data_notifikasi 	= [
						'title'			=> 'Peninjauan Lapangan',
						'description'	=> $desctiption,
						'notif_link'	=> $link,
						'notif_date'	=> date('Y-m-d H:i:s'),
						'notif_type'	=> 'info',
						'notif_icon'	=> 'fa-map-marked-alt',
						'id_user'		=> $iu,
						'transaksi'		=> 'evaluasi',
						'id_transaksi'	=> $aanwijzing->id
					];
					insert_data('tbl_notifikasi',$data_notifikasi);
				}

				if(count($email_user) > 0 && setting('email_notification')) {
					send_mail([
						'subject'				=> 'Peninjauan Lapangan #'.$aanwijzing->nomor_pengadaan,
						'bcc'					=> $email_user,
						'nama_pengadaan'		=> $aanwijzing->nama_pengadaan,
						'url'					=> $link
					]);
				}
			}
		}
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_disimpan')
		],'json');
	}

}