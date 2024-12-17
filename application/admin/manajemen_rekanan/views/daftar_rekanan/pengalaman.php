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
                <th><?php echo lang('pengalaman'); ?></th>
                <th><?php echo lang('deskripsi'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pengalaman as $d) { ?>
            <tr>
                <td><?php echo $d->pengalaman; ?></td>
                <td><?php echo $d->deskripsi; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>