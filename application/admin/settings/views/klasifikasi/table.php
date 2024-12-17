<?php foreach($klasifikasi[0] as $m0) { ?>
	<tr>
		<td><?php echo $m0->kode; ?></td>
		<td><?php echo $m0->klasifikasi; ?></td>
		<td class="text-center"><?php echo $m0->pilihan ? '<span class="text-success"><i class="fa-check"></i></span>' : '<span class="text-danger"><i class="fa-times"></i></span>' ; ?></td>
		<td class="button">
			<?php if($access_edit) { ?>
			<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m0->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
			<?php } if($access_delete) { ?>
			<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m0->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
			<?php } ?>
		</td>
	</tr>
	<?php foreach($klasifikasi[$m0->id] as $m1) { ?>
		<tr>
			<td class="sub-1"><?php echo $m1->kode; ?></td>
			<td><?php echo $m1->klasifikasi; ?></td>
			<td class="text-center"><?php echo $m1->pilihan ? '<span class="text-success"><i class="fa-check"></i></span>' : '<span class="text-danger"><i class="fa-times"></i></span>' ; ?></td>
			<td class="button">
				<?php if($access_edit) { ?>
				<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
				<?php } if($access_delete) { ?>
				<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
				<?php } ?>
			</td>
		</tr>
		<?php foreach($klasifikasi[$m1->id] as $m2) { ?>
			<tr>
				<td class="sub-2"><?php echo $m2->kode; ?></td>
				<td><?php echo $m2->klasifikasi; ?></td>
				<td class="text-center"><?php echo $m2->pilihan ? '<span class="text-success"><i class="fa-check"></i></span>' : '<span class="text-danger"><i class="fa-times"></i></span>' ; ?></td>
				<td class="button">
					<?php if($access_edit) { ?>
					<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m2->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
					<?php } if($access_delete) { ?>
					<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m2->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	<?php } ?>
<?php } ?>