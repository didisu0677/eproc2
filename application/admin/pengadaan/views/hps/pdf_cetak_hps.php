<h3 style="text-align: center; font-size: 14px; margin-bottom: 0; padding-bottom: 0;">HARGA PERKIRAAN SENDIRI/OWNER ESTIMATE (OE)</h3>
<h3 style="text-align: center; font-size: 12px; margin-bottom: 20px; margin-top: 0;">Nomor : <?php echo $nomor_hps; ?></h3>
<table style="margin-bottom: 15px">
    <tr>
        <th width="100">Nomor Pengajuan</th>
        <td width="30" style="text-align: center">:</td>
        <td><?php echo $nomor_pengajuan; ?></td>
    </tr>
    <tr>
        <th>Deskripsi</th>
        <td width="30" style="text-align: center">:</td>
        <td><?php echo $deskripsi; ?></td>
    </tr>
    <tr>
        <th>Unit Kerja</th>
        <td width="30" style="text-align: center">:</td>
        <td><?php echo $unit_kerja; ?></td>
    </tr>
    <tr>
        <th>Divisi</th>
        <td width="30" style="text-align: center">:</td>
        <td><?php echo $nama_divisi; ?></td>
    </tr>
</table>
<table width="100%" border="1" style="margin-bottom: 10px;">
    <thead>
        <tr>
            <th width="10">No.</th>
            <th width="60">Kode Material</th>
            <th>Deskripsi</th>
            <th width="40" style="text-align: right;">Jumlah</th>
            <th width="40">Satuan</th>
            <th width="70" style="text-align: right;">Harga Satuan</th>
            <th width="70" style="text-align: right;">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($detail as $k => $d) { ?>
        <tr>
            <td><?php echo $k + 1; ?></td>
            <td><?php echo $d['material_number']; ?></td>
            <td><?php echo $d['short_text']; ?></td>
            <td style="text-align: right;"><?php echo custom_format($d['quantity']); ?></td>
            <td><?php echo $d['unit_of_measure']; ?></td>
            <td style="text-align: right;"><?php echo custom_format($d['price_unit']); ?></td>
            <td style="text-align: right;"><?php echo custom_format($d['total_value']); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<table width="100%" border="1">
    <tr>
        <th style="text-align: left; border-right: 0 none; border-bottom: 0 none;">TOTAL</th>
        <th width="70" style="text-align: right; border-left: 0px none; border-bottom: 0 none; font-size: 16px;"><?php echo custom_format($total_hps_pembulatan); ?></th>
    </tr>
    <tr>
        <td colspan="2" style="border-top: 0 none; text-align: right; font-style: italic; font-weight: bold;"><?php echo terbilang($total_hps_pembulatan); ?></td>
    </tr>
</table>