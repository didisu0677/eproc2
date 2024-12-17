<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_proses extends BE_Controller {

    function __construct() {
        parent::__construct();
    }
    
    function index() {
    	$data['user'] = get_data('tbl_m_divisi','is_active=1')->result_array();
    	$data['metode_pengadaan'] = get_data('tbl_metode_pengadaan','is_active=1')->result_array();
    	$data['panitia'] = get_data('tbl_m_panitia_pengadaan','is_active=1')->result_array();
        render($data);
    }
    
    function sortable() {
        render();
    }

    function data($tahun=0, $user="", $panitia="", $metode="", $tipe = 'table') {
	    $menu = menu();
	    $ctahun = $tahun;	    

	    if($menu['access_view']) {
	        
	        $arr            = [
	            'select'	=> 'a.*,b.nama,d.id as id_tahapan,d.urutan,e.status_penetapan,e.nomor_penunjukan,e.nomor_spk, f.divisi,g.deskripsi as panitia,e.tanggal_pengumuman,h.tanggal_berita_acara as tanggal_aanwijzing, e.tanggal_penawaran,h.tanggal_ba_evaluasi,i.tanggal_berita_acara as tanggal_klarifikasi, e.tanggal_penunjukan,e.tanggal_spk',
	            'join'		=> ['tbl_user b on a.id_creator = b.id type LEFT',
	            				'tbl_pengajuan c on a.nomor_pengajuan = c.nomor_pengajuan type LEFT',
	            				'tbl_m_tahapan_lelang d on a.status_pengadaan = d.tahapan type LEFT',
	            				'tbl_pemenang_pengadaan e on a.nomor_pengadaan = e.nomor_pengadaan type lEFT',
	            				'tbl_m_divisi f on a.id_divisi = f.id type LEFT',
	            				'tbl_m_panitia_pengadaan g on a.id_panitia = g.id type LEFT',
	            				'tbl_aanwijzing h on a.nomor_pengadaan = h.nomor_pengadaan type LEFT',  
	            				'tbl_klarifikasi i on a.nomor_pengadaan = i.nomor_pengadaan type LEFT',
	            ],
	            'where' => [
	                 'a.status_pengadaan !=' => '', 	                     
	            ],
	        ];
	        	        
	        if($tahun) {
	            $arr['where']['year(a.tanggal_pengadaan)']  = $ctahun;
	        }

	        if($user) {
	        	$arr['where']['a.id_divisi'] = $user;
	        }

	        if($panitia) {
	        	$arr['where']['a.id_panitia'] = $panitia;
	        }

	        if($metode) {
	        	$arr['where']['a.id_metode_pengadaan'] = $metode;
	        }

	        if(user('is_kanwil')==1){
				$where 				= [
		    		'a.id_unit_kerja'	=>user('id_unit_kerja'),
				];		
			}

			$where 				= [
			   	'a.id_divisi'	=>user('id_divisi'),
			];		
	        	        
	        $data['grup'][0]= get_data('tbl_pengadaan a',$arr)->result();                	        
	        $data['items'] 			= "";
	        $data['items'] .= '<tr>';
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('no').'</th>';
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('nama_pengadaan').'</th>';
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('user').'</th>';
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('metode_pengadaan').'</th>';
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('tanggal_pengadaan').'</th>';
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('sla').'</th>';
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('panitia').'</th>'; 
	        $data['items'] .= '<th colspan="9" class="text-center align-middle">'.lang('tahapan_proses').'</th>';    
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('keterangan').'</th>';   
	        $data['items'] .= '</tr>';

	        $data['items'] .= '<tr>';
	        $data['items'] .= '<th rowspan="2" class="text-center align-middle">'.lang('pengumuman').'</th>';
	        $data['items'] .= '<th rowspan="2" class="text-center align-middle">'.lang('_aanwijzing').'</th>';
	        $data['items'] .= '<th rowspan="2" class="text-center align-middle">'.lang('pemasukan').'</th>';
	        $data['items'] .= '<th rowspan="2" class="text-center align-middle">'.lang('evaluasi').'</th>';
	        $data['items'] .= '<th rowspan="2" class="text-center align-middle">'.lang('kunjungan_lapangan').'</th>';
	        $data['items'] .= '<th rowspan="2" class="text-center align-middle">'.lang('klarifikasi_negosiasi').'</th>';
	        $data['items'] .= '<th rowspan="2" class="text-center align-middle">'.lang('penetapan').'</th>';
		    $data['items'] .= '<th rowspan="2" class="text-center align-middle">'.lang('penunjukan').'</th>';
		    $data['items'] .= '<th rowspan="2" class="text-center align-middle">'.lang('spk').'</th>';
	        $data['items'] .= '</tr>';
	        
	        $data['items'] .= '<tr>';
	        $data['items'] .= '</tr>';
	        
	        $no = 0;
	        foreach($data['grup'][0] as $m0) {
	            $no++;

	            $panitia	= get_data('tbl_panitia_pelaksana ',[
			   		'where' => [
			   			'nomor_delegasi' => $m0->nomor_delegasi,
			   		],
			   	])->result();
			

				$nm_panitia 			= [];
				foreach($panitia as $c) $nm_panitia[] = $c->nama_panitia;

				$panitia_pengadaan = implode(",",$nm_panitia);	

	


	            $data['items'] .= '<tr>';
	            $data['items'] .= '<td>'.$no.'</td>';
	            $data['items'] .=  '<td>'.$m0->nama_pengadaan.'</td>';
				$data['items'] .=  '<td>'.$m0->divisi.'</td>';
				$data['items'] .=  '<td>'.$m0->metode_pengadaan.'</td>';
				$data['items'] .=  '<td>'.$m0->tanggal_pengadaan.'</td>';
				$data['items'] .=  '<td></td>';
				$data['items'] .=  '<td>'.$m0->panitia.'</td>';

				for($i=1;$i <= 9;$i++) {
					if($i <= $m0->urutan) {
						$warna  = "#E6E6FA";
					}else{
						$warna  = "";					
					}	

					if($i <= 7 && $m0->status_penetapan == 1 ) {
						$warna  = "#E6E6FA";	
					}	

					if($i <= 8 && $m0->nomor_penunjukan != "" ) {
						$warna  = "#E6E6FA";	
					}	

					if($i <= 9 && $m0->nomor_spk != "" ) {
						$warna  = "#E6E6FA";	
					}	
						
					$tanggal = 'tanggal' . $i;

					
					$tanggal = '0000-00-00';
					

					if($i==1) {
						$tanggal = $m0->tanggal_pengumuman;
					}

					if($i==2) {
						$tanggal = $m0->tanggal_aanwijzing;
					}

					if($i==3) {
						$tanggal = $m0->tanggal_penawaran;
					}

					if($i==4) {
						$tanggal = $m0->tanggal_ba_evaluasi;
					}

					if($i==6) {
						$tanggal = $m0->tanggal_klarifikasi;
					}

					if($i==8) {
						$tanggal = $m0->tanggal_penunjukan;
					}

					if($i==9) {
						$tanggal = $m0->tanggal_spk;
					}

					$data['items'] .=  '<td bgcolor='.$warna.'>'.c_date($tanggal).'</td>';				
					
				}


				$data['items'] .=  '<td>'.$m0->keterangan_pengadaan.'</td>';	
       		}       	
	        
	    } else {
	        $response
	        	= array(
	            'status'	=> 'error',
	            'message'	=> 'Permission Denied'
	        );
	    }

	    render($data,'json');
	}

	function print_data($tahun=0, $user="", $panitia="", $metode="", $tipe = 'table') {
	    $menu = menu();
	    $ctahun = $tahun;	    

	    if($menu['access_view']) {
	        
	        $arr            = [
	            'select'	=> 'a.*,b.nama,d.id as id_tahapan,d.urutan,e.status_penetapan,e.nomor_penunjukan,e.nomor_spk, f.divisi,g.deskripsi as panitia',
	            'join'		=> ['tbl_user b on a.id_creator = b.id type LEFT',
	            				'tbl_pengajuan c on a.nomor_pengajuan = c.nomor_pengajuan type LEFT',
	            				'tbl_m_tahapan_lelang d on a.status_pengadaan = d.tahapan type LEFT',
	            				'tbl_pemenang_pengadaan e on a.nomor_pengadaan = e.nomor_pengadaan type lEFT',
	            				'tbl_m_divisi f on a.id_divisi = f.id type LEFT',
	            				'tbl_m_panitia_pengadaan g on a.id_panitia = g.id type LEFT'  
	            ],
	            'where' => [
	                 'a.status_pengadaan !=' => '', 	                     
	            ],
	        ];
	        	        
	        if($tahun) {
	            $arr['where']['year(a.tanggal_pengadaan)']  = $ctahun;
	        }

	        if($user) {
	        	$arr['where']['a.id_divisi'] = $user;
	        }

	        if($panitia) {
	        	$arr['where']['a.id_panitia'] = $panitia;
	        }

	        if($metode) {
	        	$arr['where']['a.id_metode_pengadaan'] = $metode;
	        }

	        	        
	        $data['result']= get_data('tbl_pengadaan a',$arr)->result_array();                	        
	
	        
	    } else {
	        $response
	        	= array(
	            'status'	=> 'error',
	            'message'	=> 'Permission Denied'
	        );
	    }

	    render($data,'pdf');
	}

	
}