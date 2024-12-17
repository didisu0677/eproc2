<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengadaan extends BE_Controller {

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

	function notif_aanwijzing($ref='ref',$encode_id='') {
		if(user('id_vendor')) {
			$id = decode_id($encode_id);
			if(count($id) == 2) {
				$aanwijzing 	= get_data('tbl_aanwijzing','id',$id[0])->row();
				if(isset($aanwijzing->id)) {
					$aanwijzing_vendor 	= get_data('tbl_aanwijzing_vendor','id_vendor = '.user('id_vendor').' AND nomor_aanwijzing = "'.$aanwijzing->nomor_aanwijzing.'"')->row();
					if(isset($aanwijzing_vendor->id)) redirect('pengadaan_v/aanwijzing_v/detail/'.encode_id([$aanwijzing_vendor->id,rand()]));
					else render('404');
				} else render('404');
			} else render('404');
		} else {
			redirect('pengadaan/aanwijzing/detail/'.$encode_id);
		}
	}

}