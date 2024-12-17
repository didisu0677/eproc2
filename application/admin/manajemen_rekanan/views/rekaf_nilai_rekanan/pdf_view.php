<h1 style="text-align: center">Rekap Nilai Vendor Rekanan</h1>
<table style="margin-bottom: 20px;">
    <tr>
        <th>Kanwil</th>
        <th width="30" style="text-align: right; padding-right: 5px;">:</th>
        <td><?php echo $nm_kanwil ;?></td>
    </tr>
</table>

<table class="table" width="100%" border="1">
    <thead>
        <tr>
            <th>Nama Vendor</th>
            <th>Nama Pengadaan</th>
            <th>Nilai Kontrak</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <?php foreach($result as $k => $v) { ?>
    <tr>
        <th colspan="11" style="background: #f9f9f9;"><?php echo $k; ?></th>
    </tr>
    <?php foreach($v as $vv) { ?>
    <tr>
        <td><?php echo $vv['nama']; ?></td>
        <td><?php echo $vv['nama_pengadaan']; ?></td>
        <td><?php echo $vv['nilai_kontrak']; ?></td>
        <td><?php echo $vv['keterangan_lain']; ?></td>
    </tr>
    <?php } ?>
    <?php } ?>
</table>