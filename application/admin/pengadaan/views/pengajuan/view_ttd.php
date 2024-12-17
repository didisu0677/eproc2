<table border="1" width="100%">
    <tr>
        <th style="background: #f7f7f7;"><?php echo 'Keterangan'; ?></th>
        <th style="background: #f7f7f7;"><?php echo 'Nama'; ?></th>
        <th style="background: #f7f7f7;"><?php echo 'Jabatan'; ?></th>
    </tr>
    <tr>
        <td style="border-top: 0 none; border-bottom: 0 none;"><?php echo 'Dibuat Oleh'; ?></td>
        <td style="border-top: 0 none; border-bottom: 0 none;"><?php echo $tor['create_by']; ?></td>
        <td style="border-top: 0 none; border-bottom: 0 none;"><?php echo $tor['jabatan_creator']; ?></td>
    </tr>
    <?php if($tor['update_by'] && $tor['create_by'] != $tor['update_by']) { ?>
    <tr>
        <td style="border-top: 0 none; border-bottom: 0 none;"><?php echo 'Perbaharuan Terakhir Oleh'; ?></td>
        <td style="border-top: 0 none; border-bottom: 0 none;"><?php echo $tor['update_by']; ?></td>
        <td style="border-top: 0 none; border-bottom: 0 none;"><?php echo $tor['jabatan_modifier']; ?></td>
    </tr>
    <?php } if($tor['status'] == 1) { ?>
    <?php foreach($ttd as $k => $o){ ?>
    <tr>
        <td style="border-top: 0 none; border-bottom: 0 none;"><?php echo 'Disetujui Oleh'; ?></td>
        <td style="border-top: 0 none; border-bottom: 0 none;"><?php echo $o->nama_user; ?></td>
        <td style="border-top: 0 none; border-bottom: 0 none;"><?php echo $o->nama_persetujuan; ?></td>
    </tr>
    <?php }} ?>
</table>