<div class="card mb-2">
    <div class="card-header"><?php echo lang('informasi_pengadaan'); ?></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-app table-detail table-normal">
            <tr>
                <th width="200"><?php echo lang('nomor'); ?></th>
                <td colspan="3"><?php echo $nomor; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('nama_vendor'); ?></th>
                <td colspan="3"><?php echo $nama_vendor; ?></td>
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
                <th><?php echo lang('nilai_kontrak'); ?></th>
                <td colspan="3"><?php echo custom_format($nilai_kontrak); ?></td>
            </tr>
            <tr>
                <th><?php echo lang('nama_evaluator'); ?></th>
                <td colspan="3"><?php echo $nama_evaluator; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('jabatan'); ?></th>
                <td colspan="3"><?php echo $jabatan; ?></td>
            </tr>
            <tr>
                <th>Hasil evaluasi</th>
                <td colspan="3"><a href="<?php echo base_url('manajemen_rekanan/evaluasi_vendor/cetak_evaluasi/'.encode_id($id,rand())); ?>" target="_blank"><?php echo $nomor; ?></a></td>
            </tr>
            <tr>
                <th><?php echo lang('keterangan_lain'); ?></th>
                <td colspan="3"><?php echo $keterangan_lain; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('rekomendasi_selanjutnya'); ?></th>
                <td colspan="3"><?php echo $hasil_rekomendasi == 1 ? lang('disarankan_untuk_bisa_menjadi_peserta_pengadaan_selanjutnya_di_Pegadaian') : lang('tidak_disarankan_untuk_bisa_menjadi_peserta_pengadaan_selanjutnya_di_Pegadaian'); ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="card mb-2">
    <div class="card-header"><?php echo lang('evaluasi_vendor'); ?></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-app table-detail table-normal">
            <tr>
                <th><?php echo lang('aspek_yang_dievaluasi'); ?></th>
                <th><?php echo lang('sangat_baik'); ?></th>
                <th><?php echo lang('baik'); ?></th>
                <th><?php echo lang('cukup_baik'); ?></th>
                <th><?php echo lang('kurang_baik'); ?></th>
                <th><?php echo lang('tidak_baik'); ?></th>
            </tr>
            <?php foreach($result as $t) { ?>
            <tr>
                <td><?php echo $t['nama_evaluasi']; ?></td>
                <td class="text-center"><?php if($t['sangat_baik'] == 1) echo '<i class="fa-check"></i>'; ?></td>
                <td class="text-center"><?php if($t['baik'] == 1) echo '<i class="fa-check"></i>'; ?></td>
                <td class="text-center"><?php if($t['cukup_baik'] == 1) echo '<i class="fa-check"></i>'; ?></td>
                <td class="text-center"><?php if($t['kurang_baik'] == 1) echo '<i class="fa-check"></i>'; ?></td>
                <td class="text-center"><?php if($t['tidak_baik'] == 1) echo '<i class="fa-check"></i>'; ?></td>
            </tr>
            <?php } ?>
            <?php foreach($lain as $t) { ?>
            <tr>
                <td><?php echo $t['nama_evaluasi']; ?></td>
                <td class="text-center"><?php if($t['sangat_baik'] == 1) echo '<i class="fa-check"></i>'; ?></td>
                <td class="text-center"><?php if($t['baik'] == 1) echo '<i class="fa-check"></i>'; ?></td>
                <td class="text-center"><?php if($t['cukup_baik'] == 1) echo '<i class="fa-check"></i>'; ?></td>
                <td class="text-center"><?php if($t['kurang_baik'] == 1) echo '<i class="fa-check"></i>'; ?></td>
                <td class="text-center"><?php if($t['tidak_baik'] == 1) echo '<i class="fa-check"></i>'; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
