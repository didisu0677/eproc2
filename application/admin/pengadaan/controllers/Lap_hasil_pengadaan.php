<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_hasil_pengadaan extends BE_Controller {

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

    function data($tahun=0, $user="", $panitia="", $hps="", $metode="", $tipe = 'table') {
        include_lang('survey');
	    $menu = menu();
	    $ctahun = $tahun;

	    if($menu['access_view']) {
	        	        	                   
	 	    $arr            = [
	            'select'	=> 'a.*,b.nama,d.nomor_spk,d.tanggal_spk,d.nama_vendor,d.penawaran_terakhir as nilai_kontrak,e.divisi, f.deskripsi as panitia',
	            'join'		=> ['tbl_user b on a.id_creator = b.id type LEFT',
	            				'tbl_pengajuan c on a.nomor_pengajuan = c.nomor_pengajuan type LEFT',
	            				'tbl_pemenang_pengadaan d on a.nomor_pengadaan = d.nomor_pengadaan type LEFT',
	            				'tbl_m_divisi e on a.id_divisi = e.id type LEFT',
	            				'tbl_m_panitia_pengadaan f on a.id_panitia = f.id type LEFT' 
	            ],
	            'where' => [
	                 'a.status_pengadaan !=' => '', 	                     
	            ],
	        ];
	        
	        $hps = str_replace('.', '', $hps);

	        if($tahun) {
	            $arr['where']['year(a.tanggal_pengadaan)']  = $ctahun;
	        }

	        if($user) {
	        	$arr['where']['a.id_divisi'] = $user;
	        }

	        if($panitia) {
	        	$arr['where']['a.id_panitia'] = $panitia;
	        }

	        if($hps) {
	        	$arr['where']['a.hps'] = $hps;
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
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('nama_pekerjaan').'</th>';
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('user').'</th>';
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('nilai_hps').'</th>';
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('metode_pengadaan').'</th>';
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('panitia_pengadaan').'</th>';
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('rekanan_pengadaan').'</th>';    
	        $data['items'] .= '<th colspan="2" class="text-center align-middle">'.lang('spk').'</th>';   
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('nilai_kontrak').'</th>';    
	        $data['items'] .= '<th rowspan="3" class="text-center align-middle">'.lang('keterangan').'</th>';    
	        $data['items'] .= '</tr>';

	        $data['items'] .= '<tr>';
		    $data['items'] .= '<th rowspan="2" class="text-center align-middle">'.lang('nomor').'</th>';
		    $data['items'] .= '<th rowspan="2" class="text-center align-middle">'.lang('tanggal').'</th>';
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
				$data['items'] .=  '<td class="text-right">'.number_format($m0->hps).'</td>';
				$data['items'] .=  '<td>'.$m0->metode_pengadaan.'</td>';
				$data['items'] .=  '<td>'.$m0->panitia.'</td>';
				$data['items'] .=  '<td>'.$m0->nama_vendor.'</td>';
				$data['items'] .=  '<td>'.$m0->nomor_spk.'</td>';
				$data['items'] .=  '<td>'.$m0->tanggal_spk.'</td>';
				$data['items'] .=  '<td class="text-right">'.number_format($m0->nilai_kontrak).'</td>';
				$data['items'] .=  '<td bgcolor="">'.$m0->keterangan_pengadaan.'</td>';	

       		}       
	        
	    } else {
	        $response	= array(
	            'status'	=> 'error',
	            'message'	=> 'Permission Denied'
	        );
	    }

	    render($data,'json');
	}
	

	function print_data($tahun=0, $user="", $panitia="", $hps="", $metode="", $tipe = 'table') {
        include_lang('survey');
	    $menu = menu();
	    $ctahun = $tahun;

	    if($menu['access_view']) {
	        	        	                   
	 	    $arr            = [
	            'select'	=> 'a.*,b.nama,d.nomor_spk,d.tanggal_spk,d.nama_vendor,d.penawaran_terakhir as nilai_kontrak,e.divisi,f.deskripsi as panitia',
	            'join'		=> ['tbl_user b on a.id_creator = b.id type LEFT',
	            				'tbl_pengajuan c on a.nomor_pengajuan = c.nomor_pengajuan type LEFT',
	            				'tbl_pemenang_pengadaan d on a.nomor_pengadaan = d.nomor_pengadaan type LEFT',
	            				'tbl_m_divisi e on a.id_divisi = e.id type LEFT',
	            				'tbl_m_panitia_pengadaan f on a.id_panitia = f.id type LEFT' 
	            ],
	            'where' => [
	                 'a.status_pengadaan !=' => '', 	                     
	            ],
	        ];
	        
	        $hps = str_replace('.', '', $hps);

	        if($tahun) {
	            $arr['where']['year(a.tanggal_pengadaan)']  = $ctahun;
	        }

	        if($user) {
	        	$arr['where']['a.id_divisi'] = $user;
	        }

	        if($panitia) {
	        	$arr['where']['a.id_panitia'] = $panitia;
	        }

	        if($hps) {
	        	$arr['where']['a.hps'] = $hps;
	        }

	        if($metode) {
	        	$arr['where']['a.id_metode_pengadaan'] = $metode;
	        }

	        	        
	        $data['result']= get_data('tbl_pengadaan a',$arr)->result_array();
	                	        

	        
	    } else {
	        $response	= array(
	            'status'	=> 'error',
	            'message'	=> 'Permission Denied'
	        );
	    }

	    render($data,'pdf');
	}
}