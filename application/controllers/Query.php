<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Query extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		if(setting('query') || user('id_group') == 1 || get('_k') == FQUERY) {
			$data['title']		= 'Query';
			$data['list_table']	= list_tables();
			render($data);
		} else {
			render('404');
		}
	}

	function proses() {
		$query = post('query');		
		if(stripos($query,'limit') === false) {
			$query .= ' LIMIT 100';
		} else {
			$q = substr($query,stripos($query,'limit'));
			$e = explode(' ',$q);
			if(count($e) >= 2) {
				if($e[1] > 100) {
					$query = str_ireplace('limit '.$e[1],'LIMIT 100',$query);
				}
			}
		}
		if(strtolower(substr($query,0,7)) == 'select ') {
			$data['query']	= post('query');
			$data['record']	= db_query($query)->result_array();
		} elseif(substr($query, 0, strlen(FQUERY) + 1) == FQUERY . ' ') {
			$query 			= str_replace(FQUERY.' ', '', post('query'));
			$allowed 		= explode('|', ALLOWED_FQUERY);
			$split_query 	= explode(' ', $query);
			if(in_array(strtolower($split_query[0]), $allowed)) {
				$data['query']	= $query;
				db_query($query);
				$data['record']	= [];
			} else {
				$data['error']	= true;				
			}
		} else {
			$data['error']	= true;
		}
		render($data,'layout:false');
	}

	function export() {
		ini_set('memory_limit', '-1');
		$query 	= post('query');
		$data 	= db_query($query)->result_array();
		$config	= [
			'title'	=> 'query_'.date('YmdHis'),
			'data'	=> $data
		];
		$this->load->library('simpleexcel',$config);
		$this->simpleexcel->export();
	}

}