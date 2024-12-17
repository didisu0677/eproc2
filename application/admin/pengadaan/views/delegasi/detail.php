<div class="card mb-2">
    <div class="card-header"><?php echo lang('delegasi'); ?></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-app table-detail table-normal">
            <tr>
                <th width="200"><?php echo lang('nomor_delegasi'); ?></th>
                <td colspan="3"><?php echo $nomor_disposisi; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('tanggal_delegasi'); ?></th>
                <td colspan="3"><?php echo date_indo($tanggal_delegasi); ?></td>
            </tr>
            <tr>
                <th><?php echo lang('panitia'); ?></th>
                <td colspan="3"><?php echo $nama_panitia; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('anggota'); ?></th>
                <td colspan="3">
                    <table class="table table-bordered table-app table-detail table-normal">
                        <tr>
                            <th><?php echo lang('nama_panitia'); ?></th>
                            <th><?php echo lang('jabatan'); ?></th>
                            <th><?php echo lang('posisi_panitia'); ?></th>
                        </tr>
                        <?php foreach($anggota as $a) { ?>
                            <tr>
                                <td><?php echo $a['nama_panitia']; ?></td>
                                <td><?php echo $a['jabatan_panitia']; ?></td>
                                <td><?php echo $a['posisi_panitia']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="card">
    <div class="card-header"><?php echo lang('pengajuan'); ?></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-app table-detail table-normal">
            <tr>
                <th width="200"><?php echo lang('nomor_pengajuan'); ?></th>
                <td colspan="3"><?php echo $nomor_pengajuan; ?></td>
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
                <td colspan="3"><?php echo custom_format($usulan_hps); ?></td>
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
</div>