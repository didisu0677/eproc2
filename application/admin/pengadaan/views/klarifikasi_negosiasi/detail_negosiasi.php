<div class="table-responsive">
	<table class="table table-bordered table-app table-items">
		<thead>
			<tr>
				<th><?php echo lang('deskripsi'); ?></th>
				<th class="text-right"><?php echo lang('jumlah'); ?></th>
				<th><?php echo lang('satuan'); ?></th>
				<th class="text-right"><?php echo lang('harga_satuan'); ?></th>
				<th class="text-right"><?php echo lang('total'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $total = 0; foreach($detail as $h) { $total += ($h['quantity'] * $h['pu']); ?>
			<tr>
				<td><?php echo $h['short_text']; ?></td>
				<td class="text-right"><?php echo custom_format($h['quantity']); ?></td>
				<td><?php echo $h['unit_of_measure']; ?></td>
				<td class="text-right"><?php echo custom_format($h['pu']); ?></td>
				<td class="text-right"><?php echo custom_format($h['quantity'] * $h['pu']); ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="4"><?php echo strtoupper(lang('total_harga')); ?></th>
				<th class="text-right"><?php echo custom_format($total); ?></th>
			</tr>
		</tfoot>
	</table>
</div>