<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Templat_cetak extends BE_Controller {

	var $variable;

	function __construct() {
		parent::__construct();
		$this->variable = [
			'tor'		=> [
				'nama_pengadaan','mata_anggaran','besar_anggaran','usulan_hps','pemberi_tugas','latar_belakang','spesifikasi','jumlah_kebutuhan','distribusi_kebutuhan','jangka_waktu','ruang_lingkup','lain_lain','ttd_persetujuan'
			],
			'rks'		=> [
				'nama_pengadaan','nama_divisi','metode_pengadaan','syarat_umum','syarat_khusus','syarat_teknis','hari_aanwijzing','tanggal_aanwijzing','jam_aanwijzing','tanggal_mulai_pemasukan_dokumen','tanggal_selesai_pemasukan_dokumen','hari_pembukaan_dokumen','tanggal_pembukaan_dokumen','minimal_rekanan_mengikuti','minimal_rekanan_sah','jam_pembukaan_dokumen','batas_hps_bawah','batas_hps_atas','bobot_harga','bobot_teknis','sanggahan_peserta','pola_pembayaran','latar_belakang','spesifikasi','jumlah_kebutuhan','jangka_waktu','ruang_lingkup','lain_lain','jaminan_pengadaan','tanggal_rks','nama_pemberi_tugas','jabatan_pemberi_tugas'
			],
			'rks_aanwijzing' => [
				'nama_pengadaan','nama_divisi','metode_pengadaan','syarat_umum','syarat_khusus','syarat_teknis','hari_aanwijzing','tanggal_aanwijzing','jam_aanwijzing','tanggal_mulai_pemasukan_dokumen','tanggal_selesai_pemasukan_dokumen','hari_pembukaan_dokumen','tanggal_pembukaan_dokumen','minimal_rekanan_mengikuti','minimal_rekanan_sah','jam_pembukaan_dokumen','batas_hps_bawah','batas_hps_atas','bobot_harga','bobot_teknis','sanggahan_peserta','pola_pembayaran','latar_belakang','spesifikasi','jumlah_kebutuhan','jangka_waktu','ruang_lingkup','lain_lain','jaminan_pengadaan','tanggal_rks','nama_pemberi_tugas','jabatan_pemberi_tugas'
			],
			'rks_klarifikasi' => [
				'nama_pengadaan','nama_divisi','metode_pengadaan','syarat_umum','syarat_khusus','syarat_teknis','hari_aanwijzing','tanggal_aanwijzing','jam_aanwijzing','tanggal_mulai_pemasukan_dokumen','tanggal_selesai_pemasukan_dokumen','hari_pembukaan_dokumen','tanggal_pembukaan_dokumen','minimal_rekanan_mengikuti','minimal_rekanan_sah','jam_pembukaan_dokumen','batas_hps_bawah','batas_hps_atas','bobot_harga','bobot_teknis','sanggahan_peserta','pola_pembayaran','latar_belakang','spesifikasi','jumlah_kebutuhan','jangka_waktu','ruang_lingkup','lain_lain','jaminan_pengadaan','tanggal_rks','nama_pemberi_tugas','jabatan_pemberi_tugas'
			],
			'pengumuman_lelang'	=> [
				'nomor_pengumuman','nama_pengadaan','syarat_umum','tanggal_pendaftaran','jam_pendaftaran','lokasi_pendaftaran','tanggal_aanwijzing','jam_aanwijzing','lokasi_aanwijzing','tanggal_pengumuman'
			],
			'kontrak' => [
				'nomor_spk','nama_pengadaan','nilai_pengadaan','nama_vendor','nomor_kontrak','tanggal_mulai_kontrak','tanggal_selesai_kontak','tanggal_dikeluarkan','tempat_dikeluarkan','nama_pihak1','jabatan_pihak1','alamat_pihak1','nama_pihak2','jabatan_pihak2','alamat_pihak2','isi_kontrak'
			]
		];
	}

	function index() {
		$data['page']		= get('p') && get('p') != 'tor' ? get('p') : 'tor';
		$periode			= get('d');
		$list 				= ['tor','rks','rks_aanwijzing','rks_klarifikasi','pengumuman_lelang','kontrak'];
		$data['variable']	= isset($this->variable[$data['page']]) ? $this->variable[$data['page']] : [];

		if(in_array($data['page'],$list)) {
			$arr 			= [
				'key'		=> $data['page']
			];
			if($periode) {
				$arr['periode']	= $periode;
			}
			$konten 		= get_data('tbl_template_cetak',[
				'where'		=> $arr
			])->row();
			if(!isset($konten->id)) {
				$konten 		= get_data('tbl_template_cetak',[
					'where'		=> [
						'key'	=> $data['page']
					],
					'sort_by'	=> 'periode',
					'sort'		=> 'DESC'
				])->row();
			}
			$data['konten']		= isset($konten->id) ? $konten->konten : '';
			$data['periode']	= isset($konten->id) ? $konten->periode : date('Y-m-d');
			$data['riwayat']	= get_data('tbl_template_cetak',[
				'select'		=> 'id,periode',
				'where'			=> [
					'key'		=> $data['page']
				],
				'sort_by'		=> 'periode',
				'sort'			=> 'DESC'
			])->result();
			render($data);
		} else {
			render('404');
		}
	}

	function save() {
		$data 		= post();
		$check 		= get_data('tbl_template_cetak',[
			'where'	=> [
				'periode'	=> $data['periode'],
				'key'		=> $data['key']
			]
		])->row();
		$variabel 	= isset($this->variable[post('key')]) ? json_encode($this->variable[post('key')]) : '';
		if(isset($check->id)) {
			update_data('tbl_template_cetak',['konten'=>post('konten','html'),'variabel'=>$variabel],'id',$check->id);
		} else {
			insert_data('tbl_template_cetak',['konten'=>post('konten','html'),'variabel'=>$variabel,'key'=>post('key'),'periode'=>$data['periode']]);
		}
		$response 	= array(
			'status'	=> 'success',
			'message'	=> lang('data_berhasil_diperbaharui')
		);
		render($response,'json');
	}

	function delete() {
		$cek_template 	= get_data('tbl_template_cetak','id',post('id'))->row();
		$cek_key 		= get_data('tbl_template_cetak',[
			'select'	=> 'count(id) AS jml',
			'where'		=> [
				'key'	=> $cek_template->key
			]
		])->row();
		if($cek_key->jml > 1) {
			$response = delete_data('tbl_template_cetak','id',post('id'));
			render([
				'status'	=> 'success',
				'message'	=> lang('data_berhasil_dihapus')
			],'json');
		} else {
			render(denied(),'json');
		}
	}

}