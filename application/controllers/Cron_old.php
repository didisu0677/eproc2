<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends MY_Controller {
    
    function backup() {
		ini_set('memory_limit', '-1');
        $backupdir = FCPATH . 'assets/backup/backup_'.date('Y_m_d_h_i');
        if(!is_dir($backupdir)) mkdir($backupdir, 0777, true);
        
        $table = db_list_table();
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
    }

    function reminder_jaminan() {
        $date   = [
            date('Y-m-d',strtotime('+14 day')),
            date('Y-m-d',strtotime('+7 day'))
        ];

        $data = get_data('tbl_jaminan',[
            'where' => [
                'status'            => 0,
                'tanggal_selesai'   => $date
            ]
        ])->result();
        foreach($data as $d) {
            $penerima   = json_decode($d->penerima_reminder,true);
            if(is_array($penerima) && count($penerima) > 0) {
                send_mail([
                    'bcc'       => $penerima,
                    'subject'   => 'Reminder Jaminan '.$d->nomor_jaminan,
                    'message'   => 'Jaminan '.$d->jenis_jaminan.' pengadaan <strong>"'.$d->nama_pengadaan.'"</strong> dengan nomor <strong>'.$d->nomor_jaminan.'</strong> akan berakhir pada tanggal '.date_indo($d->tanggal_selesai)
                ]);
            }
        }
    }

    function read_sap() {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');
        $protocol   = setting('ftp_protocol');
        $host       = setting('ftp_host');
        $port       = setting('ftp_port') ? setting('ftp_port') : 22;
        $username   = setting('ftp_user');
        $password   = setting('ftp_pass');
        $filename   = str_replace(['{Y}','{M}','{D}'],['{y}','{m}','{d}'],setting('ftp_file'));
        $filename2  = str_replace(['{Y}','{M}','{D}'],['{y}','{m}','{d}'],setting('ftp_file2'));
        $filename   = str_replace(['{y}','{m}','{d}'],[date('Y'),date('m'),date('d')],$filename);
        $filename2  = str_replace(['{y}','{m}','{d}'],[date('Y'),date('m'),date('d')],$filename2);
        $split_fl   = explode('.',basename($filename));
        $ext        = count($split_fl) > 1 ? $split_fl[count($split_fl) - 1] : '';
        $save_as    = FCPATH . 'sap/import/import_pr_'.date('Y_m_d_h_i').'.'.$ext;
        $split_fl2  = explode('.',basename($filename2));
        $ext2       = count($split_fl2) > 1 ? $split_fl2[count($split_fl2) - 1] : '';
        $save_as2   = FCPATH . 'sap/import/import_bp_'.date('Y_m_d_h_i').'.'.$ext2;

        if(!in_array($protocol,['ftp','sftp'])) {
            log_message('error', '[integrasi sap] Protokol tidak valid'); die;
        }

        if(!in_array($ext,['csv'])) {
            log_message('error', '[integrasi sap] Nama File Remot tidak valid'); die;
        }

        if($protocol == 'ftp') {
            if(!function_exists('ftp_connect')) {
                log_message('error', '[integrasi sap] Protokol FTP tidak aktif karena php_ftp tidak ter-install atau tidak diaktifkan');
                die;
            }

            $conn_id        = @ftp_connect($host);
            if(!$conn_id) {
                log_message('error', '[integrasi sap] Koneksi Gagal');
                die;
            }

            if(! @ftp_login($conn_id, $username, $password)) {
                log_message('error', '[integrasi sap] Otentifikasi Username dan Password Gagal');
                ftp_close($conn_id);
                die;
            }

            ftp_pasv($conn_id, true);
            if (! @ftp_get($conn_id, $save_as, $filename, FTP_BINARY)) {
                log_message('error', '[integrasi sap] Tidak bisa membaca file '.$filename);
            }

            if (! @ftp_get($conn_id, $save_as2, $filename2, FTP_BINARY)) {
                log_message('error', '[integrasi sap] Tidak bisa membaca file '.$filename2);
            }

            ftp_close($conn_id);
        } elseif($protocol == 'sftp') {
            if(!function_exists('ssh2_connect')) {
                log_message('error', '[integrasi sap] Protokol SFTP tidak aktif karena php_ssh2 tidak ter-install atau tidak diaktifkan');
                die;
            }

            $connection = @ssh2_connect($host, $port);
            if(!$connection) {
                log_message('error', '[integrasi sap] Koneksi Gagal'); die;
            }

            if(! @ssh2_auth_password($connection, $username, $password) ) {
                log_message('error', '[integrasi sap] Otentifikasi Username dan Password Gagal'); die;
            }

            $sftp = @ssh2_sftp($connection);
            if(!$sftp) {
                log_message('error', '[integrasi sap] Tidak bisa menginisiasi Sub-Sitem SFTP'); die;
            }

            $stream = @fopen("ssh2.sftp://$sftp$filename", 'r');
            if (! $stream) {
                log_message('error', '[integrasi sap] Tidak bisa membaca file '.$filename);
            }
            $contents = fread($stream, filesize("ssh2.sftp://$sftp$filename"));
            file_put_contents ($save_as, $contents);
            @fclose($stream);

            $stream2 = @fopen("ssh2.sftp://$sftp$filename2", 'r');
            if (! $stream2) {
                log_message('error', '[integrasi sap] Tidak bisa membaca file '.$filename2);
            }
            $contents2 = fread($stream2, filesize("ssh2.sftp://$sftp$filename2"));
            file_put_contents ($save_as2, $contents2);
            @fclose($stream2);
        }

        if(file_exists($save_as)) {
            $file       = $save_as;
            $split_file = explode('.', basename($file));
            $ext        = strtolower($split_file[count($split_file)-1]);
            $pr_item    = [];
            $cols       = get_field('sap_detail','name');
            $pr_type    = [];
            foreach(['pr_persediaan','pr_jasa','pr_biaya','pr_asset'] as $p) {
                if(setting($p)) $pr_type[] = setting($p);
            }
            $col        = [];
            foreach($cols as $c) {
                if($c != 'id' && $c != 'is_deleted' && $c != 'deleted_date') $col[] = $c;
            }
            $jml_input  = $jml_edit = 0;
            if($ext == 'csv') {
                $delimiter  = $this->detectDelimiter($file);
                if (($handle = fopen($file, "r")) !== FALSE) {
                    while (($row_data = fgetcsv($handle, 4096, $delimiter)) !== FALSE) {
                        $num    = count($row_data);
                        $data   = [];
                        if(isset($row_data[0]) && in_array($row_data[0], $pr_type)) {
                            for ($c=0; $c < $num; $c++) {
                                $data[$col[$c]] = trim($row_data[$c]);
                            }
                        }
                        if(count($data) > 0) {
                            $delivery_date  = str_replace(['/','-'.' '], '.', $data['delivery_date']);
                            $split_date     = explode('.', $delivery_date);
                            if(count($split_date) == 3) {
                                $data['delivery_date']  = $split_date[2].'-'.$split_date[1].'-'.$split_date[0];
                            }
                            $data['quantity']   = str_replace(['.',','], '', $data['quantity']);
                            $data['price_unit'] = str_replace(['.',','], '', $data['price_unit']);
                            $data['total_value']    = str_replace(['.',','], '', $data['total_value']);
                            if(in_array($data['pr_type'], $pr_type)) {
                                $pr_item[$data['purchase_req_item']]    = $data['purchase_req_item'];
                                $check_record = get_data('sap_detail',[
                                    'where'     => [
                                        'purchase_req_item' => $data['purchase_req_item'],
                                        'item_no'           => $data['item_no']
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
                $get_detail     = get_data('sap_detail',[
                    'where'     => [
                        'purchase_req_item' => $pr_item
                    ],
                    'group_by'  => 'purchase_req_item'
                ])->result();
                foreach($get_detail as $g) {
                    $total  = get_data('sap_detail',[
                        'select'    => 'SUM(total_value) AS jml',
                        'where'     => [
                            'purchase_req_item' => $g->purchase_req_item,
                            'is_deleted'        => 0
                        ],
                        'sort_by'   => 'id',
                        'sort'      => 'asc'
                    ])->row();
                    $record     = [
                        'pr_type'           => $g->pr_type,
                        'purchase_req_item' => $g->purchase_req_item,
                        'jabatan'           => $g->jabatan,
                        'nama_pengadaan'    => $g->nama_pengadaan,
                        'kode_divisi'       => $g->kode_divisi,
                        'plant'             => $g->plant,
                        'total_usulan'      => isset($total->jml) ? $total->jml : 0
                    ];
                    $check_header   = get_data('sap_header','purchase_req_item',$record['purchase_req_item'])->row();
                    if(isset($check_header->id)) {
                        update_data('sap_header',$record,'id',$check_header->id);
                    } else {
                        insert_data('sap_header',$record);
                    }
                }
            }

            log_message('success', '[integrasi sap] PR : ' . $jml_input.' data berhasil disimpan, '.$jml_edit.' data berhasil diperbaharui');
        }
        if(file_exists($save_as2)) {
            $file       = $save_as2;
            $split_file = explode('.', basename($file));
            $ext        = strtolower($split_file[count($split_file)-1]);

            $delimiter  = $this->detectDelimiter($file);
            $jml_edit   = 0;
            if (($handle = fopen($file, "r")) !== FALSE) {
                while (($row_data = fgetcsv($handle, 4096, $delimiter)) !== FALSE) {
                    $kode_sap       = isset($row_data[0]) ? $row_data[0] : '';
                    $kode_rekanan   = isset($row_data[1]) ? $row_data[1] : '';
                    if($kode_sap && $kode_rekanan) {
                        $save = update_data('tbl_vendor',['kode_sap'=>$kode_sap],'kode_rekanan',$kode_rekanan);
                        if($save) {
                            update_data('tbl_pemenang_pengadaan',['kode_sap_vendor'=>$kode_sap],'kode_vendor',$kode_rekanan);
                            $jml_edit++;
                        }
                    }
                }
                fclose($handle);
            }

            log_message('success', '[integrasi sap] BP : '.$jml_edit.' data berhasil diperbaharui');
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

    function for_sap() {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        $filename   = [
            'BP'    => 'BP', 
            'OA'    => 'OA', 
            'PO'    => 'PO', 
            'PR'    => 'PR_UPDATE'
        ];
        $dir        = FCPATH . 'sap/export/';
        $periode    = setting('export_periode') ? setting('export_periode') : 1;
        $prefix     = setting('export_prefix') && setting('export_prefix') != 'none' ? date(setting('export_prefix')) : '';
        $ext        = setting('export_type') == 'xlsx' ? 'xlsx' : 'csv';
        $zip_name   = 'EPROC'.$prefix.'.zip';
        foreach($filename as $k => $v) $filename[$k]    = $v.$prefix.'.'.$ext;

        $end_date   = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-'.$periode.' days'));

        $wilayah    = get_data('tbl_m_wilayah','parent_id',0)->result();
        $kd_wil     = [];
        foreach($wilayah as $w) {
            $kd_wil[$w->id] = $w->kode_sap;
        }

        $q_pr       = get_data('sap_detail',[
            'select'    => 'purchase_req_item , item_no',
            'where'     => [
                'is_deleted'            => 1,
                'DATE(deleted_date) >=' => $start_date,
                'DATE(deleted_date) <=' => $end_date
            ]
        ])->result();

        $q_bp       = get_data('tbl_vendor a',[
            'select'    => 'a.kode_rekanan, a.kode_sap, a.nama, a.npwp, a.alamat, a.nama_kota, a.nama_kecamatan, a.nama_kelurahan, a.id_provinsi, a.kode_pos, a.no_telepon, a.no_fax, a.email, a.kode_badan_usaha, a.kode_kelompok_rekanan, a.kode_bank, a.nomor_rekening, a.pemilik_rekening, a.kode_recont',
            'join'      => 'tbl_pemenang_pengadaan b ON a.id = b.id_vendor TYPE LEFT',
            'where'     => [
                'b.tanggal_spk >='  => $start_date,
                'b.tanggal_spk <='  => $end_date
            ],
            'group_by'  => 'a.id'
        ])->result();

        $q_po       = get_data('tbl_pemenang_pengadaan_detail a',[
            'join'      => 'tbl_pemenang_pengadaan b ON a.id_pemenang_pengadaan = b.id TYPE LEFT',
            'select'    => 'a.item_no, a.material_number, a.short_text, a.quantity, a.unit_of_measure, a.acc_asignment_category, a.delivery_date, a.price_unit, a.unit, a.plant, a.stor_loc, a.purchase_req_item, a.jabatan, a.cost_center, b.nomor_spk, b.tanggal_spk, b.tanggal_jatuh_tempo_spk, b.tipe_dokumen, b.kode_vendor, b.kelompok_pembelian, a.kode_pajak, b.tanggal_po',
            'where'     => [
                'doc_type'              => 'PO',
                'tanggal_spk >='        => $start_date,
                'tanggal_spk <='        => $end_date
            ]
        ])->result();

        $q_oa       = get_data('tbl_pemenang_pengadaan_detail a',[
            'join'      => 'tbl_pemenang_pengadaan b ON a.id_pemenang_pengadaan = b.id TYPE LEFT',
            'select'    => 'a.item_no, a.material_number, a.short_text, a.quantity, a.unit_of_measure, a.acc_asignment_category, a.delivery_date, a.price_unit, a.unit, a.plant, a.stor_loc, a.purchase_req_item, a.jabatan, a.cost_center, b.nomor_spk, b.tanggal_spk, b.tanggal_jatuh_tempo_spk, b.tipe_dokumen, b.kode_vendor, b.kelompok_pembelian, a.kode_pajak, b.tanggal_dikeluarkan_kontrak, b.tanggal_mulai_kontrak, b.tanggal_selesai_kontrak, b.target_value',
            'where'     => [
                'doc_type'              => 'OA',
                'id_kontrak >'          => 0,
                'tanggal_input_kontrak >=' => $start_date,
                'tanggal_input_kontrak <=' => $end_date
            ]
        ])->result();

        if($ext == 'xlsx') {
            $this->load->library('PHPExcel');

            // PR START
            $objPHPExcel    = new PHPExcel();
            $objset         = $objPHPExcel->setActiveSheetIndex(0);
            $objget         = $objPHPExcel->getActiveSheet();

            $objset->setCellValue("A1", "PURCHASE_REQ_ITEM");
            $objset->setCellValue("B1", "ITEM_NO");
            $objset->setCellValue("C1", "DELETION_FLAG");

            $count = 1;
            foreach($q_pr as $q) {
                $count++;
                $objset->setCellValue("A".$count, $q->purchase_req_item);
                $objset->setCellValue("B".$count, $q->item_no);
                $objset->setCellValue("C".$count, "X");
            }
            foreach(range('A', 'C') as $col) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($dir.$filename['PR']);

            // BP START
            $objPHPExcel    = new PHPExcel();
            $objset         = $objPHPExcel->setActiveSheetIndex(0);
            $objget         = $objPHPExcel->getActiveSheet();

            $objset->setCellValue("A1", "BP_NUMBER");
            $objset->setCellValue("B1", "EXTERNAL_BP_NUMBER");
            $objset->setCellValue("C1", "ACC_GROUP");
            $objset->setCellValue("D1", "TITLE");
            $objset->setCellValue("E1", "NAME1");
            $objset->setCellValue("F1", "NAME2");
            $objset->setCellValue("G1", "NAME3");
            $objset->setCellValue("H1", "SEARCH_TERM");
            $objset->setCellValue("I1", "SEARCH_TERM2");
            $objset->setCellValue("J1", "STREET1");
            $objset->setCellValue("K1", "STREET2");
            $objset->setCellValue("L1", "STREET3");
            $objset->setCellValue("M1", "POSTAL_CODE");
            $objset->setCellValue("N1", "CITY");
            $objset->setCellValue("O1", "COUNTRY");
            $objset->setCellValue("P1", "REGION");
            $objset->setCellValue("Q1", "TEL_NUMBER");
            $objset->setCellValue("R1", "FAX");
            $objset->setCellValue("S1", "EMAIL");
            $objset->setCellValue("T1", "TAX_NUMBER");
            $objset->setCellValue("U1", "BANK_KEY");
            $objset->setCellValue("V1", "BANK_ACC");
            $objset->setCellValue("W1", "ACC_HOLDER");
            $objset->setCellValue("X1", "RECONT_ACC");
            $objset->setCellValue("Y1", "TERM_PAYMENT");
            $objset->setCellValue("Z1", "CURRENCY");

            $count = 1;
            foreach($q_bp as $q) {
                $count++;
                $objset->setCellValueExplicit("A".$count, $q->kode_sap,PHPExcel_Cell_DataType::TYPE_STRING);
                $objset->setCellValueExplicit("B".$count, $q->kode_rekanan,PHPExcel_Cell_DataType::TYPE_STRING);
                $objset->setCellValue("C".$count, $q->kode_kelompok_rekanan);
                $objset->setCellValueExplicit("D".$count, $q->kode_badan_usaha,PHPExcel_Cell_DataType::TYPE_STRING);
                $objset->setCellValue("E".$count, $q->nama);
                $objset->setCellValue("H".$count, $q->nama);
                $objset->setCellValue("J".$count, $q->alamat);
                $objset->setCellValue("K".$count, $q->nama_kelurahan);
                $objset->setCellValue("L".$count, $q->nama_kecamatan);
                $objset->setCellValueExplicit("M".$count, $q->kode_pos,PHPExcel_Cell_DataType::TYPE_STRING);
                $objset->setCellValue("N".$count, $q->nama_kota);
                $objset->setCellValue("O".$count, 'ID');
                $objset->setCellValue("P".$count, isset($kd_wil[$q->id_provinsi]) ? $kd_wil[$q->id_provinsi] : '');
                $objset->setCellValueExplicit("Q".$count, $q->no_telepon,PHPExcel_Cell_DataType::TYPE_STRING);
                $objset->setCellValueExplicit("R".$count, $q->no_fax,PHPExcel_Cell_DataType::TYPE_STRING);
                $objset->setCellValue("S".$count, $q->email);
                $objset->setCellValueExplicit("T".$count, $q->npwp,PHPExcel_Cell_DataType::TYPE_STRING);
                $objset->setCellValueExplicit("U".$count, $q->kode_bank,PHPExcel_Cell_DataType::TYPE_STRING);
                $objset->setCellValueExplicit("V".$count, $q->nomor_rekening,PHPExcel_Cell_DataType::TYPE_STRING);
                $objset->setCellValue("W".$count, $q->pemilik_rekening);
                $objset->setCellValueExplicit("X".$count, $q->kode_recont,PHPExcel_Cell_DataType::TYPE_STRING);
                $objset->setCellValue("Z".$count, 'IDR');
            }
            foreach(range('A', 'Z') as $col) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($dir.$filename['BP']);

            // PO START
            $objPHPExcel    = new PHPExcel();
            $objset         = $objPHPExcel->setActiveSheetIndex(0);
            $objget         = $objPHPExcel->getActiveSheet();

            $objset->setCellValue("A1", "NO_SPK");
            $objset->setCellValue("B1", "TGL_SPK");
            $objset->setCellValue("C1", "TGL_JATUH_TEMPO");
            $objset->setCellValue("D1", "PO_DOC_TYPE");
            $objset->setCellValue("E1", "VENDOR");
            $objset->setCellValue("F1", "DOC_DATE");
            $objset->setCellValue("G1", "PURCH_GROUP");
            $objset->setCellValue("H1", "PURCH_ORG");
            $objset->setCellValue("I1", "ITEM_NUMBER");
            $objset->setCellValue("J1", "MATERIAL_NUMBER");
            $objset->setCellValue("K1", "SHORT_TEXT");
            $objset->setCellValue("L1", "PO_QUANTITY");
            $objset->setCellValue("M1", "ORDER_UNIT");
            $objset->setCellValue("N1", "DELIVERY_DATE");
            $objset->setCellValue("O1", "NET_PRICE");
            $objset->setCellValue("P1", "CURRENCY");
            $objset->setCellValue("Q1", "UNIT_PRICE");
            $objset->setCellValue("R1", "PLANT");
            $objset->setCellValue("S1", "STOR_LOC");
            $objset->setCellValue("T1", "REQUISTIONER");
            $objset->setCellValue("U1", "PURC_REQ");
            $objset->setCellValue("V1", "PR_ITEM");
            $objset->setCellValue("W1", "ACC_ASIIGN");
            $objset->setCellValue("X1", "TAX_CODE");
            $objset->setCellValue("Y1", "COST_CENTER");

            $count = 1;
            foreach($q_po as $q) {
                $count++;
                $objset->setCellValue("A".$count, $q->nomor_spk);
                $objset->setCellValue("B".$count, date('Ymd',strtotime($q->tanggal_spk)));
                $objset->setCellValue("C".$count, date('Ymd',strtotime($q->tanggal_jatuh_tempo_spk)));
                $objset->setCellValue("D".$count, $q->tipe_dokumen);
                $objset->setCellValue("E".$count, $q->kode_vendor);
                $objset->setCellValue("F".$count, date('Ymd',strtotime($q->tanggal_po)));
                $objset->setCellValue("G".$count, $q->kelompok_pembelian);
                $objset->setCellValue("H".$count, 'PGD');
                $objset->setCellValue("I".$count, $q->item_no);
                $objset->setCellValue("J".$count, $q->material_number);
                $objset->setCellValue("K".$count, $q->short_text);
                $objset->setCellValue("L".$count, $q->quantity);
                $objset->setCellValue("M".$count, $q->unit_of_measure);
                $objset->setCellValue("N".$count, date('Ymd',strtotime($q->delivery_date)));
                $objset->setCellValue("O".$count, $q->price_unit);
                $objset->setCellValue("P".$count, 'IDR');
                $objset->setCellValue("Q".$count, $q->unit);
                $objset->setCellValue("R".$count, $q->plant);
                $objset->setCellValue("S".$count, $q->stor_loc);
                $objset->setCellValue("T".$count, $q->jabatan);
                $objset->setCellValue("U".$count, $q->purchase_req_item);
                $objset->setCellValue("V".$count, $q->item_no);
                $objset->setCellValue("W".$count, $q->acc_asignment_category);
                $objset->setCellValue("X".$count, $q->kode_pajak);
                $objset->setCellValue("Y".$count, $q->cost_center);
            }
            foreach(range('A', 'Y') as $col) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($dir.$filename['PO']);

            // OA START
            $objPHPExcel    = new PHPExcel();
            $objset         = $objPHPExcel->setActiveSheetIndex(0);
            $objget         = $objPHPExcel->getActiveSheet();

            $objset->setCellValue("A1", "NO_SPK");
            $objset->setCellValue("B1", "TGL_SPK");
            $objset->setCellValue("C1", "TGL_JATUH_TEMPO");
            $objset->setCellValue("D1", "PURCH_GROUP");
            $objset->setCellValue("E1", "AGREMENT_TYPE");
            $objset->setCellValue("F1", "VENDOR");
            $objset->setCellValue("G1", "AGREMENT_DATE");
            $objset->setCellValue("H1", "VALIDITY_START");
            $objset->setCellValue("I1", "VALIDITY_END");
            $objset->setCellValue("J1", "TERGET_VALUE");
            $objset->setCellValue("K1", "PURC_ORG");
            $objset->setCellValue("L1", "ITEM_NUMBER");
            $objset->setCellValue("M1", "PR_NUMBER");
            $objset->setCellValue("N1", "PR_ITEM");
            $objset->setCellValue("O1", "ACC_ASIIGN");
            $objset->setCellValue("P1", "MATERIAL_NUMBER");
            $objset->setCellValue("Q1", "SHORT_TEXT");
            $objset->setCellValue("R1", "TARGET_QTY");
            $objset->setCellValue("S1", "ORDER_UNIT");
            $objset->setCellValue("T1", "NET_PRICE");
            $objset->setCellValue("U1", "PLANT");
            $objset->setCellValue("V1", "SLOC");
            $objset->setCellValue("W1", "COST_CENTER");

            $count = 1;
            foreach($q_oa as $q) {
                $count++;
                $objset->setCellValue("A".$count, $q->nomor_spk);
                $objset->setCellValue("B".$count, date('Ymd',strtotime($q->tanggal_spk)));
                $objset->setCellValue("C".$count, date('Ymd',strtotime($q->tanggal_jatuh_tempo_spk)));
                $objset->setCellValue("D".$count, $q->kelompok_pembelian);
                $objset->setCellValue("E".$count, $q->tipe_dokumen);
                $objset->setCellValue("F".$count, $q->kode_vendor);
                $objset->setCellValue("G".$count, date('Ymd',strtotime($q->tanggal_dikeluarkan_kontrak)));
                $objset->setCellValue("H".$count, date('Ymd',strtotime($q->tanggal_mulai_kontrak)));
                $objset->setCellValue("I".$count, date('Ymd',strtotime($q->tanggal_selesai_kontrak)));
                $objset->setCellValue("J".$count, $q->target_value);
                $objset->setCellValue("K".$count, 'PGD');
                $objset->setCellValue("L".$count, $q->item_no);
                $objset->setCellValue("M".$count, $q->purchase_req_item);
                $objset->setCellValue("N".$count, $q->item_no);
                $objset->setCellValue("O".$count, $q->acc_asignment_category);
                $objset->setCellValue("P".$count, $q->material_number);
                $objset->setCellValue("Q".$count, $q->short_text);
                $objset->setCellValue("R".$count, $q->quantity);
                $objset->setCellValue("S".$count, $q->unit_of_measure);
                $objset->setCellValue("T".$count, $q->price_unit);
                $objset->setCellValue("U".$count, $q->plant);
                $objset->setCellValue("V".$count, $q->stor_loc);
                $objset->setCellValue("W".$count, $q->cost_center);
            }
            foreach(range('A', 'W') as $col) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($dir.$filename['OA']);
        } elseif($ext == 'csv') {
            $delimiter  = setting('export_type') == 'csv_coma' ? ',' : ';';

            // PR START
            $output     = fopen($dir.$filename['PR'], "w");
            $this->fputcsv_eol($output, ['PURCHASE_REQ_ITEM','ITEM_NO','DELETION_FLAG'],$delimiter);
            foreach($q_pr as $q) {
                $this->fputcsv_eol($output, [
                    $q->purchase_req_item,
                    $q->item_no,
                    'X'
                ],$delimiter);
            }
            fclose($output);

            // BP START
            $output     = fopen($dir.$filename['BP'], "w");
            $this->fputcsv_eol($output, [
                "BP_NUMBER",
                "EXTERNAL_BP_NUMBER",
                "ACC_GROUP",
                "TITLE",
                "NAME1",
                "NAME2",
                "NAME3",
                "SEARCH_TERM",
                "SEARCH_TERM2",
                "STREET1",
                "STREET2",
                "STREET3",
                "POSTAL_CODE",
                "CITY",
                "COUNTRY",
                "REGION",
                "TEL_NUMBER",
                "FAX",
                "EMAIL",
                "TAX_NUMBER",
                "BANK_KEY",
                "BANK_ACC",
                "ACC_HOLDER",
                "RECONT_ACC",
                "TERM_PAYMENT",
                "CURRENCY"
            ],$delimiter);
            foreach($q_bp as $q) {
                $this->fputcsv_eol($output, [
                    '',
                    $q->kode_rekanan,
                    '',
                    '',
                    $q->nama,
                    '',
                    '',
                    $q->nama,
                    '',
                    preg_replace( "/(\r|\n)/", " ", $q->alamat ),
                    $q->nama_kelurahan,
                    $q->nama_kecamatan,
                    $q->kode_pos,
                    $q->nama_kota,
                    'ID',
                    isset($kd_wil[$q->id_provinsi]) ? $kd_wil[$q->id_provinsi] : '',
                    $q->no_telepon,
                    $q->no_fax,
                    $q->email,
                    $q->npwp,
                    $q->kode_bank,
                    $q->nomor_rekening,
                    $q->pemilik_rekening,
                    $q->kode_recont,
                    '',
                    'IDR'
                ],$delimiter);
            }
            fclose($output);

            // PO START
            $output     = fopen($dir.$filename['PO'], "w");
            $this->fputcsv_eol($output, [
                "NO_SPK",
                "TGL_SPK",
                "TGL_JATUH_TEMPO",
                "PO_DOC_TYPE",
                "VENDOR",
                "DOC_DATE",
                "PURCH_GROUP",
                "PURCH_ORG",
                "ITEM_NUMBER",
                "MATERIAL_NUMBER",
                "SHORT_TEXT",
                "PO_QUANTITY",
                "ORDER_UNIT",
                "DELIVERY_DATE",
                "NET_PRICE",
                "CURRENCY",
                "UNIT_PRICE",
                "PLANT",
                "STOR_LOC",
                "REQUISTIONER",
                "PURC_REQ",
                "PR_ITEM",
                "ACC_ASIIGN",
                "TAX_CODE",
                "COST_CENTER"
            ],$delimiter);
            foreach($q_po as $q) {
                $this->fputcsv_eol($output, [
                    $q->nomor_spk,
                    date('Ymd',strtotime($q->tanggal_spk)),
                    date('Ymd',strtotime($q->tanggal_jatuh_tempo_spk)),
                    $q->tipe_dokumen,
                    $q->kode_vendor,
                    date('Ymd',strtotime($q->tanggal_po)),
                    $q->kelompok_pembelian,
                    'PGD',
                    $q->item_no,
                    $q->material_number,
                    $q->short_text,
                    $q->quantity,
                    $q->unit_of_measure,
                    date('Ymd',strtotime($q->delivery_date)),
                    $q->price_unit,
                    'IDR',
                    $q->unit,
                    $q->plant,
                    $q->stor_loc,
                    $q->jabatan,
                    $q->purchase_req_item,
                    $q->item_no,
                    $q->acc_asignment_category,
                    $q->kode_pajak,
                    $q->cost_center
                ],$delimiter);
            }
            fclose($output);

            // OA START
            $output     = fopen($dir.$filename['OA'], "w");
            $this->fputcsv_eol($output, [
                "NO_SPK",
                "TGL_SPK",
                "TGL_JATUH_TEMPO",
                "PURCH_GROUP",
                "AGREMENT_TYPE",
                "VENDOR",
                "AGREMENT_DATE",
                "VALIDITY_START",
                "VALIDITY_END",
                "TERGET_VALUE",
                "PURC_ORG",
                "ITEM_NUMBER",
                "PR_NUMBER",
                "PR_ITEM",
                "ACC_ASIIGN",
                "MATERIAL_NUMBER",
                "SHORT_TEXT",
                "TARGET_QTY",
                "ORDER_UNIT",
                "NET_PRICE",
                "PLANT",
                "SLOC",
                "COST_CENTER"
            ],$delimiter);
            foreach($q_oa as $q) {
                $this->fputcsv_eol($output, [
                    $q->nomor_spk,
                    date('Ymd',strtotime($q->tanggal_spk)),
                    date('Ymd',strtotime($q->tanggal_jatuh_tempo_spk)),
                    $q->kelompok_pembelian,
                    $q->tipe_dokumen,
                    $q->kode_vendor,
                    date('Ymd',strtotime($q->tanggal_dikeluarkan_kontrak)),
                    date('Ymd',strtotime($q->tanggal_mulai_kontrak)),
                    date('Ymd',strtotime($q->tanggal_selesai_kontrak)),
                    $q->target_value,
                    'PGD',
                    $q->item_no,
                    $q->purchase_req_item,
                    $q->item_no,
                    $q->acc_asignment_category,
                    $q->material_number,
                    $q->short_text,
                    $q->quantity,
                    $q->unit_of_measure,
                    $q->price_unit,
                    $q->plant,
                    $q->stor_loc,
                    $q->cost_center
                ],$delimiter);
            }
            fclose($output);
        }

        if(setting('export_zip')) {
            if(file_exists($dir.$zip_name)) {
                @unlink($dir.$zip_name);
            }

            $zip        = new ZipArchive;
            $del_file   = [];
            if ($zip->open($dir.$zip_name, ZipArchive::CREATE) === TRUE) {
                foreach($filename as $f) {
                    if(file_exists($dir.$f)) {
                        if($zip->addFile($dir.$f,$f)) {
                            $del_file[] = $dir.$f;
                        }
                    }
                }
                $zip->close();
            }
            foreach($del_file as $d) @unlink($d);
        }

        if(setting('export_url_trigger') && function_exists('curl_version')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, setting('export_url_trigger'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
        }
    }

    private function fputcsv_eol($handle, $array, $delimiter = ',', $enclosure = '"', $eol = PHP_EOL) {
        $return = fputcsv($handle, $array, $delimiter, $enclosure);
        if($return !== FALSE && 0 === fseek($handle, -1, SEEK_CUR)) {
            fwrite($handle, $eol);
        }
        return $return;
    }

    function log($dt='') {
        if($dt && file_exists(FCPATH . 'assets/logs/log-'.$dt.'.php')) {
            $konten = file_get_contents(FCPATH . 'assets/logs/log-'.$dt.'.php');
            if($konten) {
                echo '<table width="100%" border="1" style="border-collapse: collapse;">';
                foreach (explode("\n", $konten) as $key => $value) {
                    if(strpos($value, ' - ') !== false && strpos($value, ' --> ') !== false) {
                        echo '<tr>';
                        $v1     = explode(' - ', $value);
                        echo '<td style="padding: 5px 10px;">'.$v1[0].'</td>';
                        $v2     = explode(' --> ', $v1[1], 2);
                        echo '<td style="padding: 5px 10px;">'.$v2[0].'</td>';
                        echo '<td style="padding: 5px 10px;">'.$v2[1].'</td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
            }
        } else echo lang('tidak_ada_data');
    }

}