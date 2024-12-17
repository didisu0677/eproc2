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
            <th><?php echo lang('nomor_pr_sap'); ?></th>
            <td colspan="3"><?php echo $purchase_req_item; ?></td>
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
            <th><?php echo lang('_tor'); ?></th>
            <td colspan="3"><a href="<?php echo base_url('pengadaan/pengajuan/cetak_tor/'.encode_id([$id,rand()])); ?>" target="_blank"><?php echo $nomor_tor; ?></a></td>
        </tr>
        <tr>
            <th><?php echo lang('panitia_pengadaan'); ?></th>
            <td colspan="3"><?php echo $nama_panitia ? $nama_panitia : '['.lang('belum_tersedia').']'; ?></td>
        </tr>
        <tr>
            <th><?php echo lang('metode_pengadaan'); ?></th>
            <td colspan="3"><?php echo $metode_pengadaan ? $metode_pengadaan : '['.lang('belum_tersedia').']'; ?></td>
        </tr>
        <?php if(isset($hps['id'])) { ?>
        <tr>
            <th><?php echo lang('_hps'); ?></th>
            <td><a href="<?php echo base_url('pengadaan/hps/cetak_hps/'.encode_id($hps['id'])); ?>" target="_blank"><?php echo $no_hps; ?></a></td>
            <th><?php echo lang('hps_panitia'); ?></th>
            <td><?php echo custom_format($hps['total_hps_pembulatan']); ?></td>
        </tr>
	    <?php } else { ?>
        <tr>
            <th><?php echo lang('_hps'); ?></th>
            <td>[<?php echo lang('belum_tersedia'); ?>]</td>
            <th><?php echo lang('hps_panitia'); ?></th>
            <td>[<?php echo lang('belum_tersedia'); ?>]</td>
        </tr>
	    <?php } if(isset($rks['id'])) { ?>
        <tr>
            <th><?php echo lang('_rks'); ?></th>
            <td colspan="3"><a href="<?php echo base_url('pengadaan/rks/cetak/'.encode_id($rks['id'])); ?>" target="_blank"><?php echo $nomor_rks; ?></a></td>
        </tr>
	    <?php } else { ?>
        <tr>
            <th><?php echo lang('_rks'); ?></th>
            <td colspan="3">[<?php echo lang('belum_tersedia'); ?>]</td>
        </tr>
	    <?php } ?>
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
        <tr>
            <th><?php echo lang('pelaksana_pekerjaan'); ?></th>
            <td colspan="3"><?php echo $nama_vendor ? $nama_vendor : '['.lang('belum_tersedia').']'; ?></td>
        </tr>
        <tr>
            <th><?php echo lang('nomor_spk'); ?></th>
            <td colspan="3"><?php echo $nomor_spk ? $nomor_spk : '['.lang('belum_tersedia').']'; ?></td>
        </tr>
        <tr>
            <th><?php echo lang('tanggal_spk'); ?></th>
            <td colspan="3"><?php echo $nomor_spk ? date_lang($tanggal_spk) : '['.lang('belum_tersedia').']'; ?></td>
        </tr>
        <tr>
            <th><?php echo lang('nilai_kontrak'); ?></th>
            <td colspan="3"><?php echo $nomor_spk ? custom_format($penawaran_terakhir) : '['.lang('belum_tersedia').']'; ?></td>
        </tr>
    </table>
</div>