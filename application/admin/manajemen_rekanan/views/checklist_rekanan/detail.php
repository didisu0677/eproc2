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
<div class="table-responsive mb-2">
    <table class="table table-bordered table-app table-detail table-normal">
        <thead>
            <tr>
                <th><?php echo lang('nama_dokumen'); ?></th>
                <th class="text-center"><?php echo lang('unduh'); ?></th>
                <th><?php echo lang('checklist'); ?></th>
                <th><?php echo lang('keterangan'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dokumen as $d) { ?>
            <tr>
                <td><?php echo $d->nama_dokumen; ?></td>
                <td class="text-center"><?php if($d->file) { ?><a href="<?php echo base_url('assets/uploads/rekanan/'.$id.'/'.$d->file); ?>"><i class="fa-download"></i></a><?php } ?></td>
                <td class="text-center"><?php if($d->verifikasi) echo '<i class="fa-check"></i>'; ?></td>
                <td><?php echo $d->keterangan; ?></td>
            </tr>
            <?php } ?>
            <tr>
                <td>&nbsp;</td>
                <td class="text-center"><a href="<?php echo base_url('manajemen_rekanan/download_dokumen/'.encode_id($id)); ?>" target="_blank" class="btn btn-sm btn-info"><?php echo lang('unduh_semua'); ?></a></td>
                <td class="text-center"><?php if($d->verifikasi) echo '<i class="fa-check"></i>'; ?></td>
                <td><?php echo $d->keterangan; ?></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-app table-detail table-normal">
        <tr>
            <th width="130"><?php echo lang('keterangan'); ?></th>
            <td><?php echo $keterangan; ?></td>
        </tr>
        <tr>
            <th width="130"><?php echo lang('verifikasi_oleh'); ?></th>
            <td><?php echo $verifikasi_oleh; ?></td>
        </tr>
        <tr>
            <th><?php echo lang('tanggal_verifikasi'); ?></th>
            <td><?php echo $tanggal_verifikasi; ?></td>
        </tr>
    </table>
</div>