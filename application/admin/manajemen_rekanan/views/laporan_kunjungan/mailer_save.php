<p style="text-align: justify;"><?php echo $nama; ?>, Berkaitan dengan Tim PT. Pegadaian (Persero) yang sudah melakukan kunjungan, tim verifikasi kami memutuskan <strong>"<?php echo $description; ?>"</strong>.</p>
<?php if($status == 9) { ?>
<p style="text-align: justify;">Anda bisa mengajukan kunjungan ulang.</p>
<?php } ?>
<div style="text-align:center; padding: 10px;">
	<a href="<?php echo $url; ?>" style="background: #16D39A; color: #fff; padding: .5rem 1rem; border-radius: .175rem; text-decoration: none;">Ajukan Kunjungan Ulang</a>
</div>