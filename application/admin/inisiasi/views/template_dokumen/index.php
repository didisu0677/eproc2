<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<div class="main-container">
		<div class="alert alert-info">
			<ul class="pl-3 mb-0">
				<li><?php echo lang('info_mandatori_kelengkapan_dokumen1'); ?></li>
				<li><?php echo lang('info_mandatori_kelengkapan_dokumen2'); ?></li>
			</ul>
		</div>
		<form id="form-command" action="<?php echo base_url('inisiasi/template_dokumen/save'); ?>" data-callback="reload" method="post" data-submit="ajax">
			<?php foreach($grup_dokumen as $grup => $label) { ?>
			<div class="table-responsive">
				<table class="table table-bordered table-detail">
					<thead>
						<tr>
							<th colspan="2"><?php echo strtoupper($label); ?></th>
							<th width="100">
								<div class="custom-checkbox custom-control custom-control-inline">
									<input class="custom-control-input" type="checkbox" id="mandatori_<?php echo $grup; ?>" name="mandatori[<?php echo $grup; ?>]" value="1"<?php if($mandatori[$grup]) echo ' checked'; ?>>
									<label class="custom-control-label" for="mandatori_<?php echo $grup; ?>"><?php echo lang('mandatori'); ?></label>
								</div>
							</th>
							<th width="55">
								<button type="button" class="btn btn-success btn-icon-only btn-sm btn-add" data-grup="<?php echo $grup; ?>"><i class="fa-plus"></i></button>
							</th>
							<th width="55">&nbsp;</th>
						</tr>
					</thead>
					<tbody id="grup-<?php echo $grup; ?>">
						<?php foreach($deskripsi[$grup][0] as $v) { ?>
						<tr data-idx="<?php echo $v['id']; ?>">
							<td colspan="3">
								<input type="text" name="deskripsi[<?php echo $grup; ?>][0][<?php echo $v['id']; ?>]" class="form-control" autocomplete="off" data-validation="required|max-length:150" value="<?php echo $v['deskripsi']; ?>">
							</td>
							<td><button type="button" class="btn btn-success btn-icon-only btn-sm btn-add add-sub" data-idx="<?php echo $v['id']; ?>" data-grup="<?php echo $grup; ?>"><i class="fa-plus"></i></button></td>
							<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove" data-idx="<?php echo $v['id']; ?>"><i class="fa-times"></i></button></td>
						</tr>
						<?php foreach($deskripsi[$grup][$v['id']] as $v2) { ?>
						<tr data-idx="<?php echo $v2['id']; ?>" data-parent="<?php echo $v['id']; ?>">
							<td width="30">&nbsp;</td>
							<td colspan="2">
								<input type="text" name="deskripsi[<?php echo $grup; ?>][<?php echo $v['id']; ?>][<?php echo $v2['id']; ?>]" class="form-control" autocomplete="off" data-validation="required|max-length:150" value="<?php echo $v2['deskripsi']; ?>">
							</td>
							<td>&nbsp;</td>
							<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove" data-idx="<?php echo $v2['id']; ?>"><i class="fa-times"></i></button></td>
						</tr>
						<?php }} ?>
					</tbody>
				</table>
			</div>
			<?php } ?>
			<button type="submit" class="btn btn-info"><?php echo lang('simpan'); ?></button>
		</form>
	</div>
</div>
<script type="text/javascript">
var idx = 999;
$(document).on('click','.btn-add',function(){
	var konten = '';
	if($(this).hasClass('add-sub')) {
		konten += '<tr data-idx="'+idx+'" data-parent="'+$(this).attr('data-idx')+'">';
		konten += '<td width="30">&nbsp;</td>';
		konten += '<td colspan="2"><input type="text" name="deskripsi['+$(this).attr('data-grup')+']['+$(this).attr('data-idx')+']['+idx+']" class="form-control" autocomplete="off" data-validation="required|max-length:150"></td>';
		konten += '<td>&nbsp;</td>';
		konten += '<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove" data-idx="'+idx+'"><i class="fa-times"></i></button></td>';
	} else {
		konten += '<tr data-idx="'+idx+'">';
		konten += '<td colspan="3"><input type="text" name="deskripsi['+$(this).attr('data-grup')+'][0]['+idx+']" class="form-control" autocomplete="off" data-validation="required|max-length:150"></td>';
		konten += '<td><button type="button" class="btn btn-success btn-icon-only btn-sm btn-add add-sub" data-idx="'+idx+'" data-grup="'+$(this).attr('data-grup')+'"><i class="fa-plus"></i></button></td>';
		konten += '<td><button type="button" class="btn btn-danger btn-icon-only btn-sm btn-remove" data-idx="'+idx+'"><i class="fa-times"></i></button></td>';
	}
	konten += '</tr>';
	$('#grup-'+$(this).attr('data-grup')).append(konten);
	idx++;
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
	$('tr[data-parent="'+$(this).attr('data-idx')+'"]').remove();
});
</script>