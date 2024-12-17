<p style="text-align: justify;"><?php echo $nama; ?>, Setelah dilakukan proses verifikasi oleh Tim PT. Pegadaian (Persero), tim verifikasi kami memutuskan <strong>"<?php echo $description; ?>"</strong>.</p>
<?php if($status == 9) { ?>
<p style="text-align: justify;">Anda bisa melengkapi persyaratan dan mengajukan verifikasi ulang.</p>
<div style="text-align:center; padding: 10px;">
	<a href="<?php echo $url; ?>" style="background: #16D39A; color: #fff; padding: .5rem 1rem; border-radius: .175rem; text-decoration: none;">Ajukan Verifikasi Ulang</a>
</div>
<?php } else { ?>
<p style="text-align: justify;">Selamat anda sudah resmi menjadi rekanan PT. Pegadaian (Persero) dan dapat mengikuti proses pengadaan yang dilakukan oleh PT. Pegadaian (Persero)</p>
<?php } ?>