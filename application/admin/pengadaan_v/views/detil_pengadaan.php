<div class="table-responsive">
	<table class="table table-bordered table-app table-hover">
		<thead>
			<tr>
				<th width="10"><?php echo lang('no'); ?></th>
				<th><?php echo lang('deskripsi'); ?></th>
				<th><?php echo lang('satuan'); ?></th>
				<th class="text-right"><?php echo lang('jumlah'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($detail as $k => $d) { ?>
			<tr>
				<td><?php echo $k + 1; ?></td>
				<td><?php echo $d['short_text']; ?></td>
				<td class="text-right"><?php echo custom_format($d['quantity']); ?></td>
				<td><?php echo $d['unit_of_measure']; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>