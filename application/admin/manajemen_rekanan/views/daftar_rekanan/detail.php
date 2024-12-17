<div class="card mb-2">
    <div class="card-header"><?php echo lang('informasi_umum'); ?></div>
    <div class="card-body p-1">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-app table-detail table-normal">
            <tr>
                <th><?php echo lang('kode_rekanan'); ?></th>
                    <td colspan="3"><?php echo $kode_rekanan; ?></td>
                </tr>
                <th><?php echo lang('kode_sap'); ?></th>
                    <td colspan="3"><?php echo $kode_sap; ?></td>
                </tr>
                <th><?php echo lang('username'); ?></th>
                    <td colspan="3"><?php echo $username; ?></td>
                </tr>
                
                <tr>
                    <th width="200"><?php echo lang('jenis_rekanan'); ?></th>
                    <td colspan="3"><?php echo $jenis_rekanan == 1 ? lang('badan_usaha') : lang('perorangan'); ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('nama_rekanan'); ?></th>
                    <td colspan="3"><?php echo $nama; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('npwp_rekanan'); ?></th>
                    <td colspan="3"><?php echo $npwp; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('kategori_rekanan'); ?></th>
                    <td colspan="3"><?php echo $kategori_rekanan; ?></td>
                </tr>
                <?php if($jenis_rekanan == 1) { ?>
                <tr>
                    <th><?php echo lang('bentuk_badan_usaha'); ?></th>
                    <td colspan="3"><?php echo $bentuk_badan_usaha; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('status_perusahaan'); ?></th>
                    <td colspan="3"><?php echo $status_perusahaan; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('nama_pimpinan'); ?></th>
                    <td colspan="3"><?php echo $nama_pimpinan; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('jabatan'); ?></th>
                    <td colspan="3"><?php echo $jabatan_pimpinan; ?></td>
                </tr>

                <?php } else { ?>
                <tr>
                    <th><?php echo lang('nomor_identitas'); ?></th>
                    <td><?php echo $no_identitas; ?></td>
                    <th width="100"><?php echo lang('berlaku_sampai'); ?></th>
                    <td><?php echo date_lang($tanggal_berakhir_identitas); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th><?php echo lang('kualifikasi'); ?></th>
                    <td colspan="3"><?php echo $kualifikasi; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('asosiasi'); ?></th>
                    <td colspan="3"><?php echo $asosiasi; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('mendaftar_di_unit'); ?></th>
                    <td colspan="3"><?php echo $unit_daftar; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="card mb-2">
    <div class="card-header"><?php echo lang('alamat_lengkap'); ?></div>
    <div class="card-body p-1">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-app table-detail table-normal">
                <tr>
                    <th width="200"><?php echo lang('negara'); ?></th>
                    <td colspan="3"><?php echo $nama_negara; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('alamat'); ?></th>
                    <td colspan="3"><?php echo $alamat; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('provinsi'); ?></th>
                    <td colspan="3"><?php echo $nama_provinsi; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('kota'); ?></th>
                    <td colspan="3"><?php echo $nama_kota; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('kecamatan'); ?></th>
                    <td colspan="3"><?php echo $nama_kecamatan; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('kelurahan'); ?></th>
                    <td colspan="3"><?php echo $nama_kelurahan; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('kode_pos'); ?></th>
                    <td colspan="3"><?php echo $kode_pos; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('no_telepon'); ?></th>
                    <td colspan="3"><?php echo $no_telepon; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('no_fax'); ?></th>
                    <td colspan="3"><?php echo $no_fax; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('email'); ?></th>
                    <td colspan="3"><?php echo $email; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="card mb-2">
    <div class="card-header"><?php echo lang('kontak_person'); ?></div>
    <div class="card-body p-1">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-app table-detail table-normal">
                <tr>
                    <th width="200"><?php echo lang('nama'); ?></th>
                    <td colspan="3"><?php echo $nama_cp; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('hp'); ?></th>
                    <td colspan="3"><?php echo $hp_cp; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('email') .' (digunakan untuk notifikasi email)'; ?></th>
                    <td colspan="3"><?php echo $email_cp; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('tardaftar_sejak'); ?></th>
                    <td colspan="3"><?php echo date_lang($terdaftar_sejak); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>