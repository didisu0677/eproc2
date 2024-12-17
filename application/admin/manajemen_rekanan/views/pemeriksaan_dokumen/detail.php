<div class="card mb-2">
    <div class="card-header"><?php echo lang('pemeriksaan_dokumen'); ?></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-app table-detail table-normal">
            <tr>
                <th width="200"><?php echo lang('kode_rekanan'); ?></th>
                <td colspan="3"><?php echo $kode_rekanan; ?></td>
            </tr>
            <tr>
                <th width="200"><?php echo lang('nama_rekanan'); ?></th>
                <td colspan="3"><?php echo $nama_rekanan; ?></td>
            </tr>
            <tr>
                <th width="200"><?php echo lang('nama_dokumen'); ?></th>
                <td colspan="3"><?php echo $nama_dokumen; ?></td>
            </tr>

            <tr>
                <th width="200"><?php echo lang('file'); ?></th>
                <td colspan="3"><?php echo '<a href="'.base_url('assets/uploads/rekanan/'.$id_vendor.'/'.$file).'" target="_blank">'.$file.'</a>'; ?></td>
            </tr>

            <tr>
                <th width="200"><?php echo lang('tanggal_kadaluarsa'); ?></th>
                <td colspan="3"><?php echo date("d-m-Y", strtotime($tanggal_kadaluarsa)); ?></td>
            </tr>

        </table>
    </div>
</div>
