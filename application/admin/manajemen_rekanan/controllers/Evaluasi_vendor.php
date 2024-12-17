<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluasi_vendor extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['m_evaluasi'] = get_data('tbl_m_penilaian_vendor','is_active',1)->result();
		render($data);
	}

	function data() {
		$config['button'][]		= button_serverside('btn-success','btn-print',['fa-print',lang('cetak_tor'),true]);

		$data = data_serverside($config);

		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_evaluasi_vendor','id',post('id'))->row_array();
        $data['aspek_evaluasi']= json_decode($data['aspek_evaluasi'],true);
        $data['lain'] = $data['aspek_evaluasi']['lain'];
		render($data,'json');
	}

	function save() {
		$data = post();
		$id_dok 		= post('id_dok');
		$nama_evaluasi  = post('nama_evaluasi');
		$keterangan 	= post('keterangan_lain');
		$check_a = post('check_a');
		$check_b = post('check_b');
		$check_c = post('check_c');
		$check_d = post('check_d');
		$check_e = post('check_e');
		$evaluasi_lain	= post('evaluasi_lain');
		$checklain_a = post('checklain_a');
		$checklain_d = post('checklain_b');
		$checklain_c = post('checklain_c');
		$checklain_d = post('checklain_d');
		$checklain_e = post('checklain_e');

		$pengadaan = get_data('tbl_pemenang_pengadaan','nomor_pengadaan',post('nomor_pengadaan'))->row();
		if(isset($pengadaan->nama_pengadaan)) {
			$data['nama_pengadaan'] = $pengadaan->nama_pengadaan;
		}
        


		$aspek_evaluasi = [];
		foreach($nama_evaluasi as $k => $v) {
			$aspek_evaluasi[$k]	= [
				'nama_evaluasi'	=> $nama_evaluasi[$k],
				'sangat_baik'	=> isset($check_a[$k]) ? 1 : 0,
				'baik'			=> isset($check_b[$k]) ? 1 : 0,
				'cukup_baik'	=> isset($check_c[$k]) ? 1 : 0,
				'kurang_baik'	=> isset($check_d[$k]) ? 1 : 0,
				'tidak_baik'	=> isset($check_e[$k]) ? 1 : 0,
			];

		}
	//	$data['aspek_evaluasi']		= json_encode($aspek_evaluasi);
	//	debug($data['aspek_evaluasi']);die;



		$aspek_evaluasi['lain']	= [];
		if(is_array($evaluasi_lain) && count($evaluasi_lain) > 0) {
			foreach($evaluasi_lain as $k => $v) {
				$aspek_evaluasi['lain'][$k]	= [
					'nama_evaluasi'	=> $evaluasi_lain[$k],
					'sangat_baik'	=> isset($checklain_a[$k]) ? 1 : 0,
					'baik'			=> isset($checklain_b[$k]) ? 1 : 0,
					'cukup_baik'	=> isset($checklain_c[$k]) ? 1 : 0,
					'kurang_baik'	=> isset($checklain_d[$k]) ? 1 : 0,
					'tidak_baik'	=> isset($checklain_e[$k]) ? 1 : 0,
				];
			}
		}

	//	debug($aspek_evaluasi['lain']);die;

		$data['aspek_evaluasi']		= json_encode($aspek_evaluasi);

		$response 		= save_data('tbl_evaluasi_vendor',$data,[],true);


		render($response,'json');
	}

	function delete() {		
		$response = destroy_data('tbl_evaluasi_vendor','id',post('id'));
		render($response,'json');
	}

	function get_combo(){
		$cek_evaluasi	= get_data('tbl_evaluasi_vendor')->result();
		$nomor 			= [''];
		foreach($cek_evaluasi as $c) $nomor[] = $c->nomor_pengadaan;

		$cb_nopengadaan	= get_data('tbl_pemenang_pengadaan a',[
			'select'	=> 'a.nomor_pengadaan, a.nama_pengadaan, a.tanggal_pengadaan, a.keterangan_pengadaan,a.penawaran_terakhir as nilai_kontrak, a.id_vendor,a.nama_vendor,a.alamat_vendor',
			'where' => [
				'a.nomor_pengadaan not' => $nomor, 
			],
		])->result();

		$data['pengadaan']				=  [];
		foreach($cb_nopengadaan as $d) {
			$data['pengadaan'][$d->nomor_pengadaan] 			= $d;
	
		}


		render($data,'json');
	}

	function template() {
		ini_set('memory_limit', '-1');
		$arr = ['nomor' => 'nomor','nomor_pengadaan' => 'nomor_pengadaan','nama_pengadaan' => 'nama_pengadaan','id_vendor' => 'id_vendor','nama_vendor' => 'nama_vendor','nilai_kontrak' => 'nilai_kontrak','hasil_rekomendasi' => 'hasil_rekomendasi','nama_evaluator' => 'nama_evaluator','jabatan' => 'jabatan','is_active' => 'is_active'];
		$config[] = [
			'title' => 'template_import_evaluasi_vendor',
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function import() {
		ini_set('memory_limit', '-1');
		$file = post('fileimport');
		$col = ['nomor','nomor_pengadaan','nama_pengadaan','id_vendor','nama_vendor','nilai_kontrak','hasil_rekomendasi','nama_evaluator','jabatan','is_active'];
		$this->load->library('simpleexcel');
		$this->simpleexcel->define_column($col);
		$jml = $this->simpleexcel->read($file);
		$c = 0;
		foreach($jml as $i => $k) {
			if($i==0) {
				for($j = 2; $j <= $k; $j++) {
					$data = $this->simpleexcel->parsing($i,$j);
					$data['create_at'] = date('Y-m-d H:i:s');
					$data['create_by'] = user('nama');
					$save = insert_data('tbl_evaluasi_vendor',$data);
					if($save) $c++;
				}
			}
		}
		$response = [
			'status' => 'success',
			'message' => $c.' '.lang('data_berhasil_disimpan').'.'
		];
		@unlink($file);
		render($response,'json');
	}

	function export() {
		ini_set('memory_limit', '-1');
		$arr = ['nomor' => 'Nomor','nomor_pengadaan' => 'Nomor Pengadaan','nama_pengadaan' => 'Nama Pengadaan','id_vendor' => 'Id Vendor','nama_vendor' => 'Nama Vendor','nilai_kontrak' => 'Nilai Kontrak','hasil_rekomendasi' => 'Hasil Rekomendasi','nama_evaluator' => 'Nama Evaluator','jabatan' => 'Jabatan','is_active' => 'Aktif'];
		$data = get_data('tbl_evaluasi_vendor')->result_array();
		$config = [
			'title' => 'data_evaluasi_vendor',
			'data' => $data,
			'header' => $arr,
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

	function cetak_evaluasi($encode_id='') {
	    $id = decode_id($encode_id);
	    $id = isset($id[0]) ? $id[0] : 0;
	    $data 		= get_data('tbl_evaluasi_vendor','id',$id)->row_array();
	    if(isset($data['id'])) {
	        $data['result']		= $data['aspek_evaluasi'] ? json_decode($data['aspek_evaluasi'],true) : [];
	        $data['lain']		= isset($data['result']['lain']) ? $data['result']['lain'] : [];

	        unset($data['result']['lain']);

	        $data['kriteria']		= get_data('tbl_m_indikator_kriteria','is_active',1)->result_array();

        //	debug($data);die;
	        render($data,'pdf');
	    } else render('404');
	}    

		function detail($id=0) {
		$data 				= get_data('tbl_evaluasi_vendor','id',$id)->row_array();

        $data['result']		= $data['aspek_evaluasi'] ? json_decode($data['aspek_evaluasi'],true) : [];
        $data['lain']		= isset($data['result']['lain']) ? $data['result']['lain'] : [];

        unset($data['result']['lain']);

		render($data,'layout:false');
	}
}