<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hps extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['klasifikasi']	= get_data('tbl_m_klasifikasi a',[
			'select'			=> 'a.id AS _id, a.klasifikasi AS _klasifikasi, b.*',
			'join'				=> 'tbl_template_hps b ON a.id = b.id_klasifikasi TYPE LEFT',
			'where'				=> [
				'a.is_active'	=> 1,
				'a.pilihan'		=> 1
			]
		])->result_array();
		$data['unit']			= get_data('tbl_m_unit','is_active = 1')->result_array();
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
		if(menu()['access_additional']) {
			$config['button'][]	= button_serverside('btn-success','btn-print',['fa-print',lang('cetak_hps'),true],'act-print');
		}
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
		$data				= get_data('tbl_m_hps','id',post('id'))->row_array();
		$cb_nopengadaan		= get_data('tbl_m_hps a',[
			'select'		=> 'a.nomor_pengajuan,b.nama_pengadaan,b.tanggal_pengadaan,b.nama_divisi,b.mata_anggaran,b.besar_anggaran,b.usulan_hps',
			'join'			=> 'tbl_pengajuan b ON a.nomor_pengajuan = b.nomor_pengajuan type LEFT',
			'where'			=> [
				'a.id'		=> post('id'),
			]
		])->result();
		$data['nomor_pengajuan1']		= '<option value=""></option>';
		foreach($cb_nopengadaan as $d) {
			$data['nomor_pengajuan1']	.= '<option value="'.$d->nomor_pengajuan.'"
			data-nama_pengadaan="'.$d->nama_pengadaan.'"
			data-tanggal_pengadaan="'.$d->tanggal_pengadaan.'"
			data-divisi="'.$d->nama_divisi.'"
			data-mata_anggaran="'.$d->mata_anggaran.'"
			data-besar_anggaran="'.custom_format($d->besar_anggaran).'"
			data-usulan_hps="'.custom_format($d->usulan_hps).'"
			>'.$d->nomor_pengajuan.'  |  '.$d->nama_pengadaan.'</option>';
		}
		$data['detail']		= get_data('tbl_d_hps',[
			'where'			=> [
				'id_hps'	=> post('id')
			],
			'sort_by'		=> 'id'
		])->result_array();
		$data['items']		= $this->get_items($data['id_m_klasifikasi'],'return');
		render($data,'json');
	}

	function save() {
		$data 					= post();
		$items 					= post('item');
		$volume					= post('volume');
		$durasi					= post('durasi');
		$hps_satuan				= post('hps_satuan');
		$fee_management			= post('fee_management');
		$hps_total				= post('hps_total');
		$pengajuan 				= get_data('tbl_pengajuan','nomor_pengajuan',$data['nomor_pengajuan'])->row();
		if(isset($pengajuan->id)) {
			$delegasi 			= get_data('tbl_delegasi_pengadaan','nomor_delegasi',$pengajuan->nomor_delegasi)->row();
			if(isset($delegasi->id)) {
				$data['id_panitia']		= $delegasi->id_m_panitia;
				$data['nama_panitia']	= $delegasi->nama_panitia;
			}
		}

		$data['persen_fee']		= isset($data['persen_fee']) ? str_replace(',','.',$data['persen_fee']) : 0;
		$data['persen_jasa']	= isset($data['persen_jasa']) ? str_replace(',','.',$data['persen_jasa']) : 0;
		$data['persen_ppn']		= str_replace(',','.',$data['persen_ppn']);

		$klasifikasi 			= get_data('tbl_m_klasifikasi','id',$data['id_m_klasifikasi'])->row();
		$data['klasifikasi']	= isset($klasifikasi->klasifikasi) ? $klasifikasi->klasifikasi : '';

		$response 				= save_data('tbl_m_hps',$data,post(':validation'));
		if($response['status'] == 'success') {
			$dt_hps 			= get_data('tbl_m_hps','id',$response['id'])->row();
			update_data('tbl_pengajuan',[
				'no_hps'		=> $dt_hps->nomor_hps,
			],['nomor_pengajuan'=>$dt_hps->nomor_pengajuan]);

			// UPDATE INISIASI
			$cek_inisiasi 		= get_data('tbl_inisiasi_pengadaan','nomor_pengajuan',$dt_hps->nomor_pengajuan)->row();
			if(isset($cek_inisiasi->id)) {
				$d_inisiasi['hps_panitia']	= $dt_hps->total_hps_pembulatan;
				$cek_metode		= get_data('tbl_metode_pengadaan',[
					'where'		=> [
						'id'						=> $cek_inisiasi->id_metode_pengadaan,
						'limit_bawah_pengadaan <='	=> $dt_hps->total_hps_pembulatan,
						'limit_atas_pengadaan >='	=> $dt_hps->total_hps_pembulatan,
						'is_active'					=> 1
					]
				])->row();
				if(!isset($cek_metode->id) && $pengajuan->is_pos_approve == 0) {
					$d_inisiasi['id_metode_pengadaan']	= 0;
					$d_inisiasi['metode_pengadaan']		= '';
					$d_inisiasi['tipe_pengadaan']		= '';
					$d_inisiasi['id_vendor']			= '';
					$d_inisiasi['vendor']				= '';
				}
				update_data('tbl_inisiasi_pengadaan',$d_inisiasi,'nomor_pengajuan',$dt_hps->nomor_pengajuan);

				$d_rks['hps_panitia']					= $d_inisiasi['hps_panitia'];
				if(isset($d_inisiasi['metode_pengadaan'])) {
					$d_rks['metode_pengadaan']			= $d_inisiasi['metode_pengadaan'];
				}
				$d_rks['jenis_pengadaan']				= $cek_inisiasi->jenis_pengadaan;
				update_data('tbl_rks',$d_rks,'nomor_pengajuan',$dt_hps->nomor_pengajuan);
			}
			// END EDIT INISIASI

			if(isset($pengajuan->id) && $pengajuan->nomor_delegasi) {
				update_data('tbl_delegasi_pengadaan',['status_proses'=>1],'nomor_delegasi',$pengajuan->nomor_delegasi);
			}

			delete_data('tbl_d_hps','id_hps',$response['id']);
			foreach($items as $k => $i) {
				if(!isset($hps_total[$k])) {
					$rec 	= [
						'id_hps'	=> $dt_hps->id,
						'nomor_hps'	=> $dt_hps->nomor_hps,
						'nama_item'	=> $items[$k],
						'is_group'	=> 1
					];
				} else {
					$rec 	= [
						'id_hps'		=> $dt_hps->id,
						'nomor_hps'		=> $dt_hps->nomor_hps,
						'id_m_item'		=> $items[$k],
						'volume'		=> $volume[$k],
						'durasi'		=> is_array($durasi) && isset($durasi[$k]) ? $durasi[$k] : 0,
						'hps_satuan'	=> str_replace('.', '', $hps_satuan[$k]),
						'fee_management'=> is_array($fee_management) && isset($fee_management[$k]) ? str_replace('.','',$fee_management[$k]) : '',
						'hps_total'		=> str_replace('.', '', $hps_total[$k])
					];

					$check_items			= get_data('tbl_m_item a',[
						'select'			=> 'a.*,b.satuan',
						'join'				=> 'tbl_m_satuan b ON a.id_satuan = b.id type LEFT',
						'where' 			=> [
							'a.id' 			=> $rec['id_m_item']
						]
					])->row();

					if(isset($check_items->nama) && !empty($check_items->nama)) {
						$rec['satuan']		= $check_items->satuan;
						$rec['kode_item']	= $check_items->kode;
						$rec['nama_item']	= $check_items->nama;
						$rec['spesifikasi']	= $check_items->spesifikasi;
					}
				}

				insert_data('tbl_d_hps',$rec);
			}

			if($pengajuan->approve == 0 && $pengajuan->id_user_persetujuan == 0) {
				$this->load->helper('pengadaan');
				cek_approval($pengajuan->nomor_pengajuan);
			}
		}
		render($response,'json');
	}

	function delete() {
		$dt_hps 			= get_data('tbl_m_hps','id',post('id'))->row();
		$response 			= destroy_data('tbl_m_hps','id',post('id'),[
			'id_hps'		=> 'tbl_d_hps'
		]);
		if($response['status'] == 'success') {
			update_data('tbl_pengajuan',[
				'no_hps'	=> '',
			],['nomor_pengajuan'=>$dt_hps->nomor_pengajuan]);
		}
		render($response,'json');
	}

	function get_combo() {
		$a 				= array('0000');
		$pengadaan 		= get_data('tbl_panitia_pelaksana','userid',user('id'))->result();
		foreach($pengadaan as $ci) {
			$a[]		= $ci->nomor_pengajuan;
		}
		$cb_nopengadaan	= get_data('tbl_delegasi_pengadaan a',[
			'select'	=> 'a.nomor_pengajuan,a.nama_pengadaan,a.tanggal_pengadaan,b.nama_divisi,a.mata_anggaran,a.besar_anggaran,a.usulan_hps',
			'join'		=> 'tbl_pengajuan b ON a.nomor_pengajuan = b.nomor_pengajuan type LEFT',
			'where'		=> [
				'a.nomor_pengajuan'	=> $a,
				'b.no_hps'			=> '',
			]
		])->result();

		$data['nomor_pengajuan']    = '<option value=""></option>';
		foreach($cb_nopengadaan as $d) {
			$data['nomor_pengajuan'] .= '<option value="'.$d->nomor_pengajuan.'"
			data-nama_pengadaan="'.$d->nama_pengadaan.'"
			data-tanggal_pengadaan="'.$d->tanggal_pengadaan.'"
			data-divisi="'.$d->nama_divisi.'"
			data-mata_anggaran="'.$d->mata_anggaran.'"
			data-besar_anggaran="'.custom_format($d->besar_anggaran).'"
			data-usulan_hps="'.custom_format($d->usulan_hps).'"
			>'.$d->nomor_pengajuan.'  |  '.$d->nama_pengadaan.'</option>';
		}
		render($data,'json');
	}

	function get_items($id_klasifikasi="",$type='echo') {
		$id_klasifikasi = post('id_klasifikasi');
		$items 			= get_data('tbl_m_item a',[
			'select'	=> 'a.id,b.satuan,a.nama,a.spesifikasi,a.harga',
			'join'		=> 'tbl_m_satuan b ON a.id_satuan = b.id type LEFT',
			'where' 	=> [
				'a.is_active' => 1,
			]
		])->result();
		$data 			= '<option value=""></option>';
		foreach($items as $e) {
			$data 		.= '<option value="'.$e->id.'" data-satuan="'.$e->satuan.'" data-spesifikasi="'.$e->spesifikasi.'" data-harga="'.custom_format($e->harga).'">'.$e->nama.' | '.$e->spesifikasi.'</option>';
		}
		if($type == 'echo') echo $data;
		else return $data;
	}

	function cetak_hps($encode_id='') {
		$decode = decode_id($encode_id);
		if(count($decode) == 2) {
			$id 	= $decode[0];
			$data	= get_data('tbl_m_hps','id',$id)->row_array();
			if(isset($data['id'])) {
				$data['detail']		= get_data('tbl_d_hps',[
					'where'			=> [
						'id_hps'	=> $id
					],
					'sort_by'		=> 'id'
				])->result_array();
				$data['template']	= get_data('tbl_template_hps','id_klasifikasi',$data['id_m_klasifikasi'])->row_array();
				render($data,'pdf:landscape');
			} else {
				render('404');
			}
		} else {
			render('404');
		}
	}
}