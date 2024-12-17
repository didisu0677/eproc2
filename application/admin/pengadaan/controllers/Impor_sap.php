<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Impor_sap extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function proses() {
		ini_set('memory_limit', '-1');
		$doc_type	= post('tipe');
		$file 		= post('fileimport');
		$split_file	= explode('.', basename($file));
		$ext 		= strtolower($split_file[count($split_file)-1]);
		$pr_item 	= [];

		if($doc_type == 'PR') {
			$cols 		= get_field('sap_detail','name');
			$pr_type 	= [];
			foreach(['pr_persediaan','pr_jasa','pr_biaya','pr_asset'] as $p) {
				if(setting($p)) $pr_type[] = setting($p);
			}
			$col 		= [];
			foreach($cols as $c) {
				if($c != 'id' && $c != 'is_deleted' && $c != 'deleted_date') $col[]	= $c;
			}
			$jml_input	= $jml_edit = 0;
			if(in_array($ext, ['xls','xlsx'])) {
				$this->load->library('simpleexcel');
				$this->simpleexcel->define_column($col);
				$jml = $this->simpleexcel->read($file);
				$first_id 	= 0;
				foreach($jml as $i => $k) {
					if($i==0) {
						for($j = 1; $j <= $k; $j++) {
							$data = $this->simpleexcel->parsing($i,$j);
							$delivery_date	= str_replace(['/','-'.' '], '.', $data['delivery_date']);
							$split_date		= explode('.', $delivery_date);
							if(count($split_date) == 3) {
								$data['delivery_date']	= $split_date[2].'-'.$split_date[1].'-'.$split_date[0];
							}
							$data['quantity']	= str_replace(['.',','], '', $data['quantity']);
							$data['price_unit']	= str_replace(['.',','], '', $data['price_unit']);
							$data['total_value']	= str_replace(['.',','], '', $data['total_value']);
							if(in_array($data['pr_type'], $pr_type)) {
								$pr_item[$data['purchase_req_item']]	= $data['purchase_req_item'];
								$check_record = get_data('sap_detail',[
									'where'		=> [
										'purchase_req_item'	=> $data['purchase_req_item'],
										'item_no'			=> $data['item_no']
									]
								])->row();
								if(isset($check_record->id)) {
									update_data('sap_detail',$data,'id',$check_record->id);
									$jml_edit++;
								} else {
									insert_data('sap_detail',$data);
									$jml_input++;
								}
							}
						}
					}
				}
			} elseif($ext == 'csv') {
				$delimiter 	= $this->detectDelimiter($file);
				if (($handle = fopen($file, "r")) !== FALSE) {
					while (($row_data = fgetcsv($handle, 4096, $delimiter)) !== FALSE) {
						$num 	= count($row_data);
						$data 	= [];
						if(isset($row_data[0]) && in_array($row_data[0], $pr_type)) {
							for ($c=0; $c < $num; $c++) {
								$data[$col[$c]]	= $row_data[$c];
							}
						}
						if(count($data) > 0) {
							$delivery_date	= str_replace(['/','-'.' '], '.', $data['delivery_date']);
							$split_date		= explode('.', $delivery_date);
							if(count($split_date) == 3) {
								$data['delivery_date']	= $split_date[2].'-'.$split_date[1].'-'.$split_date[0];
							}
							$data['quantity']	= str_replace(['.',','], '', $data['quantity']);
							$data['price_unit']	= str_replace(['.',','], '', $data['price_unit']);
							$data['total_value']	= str_replace(['.',','], '', $data['total_value']);
							if(in_array($data['pr_type'], $pr_type)) {
								$pr_item[$data['purchase_req_item']]	= $data['purchase_req_item'];
								$check_record = get_data('sap_detail',[
									'where'		=> [
										'purchase_req_item'	=> $data['purchase_req_item'],
										'item_no'			=> $data['item_no']
									]
								])->row();
								if(isset($check_record->id)) {
									update_data('sap_detail',$data,'id',$check_record->id);
									$jml_edit++;
								} else {
									insert_data('sap_detail',$data);
									$jml_input++;
								}
							}
						}
					}
					fclose($handle);
				}
			}
			if(count($pr_item)) {
				$get_detail 	= get_data('sap_detail',[
					'where'		=> [
						'purchase_req_item'	=> $pr_item
					],
					'group_by'	=> 'purchase_req_item'
				])->result();
				foreach($get_detail as $g) {
					$total 	= get_data('sap_detail',[
						'select'	=> 'SUM(total_value) AS jml',
						'where'		=> [
							'purchase_req_item'	=> $g->purchase_req_item,
							'is_deleted'		=> 0
						],
						'sort_by'	=> 'id',
						'sort'		=> 'asc'
					])->row();
					$record 	= [
						'pr_type'			=> $g->pr_type,
						'purchase_req_item'	=> $g->purchase_req_item,
						'jabatan'			=> $g->jabatan,
						'nama_pengadaan'	=> $g->nama_pengadaan,
						'kode_divisi'		=> $g->kode_divisi,
						'plant'				=> $g->plant,
						'total_usulan'		=> isset($total->jml) ? $total->jml : 0
					];
					$check_header 	= get_data('sap_header','purchase_req_item',$record['purchase_req_item'])->row();
					if(isset($check_header->id)) {
						update_data('sap_header',$record,'id',$check_header->id);
					} else {
						insert_data('sap_header',$record);
					}
				}
			}
			delete_dir(str_replace(basename($file), '', $file));
			render([
				'status'	=> 'success',
				'message'	=> $jml_input.' '.lang('data_berhasil_disimpan').', '.$jml_edit.' '.lang('data_berhasil_diperbaharui')
			],'json');
		} elseif($doc_type == 'BP') {
			$col 		= ['kode_sap','kode_rekanan','npwp'];
			$jml_edit	= 0;
			if(in_array($ext, ['xls','xlsx'])) {
				$this->load->library('simpleexcel');
				$this->simpleexcel->define_column($col);
				$jml = $this->simpleexcel->read($file);
				foreach($jml as $i => $k) {
					if($i==0) {
						for($j = 1; $j <= $k; $j++) {
							$data = $this->simpleexcel->parsing($i,$j);
							if($data['kode_sap'] && $data['kode_rekanan'] && $data['npwp']) {
								$save = update_data('tbl_vendor',['kode_sap'=>$data['kode_sap']],[
									'kode_rekanan'	=> $data['kode_rekanan'],
									'npwp'			=> $data['npwp']
								]);
								if($save) {
									update_data('tbl_pemenang_pengadaan',['kode_sap_vendor'=>$data['kode_sap']],'kode_vendor',$data['kode_rekanan']);
									$jml_edit++;
								}
							}
						}
					}
				}
			} elseif($ext == 'csv') {
				$delimiter 	= $this->detectDelimiter($file);
				if (($handle = fopen($file, "r")) !== FALSE) {
					while (($row_data = fgetcsv($handle, 4096, $delimiter)) !== FALSE) {
						$kode_sap 		= isset($row_data[0]) ? $row_data[0] : '';
						$kode_rekanan 	= isset($row_data[1]) ? $row_data[1] : '';
						$npwp 			= isset($row_data[2]) ? $row_data[2] : '';
						if($kode_sap && $kode_rekanan && $npwp) {
							$save = update_data('tbl_vendor',['kode_sap'=>$kode_sap],[
								'kode_rekanan'	=> $kode_rekanan,
								'npwp'			=> $npwp
							]);
							if($save) {
								update_data('tbl_pemenang_pengadaan',['kode_sap_vendor'=>$kode_sap],'kode_vendor',$kode_rekanan);
								$jml_edit++;
							}
						}
					}
					fclose($handle);
				}
			}
			delete_dir(str_replace(basename($file), '', $file));
			render([
				'status'	=> 'success',
				'message'	=> $jml_edit.' '.lang('data_berhasil_diperbaharui')
			],'json');
		}
	}

	private function detectDelimiter($fh) {
		$delimiters = ["\t", ";", "|", ","];
		$data_1 = $data_2 = [];
		$delimiter = $delimiters[0];
		foreach($delimiters as $d) {
			if (($handle = fopen($fh, "r")) !== FALSE) {
				$data_1 = fgetcsv($handle, 4096, $d);
				if(count($data_1) > count($data_2)) {
					$delimiter = $d;
					$data_2 = $data_1;
				}
				fclose($handle);
			}
		}
		return $delimiter;
	}

}