<h1 style="text-align: center">Laporan Daftar Rekanan Mampu (DRM)</h1>
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
            <th>Alamat</th>
            <th>Jenis</th>
            <th>Kategori</th>
        </tr>
    </thead>
    <?php foreach($result as $k => $v) { ?>
    <tr>
        <th colspan="11" style="background: #f9f9f9;"><?php echo $k; ?></th>
    </tr>
    <?php foreach($v as $vv) { ?>
    <tr>
        <td><?php echo $vv['nama']; ?></td>
        <td><?php echo $vv['alamat']; ?></td>
        <td><?php echo $vv['jenis']; ?></td>
        <td><?php echo $vv['kategori']; ?></td>
    </tr>
    <?php } ?>
    <?php } ?>
</table>