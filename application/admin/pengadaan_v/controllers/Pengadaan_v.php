<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengadaan_v extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$data = array();
		$module = uri_segment(1);
		$m_name = get_data('tbl_menu','target',$module)->row();
		if(isset($m_name->id)) {
			$access = get_data('tbl_user_akses',array('where_array'=>array('act_view'=>1,'id_group'=>user('id_group'))))->result();
			$id_menu = array(0);
			foreach($access as $a) {
				$id_menu[] = $a->id_menu;
			}
			$data['quick_link'] = get_data('tbl_menu',array('where_array'=>array('is_active'=>1,'parent_id'=>$m_name->id),'where_in'=>array('id'=>$id_menu),'sort_by'=>'urutan','sort'=>'ASC'))->result();
		}
		render($data,'view:home/welcome/quick_link');
	}

	function detil_pengadaan($id=0) {
		if($id && !get('id_awz')) {
			$pengadaan 	= get_data('tbl_pengadaan','id',$id)->row();
		} elseif(get('id_awz')) {
			$pengadaan 	= get_data('tbl_aanwijzing','id',get('id_awz'))->row();
		}
		if(isset($pengadaan->id)) {
			$pengajuan 	= get_data('tbl_pengajuan','nomor_pengajuan',$pengadaan->nomor_pengajuan)->row();
			if(isset($pengajuan->id)) {
				$hps 	= get_data('tbl_m_hps','nomor_hps',$pengajuan->no_hps)->row();
				if(isset($hps->id)) {
					include_lang('pengadaan');
					$data['detail']	= get_data('tbl_hps_detail','id_hps',$hps->id)->result_array();
					render($data, 'layout:false');
				} else echo lang('tidak_ada_data');
			} else echo lang('tidak_ada_data');
		} else echo lang('tidak_ada_data');
	}

}