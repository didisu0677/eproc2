<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SP_rekanan extends BE_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		render();
	}

	function data($type='rekanan') {
		$config				= [
			'access_view'	=> false,
			'access_edit'	=> false,
			'access_delete'	=> false
		];
		
		if($type == 'blacklist') {
		    $config['access_edit']			= false;
		    $config['where']['is_active']	= 0;
		    $config['where']['status_sp']	= 1;
		    if(menu()['access_additional']) {
		        $config['button'][]	= button_serverside('btn-success','btn-pulihkan',['fa-user-check',lang('pulihkan'),true],'act-pulihkan');
		    }
		}else{
		    $config['where']['is_active']	= 1;
		    $config['where']['status_sp']	= 1;
		    if(menu()['access_additional']) {
		        $config['button'][]	= button_serverside('btn-secondary','btn-blacklist',['fa-user-times',lang('blacklist'),true],'act-blacklist');
		    }
		}
		
		$config['button'][]	= button_serverside('btn-info','btn-act-view',['fa-search',lang('detil'),true],'act-view');
		if(menu()['access_edit']) {
			$config['button'][]	= button_serverside('btn-warning','btn-input',['fa-edit',lang('ubah'),true],'edit');
		}
		if(menu()['access_delete']) {
			$config['button'][]	= button_serverside('btn-danger','btn-delete',['fa-trash-alt',lang('hapus'),true],'delete');
		}


		if(user('is_kanwil')==1){
			$kanwil	= get_data('tbl_sp_vendor a',[
				'select' => 'a.id_vendor',
				'join' => 'tbl_vendor b on a.id_vendor=b.id',
				'where' => [
				'b.id_unit_daftar' => user('id_unit_kerja'),
			],
			])->result();
			
			$id_vendor			= [0];
			foreach($kanwil as $a) $id_vendor[] = $a->id_vendor;

			$config['where']['id_vendor']	= $id_vendor;

		}
		$data = data_serverside($config);
		render($data,'json');
	}

	function get_data() {
		$data 			= get_data('tbl_sp_vendor','id',post('id'))->row_array();
		
		$data['detail']	= get_data('tbl_detail_sp ',[
		    'select'	=> '*',
		    'where' 	=> [
		        'id_vendor' => $data['id_vendor'],
		        'id_sp'   => $data['id']
		    ],
		    'sort_by'  => 'nomor',
		])->result_array();
		    
		render($data,'json');
	}

	function get_rekanan() {
	    $vendor	= get_data('tbl_vendor ',[
	        'select'	=> 'id, nama, alamat,nama_kelurahan,nama_kecamatan,nama_kota,nama_provinsi',
	        'where' 	=> [
	        	'status_drm'	=> 1,
	            'is_active' 	=> 1,
	            'status_sp' 	=> 0
	        ]
	    ])->result();
	    
	    if($vendor){
	        foreach($vendor as $d) {
	            $data['vendor'][$d->id] 			= $d;
	        }
	    }

		render($data,'json');
	}

	function save() {
		$data 		= post();
		$vendor 		= get_data('tbl_vendor','id',$data['id_vendor'])->row();

		if(isset($vendor->id)) {
			$data['id_vendor']				= $data['id_vendor'];
			$data['kode_rekanan']			= $vendor->kode_rekanan;
			$data['nama_rekanan']		    = $vendor->nama;
			$data['alamat']			        = $data['alamat'];
			$data['nama_cp']	            = $vendor->nama_cp;
			$data['hp_cp']				    = $vendor->hp_cp;
			$data['email_cp']			    = $vendor->email_cp;
			$data['is_active']              = 1;
		}
		$response 	= save_data('tbl_sp_vendor',$data,post(':validation'));
		$id_sp = $response['id'];
		if($response['status'] == 'success') {

			$nomor 	= post('nomor');
			$jenis	= post('jenis');
			$perihal = post('perihal');
			$tanggal_berlaku = post('tanggal_berlaku');
			$catatan = post('catatan');
			$isi 	= post('isi_pasal','html');
		
			$d 		= [];
			$a = '';
			foreach($nomor as $k => $v) {
			    
			    $d[]= [
				    'id_vendor'       => $data['id_vendor'],
			        'id_sp'           => $id_sp, 
					'nomor'           => $nomor[$k],
				    'jenis'     	  => $jenis[$k],
			        'perihal'         => $perihal[$k],
					'tanggal_mulai'   => $tanggal_berlaku[$k],
				    'tanggal_akhir'   => $tanggal_berlaku[$k],
				    'catatan'	      => $catatan[$k],
					'isi_surat'		  => $isi[$k]
				];
				$a = $nomor[$k];				
				
				$ceksp 		= get_data('tbl_detail_sp','nomor',$nomor[$k])->row();

				$last_file 		= [];
				if($data['id']) {
					$dt 		= get_data('tbl_detail_sp','nomor',$nomor[$k])->row();
					if(isset($dt->nomor)) {
						$last_file = $dt->file;
					}
				}		
				$file 				= post('file');
				$filename 			= [];
				$dir 				= '';

				if(isset($file[$k]) && !empty($file[$k]) && $file[$k] != '') {
					if(!is_dir(FCPATH . "assets/uploads/rekanan/".$vendor->id.'/')){
						$oldmask = umask(0);
						mkdir(FCPATH . "assets/uploads/rekanan/".$vendor->id.'/',0777);
						umask($oldmask);
					}
					$copy = 0 ;
					if($file[$k]) {						
						if(@copy($file[$k], FCPATH . 'assets/uploads/rekanan/'.$vendor->id.'/'.basename($file[$k]))) {
							$filename[$k]	= basename($file[$k]);
							if(!$dir) $dir = str_replace(basename($file[$k]),'',$file[$k]);
							$copy = 1 ;
						}
					}

				}	

				if(!isset($ceksp->nomor)){

					$d_insert = array(
					        'id_vendor'       => $data['id_vendor'],
					        'id_sp'           => $id_sp,  
					        'nomor'           => $nomor[$k],
					        'jenis'     	  => $jenis[$k],
					        'perihal'         => $perihal[$k],
					        'tanggal_mulai'   => $tanggal_berlaku[$k],
					        'tanggal_akhir'   => $tanggal_berlaku[$k],
					        'catatan'	      => $catatan[$k],
					        'isi_surat'		  => $isi[$k]
						 );

					if($copy == 1) {
						$d_insert['file'] = $filename[$k];
					} 

				    insert_data('tbl_detail_sp',$d_insert);

				    
				}else{

					$d_update = array(
						'perihal'         => $perihal[$k],
					    'tanggal_mulai'   => $tanggal_berlaku[$k],
					    'tanggal_akhir'   => $tanggal_berlaku[$k],
					    'catatan'	      => $catatan[$k],
					    'isi_surat'		  => $isi[$k]
					);


					if($copy == 1) {
						$d_update['file'] = $filename[$k];
					} 

				    update_data('tbl_detail_sp',$d_update,['nomor'=>$nomor[$k],'id_vendor'=>$data['id_vendor']]);    
				    
				    delete_data('tbl_detail_sp',['id_vendor'=>$data['id_vendor'], 'nomor not'=>$nomor]);
				    

				}
			}
			// die;
			
			$datax = get_data('tbl_detail_sp',[			    
			    'where'   =>  [
			        'nomor' => "",'id_vendor'=>$data['id_vendor']]			        
			    ])->result_array();
			foreach($datax as $d) {
			    $row['nomor'] = generate_code('tbl_detail_sp','nomor');
			    update_data('tbl_detail_sp',$row,'id',$d['id']);
			}
			
			
 			$cek_terakhir	= get_data('tbl_detail_sp ',[
 			    'select'	=> '*',
 			    'where' 	=> [
 			        'id_vendor' => $data['id_vendor'],
 			    ],
 			    'sort_by'  => 'nomor',
 			    'sort'   => 'DESC'
 			])->row();
 			
 			
 			// notif
 			$id_user 				= $email_user = [];
 			$user 					= get_data('tbl_user','id_vendor',$cek_terakhir->id_vendor)->result();
 			foreach($user as $u) {
 			    $id_user[] 			= $u->id;
 			    $email_user[]		= $u->email;
 			}
 			
 			if(count($id_user)) {
 			    
 			    $ceksp 		= get_data('tbl_detail_sp','nomor',$cek_terakhir->nomor)->row();
 			    
 			    
 			    $link				= base_url().'manajemen_rekanan/sp_rekanan/cetak_sp/'.encode_id([$ceksp->id,rand()]);
 			    
 			    $description 		= 'Surat Peringatan . <strong>'.$cek_terakhir->nomor.'</strong>';
 			    foreach($id_user as $iu) {
 			        $data_notifikasi 	= [
 			            'title'			=> 'Surat Peringatan',
 			            'description'	=> $description,
 			            'notif_link'	=> $link,
 			            'notif_date'	=> date('Y-m-d H:i:s'),
 			            'notif_type'	=> 'info',
 			            'notif_icon'	=> 'fa-file-alt',
 			            'id_user'		=> $iu,
 			            'transaksi'		=> 'sp_rekanan',
 			            'id_transaksi'	=> $cek_terakhir->id
 			        ];
 			        
 			        insert_data('tbl_notifikasi',$data_notifikasi);
 			    }
 			    
 			    if(setting('email_notification') && $email_user) {
 			        send_mail([
 			            'subject'		=> 'Surat Peringatan #'.$cek_terakhir->nomor,
 			            'to'			=> $email_user,
 			            'nama_user'		=> '',
 			            'description'	=> $description,
 			            'url'			=> $link
 			        ]);
 			    }
 			}
 			//
			    
			update_data('tbl_sp_vendor',['sp_terakhir'=>$cek_terakhir->nomor, 'status_sp' => 1 ],['id_vendor'=>$data['id_vendor'],'id'=>$id_sp]);
			update_data('tbl_vendor',['status_sp'=>1],'id',$data['id_vendor']);
			
		
		}
		render($response,'json');
	}

	function delete() {
		$data 		= get_data('tbl_sp_vendor','id',post('id'))->row();
		$response 	= destroy_data('tbl_sp_vendor','id',post('id'));
		if($response['status'] == 'success' && isset($data->id)) {
			delete_data('tbl_detail_sp','id_vendor',$data->id_vendor);
		}
		render($response,'json');
	}
	
	function blacklist() {	    
	    $data 		= get_data('tbl_sp_vendor','id',post('id'))->row();
	    update_data('tbl_sp_vendor',['is_active'=>0],'id',post('id'));
	    update_data('tbl_vendor',['is_active'=>0],'id',$data->id_vendor);
	    update_data('tbl_user',['is_block'=>1],'id_vendor',$data->id_vendor);
	    echo lang('data_berhasil_disimpan');
	}
	
	function pulihkan() {
	    $data 		= get_data('tbl_sp_vendor','id',post('id'))->row();	    
	    update_data('tbl_sp_vendor',['is_active'=>1,'status_sp'=>0],'id',$data->id);
	    update_data('tbl_vendor',['is_active'=>1,'status_sp'=>0],'id',$data->id_vendor);
	    update_data('tbl_user',['is_block'=>0],'id_vendor',$data->id_vendor);
	    echo lang('data_berhasil_disimpan');
	}

	function detail($id = 0) {
		$data 	= get_data('tbl_sp_vendor','id',$id)->row_array();		
		
		
		if(isset($data['id'])) {
	       $data['detail']	= get_data('tbl_detail_sp ',[
		        'select'	=> '*',
		        'where' 	=> [
		            'id_vendor' => $data['id_vendor'],
		            'id_sp' => $data['id'],
		        ],
		        'sort_by'  => 'nomor',
		    ])->result();
		        

			render($data,'layout:false');
		} else {
			echo lang('tidak_ada_data');
		}
	}
	
	function cetak_sp($encode_id='') {
	    $id = decode_id($encode_id);
	    $id = isset($id[0]) ? $id[0] : 0;
	    
	    $data 		= get_data('tbl_detail_sp a',[
	        'select'	=> 'a.*,b.kode_rekanan,b.nama_rekanan,b.alamat,b.nama_pembuat,b.jabatan',
	        'join'		=> 'tbl_sp_vendor b ON a.id_vendor = b.id_vendor TYPE LEFT',
	        'where'		=> [
	            'a.id'			=> $id,
	        ],
	    ])->row_array();
	    

	    if(isset($data['id'])) {
	        render($data,'pdf');
	    } else render('404');
	}

}