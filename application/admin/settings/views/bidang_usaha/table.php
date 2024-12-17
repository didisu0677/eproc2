<?php foreach($bidang_usaha[0] as $m0) { ?>
	<tr>
		<td><?php echo $m0->kode; ?></td>
		<td><?php echo $m0->bidang_usaha; ?></td>
		<td class="button">
			<?php if($access_edit) { ?>
			<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m0->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
			<?php } if($access_delete) { ?>
			<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m0->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
			<?php } ?>
		</td>
	</tr>
	<?php foreach($bidang_usaha[$m0->id] as $m1) { ?>
		<tr>
			<td class="sub-1"><?php echo $m1->kode; ?></td>
			<td><?php echo $m1->bidang_usaha; ?></td>
			<td class="button">
				<?php if($access_edit) { ?>
				<button type="button" class="btn btn-warning btn-input" data-key="edit" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('ubah'); ?>"><i class="fa-edit"></i></button>
				<?php } if($access_delete) { ?>
				<button type="button" class="btn btn-danger btn-delete" data-key="delete" data-id="<?php echo $m1->id; ?>" title="<?php echo lang('hapus'); ?>"><i class="fa-trash-alt"></i></button>
				<?php } ?>
			</td>
		</tr>
	<?php } ?>
<?php } ?>