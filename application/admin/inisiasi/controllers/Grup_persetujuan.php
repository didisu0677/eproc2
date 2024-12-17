<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grup_persetujuan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

    function index() {
		$data['user_approval']	= get_data('tbl_user',[
			'where'	=> [
				'is_active'	=> 1,
				'id_group'	=> id_group_access('approval_permintaan')
			]
		])->result_array();
		$data['user_disposisi']	= get_data('tbl_user',[
			'where'	=> [
				'is_active'	=> 1,
				'id_group'	=> id_group_access('disposisi')
			]
		])->result_array();
        $data['opt_id_divisi'] 	= get_data('tbl_m_divisi','is_active',1)->result_array();
		if(user('is_kanwil')) {
			$data['unit_kerja']	= get_data('tbl_m_unit','id = "'.user('id_unit_kerja').'"')->result_array();
		} else {
			$data['unit_kerja']	= get_data('tbl_m_unit','is_active = 1')->result_array();
		}
        render($data);
	}

	function data() {
		$config['access_view']	= false;
		if(get('id_unit_kerja')) $config['where']['id_unit_kerja'] = get('id_unit_kerja');
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data = get_data('tbl_master_group_persetujuan','id',post('id'))->row_array();
		$data['detail']	= get_data('tbl_detail_group_persetujuan',[
		    'where'		=> 'id_m_persetujuan = '.post('id'),
		    'sort_by'	=> 'limit_approval'
		])->result_array();
		render($data,'json');
	}

	function save() {
	    $data = post();
	    $nama_persetujuan	= post('nama_persetujuan');
	    $limit_approval		= post('limit_approval');
	    $username			= post('username');
	    $dt_master			= get_data('tbl_m_divisi','id',$data['id_divisi'])->row();
		if(isset($dt_master->id)) {
			$data['kode_divisi'] = $dt_master->kode;
			$data['nama_divisi'] = $dt_master->divisi;
		}
	    $data['is_active']	= 1;

		$response = save_data('tbl_master_group_persetujuan',$data,post(':validation'));
		if($response['status'] == 'success') {
		    delete_data('tbl_detail_group_persetujuan','id_m_persetujuan',$response['id']);
		    
		    $c = [];
		    foreach($nama_persetujuan as $i => $v) {
		        $c[$i] = [
					'id_m_persetujuan'	=> $response['id'],
					'id_unit_kerja'		=> $data['id_unit_kerja'],
					'kode_divisi'		=> $data['kode_divisi'],
		            'nama_persetujuan'	=> $nama_persetujuan[$i],
		            'limit_approval'	=> str_replace(['.',','],'',$limit_approval[$i]),
		            'userid'			=> $username[$i]
				];
				$dt_user = get_data('tbl_user','id',$username[$i])->row();
				$c[$i]['username']		= isset($dt_user->id) ? $dt_user->username : '';
				$c[$i]['nama_lengkap']	= isset($dt_user->id) ? $dt_user->nama : '';
			}
			if(count($c) > 0) insert_batch('tbl_detail_group_persetujuan',$c);
		}
		
		render($response,'json');
	}

	function delete() {
		$response = destroy_data('tbl_master_group_persetujuan','id',post('id'),[
			'id_m_persetujuan'	=> 'tbl_detail_group_persetujuan'
		]);
		
		render($response,'json');
	}
}