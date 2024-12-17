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
	table_open('',true,base_url('inisiasi/grup_persetujuan_pengadaan/data'),'tbl_m_penyetuju_pengadaan_header');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('kode_divisi'),'','data-content="kode_divisi"');
				th(lang('divisi'),'','data-content="nama_divisi"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<select id="user-approval" class="hidden">
	<?php foreach($opt_id_user as $u) {
		echo '<option value="'.$u['id'].'" data-jabatan="'.$u['jabatan'].'" data-unit="'.$u['id_unit_kerja'].'">'.$u['nama'].'</option>';
	} ?>
</select>
<?php 
modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
modal_body();
	form_open(base_url('inisiasi/grup_persetujuan_pengadaan/save'),'post','form');
		col_init(2,4);
		input('hidden','id','id');
		input('hidden',lang('unit_kerja'),'id_unit_kerja','unique_group');
		input('text',lang('unit_kerja'),'unit_kerja','','','disabled');
		select2(lang('divisi'),'id_divisi','required|unique_group',$opt_id_divisi,'id','divisi');
		?>
		<div class="form-group row">
			<label class="col-form-label col-md-2 required"><?php echo lang('penyetuju'); ?></label>
			<div class="col-md-4 col-9 mb-1 mb-md-0">
				<select id="username" class="form-control col-md-9 col-xs-9 username select2" name="username[]" data-validation="required" aria-label="<?php echo lang('pengguna'); ?>">
					<option value=""></option>
					<?php foreach($opt_id_user as $u) {
						echo '<option value="'.$u['id'].'" data-jabatan="'.$u['jabatan'].'">'.$u['nama'].'</option>';
					} ?>
				</select>
			</div>
			<div class="col-md-3 col-9 mb-1 mb-md-0">
				<input type="text" name="nama_persetujuan[]" autocomplete="off" class="form-control nama_persetujuan" data-validation="required|max-length:255" placeholder="<?php echo lang('nama_persetujuan'); ?>" aria-label="<?php echo lang('nama_persetujuan'); ?>" id="nama_persetujuan">
			</div>
			<div class="col-md-2 col-9 mb-1 mb-md-0">
				<input type="text" name="limit_persetujuan[]" autocomplete="off" class="form-control limit_persetujuan money" data-validation="required|max-length:25" placeholder="<?php echo lang('batas_atas_persetujuan'); ?>" aria-label="<?php echo lang('batas_atas_persetujuan'); ?>" id="limit_persetujuan">
			</div>
			<div class="col-md-1 col-3 mb-1 mb-md-0">
				<button type="button" class="btn btn-block btn-success btn-icon-only btn-add-anggota"><i class="fa-plus"></i></button>
			</div>
		</div>
		<div id="additional-anggota" class="mb-2"></div>
		<?php 
		form_button(lang('simpan'),lang('batal'));
	form_close();
modal_footer();
modal_close();
?>
<script>
var select_value = '';
function add_row_anggota() {
	konten = '<div class="form-group row">'
			+ '<label class="col-form-label col-md-2"></label>'
			+ '<div class="col-md-4 col-9 mb-1 mb-md-0">'
			+ '<select class="form-control username" name="username[]" data-validation="required" aria-label="'+$('#username').attr('aria-label')+'">'+select_value+'</select> '
			+ '</div>'
			+ '<div class="col-md-3 col-9 mb-1 mb-md-0">'
			+ '<input type="text" name="nama_persetujuan[]" autocomplete="off" class="form-control nama_persetujuan" data-validation="required|max-length:255" placeholder="'+$('#nama_persetujuan').attr('placeholder')+'" aria-label="'+$('#nama_persetujuan').attr('placeholder')+'">'
			+ '</div>'
			+ '<div class="col-md-2 col-9 mb-1 mb-md-0">'
			+ '<input type="text" name="limit_persetujuan[]" autocomplete="off" class="form-control limit_persetujuan money" data-validation="required|max-length:25" placeholder="'+$('#limit_persetujuan').attr('placeholder')+'" aria-label="'+$('#limit_persetujuan').attr('placeholder')+'">'
			+ '</div>'
			+ '<div class="col-md-1 col-3 mb-1 mb-md-0">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>';
	$('#additional-anggota').append(konten);
	$(".money").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});
	var $t = $('#additional-anggota .username:last-child');
	$t.select2({
		dropdownParent : $t.parent()
	});
}
$(document).on('change','.username',function(){
	if($(this).val() != '') {
		var jml = 0;
		var cur_val = $(this).val();
		$('.username').each(function(){
			if( $(this).val() == cur_val) jml++;
		});
		if(jml > 1) {
			$(this).val('').trigger('change');
		} else {
			$(this).closest('.form-group').find('.nama_persetujuan').val($(this).find(':selected').attr('data-jabatan'));
		}
	}
});
$('#filter-unit').change(function(){
	$('#username').html('<option value=""></option>');
	$('#user-approval [data-unit="'+$(this).val()+'"]').each(function(){
		$('#username').append('<option value="'+$(this).attr('value')+'" data-jabatan="'+$(this).attr('data-jabatan')+'">'+$(this).text()+'</option>');
	});
	$('#username').trigger('change');
	$('[data-serverside]').attr('data-serverside',base_url + 'inisiasi/grup_persetujuan_pengadaan/data?id_unit_kerja=' + $(this).val());
	refreshData();
});
$('.btn-add-anggota').click(function(){
	add_row_anggota();
});
$(document).on('click','.btn-remove-anggota',function(){
	$(this).closest('.form-group').remove();
});
$(document).ready(function(){
	select_value = $('#username').html();
	$('#filter-unit').trigger('change');
});
function formOpen() {
	$('#additional-anggota').html('');
	$('#id_unit_kerja').val($('#filter-unit').val());
	$('#unit_kerja').val($('#filter-unit').find(':selected').text());
	var response = response_edit;
	if(typeof response.id != 'undefined') {
		$.each(response.detail,function(e,d){
			if(e == '0') {
				$('#username').val(d.id_user).trigger('change');
				$('#nama_persetujuan').val(d.nama_persetujuan);
				$('#limit_persetujuan').val(numberFormat(d.limit_persetujuan,0,',','.'));
			} else {
				add_row_anggota();
				$('#additional-anggota .username').last().val(d.id_user).trigger('change');
				$('#additional-anggota .nama_persetujuan').last().val(d.nama_persetujuan);
				$('#additional-anggota .limit_persetujuan').last().val(numberFormat(d.limit_persetujuan,0,',','.'));
			}
		});
	}
}
</script>