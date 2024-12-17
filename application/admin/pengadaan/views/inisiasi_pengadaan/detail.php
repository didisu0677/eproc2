<div class="card mb-2">
    <div class="card-header"><?php echo lang('pengajuan'); ?></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-app table-detail table-normal">
            <tr>
                <th width="200"><?php echo lang('nomor_pengajuan'); ?></th>
                <td colspan="3"><?php echo $pengajuan['nomor_pengajuan']; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('unit_kerja'); ?></th>
                <td colspan="3"><?php echo $unit_kerja; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('divisi'); ?></th>
                <td colspan="3"><?php echo $pengajuan['nama_divisi']; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('tanggal_pengadaan'); ?></th>
                <td colspan="3"><?php echo date_indo($pengajuan['tanggal_pengadaan']); ?></td>
            </tr>
            <tr>
                <th><?php echo lang('nama_pemberi_tugas'); ?></th>
                <td colspan="3"><?php echo $pengajuan['pemberi_tugas']; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('nama_pengadaan'); ?></th>
                <td colspan="3"><?php echo $pengajuan['nama_pengadaan']; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('mata_anggaran'); ?></th>
                <td><?php echo $pengajuan['mata_anggaran']; ?></td>
                <th width="100"><?php echo lang('besar_anggaran'); ?></th>
                <td><?php echo custom_format($pengajuan['besar_anggaran']); ?></td>
            </tr>
            <tr>
                <th><?php echo lang('usulan_hps'); ?></th>
                <td colspan="3"><?php echo custom_format($pengajuan['usulan_hps']); ?></td>
            </tr>
            <tr>
                <th>TOR</th>
                <td colspan="3"><a href="<?php echo base_url('pengadaan/pengajuan/cetak_tor/'.encode_id([$pengajuan['id'],rand()])); ?>" target="_blank"><?php echo $pengajuan['nomor_tor']; ?></a></td>
            </tr>
            <tr>
                <th><?php echo lang('hps_panitia'); ?></th>
                <td colspan="3"><?php echo custom_format($hps_panitia); ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="card mb-2">
    <div class="card-header"><?php echo lang('_inisiasi_pengadaan'); ?></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-app table-detail table-normal">
            <tr>
                <th width="200"><?php echo lang('nomor_inisiasi'); ?></th>
                <td colspan="3"><?php echo $nomor_inisiasi; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('tanggal_inisiasi'); ?></th>
                <td colspan="3"><?php echo date_indo($tanggal_inisiasi); ?></td>
            </tr>
            <tr>
                <th><?php echo lang('_bidang_usaha'); ?></th>
                <td colspan="3"><?php 
                if($bidang_usaha) {
                    echo '<table>';
                    $i = 1;
                    foreach(json_decode($bidang_usaha,true) AS $v) {
                        echo '<tr>';
                        echo '<td width="20" style="border: 0 none;">'.$i.'</td>';
                        echo '<td width="150" style="border: 0 none;">'.$v['bidang_usaha'].'</td>';
                        echo '<td style="border: 0 none;">'.$v['subbidang_usaha'].'</td>';
                        echo '</tr>';
                        $i++;
                    }
                    echo '</table>';
                }
                ?></td>
            </tr>
            <tr>
                <th><?php echo lang('kualifikasi_penyedia_barang_jasa'); ?></th>
                <td colspan="3"><?php echo $kategori_rekanan; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('metode_pengadaan'); ?></th>
                <td colspan="3"><?php echo $metode_pengadaan; ?></td>
            </tr>
            <?php if($vendor) { ?>
            <tr>
                <th><?php echo lang('rekanan_diundang'); ?></th>
                <td colspan="3"><?php echo $vendor; ?></td>
            </tr>
            <?php } ?>
            <tr>
                <th><?php echo lang('identifikasi_pajak'); ?></th>
                <td colspan="3"><?php echo $identifikasi_pajak; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('keterangan_pengadaan'); ?></th>
                <td colspan="3"><?php echo $keterangan_pengadaan; ?></td>
            </tr>
            <tr>
                <th><?php echo lang('dokumen_pendukung'); ?></th>
                <td colspan="3">
                    <ul class="pl-3 mb-0">
                        <?php
                        foreach(json_decode($file,true) as $k => $v) {
                            echo '<li><a href="'.base_url('assets/uploads/inisiasi_pengadaan/'.$v).'" target="_blank">'.$k.'</a></li>';
                        }
                        ?>
                    </ul>    
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="card mb-2">
    <div class="card-header"><?php echo lang('penjadwalan'); ?></div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-app table-detail table-normal">
            <tr>
                <th><?php echo lang('jadwal'); ?></th>
                <th><?php echo lang('lokasi'); ?></th>
                <th><?php echo lang('tanggal_mulai'); ?></th>
                <th><?php echo lang('tanggal_selesai'); ?></th>
            </tr>
            <?php foreach($detail as $d) { ?>
            <tr>
                <td><?php echo $d['nama_jadwal']; ?></td>
                <td><?php echo $d['lokasi']; ?></td>
                <td><?php echo c_date($d['tanggal_awal']); ?></td>
                <td><?php echo c_date($d['tanggal_akhir']); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
<div class="card mb-2">
    <div class="card-header"><?php echo lang('dokumen_persyaratan'); ?></div>
    <div class="card-body">
        <?php foreach($grup_dokumen as $k => $v) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-detail table-normal">
                <thead>
                    <tr>
                        <th><?php echo strtoupper($v); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dokumen_persyaratan[$k][0] as $d) { ?>
                    <tr>
                        <td><?php echo $d['deskripsi']; ?></td>
                    </tr>
                    <?php foreach($dokumen_persyaratan[$k][$d['id']] as $d2) { ?>
                    <tr>
                        <td class="sub-1"><?php echo $d2['deskripsi']; ?></td>
                    </tr>
                    <?php }} ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
