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
		<form id="form-command" action="<?php echo base_url('manajemen_rekanan/template_kunjungan/save'); ?>" data-callback="reload" method="post" data-submit="ajax">
			<div class="table-responsive mb-2">
				<table class="table table-bordered table-detail table-app">
					<thead>
						<tr>
							<th><?php echo lang('pertanyaan_wawancara'); ?></th>
							<th width="10">
								<button type="button" class="btn btn-sm btn-icon-only btn-success btn-add-aspek"><i class="fa-plus"></i></button>
							</th>
						</tr>
					</thead>
					<tbody id="d1">
						<?php foreach($template1 as $t) {
							echo '<tr>';
							echo '<td><input type="text" class="form-control" autocomplete="off" name="deskripsi1[]" value="'.$t['deskripsi'].'" data-validation="required" /></td>';
							echo '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';
							echo '</tr>';
						} ?>
					</tbody>
				</table>
			</div>
			<div class="table-responsive mb-2">
				<table class="table table-bordered table-detail table-app">
					<thead>
						<tr>
							<th colspan="4"><?php echo lang('data_pendukung'); ?></th>
						</tr>
						<tr>
							<th width="300"><?php echo lang('dokumen_pendukung'); ?></th>
							<th width="40"><?php echo lang('nomor_dokumen'); ?></th>
							<th><?php echo lang('pilihan'); ?></th>
							<th width="10">
								<button type="button" class="btn btn-sm btn-icon-only btn-success btn-add"><i class="fa-plus"></i></button>
							</th>
						</tr>
					</thead>
					<tbody id="d2">
						<?php foreach($template as $t) {
							echo '<tr>';
							echo '<td><input type="text" class="form-control" autocomplete="off" name="deskripsi['.$t['id'].']" value="'.$t['deskripsi'].'" data-validation="required" /></td>';
							echo '<td class="text-center">';
							echo '<div class="custom-checkbox custom-control">';
							$hidden = '';
							if($t['nomor']) {
								$hidden = ' hidden';
								echo '<input class="custom-control-input chk" type="checkbox" id="chk-th-'.$t['id'].'" name="nomor['.$t['id'].']" value="1" checked>';
							} else {
								echo '<input class="custom-control-input chk" type="checkbox" id="chk-th-'.$t['id'].'" name="nomor['.$t['id'].']" value="1">';
							}
							echo '<label class="custom-control-label" for="chk-th-'.$t['id'].'">&nbsp;</label></div>';
							echo '</td>';
							$pilihan = $t['pilihan'] ? implode(',', json_decode($t['pilihan'],true)) : '';
							echo '<td><div class="pilihan'.$hidden.'"><input type="text" class="form-control tags" autocomplete="off" name="pilihan['.$t['id'].']" value="'.$pilihan.'" /></div></td>';
							echo '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>';
							echo '</tr>';
						} ?>
					</tbody>
				</table>
			</div>
			<button type="submit" class="btn btn-info"><?php echo lang('simpan'); ?></button>
		</form>
	</div>
</div>
<script type="text/javascript">
var idx = 999;
$(document).on('click','.btn-add-aspek',function(){
	var konten = '<tr>'
		+ '<td><input type="text" class="form-control" autocomplete="off" name="deskripsi1[]" data-validation="required" /></td>'
		+ '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>'
	+ '</tr>';
	$('#d1').append(konten);
});
$(document).on('click','.btn-add',function(){
	var konten = '<tr>'
		+ '<td><input type="text" class="form-control" autocomplete="off" name="deskripsi['+idx+']" data-validation="required" /></td>'
		+ '<td class="text-center">'
			+ '<div class="custom-checkbox custom-control"><input class="custom-control-input chk" type="checkbox" id="chk-th-'+idx+'" name="nomor['+idx+']" value="1"><label class="custom-control-label" for="chk-th-'+idx+'">&nbsp;</label></div>'
		+ '</td>'
		+ '<td><div class="pilihan"><input type="text" class="form-control tags" autocomplete="off" name="pilihan['+idx+']" /></div></td>'
		+ '<td><button type="button" class="btn btn-sm btn-icon-only btn-danger btn-remove"><i class="fa-times"></i></button></td>'
	+ '</tr>';
	$('#d2').append(konten);
	$('.tags').tagsinput();
	idx++;
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
});
$(document).on('click','.chk',function(){
	if($(this).is(':checked')) {
		$(this).closest('tr').find('.pilihan').addClass('hidden');
	} else {
		$(this).closest('tr').find('.pilihan').removeClass('hidden');		
	}
});
</script>