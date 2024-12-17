<table class="table table-bordered table-detail">
	<?php foreach($file as $k => $f) { ?>
	<tr>
		<td><?php echo $k; ?></td>
		<td width="50"><a href="<?php echo base_url(dir_upload('pengajuan').$f); ?>" target="_blank" class="btn btn-sm btn-info btn-icon only"><i class="fa-download"></i></a>
	</tr>
	<?php } ?>
</table>