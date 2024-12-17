<?php if(!$rm) { ?>
<table width="100%" class="mb-3">
	<tr>
		<th class="text-left" width="170"><?php echo lang('nomor_pengajuan_pembelian'); ?></th>
		<th class="text-center" width="20">:</th>
		<td><?php echo $purchase_req_item; ?></td>
	</tr>
	<tr>
		<th class="text-left"><?php echo lang('nama_pengadaan'); ?></th>
		<th class="text-center">:</th>
		<td><?php echo $nama_pengadaan; ?></td>
	</tr>
</table>
<?php } ?>
<div class="table-responsive">
	<table class="table table-bordered table-app">
		<thead>
			<tr>
				<?php if($rm) { ?>
				<th>&nbsp;</th>
				<?php } else { ?>
				<th><?php echo lang('no'); ?></th>
				<?php } ?>
				<th><?php echo lang('kode_material'); ?></th>
				<th><?php echo lang('deskripsi'); ?></th>
				<th class="text-right"><?php echo lang('jumlah'); ?></th>
				<th><?php echo lang('satuan'); ?></th>
				<th class="text-right"><?php echo lang('harga_satuan'); ?></th>
				<th class="text-right"><?php echo lang('total'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $total = 0; foreach($detail as $k => $d) { if(!$d['is_deleted']) $total += $d['total_value']; ?>
			<tr<?php if($d['is_deleted']) echo ' class="grey-row"'; ?>>
				<?php if($rm) { ?>
				<td><button data-id="<?php echo $d['id']; ?>" type="button" class="btn btn-sm btn-icon-only <?php if($d['is_deleted']) echo 'btn-success btn-restore'; else echo 'btn-danger btn-remove'; ?>"><i class="<?php echo $d['is_deleted'] ? 'fa-sync' : 'fa-trash'; ?>"></i></button>
				<?php } else { ?>
				<td><?php echo $k + 1; ?></td>
				<?php } ?>
				<td><?php echo $d['material_number']; ?></td>
				<td><?php echo $d['short_text']; ?></td>
				<td class="text-right"><?php echo custom_format($d['quantity']); ?></td>
				<td><?php echo $d['unit_of_measure']; ?></td>
				<td class="text-right"><?php echo custom_format($d['price_unit']); ?></td>
				<td class="text-right"><strong><?php echo custom_format($d['total_value']); ?></strong></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="6" class="text-right"><?php echo strtoupper(lang('total')); ?></th>
				<th class="text-right" id="total-hps"><?php echo custom_format($total); ?></th>
			</tr>
		</tfoot>
	</table>
</div>