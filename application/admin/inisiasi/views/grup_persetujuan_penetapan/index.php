<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<select class="select2 infinity custom-select" id="filter-unit">
				<?php echo select_option($unit_kerja,'id','unit', count($unit_kerja) > 0 ? $unit_kerja[0]['id'] : ''); ?>
			</select>
			<?php echo access_button('delete'); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('inisiasi/grup_persetujuan_penetapan/data'),'tbl_m_penyetuju_penetapan');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('pengguna'),'','data-content="nama_lengkap"');
				th(lang('nama_persetujuan'),'','data-content="nama_persetujuan"');
				th(lang('batas_atas_persetujuan'),'text-right','data-content="limit_persetujuan" data-type="currency"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','','data-openCallback="openForm"');
	modal_body();
		form_open(base_url('inisiasi/grup_persetujuan_penetapan/save'),'post','form');
			col_init(4,8);
			input('hidden','id','id');
			input('hidden',lang('unit_kerja'),'id_unit_kerja','unique_group');
			input('text',lang('unit_kerja'),'unit_kerja','','','disabled');
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-4 required" for="id_user"><?php echo lang('pengguna'); ?></label>
				<div class="col-sm-8">
					<select name="id_user" id="id_user" class="form-control select2" data-validation="required|unique_group">
						<option value=""></option>
						<?php foreach($opt_id_user as $u) {
							echo '<option value="'.$u['id'].'" data-jabatan="'.$u['jabatan'].'">'.$u['nama'].'</option>';
						} ?>
					</select>
				</div>
			</div>
			<?php
			input('text',lang('nama_persetujuan'),'nama_persetujuan','required|max-length:255');
			input('money',lang('batas_persetujuan'),'limit_persetujuan','required|max-length:25');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
?>
<script>
$('#id_user').change(function(){
	$('#nama_persetujuan').val($(this).find(':selected').attr('data-jabatan'));
});
$('#filter-unit').change(function(){
	$('[data-serverside]').attr('data-serverside',base_url + 'inisiasi/grup_persetujuan_penetapan/data?id_unit_kerja=' + $(this).val());
	refreshData();
});
$(document).ready(function(){
	$('#filter-unit').trigger('change');
})
function openForm() {
	$('#id_unit_kerja').val($('#filter-unit').val());
	$('#unit_kerja').val($('#filter-unit').find(':selected').text());
	if(typeof response_edit.id != 'undefined') {
		$('#nama_persetujuan').val(response_edit.nama_persetujuan);
	}
}
</script>