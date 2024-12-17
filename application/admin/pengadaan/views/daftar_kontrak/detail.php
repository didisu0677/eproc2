<div class="card-body table-responsive">
<table class="table table-bordered table-app table-detail table-normal">
    <tr>
        <th width="200"><?php echo lang('divisi'); ?></th>
        <td colspan="3"><?php echo $divisi; ?></td>
    </tr>
    <tr>
        <th width="200"><?php echo lang('unit_kerja'); ?></th>
        <td colspan="3"><?php echo $unit; ?></td>
    </tr>

    <tr>
        <th width="200"><?php echo lang('nomor_kontrak'); ?></th>
        <td colspan="3"><?php echo $nomor_kontrak; ?></td>
    </tr>
    <tr>
        <th><?php echo lang('nomor_spk'); ?></th>
        <td colspan="3"><?php echo $nomor_spk; ?></td>
    </tr>
    <tr>
        <th><?php echo lang('nomor_pengadaan'); ?></th>
        <td colspan="3"><?php echo $nomor_pengadaan; ?></td>
    </tr>
    <tr>
        <th><?php echo lang('nama_pengadaan'); ?></th>
        <td colspan="3"><?php echo $nama_pengadaan; ?></td>
    </tr>
    <tr>
        <th><?php echo lang('nilai_pengadaan'); ?></th>
        <td colspan="3"><?php echo custom_format($nilai_pengadaan); ?></td>
    </tr>
    <tr>
        <th><?php echo lang('id_vendor'); ?></th>
        <td colspan="3"><?php echo $id_vendor; ?></td>
    </tr>

    <tr>
        <th><?php echo lang('nama_vendor'); ?></th>
        <td colspan="3"><?php echo $nama_vendor; ?></td>
    </tr>

    <tr>
        <th><?php echo lang('tanggal_mulai_kontrak'); ?></th>
        <td colspan="3"><?php echo date_indo($tanggal_mulai_kontrak); ?></td>
    </tr>

    <tr>
        <th><?php echo lang('tanggal_selesai_kontrak'); ?></th>
        <td colspan="3"><?php echo date_indo($tanggal_selesai_kontrak); ?></td>
    </tr>

    <tr>
        <th><?php echo lang('tanggal_dikeluarkan'); ?></th>
        <td colspan="3"><?php echo date_indo($tanggal_dikeluarkan); ?></td>
    </tr>

    <tr>
        <th><?php echo lang('tempat_dikeluarkan'); ?></th>
        <td colspan="3"><?php echo $tempat_dikeluarkan; ?></td>
    </tr>
    <tr>
        <th><?php echo lang('nama_pihak1'); ?></th>
        <td colspan="3"><?php echo $nama_pihak1; ?></td>
    </tr>
    <tr>
        <th><?php echo lang('jabatan_pihak1'); ?></th>
        <td colspan="3"><?php echo $jabatan_pihak1; ?></td>
    </tr>
    <tr>
        <th><?php echo lang('alamat_pihak1'); ?></th>
        <td colspan="3"><?php echo $alamat_pihak1; ?></td>
    </tr>
    <tr>
        <th><?php echo lang('nama_pihak2'); ?></th>
        <td colspan="3"><?php echo $nama_pihak1; ?></td>
    </tr>
    <tr>
        <th><?php echo lang('jabatan_pihak2'); ?></th>
        <td colspan="3"><?php echo $jabatan_pihak1; ?></td>
    </tr>
    <tr>
        <th><?php echo lang('alamat_pihak2'); ?></th>
        <td colspan="3"><?php echo $alamat_pihak1; ?></td>
    </tr>
</table>
</div>