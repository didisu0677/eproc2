<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<?php if($menu_access['access_input']) { ?>
		<div class="float-right">
			<button type="button" class="btn btn-primary btn-sm btn-backup" data-id="0"><i class="fa-save"></i><?php echo lang('backup'); ?></button>
		</div>
		<?php } ?>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true);
		thead();
			tr();
				th(lang('backup'));
				th('&nbsp;','','width="30"');
		tbody();
			if(count($backup) > 0) {
				foreach($backup as $b) {
					$button = '';
					if($menu_access['access_additional']) $button .= '<a href="'.base_url('settings/backup/download?b='.$b).'" title="'.lang('unduh').'" class="btn btn-info"><i class="fa-download"></i></a> ';
					if($menu_access['access_delete']) $button .= '<button type="button" class="btn btn-danger btn-delete-backup" data-key="delete" data-id="'.$b.'" title="'.lang('hapus').'"><i class="fa-trash-alt"></i></button>';
					tr();
						td($b);
						td($button,'button');
				}
			} else {
				tr();
					td(lang('tidak_ada_data'),'','colspan="2"');
			}
	table_close();
	?>
</div>
<?php 
	modal_open('modal-backup',lang('backup'));
		modal_body();
			form_open(base_url('settings/backup/proccess'),'post','form','data-callback="reload"');
				col_init(0,12);
				if(user('id_group') == 1) {
					echo '<div class="alert alert-light"><strong>cron:</strong> <code>{base_url}/cron/backup</code></div>';
				}
				input('hidden','x','x','','x');
				echo '<div class="custom-control custom-checkbox mb-3">
					<input type="checkbox" class="custom-control-input" id="all" name="example1" checked>
					<label class="custom-control-label" for="all"><strong>'.lang('semua').'</strong></label>
				</div>';
				echo '<div id="chk-backup" class="row mb-3">';
				foreach($table as $k => $t) {
					echo '<div class="col-sm-6 mb-1">
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input" id="chk-'.$k.'" name="table[]" value="'.$t.'" checked>
							<label class="custom-control-label" for="chk-'.$k.'">'.$t.'</label>
						</div>
					</div>';
				}
				echo '</div>';
				form_button(lang('backup'),lang('batal'));
			form_close();

	modal_close();
?>
<script>
var del_backup;
$('.btn-backup').click(function(e){
	e.preventDefault();
	$('#modal-backup form')[0].reset();
	$('#modal-backup').modal();
});
$(document).on('click','.btn-delete-backup',function(e){
	e.preventDefault();
	del_backup = $(this).attr('data-id');
	cConfirm.open(lang.anda_yakin_menghapus_data_ini+'?','deleteBackup');
});
function deleteBackup(){
	$.ajax({
		url : base_url + 'settings/backup/delete',
		data : {backup: del_backup},
		type : 'post',
		dataType : 'json',
		success : function(response) {
			if(response.status == 'success') {
				cAlert.open(response.message,response.status,'reload');
			} else {
				cAlert.open(response.message,response.status);
			}
		}
	});
}
$('#all').click(function(){
	if($(this).is(':checked')) {
		$('#chk-backup .custom-control-input').prop('checked',true);
	} else {
		$('#chk-backup .custom-control-input').prop('checked',false);
	}
});
$(document).on('click','#chk-backup .custom-control-input', function(){
	if($('#chk-backup .custom-control-input:checked').length == 0) {
		$('#all').prop('indeterminate',false);
		$('#all').prop('checked',false);
	} else if($('#chk-backup .custom-control-input:checked').length == $('#chk-backup .custom-control-input').length) {
		$('#all').prop('indeterminate',false);
		$('#all').prop('checked',true);
	} else {
		$('#all').prop('checked',false);
		$('#all').prop('indeterminate',true);
	}
});

</script>