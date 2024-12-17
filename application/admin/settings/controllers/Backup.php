<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends BE_Controller {
	
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$data['backup']	= scandir(FCPATH . 'assets/backup/');
		foreach($data['backup'] as $k => $f) {
			if(substr($f,0,1) == '.') unset($data['backup'][$k]);
		}
		rsort($data['backup']);
		$data['table']	= db_list_table();
		render($data);
	}
	
	function proccess() {
		ini_set('memory_limit', '-1');
		if(get_access('backup')['access_input'] && post('x') == 'x') {
			$backupdir = FCPATH . 'assets/backup/backup_'.date('Y_m_d_h_i');
			if(!is_dir($backupdir)) mkdir($backupdir, 0777, true);
			
			$table = post('table');
			$this->load->dbutil();
			$this->load->helper('file');
			foreach($table as $t) {
				$prefs = array(
					'tables'      => array($t),
					'format'      => 'sql',
					'filename'    => $t.'.sql'
				);
				$backup		= $this->dbutil->backup($prefs);
				$db_name 	= $t.'.sql';
				$save 		= $backupdir.'/'.$db_name;
				write_file($save, $backup);
			}
			
			$response = [
				'status'	=> 'success',
				'message'	=> lang('data_berhasil_dibackup')
			];
		} else {
			$response = [
				'status'	=> 'failed',
				'message'	=> lang('izin_ditolak')
			];	
		}
		render($response,'json');
	}

	function delete() {
		$backup = post('backup');
		$response 	= [
			'status'	=> 'failed',
			'message'	=> lang('izin_ditolak')
		];
		if(get_access('backup')['access_delete']) {
			delete_dir(FCPATH . 'assets/backup/'.$backup.'/');
			$response = [
				'status'	=> 'success',
				'message'	=> lang('data_berhasil_dihapus')
			];
		}
		render($response,'json');
	}

	function download() {
		ini_set('memory_limit', '-1');
		if(get('b') && is_dir(FCPATH . 'assets/backup/'.get('b')) && get_access('backup')['access_additional']) {
			$this->load->library('zip');
			$path = 'assets/backup/'.get('b');
			$this->zip->read_dir($path,false);
			$this->zip->download(get('b').'.zip');
		} else {
			flash_message('error',lang('izin_ditolak'));
			redirect('settings/backup');
		}
	}
	
}