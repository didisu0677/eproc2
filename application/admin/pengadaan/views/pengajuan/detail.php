<?php if($approve_user == 9) { ?>
<div class="alert alert-danger"><?php echo '<strong>'.lang('alasan_ditolak').'</strong> : '.$alasan_ditolak; ?></div>
<?php } elseif($approve_user == 8) { ?>
<div class="alert alert-warning"><?php echo '<strong>'.lang('alasan_dikembalikan').'</strong> : '.$alasan_ditolak; ?></div>
<?php } ?>
<div class="table-responsive">
    <table class="table table-bordered table-app table-detail table-normal">
        <tr>
            <th width="130"><?php echo lang('nomor_pengajuan'); ?></th>
            <td colspan="3"><?php echo $nomor_pengajuan; ?></td>
        </tr>
        <tr>
            <th><?php echo lang('unit_kerja'); ?></th>
            <td colspan="3"><?php echo $unit_kerja; ?></td>
        </tr>
        <tr>
            <th><?php echo lang('divisi'); ?></th>
            <td colspan="3"><?php echo $nama_divisi; ?></td>
        </tr>
        <tr>
            <th><?php echo lang('tanggal_pengadaan'); ?></th>
            <td colspan="3"><?php echo date_indo($tanggal_pengadaan); ?></td>
        </tr>
        <tr>
            <th><?php echo lang('nama_pemberi_tugas'); ?></th>
            <td colspan="3"><?php echo $pemberi_tugas; ?></td>
        </tr>
        <tr>
            <th><?php echo lang('nama_pengadaan'); ?></th>
            <td colspan="3"><?php echo $nama_pengadaan; ?></td>
        </tr>
        <tr>
            <th><?php echo lang('mata_anggaran'); ?></th>
            <td><?php echo $mata_anggaran; ?></td>
            <th width="100"><?php echo lang('besar_anggaran'); ?></th>
            <td><?php echo custom_format($besar_anggaran); ?></td>
        </tr>
        <tr>
            <th><?php echo lang('usulan_hps'); ?></th>
            <td colspan="3"><a href="<?php echo base_url('pengadaan/pengajuan/hps_usulan/'.encode_id($id)); ?>" target="_blank"><?php echo custom_format($usulan_hps); ?></a></td>
        </tr>
        <tr>
            <th>TOR</th>
            <td colspan="3"><a href="<?php echo base_url('pengadaan/pengajuan/cetak_tor/'.encode_id([$id,rand()])); ?>" target="_blank"><?php echo $nomor_tor; ?></a></td>
        </tr>
        <tr>
            <th><?php echo lang('dokumen_pendukung'); ?></th>
            <td colspan="3">
                <ul class="pl-3 mb-0">
                    <?php
                    foreach(json_decode($file,true) as $k => $v) {
                        echo '<li><a href="'.base_url('assets/uploads/pengajuan/'.$v).'" target="_blank">'.$k.'</a></li>';
                    }
                    ?>
                </ul>    
            </td>
        </tr>
    </table>
</div>