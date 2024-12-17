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
			<?php echo access_button(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('inisiasi/panitia_pengadaan/data'),'tbl_m_panitia_pengadaan');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('id_panitia'),'','data-content="id_panitia"');
				th(lang('tanggal'),'','data-content="tanggal" data-type="daterange"');
				th(lang('deskripsi'),'','data-content="deskripsi"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<select id="users" class="hidden">
	<?php foreach($user as $u) {
		echo '<option value="'.$u['username'].'" data-jabatan="'.$u['jabatan'].'" data-unit="'.$u['id_unit_kerja'].'">'.$u['nama'].'</option>';
	} ?>
</select>
<?php 
modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('inisiasi/panitia_pengadaan/save'),'post','form');
			col_init(2,6);
			input('hidden','id','id');
			input('hidden','id_unit_kerja','id_unit_kerja');
			input('text',lang('id_panitia'),'id_panitia','required|unique');
			input('text',lang('unit_kerja'),'unit_kerja','','','disabled');
			input('text',lang('deskripsi'),'deskripsi','required');
			input('date',lang('tanggal'),'tanggal','required');
			?>			
			<div class="form-group row">
				<label class="col-form-label col-md-2 required"><?php echo lang('anggota'); ?></label>
                <div class="col-md-3 col-9 mb-1 mb-md-0">
					<input type="hidden" name="nama_panitia[]" class="nama_panitia">
                    <select id="username" class="form-control username select2" name="username[]" data-validation="required" aria-label="<?php echo lang('nama_panitia'); ?>" placeholder="<?php echo lang('nama_panitia'); ?>">
                    </select>
                </div>
				<div class="col-md-3 col-9 mb-1 mb-md-0">
					<input type="text" name="jabatan[]" autocomplete="off" class="form-control jabatan" placeholder="<?php echo lang('jabatan'); ?>" aria-label="<?php echo lang('jabatan'); ?>" id="jabatan" data-validation="required">
				</div>
				<div class="col-md-3 col-9 mb-1 mb-md-0">
					<input type="text" name="posisi_panitia[]" autocomplete="off" class="form-control posisi_panitia" placeholder="<?php echo lang('posisi_panitia'); ?>" id="posisi_panitia" aria-label="<?php echo lang('posisi_panitia'); ?>" data-validation="required">
				</div>				
				<div class="col-sm-1 col-3">
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
	var index = rand();
	konten = '<div class="form-group row">'
			+ '<label class="col-form-label col-md-2 mb-0"></label>'
			+ '<div class="col-md-3 mb-1 mb-md-0 col-9">'
			+ '<input type="hidden" name="nama_panitia[]" class="nama_panitia">'
			+ '<select class="form-control username" name="username[]" data-validation="required" aria-label="'+$('#username').attr('aria-label')+'">'+select_value+'</select> '
			+ '</div>'
			+ '<div class="col-md-3 mb-1 mb-md-0 col-9">'
			+ '<input type="text" name="jabatan[]" autocomplete="off" class="form-control jabatan"  placeholder="'+$('#jabatan').attr('placeholder')+'" aria-label="'+$('#jabatan').attr('placeholder')+'" data-validation="required">'
			+ '</div>'
			+ '<div class="col-md-3 mb-1 mb-md-0 col-9">'
			+ '<input type="text" name="posisi_panitia[]" autocomplete="off" class="form-control posisi_panitia" placeholder="'+$('#posisi_panitia').attr('placeholder')+'" aria-label="'+$('#posisi_panitia').attr('placeholder')+'" data-validation="required">'
			+ '</div>'
			+ '<div class="col-md-1 mb-1 mb-md-0 col-3">'
			+ '<button type="button" class="btn btn-block btn-danger btn-icon-only btn-remove-anggota"><i class="fa-times"></i></button>'
			+ '</div>'
			+ '</div>'
			$('#additional-anggota').append(konten);
			var $t = $('#additional-anggota .username:last-child');
			$t.select2({
				dropdownParent : $t.parent(),
				placeholder : ''
			});
}
$('#filter-unit').change(function(){
	$('#username').html('<option value=""></option>');
	$('#users [data-unit="'+$(this).val()+'"]').each(function(){
		$('#username').append('<option value="'+$(this).attr('value')+'" data-jabatan="'+$(this).attr('data-jabatan')+'">'+$(this).text()+'</option>');
	});
	$('#username').trigger('change');
	select_value = $('#username').html();
	$('[data-serverside]').attr('data-serverside',base_url + 'inisiasi/panitia_pengadaan/data?id_unit_kerja=' + $(this).val());
	refreshData();
});
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
			$(this).closest('.form-group').find('.jabatan').val($(this).find(':selected').attr('data-jabatan'));
			$(this).closest('.form-group').find('.nama_panitia').val($(this).find(':selected').text());
		}
	}
});
$('.btn-add-anggota').click(function(){
	add_row_anggota();
});
$(document).on('click','.btn-remove-anggota',function(){
	$(this).closest('.form-group').remove();
});
$(document).ready(function(){
	$('#filter-unit').trigger('change');
});
function formOpen() {
	var response = response_edit;
	$('#additional-anggota').html('');
	$('#id_unit_kerja').val($('#filter-unit').val());
	$('#unit_kerja').val($('#filter-unit').find(':selected').text());
	$.each(response.detail,function(e,d){
		if(e == '0') {
			$('#username').val(d.username).trigger('change');
			$('#nama_panitia').val(d.nama_panitia);
			$('#jabatan').val(d.jabatan);
			$('#posisi_panitia').val(d.posisi_panitia);
		} else {
			add_row_anggota();
			$('#additional-anggota .username').last().val(d.username).trigger('change');
			$('#additional-anggota .nama_panitia').last().val(d.nama_panitia);
			$('#additional-anggota .jabatan').last().val(d.jabatan);
			$('#additional-anggota .posisi_panitia').last().val(d.posisi_panitia);
		}
	});
}
</script>