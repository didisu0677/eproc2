<div class="card mb-2">
    <div class="card-header"><?php echo lang('pengadaan'); ?></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-app table-detail table-normal">
            <tr>
                <th width="130"><?php echo lang('nomor_pengadaan'); ?></th>
                <td><?php echo $nomor_pengadaan; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('tanggal_pengadaan'); ?></th>
                <td><?php echo date_indo($tanggal_pengadaan);; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('nama_pengadaan'); ?></th>
                <td><?php echo $nama_pengadaan; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('_hps'); ?></th>
                <td><?php echo custom_format($hps); ?></td>
            </tr>
            <tr>
                <th><?php echo lang('metode_pengadaan'); ?></th>
                <td><?php echo $metode_pengadaan; ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="card">
    <div class="card-header"><?php echo lang('pemenang'); ?></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-app table-detail table-normal">
            <tr>
                <th width="130"><?php echo lang('nama_rekanan'); ?></th>
                <td><?php echo $nama_vendor; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('alamat'); ?></th>
                <td><?php echo $alamat_vendor; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('penawaran_awal'); ?></th>
                <td><?php echo custom_format($penawaran_awal); ?></td>
            </tr>
            <tr>
                <th><?php echo lang('penawaran_terakhir'); ?></th>
                <td><?php echo custom_format($penawaran_terakhir); ?></td>
            </tr>
        </table>
    </div>
</div>