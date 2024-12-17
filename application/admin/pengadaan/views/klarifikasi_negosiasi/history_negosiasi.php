<table class="table table-bordered table-detail mb-2">
	<tr>
		<th width="200"><?php echo lang('_hps'); ?></th>
		<td><?php echo custom_format($hps); ?></td>
	</tr>
	<tr>
		<th><?php echo lang('penawaran_awal'); ?></th>
		<td><?php echo custom_format($rekanan['nilai_total_penawaran']); ?></td>
	</tr>
</table>
<table class="table table-bordered table-detail">
	<tr>
		<th colspan="2"><?php echo lang('riwayat_negosiasi'); ?></th>
	</tr>
	<?php foreach($negosiasi as $n) { ?>
	<tr>
		<th width="200"><?php echo lang('panitia'); ?>
		<td><a href="<?php echo base_url('pengadaan/klarifikasi_negosiasi/detail_negosiasi?i='.$n['id'].'&t=panitia'); ?>" class="cInfo"><?php echo custom_format($n['penawaran_panitia']); ?></a></td>
	</tr>
	<tr>
		<th><?php echo $rekanan['nama_vendor']; ?>
		<td class="bg-grey"><a href="<?php echo base_url('pengadaan/klarifikasi_negosiasi/detail_negosiasi?i='.$n['id'].'&t=vendor'); ?>" class="cInfo"><?php echo custom_format($n['penawaran_vendor']); ?></a></td>
	</tr>
	<?php } ?>
</table>