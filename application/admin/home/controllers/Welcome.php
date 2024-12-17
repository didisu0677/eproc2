<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends BE_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['title']      = 'Welcome';
        $data['ip']         = $this->input->ip_address();
        $data['agent']      = $this->agent->agent_string();
        $data['browser']    = 'globe';
        if(strpos($data['agent'],'Firefox') != false) $data['browser'] = 'firefox';
        else if(strpos($data['agent'],'Chrome') != false) $data['browser'] = 'chrome';
        $data['pengumuman'] = get_data('tbl_pengumuman a',array(
            'select'    => 'a.pengumuman,b.nama',
            'join'      => 'tbl_user b on a.id_user = b.id type left',
            'where'     => array(
                'a.tanggal_publish <='  => date('Y-m-d H:i:s'),
                'a.tanggal_selesai >='  => date('Y-m-d H:i:s'),
                'a.is_active'           => 1
            ),
            'sort_by'   => 'a.update_at',
            'sort'      => 'desc'
        ))->result_array();
        if(user('id_vendor')) {
            if(user('id_group') == 7) {
                include_lang('pengadaan');
                $vendor_kategori    = get_data('tbl_vendor_kategori','id_vendor',user('id_vendor'))->result();
                $id_bidang_usaha    = [0];
                foreach($vendor_kategori as $v) {
                    $id_bidang_usaha[] = $v->id_kategori_rekanan;
                }
                $pengadaan_baru     = get_data('tbl_pengadaan_detail',[
                    'select'    => 'COUNT(DISTINCT(nomor_pengadaan)) AS jml',
                    'where'     => [
                        'id_bidang_usaha'   => $id_bidang_usaha,
                        'tipe_pengadaan'    => 'Lelang',
                        'status_pengadaan'  => 'BIDDING',
                    ]
                ])->row();
                $data['pengadaan_baru']         = $pengadaan_baru->jml;
                $data['pengadaan_baru_list']    = get_data('tbl_pengadaan_detail',[
                    'where'     => [
                        'id_bidang_usaha'   => $id_bidang_usaha,
                        'tipe_pengadaan'    => 'Lelang',
                        'status_pengadaan'  => 'BIDDING',
                    ],
                    'limit'     => 5,
                    'group_by'  => 'nomor_pengadaan'
                ])->result();

                $undangan_pengadaan         =  get_data('tbl_pengadaan_bidder',[
                    'select'    => 'COUNT(id) AS jml',
                    'where'     => [
                        'status_pengadaan'  => 'BIDDING',
                        'is_invite'         => 1,
                        'id_vendor'         => user('id_vendor')
                    ]
                ])->row();
                $data['undangan_pengadaan']     = $undangan_pengadaan->jml;
                $data['undangan_pengadaan_list']= get_data('tbl_pengadaan_bidder',[
                    'where'     => [
                        'status_pengadaan'  => 'BIDDING',
                        'is_invite'         => 1,
                        'id_vendor'         => user('id_vendor')
                    ]
                ])->result();
                render($data,'view:home/welcome/index_vendor');
            } else {
                render($data,'view:home/welcome/index_vendor_unverify');
            }
        } else {
            render($data,'view:home/welcome/index_old');            
        }
    }

    function info() {
        $data['pengumuman'] = get_data('tbl_pengumuman a',array(
            'select'    => 'a.pengumuman,b.nama,b.foto,a.create_at',
            'join'      => array(
                'tbl_user b'    => array(
                    'on'    => 'a.id_user = b.id',
                    'type'  => 'left'
                )
            ),
            'where_array'   => array(
                'a.tanggal_publish <='  => date('Y-m-d H:i:s'),
                'a.tanggal_selesai >='  => date('Y-m-d H:i:s'),
                'a.is_active'           => 1
            ),
            'sort_by'   => 'a.update_at',
            'sort'      => 'desc'
        ))->result();
        render($data,'layout:false');        
    }

    function chart_data() {
        $tahun  = post('tahun');
        
        $data   = [];
        $id_department = post('id_department') != 'all' ? 'id_department_penerima = '.post('id_department').' AND ' : '';
        for($i=1;$i<=12;$i++) {
            $j = $i-1;
            $tanggal = date('Y-m-d',strtotime($tahun.'-'.$i.'-01'));
            $task_masuk     = get_data('tbl_tiket',[
                'select'    => 'count(*) AS jml',
                'where'     => $id_department.'MONTH(create_date) = "'.date('m',strtotime($tanggal)).'" AND YEAR(create_date) = "'.date('Y',strtotime($tanggal)).'"'
            ])->row();
            $task_selesai     = get_data('tbl_tiket',[
                'select'    => 'count(*) AS jml',
                'where'     => $id_department.'MONTH(close_date) = "'.date('m',strtotime($tanggal)).'" AND YEAR(close_date) = "'.date('Y',strtotime($tanggal)).'"'
            ])->row();
            $data[$j]['bulan']  = bulan($i);
            $data[$j]['open']   = $task_masuk->jml ? $task_masuk->jml : 0;
            $data[$j]['close']  = $task_selesai->jml ? $task_selesai->jml : 0;
        }
        render($data,'json');
    }

    function get_category() {
        $kategori   = get_data('tbl_m_kategori_tiket','is_active',1)->result_array();
        $data       = [];
        foreach($kategori as $k) {
            $arr    = [
                'select'    => 'COUNT(*) AS jml',
            ];
            if(post('periode') == 'bulanan') $arr['where'] = 'MONTH(create_date) = "'.date('n').'" AND id_kategori = "'.$k['id'].'"';
            else if(post('periode') == 'tahunan') $arr['where'] = 'YEAR(create_date) = "'.date('Y').'" AND id_kategori = "'.$k['id'].'"';
            else  $arr['where'] = 'id_kategori = "'.$k['id'].'"';
            $jml = get_data('tbl_tiket',$arr)->row();
            $data[] = [
                'kategori'  => $k['nama'],
                'jml'       => $jml->jml ? $jml->jml : 0
            ];    
        }
        render($data,'json');
    }

}