</div>
<div class="card mb-2">
    <div class="card-header"><?php echo lang('pembobotan'); ?></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-app table-detail table-normal mb-2">
                <tr>
                    <th width="200"><?php echo lang('jenis_pengadaan'); ?></th>
                    <td colspan="3"><?php echo $jenis_pengadaan; ?></td>
                </tr>
                <tr>
                    <th><?php echo lang('bobot_harga'); ?></th>
                    <td colspan="3"><?php echo c_percent($bobot_harga); ?>%</td>
                </tr>
                <tr>
                    <th><?php echo lang('bobot_teknis'); ?></th>
                    <td colspan="3"><?php echo c_percent($bobot_teknis); ?>%</td>
                </tr>
            </table>
        </div>
        <div class="mb-2"><strong><?php echo lang('detil_bobot_teknis'); ?></strong></div>
        <div class="table-responsive">
            <?php foreach($pembobotan[0] as $p) { ?>
            <table class="table table-bordered table-detail table-normal">
                <thead>
                    <tr>
                        <th colspan="2"><?php echo $p['deskripsi']; ?></th>
                        <th width="100"><?php echo lang('bobot').' : '.c_percent($p['bobot']); ?></th>
                    </tr>
                    <tr>
                        <?php if($p['tipe_rumus'] == 'range') { ?>
                        <th><?php echo lang('batas_bawah'); ?></th>
                        <th><?php echo lang('batas_atas'); ?></th>
                        <?php } elseif($p['tipe_rumus'] == 'acuan') { ?>
                        <th colspan="2"><?php echo lang('acuan_nilai'); ?></th>
                        <?php } else { ?>
                        <th colspan="2"><?php echo lang('poin_yang_dinilai'); ?></th>
                        <?php } ?>
                        <th><?php echo lang('bobot'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pembobotan[$p['id']] as $p2) { 
                        echo '<tr>';
                        if($p['tipe_rumus'] == 'range') { 
                            echo '<td>'.$p2['batas_bawah'].'</td>';
                            echo '<td>'.$p2['batas_atas'].'</td>';
                        } else {
                            echo '<td colspan="2">'.$p2['deskripsi'].'</td>';
                        }
                        echo '<td>'.c_percent($p2['bobot']).'</td>';
                        echo '</tr>';
                    } ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>
</div>