<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manajemen_rekanan extends BE_Controller {

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

	function download_dokumen($id='') {
		$id = decode_id($id);
		if(isset($id[0]) && file_exists(FCPATH . 'assets/uploads/rekanan/'.$id[0].'/')) {
			$vendor = get_data('tbl_vendor','id',$id[0])->row();
			$filename = isset($vendor->id) ? 'dokumen_'.str_replace(' ','_',preg_replace("/[^A-Za-z0-9 ]/", '', $vendor->nama)) : 'dokumen';
			$this->load->library('zip');
			$this->zip->read_dir(FCPATH . 'assets/uploads/rekanan/'.$id[0].'/',false);
			$this->zip->download($id[0].'('.$filename.').zip');
		} else render('404');
	}

	function save_image() {
		$post 	= post();
		if($post['image']) {
			$fn 	= user('id').'_'.$post['tipe'].'.png';
			$file = FCPATH . 'assets/images/'.$fn;
			file_put_contents($file,base64_decode(str_replace('data:image/png;base64,','',str_replace('[removed]','',$post['image']))));
			$oldmask = umask(0);
			chmod($file, 0777);
			umask($oldmask);
		} elseif(is_array(post('image'))) {
			foreach(post('image') as $k => $v) {
				$fn 	= user('id').'_'.$post['tipe'].'_'.$k.'.png';
				$file = FCPATH . 'assets/images/'.$fn;
				file_put_contents($file,base64_decode(str_replace('data:image/png;base64,','',str_replace('[removed]','',$v))));
				$oldmask = umask(0);
				chmod($file, 0777);
				umask($oldmask);	
			}
		}
	}

}