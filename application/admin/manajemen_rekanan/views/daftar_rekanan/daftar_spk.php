<div class="card mb-2">
    <div class="card-header"><?php echo lang('informasi_umum'); ?></div>
    <div class="card-body p-1">
        <div class="table-responsive mb-2">
            <table class="table table-bordered table-app table-detail table-normal">
                <tr>
                    <th width="130"><?php echo lang('kode_rekanan'); ?></th>
                    <td><?php echo $kode_rekanan; ?></td>
                </tr>
                <tr>
                    <th width="130"><?php echo lang('nama_rekanan'); ?></th>
                    <td><?php echo $nama; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('alamat'); ?></th>
                    <td><?php echo $alamat.', '.$nama_kelurahan.', '.$nama_kecamatan.', '.$nama_kota.', '.$nama_provinsi.' - '.$kode_pos; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="table-responsive mb-2">
    <table class="table table-bordered table-app table-detail table-normal">
        <thead>
            <tr>
                <th><?php echo lang('nomor_kontrak'); ?></th>
                <th><?php echo lang('nama_pekerjaan'); ?></th>
                <th><?php echo lang('nilai_pekerjaan'); ?></th>
                <th><?php echo lang('tanggal_mulai_kontrak'); ?></th>
                <th><?php echo lang('tanggal_selesai_kontrak'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($kontrak as $d) { ?>
            <tr>
                <td><?php echo $d->nomor_kontrak; ?></td>
                <td><?php echo $d->nama_pengadaan; ?></td>
                <td><?php echo custom_format($d->nilai_pengadaan); ?></td>
                <td><?php echo c_date($d->tanggal_mulai_kontrak); ?></td>
                <td><?php echo c_date($d->tanggal_selesai_kontrak); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>