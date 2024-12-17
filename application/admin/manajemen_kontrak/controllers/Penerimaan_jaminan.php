<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penerimaan_jaminan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data() {
		$config				= [
			'where'			=> [
				'status'	=> get('status') == 1 ? 1 : 0
			]
		];
		if(get('status') == 1) {
			$config['access_edit']		= false;
			$config['access_delete']	= false;
		}
		if(user('is_kanwil')) {
			$config['where']['id_unit_kerja']	= user('id_unit_kerja');
		}
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_jaminan','id',post('id'))->row_array();
		render($data,'json');
	}

	function get_spk() {
		$query 	= get('query');
		$detail = user('is_kanwil') ? ' AND id_unit_kerja = "'.user('id_unit_kerja').'"' : '';
		$spk 	= get_data('tbl_pemenang_pengadaan','status_sanggah = 1 AND (nomor_spk LIKE "%'.$query.'%" OR nama_pengadaan LIKE "%'.$query.'%") AND status_jaminan = 0'.$detail)->result();
		$data['suggestions'] = [];
		foreach($spk as $u) {
			$data['suggestions'][] = [
				'value'				=> $u->nomor_spk.' | '.$u->nama_pengadaan,
				'data'				=> $u->nomor_spk,
				'nama_pengadaan'	=> $u->nama_pengadaan,
				'nama_vendor'		=> $u->nama_vendor,
				'nilai_pengadaan'	=> $u->penawaran_terakhir
			];
		}
		render($data,'json');
	}

	function save() {
		$data 			= post();
		$spk 			= get_data('tbl_pemenang_pengadaan','nomor_spk',$data['nomor_spk'])->row();
		if($data['nomor_spk'] && isset($spk->id)) {
			$data['id_unit_kerja']			= $spk->id_unit_kerja;
			$data['nomor_pengadaan']		= $spk->nomor_pengadaan;
			$data['nama_pengadaan']			= $spk->nama_pengadaan;
			$data['id_vendor']				= $spk->id_vendor;
			$data['nama_vendor']			= $spk->nama_vendor;
			$data['nilai_pengadaan']		= $spk->penawaran_terakhir;

			$id_penerima_reminder			= [$spk->id_creator];
			$panitia 						= get_data('tbl_panitia_pelaksana',[
				'where'						=> [
					'nomor_pengajuan'		=> $spk->nomor_pengajuan,
					'id_m_panitia'			=> $spk->id_panitia
				]
			])->result();
			foreach($panitia as $p) {
				$id_penerima_reminder[]		= $p->userid;
			}
			$penerima_reminder 				= get_data('tbl_user',[
				'where'						=> [
					'id'					=> $id_penerima_reminder,
					'OR id_vendor'			=> $spk->id_vendor
				]
			])->result();
			$email_reminder 				= [];
			foreach($penerima_reminder as $p) {
				$email_reminder[]			= $p->email;
			}
			$data['penerima_reminder']		= json_encode($email_reminder);
			$response = save_data('tbl_jaminan',$data,post(':validation'));
			if($response['status'] == 'success' && isset($spk->id)) {
				update_data('tbl_pemenang_pengadaan',['status_jaminan'=>1],'nomor_spk',$spk->nomor_spk);
			}
			render($response,'json');
		} else {
			render([
				'status'	=> 'failed',
				'message'	=> lang('data_gagal_disimpan')
			],'json');
		}
	}

	function delete() {
		$dt 		= get_data('tbl_jaminan','id',post('id'))->row();
		$response 	= destroy_data('tbl_jaminan','id',post('id'));
		if($response['status'] == 'success' && isset($dt->id)) {
			update_data('tbl_pemenang_pengadaan',['status_jaminan'=>0],'nomor_spk',$dt->nomor_spk);
		}
		render($response,'json');
	}

	function detail($id=0) {
		$data 	= get_data('tbl_jaminan','id',$id)->row_array();
		if(isset($data['id'])) {
			render($data,'layout:false');
		} else echo lang('tidak_ada_data');
	}

}