<img src="<?php echo dir_upload('setting').setting('logo_perusahaan'); ?>" width="150" style="margin-bottom: 20px;" />
<table style="margin-bottom: 15px">
    <tr>
        <th>Nomor Pengadaan</th>
        <td width="30" style="text-align: center">:</td>
        <td><?php echo $nomor_pengadaan; ?></td>
    </tr>
    <tr>
        <th>Nama Pengadaan</th>
        <td style="text-align: center">:</td>
        <td><?php echo $nama_pengadaan; ?></td>
    </tr>
    <tr>
        <th>Tanggal Pengadaan</th>
        <td style="text-align: center">:</td>
        <td><?php echo date_indo($tanggal_pengadaan); ?></td>
    </tr>
    <tr>
        <th>Unit Kerja</th>
        <td style="text-align: center">:</td>
        <td><?php echo $unit_kerja; ?></td>
    </tr>
    <tr>
        <th colspan="3">&nbsp;</th>
    </tr>
    <tr>
        <th colspan="3" style="padding-bottom: 6px;"><span style="text-decoration: underline;">Informasi Vendor</span></th>
    </tr>
    <tr>
        <th>Nama</th>
        <td style="text-align: center">:</td>
        <td><?php echo $nama_vendor; ?></td>
    </tr>
    <tr>
        <th>Alamat</th>
        <td style="text-align: center">:</td>
        <td><?php echo $alamat_vendor; ?></td>
    </tr>
    <tr>
        <th>NPWP</th>
        <td style="text-align: center">:</td>
        <td><?php echo $npwp_vendor; ?></td>
    </tr>
    <tr>
        <th colspan="3">&nbsp;</th>
    </tr>
    <tr>
        <th colspan="3"><span style="text-decoration: underline;">Detil Pembelian</span></th>
    </tr>
</table>
<table border="1" width="100%" style="margin-bottom: 10px;">
    <thead>
        <tr>
            <th width="20">No.</th>
            <th>Deskripsi</th>
            <th width="50" style="text-align: center;">Satuan</th>
            <th width="70" style="text-align: center;">Harga</th>
            <th width="50" style="text-align: center;">Jumlah</th>
            <th width="70" style="text-align: center;">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($detail as $k => $d) { ?>
        <tr>
            <td style="text-align: center;"><?php echo $k + 1; ?>.</td>
            <td><?php echo $d['deskripsi']; ?></td>
            <td style="text-align: center;"><?php echo $d['satuan']; ?></td>
            <td style="text-align: right;"><?php echo custom_format($d['harga']); ?></td>
            <td style="text-align: center;"><?php echo $d['jumlah']; ?></td>
            <td style="text-align: right;"><?php echo custom_format($d['total']); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<table border="1" width="100%">
    <tr>
        <td style="border-right: 0 none; border-bottom: 0 none;"><strong>TOTAL</strong></td>
        <td width="70" style="text-align: right; border-left: 0 none; border-bottom: 0 none;"><strong><?php echo custom_format($total_pengadaan); ?></strong></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: right; border-top: 0 none;">(<?php echo terbilang($total_pengadaan); ?>)</td>
    </tr>
</table>