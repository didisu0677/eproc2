<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Klarifikasi_negosiasi_v extends BE_Controller {

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
				button_serverside('btn-info',base_url('pengadaan_v/klarifikasi_negosiasi_v/detail/'),['fa-search',lang('detil'),true],'btn-detail'),
			],
			'where'			=> [
				'id_vendor'				=> user('id_vendor')
			]
		];
		if($status == 1) $config['where']['status_klarifikasi']	= '';
		else {
			$config['sort_by'] 	= 'id';
			$config['sort']		= 'DESC';
			$config['where']['status_klarifikasi !=']	= '';
		}
		$data 	= data_serverside($config);
		render($data,'json');
	}

	function ref($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$klarifikasi 	= get_data('tbl_klarifikasi_vendor','id_klarifikasi = "'.$id[0].'" AND id_vendor = "'.user('id_vendor').'"')->row();
			if(isset($klarifikasi->id)) {
				redirect('pengadaan_v/klarifikasi_negosiasi_v/detail/'.encode_id($klarifikasi->id));
			} else render('404');
		} else render('404');
	}

	function detail($encode_id='') {
		$id 		= decode_id($encode_id);
		if(count($id) == 2) {
			$klarifikasi_vendor = get_data('tbl_klarifikasi_vendor','id = '.$id[0].' AND id_vendor = '.user('id_vendor'))->row_array();
			$data 				= [];
			if(isset($klarifikasi_vendor['id'])) {
				$data 			= get_data('tbl_klarifikasi','id',$klarifikasi_vendor['id_klarifikasi'])->row_array();
			}
			if(isset($data['id'])) {
				$this->load->helper('pengadaan');
				$data['id_rks_aanwijzing']	= id_by_nomor($data['nomor_pengajuan'],'rks','aanwijzing');
				$data['id_rks_klarifikasi']	= id_by_nomor($data['nomor_pengajuan'],'rks','klarifikasi');
				$data['title']				= $data['nomor_pengadaan'];
				$data['rekanan']			= get_data('tbl_klarifikasi_vendor','id_klarifikasi',$data['id'])->result_array();
				$last_negosiasi 			= get_data('tbl_klarifikasi_negosiasi',[
					'where'				=> [
						'id_klarifikasi'		=> $data['id'],
					],
					'sort_by'			=> 'id',
					'sort'				=> 'DESC',
					'limit'				=> 1
				])->row();
				$last_penawaran 		= get_data('tbl_klarifikasi_negosiasi',[
					'where'				=> [
						'id_klarifikasi'		=> $data['id'],
						'penawaran_vendor >'	=> 0,
					],
					'sort_by'			=> 'id',
					'sort'				=> 'DESC',
					'limit'				=> 1
				])->row();
				$data['penawaran_terakhir']	= isset($last_penawaran->id) ? $last_penawaran->penawaran_vendor : $klarifikasi_vendor['nilai_total_penawaran'];
				$data['id_negosiasi']		= isset($last_negosiasi->id) ? $last_negosiasi->id : 0;
				$data['penawaran_panitia']	= isset($last_negosiasi->id) ? $last_negosiasi->penawaran_panitia : 0;
				$data['penawaran_vendor']	= isset($last_negosiasi->id) ? $last_negosiasi->penawaran_vendor : 0;
				$data['is_terakhir']		= isset($last_negosiasi->id) ? $last_negosiasi->penawaran_terakhir : 0;

				$pengajuan 				= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
				$data['hps']			= get_data('tbl_m_hps','nomor_hps',$pengajuan->no_hps)->row_array();
				$data['detail_hps']		= get_data('tbl_hps_detail','id_hps',$data['hps']['id'])->result_array();

				if(isset($last_negosiasi)) {
					$data['penawaran_panitia_detail'] = $data['penawaran_terakhir_detail'] = $data['detail_hps'];
					if(isset($last_negosiasi->id)) {
						$penawaran_panitia			= get_data('tbl_klarifikasi_detail',[
							'where'					=> [
								'id_detail'			=> $last_negosiasi->id,
								'tipe'				=> 'negosiasi',
								'tipe_detail'		=> 'panitia'
							],
							'sort_by'				=> 'id',
							'sort'					=> 'asc'
						])->result_array();
						$_p = [];
						foreach($penawaran_panitia as $p) {
							$_p[$p['id_hps_detail']]	= $p['price_unit'];
						}
						foreach($data['penawaran_panitia_detail'] as $k => $v) {
							$price_unit				= isset($_p[$v['id']]) ? $_p[$v['id']] : 0;
							$data['penawaran_panitia_detail'][$k]['price_unit'] 	= $price_unit;
							$data['penawaran_panitia_detail'][$k]['total_value'] 	= $price_unit * $v['quantity'];
						}
					} else {
						$data['penawaran_panitia_detail']	= [];
					}

					if(isset($last_penawaran->id)) {
						$penawaran_vendor			= get_data('tbl_klarifikasi_detail',[
							'where'					=> [
								'id_detail'			=> $last_penawaran->id,
								'tipe'				=> 'negosiasi',
								'tipe_detail'		=> 'vendor'
							],
							'sort_by'				=> 'id',
							'sort'					=> 'asc'
						])->result_array();
						$_p = [];
						foreach($penawaran_vendor as $p) {
							$_p[$p['id_hps_detail']]	= $p['price_unit'];
						}
						foreach($data['penawaran_terakhir_detail'] as $k => $v) {
							$price_unit				= isset($_p[$v['id']]) ? $_p[$v['id']] : 0;
							$data['penawaran_terakhir_detail'][$k]['price_unit'] 	= $price_unit;
							$data['penawaran_terakhir_detail'][$k]['total_value'] 	= $price_unit * $v['quantity'];
						}
					} else {
						$data['penawaran_terakhir_detail']	= [];
					}
				}
				$data['counter']	= 0;
				if($data['selesai_sesi'] != '0000-00-00 00:00:00') {
					$data['counter']		= strtotime($data['selesai_sesi']) - strtotime(date('Y-m-d H:i:s'));
				}
				$penawaran					= get_data('tbl_klarifikasi_lelang',[
					'where'					=> [
						'id_klarifikasi'	=> $data['id'],
						'id_vendor'			=> user('id_vendor'),
						'sesi'				=> $data['current_sesi']
					]
				])->row();
				$data['penawaran']			= isset($penawaran->id) ? $penawaran->penawaran : 0;
				$data['vendor']				= get_data('tbl_klarifikasi_vendor',[
					'where'					=> [
						'id_klarifikasi'	=> $data['id']
					],
					'sort_by'				=> 'nilai_total_penawaran',
					'sort'					=> 'ASC'
				])->result();
				$last_sesi 					= get_data('tbl_klarifikasi_lelang',[
					'where'					=> [
						'id_klarifikasi'	=> $data['id'],
						'sesi'				=> $data['current_sesi'] - 1,
						'penawaran >'		=> 0
					],
					'sort_by'				=> 'penawaran',
					'sort'					=> 'ASC'
				])->result();
				$data['max_penawaran']		= isset($data['vendor'][0]->nilai_total_penawaran) ? $data['vendor'][0]->nilai_total_penawaran : 0;
				if(isset($last_sesi[0]->penawaran)){
					$data['max_penawaran']	= $last_sesi[0]->penawaran;
				}
				foreach($data['vendor'] as $k => $v) {
					$data['vendor'][$k]->penawaran_sebelumnya 	= $data['current_sesi'] == 1 ? $data['vendor'][$k]->nilai_total_penawaran :  0;
					foreach($last_sesi as $l) {
						if($l->id_vendor == $v->id_vendor) {
							$data['vendor'][$k]->penawaran_sebelumnya	= $l->penawaran;
						}
					}
				}
				include_lang('pengadaan');
				render($data);
			} else render('404');
		} else render('404');
	}

	function satu_rekanan() {
		include_lang('pengadaan');
		$post 	= post();
		if($post['total_penawaran'] <= $post['p_terakhir']) {
			$data['id_klarifikasi']		= $post['id_klarifikasi'];

			$klarifikasi 	= get_data('tbl_klarifikasi','id',$data['id_klarifikasi'])->row();
			$panitia 		= get_data('tbl_panitia_pelaksana a',[
				'select'	=> 'a.userid,b.email',
				'join'		=> 'tbl_user b ON a.userid = b.id TYPE LEFT',
				'where'	=> [
					'a.id_m_panitia'		=> $klarifikasi->id_panitia,
					'a.nomor_pengajuan'	=> $klarifikasi->nomor_pengajuan
				]
			])->result();
			$id_user 		= $email_user = [];
			foreach($panitia as $p) {
				$id_user[]		= $p->userid;
				$email_user[]	= $p->email;
			}

			$k 				= get_data('tbl_klarifikasi','id',$data['id_klarifikasi'])->row();
			$pengajuan 		= get_data('tbl_pengajuan','nomor_pengajuan',$k->nomor_pengajuan)->row();
			$hps 			= get_data('tbl_m_hps','nomor_hps',$pengajuan->no_hps)->row();
			$price_unit 	= post('price_unit');

			if(is_array($price_unit) && count($price_unit)) {
				$total 	= 0;
				foreach($price_unit as $id_hps_detail => $p) {
					$dt 	= [
						'id_klarifikasi'	=> $data['id_klarifikasi'],
						'tipe'				=> 'negosiasi',
						'id_detail'			=> $post['id_negosiasi'],
						'tipe_detail'		=> 'vendor',
						'id_hps_detail'		=> $id_hps_detail,
						'price_unit'		=> str_replace('.', '', $p)
					];

					$s = insert_data('tbl_klarifikasi_detail',$dt);
					if($s) {
						$d_hps 	= get_data('tbl_hps_detail','id',$id_hps_detail)->row();
						if(isset($d_hps->id)) {
							$total += $dt['price_unit'] * $d_hps->quantity;
						}
					}
				}
				if($total) {
					$data['penawaran_vendor']	= $total;
				}
			}

			$save 		= update_data('tbl_klarifikasi_negosiasi',$data,'id',$post['id_negosiasi']);
			if($save) {
				$negosiasi 	= get_data('tbl_klarifikasi_negosiasi','id',$post['id_negosiasi'])->row();
				if($negosiasi->penawaran_terakhir) {
					update_data('tbl_klarifikasi',['status_klarifikasi'=>'CLOSE'],'id',$data['id_klarifikasi']);
					update_data('tbl_klarifikasi_vendor',['status_klarifikasi'=>'CLOSE','penawaran_terakhir'=>$data['penawaran_vendor']],'id_klarifikasi',$data['id_klarifikasi']);
				}
				if(count($id_user) > 0) {
					$jml 			= get_data('tbl_klarifikasi_negosiasi',[
						'select'	=> 'COUNT(id) AS jml',
						'where'	 	=> [
							'id_klarifikasi'	=> $data['id_klarifikasi']
						]
					])->row();
					$link				= base_url('pengadaan/klarifikasi_negosiasi/detail/'.encode_id($data['id_klarifikasi']));
					$desctiption 		= user('nama').' mengirimkan penawaran untuk Pengadaan <strong>'.$klarifikasi->nomor_pengadaan.'</strong>.';
					foreach($id_user as $iu) {
						$data_notifikasi 	= [
							'title'			=> 'Negosiasi',
							'description'	=> $desctiption,
							'notif_link'	=> $link,
							'notif_date'	=> date('Y-m-d H:i:s'),
							'notif_type'	=> 'info',
							'notif_icon'	=> 'fa-comments-dollar',
							'id_user'		=> $iu,
							'transaksi'		=> 'negosiasi',
							'id_transaksi'	=> $data['id_klarifikasi']
						];
						insert_data('tbl_notifikasi',$data_notifikasi);
					}

					if(count($email_user) && setting('email_notification')) {
						send_mail([
							'subject'				=> 'Negosiasi #'.$klarifikasi->nomor_pengadaan.' ('.$jml->jml.')',
							'to'					=> $email_user,
							'nama_pengadaan'		=> $klarifikasi->nama_pengadaan,
							'url'					=> $link
						]);
					}
				}
			}
			render([
				'status'	=> 'success',
				'message'	=> lang('penawaran_berhasil_dikirim')
			],'json');
		} else {
			render([
				'status'	=> 'failed',
				'message'	=> lang('penawaran_anda_tidak_boleh_melebihi_penawaran_sebelumnya')
			],'json');
		}
	}

	function lelang() {
		include_lang('pengadaan');
		$post 	= post();
		$k 				= get_data('tbl_klarifikasi','id',post('id_klarifikasi'))->row();
		$pengajuan 		= get_data('tbl_pengajuan','nomor_pengajuan',$k->nomor_pengajuan)->row();
		$hps 			= get_data('tbl_m_hps','nomor_hps',$pengajuan->no_hps)->row();
		$price_unit 	= post('price_unit');

		$post['penawaran']	= 0;

		if(is_array($price_unit) && count($price_unit)) {
			foreach($price_unit as $id_hps_detail => $p) {
				$d_hps 	= get_data('tbl_hps_detail','id',$id_hps_detail)->row();
				if(isset($d_hps->id)) {
					$post['penawaran'] += str_replace('.', '', $p) * $d_hps->quantity;
				}
			}
		}

		if($post['batas_penawaran'] >= $post['penawaran']) {
			$data 	= [
				'id_vendor'			=> user('id_vendor'),
				'sesi'				=> post('sesi'),
				'penawaran'			=> $post['penawaran'],
				'id_klarifikasi'	=> post('id_klarifikasi')
			];
			$check	= get_data('tbl_klarifikasi_lelang',[
				'where'		=> [
					'id_vendor'			=> user('id_vendor'),
					'sesi'				=> post('sesi'),
					'id_klarifikasi'	=> post('id_klarifikasi')
				]
			])->row();
			$id_lelang	= 0;
			if(isset($check->id)) {
				update_data('tbl_klarifikasi_lelang',$data,'id',$check->id);
				$id_lelang = $check->id;
			} else {
				$id_lelang = insert_data('tbl_klarifikasi_lelang',$data);
			}

			delete_data('tbl_klarifikasi_detail',[
				'id_detail'			=> $id_lelang,
				'tipe'				=> 'lelang',
				'id_klarifikasi'	=> post('id_klarifikasi'),
				'tipe_detail'		=> post('sesi')
			]);

			if(is_array($price_unit) && count($price_unit)) {
				foreach($price_unit as $id_hps_detail => $p) {
					$dt 	= [
						'id_klarifikasi'	=> post('id_klarifikasi'),
						'tipe'				=> 'lelang',
						'id_detail'			=> $id_lelang,
						'tipe_detail'		=> post('sesi'),
						'id_hps_detail'		=> $id_hps_detail,
						'price_unit'		=> str_replace('.', '', $p)
					];

					$s = insert_data('tbl_klarifikasi_detail',$dt);
				}
			}

			render([
				'status'	=> 'success',
				'message'	=> lang('penawaran_berhasil_dikirim')
			],'json');
		} else {
			render([
				'status'	=> 'failed',
				'message'	=> lang('penawaran_anda_tidak_boleh_melebihi_penawaran_terkecil_sebelumnya')
			],'json');
		}
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

}