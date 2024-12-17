<?php foreach($pasal as $p) { ?>
<div style="font-weight: bold; text-align: center;"><?php echo $p['pasal']; ?></div>
<div style="font-weight: bold; text-align: center; margin-bottom: 10px;"><?php echo $p['judul_pasal']; ?></div>
<p style="text-align: justify;"><?php echo $p['isi_pasal']; ?></p>
<?php } ?>