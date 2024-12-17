<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['id']			= 0;
		$id					= decode_id(get('i'));
		if(is_array($id) && isset($id[0])) $data['id'] = $id[0];
		if(user('id_group') > 2){
		    $data['divisi'] = get_data('tbl_m_divisi','is_active = 1 AND id = "'.user('id_divisi').'"')->result_array();
		}else{
		    $data['divisi'] = get_data('tbl_m_divisi','is_active=1')->result_array();
		}
		
		$data['mata_anggaran'] = get_data('tbl_mata_anggaran','is_active=1')->result_array();
		$data['proker'] = get_data('tbl_m_proker','is_active=1')->result_array();
		render($data);
	}

	function data() {
		$config = [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false
		];
		if(user('id_group') > 2){
			$config['where'] 	= [
				'tbl_pengajuan.id_divisi' 		=> user('id_divisi')
			];
		}
		$config['button'][]		= button_serverside('btn-success','btn-print',['fa-print',lang('cetak_tor'),true]);
		$config['button'][]		= button_serverside('btn-info','btn-act-view',['fa-search',lang('detil'),true],'view');
		if(menu()['access_edit']) {
			$config['button'][]	= button_serverside('btn-warning','btn-input',['fa-edit',lang('ubah'),true],'edit',['approve_user'=>[0,8]]);
		}
		if(menu()['access_delete']) {
			$config['button'][]	= button_serverside('btn-danger','btn-delete',['fa-trash-alt',lang('hapus'),true],'delete',['approve_user'=>[0,8]]);
		}

		if(user('is_kanwil')==1){
			$config['where']['tbl_pengajuan.id_unit_kerja']	= user('id_unit_kerja');
		}
		
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data 					= get_data('tbl_pengajuan','id',post('id'))->row_array();
		$data['file'] 			= json_decode($data['file'],true);
		render($data,'json');
	}

	function save() {
		$data = post();
			
		$data['latar_belakang'] 		= post('latar_belakang','html');
		$data['spesifikasi'] 			= post('spesifikasi','html');
		$data['ruang_lingkup'] 			= post('ruang_lingkup','html');
		$data['distribusi_kebutuhan'] 	= post('distribusi_kebutuhan','html');
		$data['jangka_waktu'] 			= post('jangka_waktu','html');
		$data['jumlah_kebutuhan'] 		= post('jumlah_kebutuhan','html');
		$data['lain_lain'] 				= post('lain_lain','html');
		$data['status']					= 1; // langsung di proses

		$sap 							= get_data('sap_header','purchase_req_item',post('purchase_req_item'))->row();
		$data['jabatan_pemberi_tugas']	= $sap->jabatan;
		$data['nama_pengadaan']			= $sap->nama_pengadaan;
		$data['usulan_hps']				= $sap->total_usulan;

		$unit 							= get_data('tbl_m_unit','id',user('id_unit_kerja'))->row();
		if(isset($unit->id)) {
			$data['id_unit_kerja']		= $unit->id;
			$data['kode_unit_kerja']	= $unit->kode;
			$data['unit_kerja']			= $unit->unit;
		}

		if ($data['usulan_hps'] > $data['besar_anggaran']){
			$response = [
				'status'	=> 'info',
				'message' 	=> lang('msg_hps_lebih_besar_dari_anggaran')
			];
		} else {
			$divisi = get_data('tbl_m_divisi','kode',$sap->kode_divisi)->row();
			$data['id_divisi'] 		= isset($divisi->id) ? $divisi->id : '';
			$data['kode_divisi'] 	= isset($divisi->id) ? $divisi->kode : '';
			$data['nama_divisi'] 	= isset($divisi->id) ? $divisi->divisi : '';

			$persetujuan_user 	= $persetujuan_pengadaan = [];
			$user_tederkat		= get_data('tbl_detail_group_persetujuan',[
				'where' => [
					'kode_divisi'		=> $data['kode_divisi'],
					'id_unit_kerja'		=> user('id_unit_kerja'),
					'limit_approval >='	=> $data['usulan_hps']
				],
				'sort_by'	=> 'limit_approval','sort'=>'ASC',
				'limit'		=> 1
			])->row();
			
			if(isset($user_tederkat->id)) {
				$persetujuan_user 	= get_data('tbl_detail_group_persetujuan',[
					'where' => [
						'kode_divisi'		=> $data['kode_divisi'],
						'id_unit_kerja'		=> user('id_unit_kerja'),
						'limit_approval <='	=> $user_tederkat->limit_approval
					],
					'sort_by'=>'limit_approval','sort'=>'ASC'    
				])->result();
			}
			
			if($data['status'] == 1 && count($persetujuan_user) == 0) {
				$response = [
					'status'	=> 'info',
					'message' 	=> lang('alur_persetujuan_tidak_ditemukan')
				];
			} else {
				if(!$data['id']) {
					$data['id_user_creator'] = user('id');
				}
				$last_file = [];
				if($data['id']) {
					$dt = get_data('tbl_pengajuan','id',$data['id'])->row();
					if(isset($dt->id)) {
						$lf 	= json_decode($dt->file,true);
						foreach($lf as $l) {
							$last_file[$l] = $l;
						}
					}
				}
				$file 						= post('file');
				$keterangan_file 			= post('keterangan_file');
				$filename 					= [];
				$dir 						= '';
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
								if(@copy($f, FCPATH . 'assets/uploads/pengajuan/'.basename($f))) {
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
					@unlink(FCPATH . 'assets/uploads/pengajuan/' . $lf);
				}
				$data['file']					= json_encode($filename);

				$mataanggaran = get_data('tbl_mata_anggaran','id',$data['id_mata_anggaran'])->row();
				$data['mata_anggaran'] = $mataanggaran->nama_anggaran;
				
				$proker = get_data('tbl_m_proker','id',$data['id_proker'])->row();
				$data['nama_proker'] = $proker->nama_program_kerja;

				if($data['id']) {
					$data['jabatan_modifier']	= user('jabatan');
				} else {
					$data['jabatan_creator']	= user('jabatan');
				}

				$response = save_data('tbl_pengajuan',$data,post(':validation'));
				if($response['status'] == 'success' && $data['status'] == 1 && post('ajukan')) {
					$dt_pengadaan = get_data('tbl_pengajuan','id',$response['id'])->row();

					update_data('sap_header',['no_pengajuan'=>$dt_pengadaan->nomor_pengajuan],'purchase_req_item',$dt_pengadaan->purchase_req_item);

					if(in_array($dt_pengadaan->approve_user,[0,8]) && $dt_pengadaan->id_user_persetujuan == 0) {
						delete_data('tbl_alur_persetujuan','nomor_pengajuan',$dt_pengadaan->nomor_pengajuan);

						$i = 1;
						foreach($persetujuan_user as $m) {
							$data_p = [
								'id_pengajuan'		=> $dt_pengadaan->id,
								'nomor_pengajuan'	=> $dt_pengadaan->nomor_pengajuan,
								'level_persetujuan'	=> $i,
								'nama_persetujuan'	=> $m->nama_persetujuan,
								'jenis_approval'	=> 'PERMINTAAN',
								'id_user'			=> $m->userid,
								'username'			=> $m->username,
								'nama_user'			=> $m->nama_lengkap
							];
							
							insert_data('tbl_alur_persetujuan',$data_p);
							$i++;
						}
						
						$next_tabel_persetujuan  = get_data('tbl_alur_persetujuan',[
							'where'=> [
								'nomor_pengajuan'		=> $dt_pengadaan->nomor_pengajuan,
								'tanggal_persetujuan' 	=> '0000-00-00 00:00:00',
							],
							'sort_by'=>'level_persetujuan','sort'=>'ASC'  
						])->row();
						
						if(isset($next_tabel_persetujuan->id)){
							update_data('tbl_pengajuan',[
								'id_user_persetujuan'	=> $next_tabel_persetujuan->id_user,
								'nama_persetujuan' 		=> $next_tabel_persetujuan->nama_persetujuan,
								'tanggal_pengajuan'		=> date('Y-m-d')
							],'nomor_pengajuan',$dt_pengadaan->nomor_pengajuan);

							// kirim notifikasi ke approver
							$usr 				= get_data('tbl_user','id',$next_tabel_persetujuan->id_user)->row();
							if(isset($usr->id)) {
								$link				= base_url().'pengadaan/approval_permintaan?i='.encode_id([$response['id'],rand()]);
								$desctiption 		= 'Pengajuan pengadaan dengan no. <strong>'.$dt_pengadaan->nomor_pengajuan.'</strong> membutuhkan persetujuan anda';
								$data_notifikasi 	= [
									'title'			=> 'Pengajuan Pengadaan',
									'description'	=> $desctiption,
									'notif_link'	=> $link,
									'notif_date'	=> date('Y-m-d H:i:s'),
									'notif_type'	=> 'info',
									'notif_icon'	=> 'fa-file-alt',
									'id_user'		=> $usr->id,
									'transaksi'		=> 'pengajuan',
									'id_transaksi'	=> $response['id']
								];
								insert_data('tbl_notifikasi',$data_notifikasi);

								if(setting('email_notification') && $usr->email) {
									send_mail([
										'subject'		=> 'Pengajuan Pengadaan #'.$dt_pengadaan->nomor_pengajuan,
										'to'			=> $usr->email,
										'nama_user'		=> $usr->nama,
										'description'	=> $desctiption,
										'url'			=> $link
									]);
								}
							}
						}
						$status_desc	= isset($usr->id) ? 'Persetujuan Pengajuan (Menunggu : '.$usr->nama.')' : 'Menunggu Persetujuan (Menunggu)';
						update_data('tbl_pengajuan',['status_desc'=>$status_desc,'approve_user'=>0],'id',$response['id']);
					}
				}
			}
		}
		render($response,'json');
	}

	function delete() {
		$dt 		= get_data('tbl_pengajuan','id',post('id'))->row();
		$response = destroy_data('tbl_pengajuan','nomor_pengajuan',$dt->nomor_pengajuan,[
			'nomor_pengajuan'	=> [
				'tbl_alur_persetujuan','tbl_disposisi','tbl_delegasi_pengadaan','tbl_inisiasi_pengadaan','tbl_jadwal_pengadaan','tbl_panitia_pelaksana','tbl_dokumen_persyaratan'
			]
		]);
		if($response['status'] == 'success') {
			if(isset($dt->file) && $dt->file) {
				$file = json_decode($dt->file,true);
				foreach($file as $f) {
					@unlink(FCPATH . 'assets/uploads/pengajuan/'.$f);
				}
			}
		}
		render($response,'json');
	}

	function detail($id=0) {
		$no_pengajuan = get('no_pengajuan');
		$data	= get_data('tbl_pengajuan','id = "'.$id.'" OR nomor_pengajuan = "'.$no_pengajuan.'"')->row_array();
		if(isset($data['id'])) {
			render($data,'layout:false access:true');
		} else render(lang('tidak_ada_data'));
	}
	
	function cetak_tor($encode_id=''){
		$decode = decode_id($encode_id);
		if(count($decode) == 2) {
			$id 						= $decode[0];
			$record						= get_data('tbl_pengajuan','id',$id)->row_array();
			$tanggal_tor				= $record['tanggal_tor'];
			$ttd  						= get_data('tbl_alur_persetujuan',[
				'where'					=> [
					'jenis_approval'	=> 'PERMINTAAN',
					'nomor_pengajuan'	=> $record['nomor_pengajuan']
				],
				'sort_by'				=> 'level_persetujuan'
			])->result();
			$record['ttd_persetujuan']	= include_view('pengadaan/pengajuan/view_ttd',['tor'=>$record,'ttd'=>$ttd]);
			$record['besar_anggaran']	= custom_format($record['besar_anggaran']);
			$record['usulan_hps']		= custom_format($record['usulan_hps']);
			$data['html']				= template_pdf($record,'tor',$tanggal_tor);
			render($data,'pdf');
		} else {
			render('404');
		}
	}

	function browse_req() {
		$data['layout']	= 'browse';
		render($data);
	}

	function data_sap() {
		$unit 						= get_data('tbl_m_unit','id',user('id_unit_kerja'))->row();
		$kode_unit 					= isset($unit->id) ? $unit->kode : 'XX';
		$config 					= [
			'access_edit'			=> false,
			'access_view'			=> false,
			'access_delete'			=> false,
			'where'					=> [
				'plant'				=> $kode_unit,
				'kode_divisi'		=> user('kode_divisi'),
				'no_pengajuan'		=> ''
			]
		];
		$config['button'][] 	= button_serverside('btn-success','btn-act-choose',['fa-check',lang('pilih'),true],'btn-act-choose');
		$data = data_serverside($config);
		render($data,'json');
	}

	function detail_sap() {
		if(get('req_no')) {
			$data	= get_data('sap_header','purchase_req_item',get('req_no'))->row_array();
		}
		if(get('no_pengajuan')) {
			$p 		= get_data('tbl_pengajuan','nomor_pengajuan',get('no_pengajuan'))->row_array();
			if(isset($p['id'])) {
				$data 	= get_data('sap_header','purchase_req_item',$p['purchase_req_item'])->row_array();
			}
		}
		if(isset($data['id'])) {
			$data['rm']		= get('req_no') && get('rm') ? true : false;
			$where 			= $data['rm'] ? 'purchase_req_item = "'.$data['purchase_req_item'].'"' : 'purchase_req_item = "'.$data['purchase_req_item'].'" AND is_deleted = 0';
			$data['detail']	= get_data('sap_detail',[
				'where'		=> $where,
				'sort_by'	=> 'id',
				'sort'		=> 'asc'
			])->result_array();
			render($data,'layout:false',true);
		} else echo lang('tidak_ada_data');
	}

	function act_sap($t='del') {
		$is_deleted = $t == 'del' ? 1 : 0;
		$date 		= $t == 'del' ? date('Y-m-d H:i:s') : '0000-00-00 00:00:00';
		$sap 		= get_data('sap_detail','id',post('id'))->row();
		if(isset($sap->id)) {
			$save = update_data('sap_detail',[
				'is_deleted'	=> $is_deleted,
				'deleted_date'	=> $date
			],'id',post('id'));
			if($save) {
				$total 	= get_data('sap_detail',[
					'select'	=> 'SUM(total_value) AS jml',
					'where'		=> [
						'purchase_req_item'	=> $sap->purchase_req_item,
						'is_deleted'		=> 0
					],
					'sort_by'	=> 'id',
					'sort'		=> 'asc'
				])->row();
				update_data('sap_header',['total_usulan'=>$total->jml],'purchase_req_item',$sap->purchase_req_item);
			}
		}
		render([
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_diperbaharui')
		],'json');
	}

	function hps_usulan($encode_id='') {
		$id 	= decode_id($encode_id);
		$data 	= get_data('tbl_pengajuan','id',$id[0])->row_array();
		if(isset($data['id'])) {
			$data['detail']	= get_data('sap_detail',[
				'where'	=> [
					'purchase_req_item'	=> $data['purchase_req_item'],
					'is_deleted'		=> 0
				],
				'sort_by'	=> 'id',
				'sort'		=> 'asc'
			])->result_array();
			render($data,'pdf');
		} else render('404');
	}

}
