<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php echo access_button('import,export,delete,active,inactive'); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('settings/user_lists/data'),'tbl_user');
		thead();
			tr();
				th('checkbox','text-center','width="30px" data-content="id"');
				th(lang('kode'),'','data-content="kode"');
				th(lang('nama'),'','data-content="nama"');
				th(lang('nama_pengguna'),'','data-content="username"');
				th(lang('email'),'','data-content="email"');
				th(lang('jabatan'),'','data-content="jabatan"');
				th(lang('unit_kerja'),'','data-content="unit" data-table="tbl_m_unit tbl_unit_kerja"');
				th(lang('hak_akses'),'','width="150" data-content="nama" data-table="tbl_user_group"');
				th(lang('aktif').'?','text-center','width="120px" data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form');
		modal_body();
			form_open(base_url('settings/user_lists/save'),'post','form');
				col_init(3,9);
				input('hidden','id','id');
				input('text',lang('kode'),'kode','required|max-length:30|unique');
				input('text',lang('nama'),'nama','required|min-length:4');
				input('text',lang('email'),'email','required|email|unique');
				input('text',lang('telepon'),'telepon','number|min-length:10');
				input('text',lang('jabatan'),'jabatan','required');
				select2(lang('hak_akses'),'id_group','required',$group,'id','nama');
				input('text',lang('nama_pengguna'),'username','required|min-length:4|unique|alphanumeric');
				select2(lang('divisi'),'id_divisi','required',$opt_id_divisi,'id','divisi');
				select2(lang('unit_kerja'),'id_unit_kerja','required',$opt_id_unit_kerja,'id','unit');
				input('password',lang('kata_sandi'),'password');
				input('password',lang('konfirmasi_kata_sandi'),'konfirmasi','equal:password');
				if(!user('is_kanwil')) {
				?>
				<div class="form-group row">
					<label class="col-form-label col-sm-3" for="is_kanwil"><?php echo lang('user_master'); ?><small>(<?php echo lang('dapat_melihat_data_dari_semua_unit_kerja'); ?>)</small></label>
					<div class="col-sm-3">
						<label class="switch">
							<input type="checkbox" value="1" name="is_kanwil" id="is_kanwil">
							<span class="slider"></span>
						</label>
					</div>
				</div>
				<?php
				}
				toggle(lang('aktif').'?','is_active');
				form_button(lang('simpan'),lang('batal'));
			form_close();
		modal_footer();
	modal_close();
	modal_open('modal-import',lang('impor'));
		modal_body();
			form_open(base_url('settings/user_lists/import'),'post','form-import');
				col_init(3,9);
				fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
				form_button(lang('impor'),lang('batal'));
			form_close();
	modal_close();
?>
<script type="text/javascript">
var id_unlock = 0;
$(document).on('click','.btn-unlock',function(e){
	e.preventDefault();
	id_unlock = $(this).attr('data-id');
	cConfirm.open(lang.apakah_anda_yakin + '?','lanjut');
});
function lanjut() {
	$.ajax({
		url : base_url + 'settings/user_lists/unlock',
		data : {id:id_unlock},
		type : 'post',
		dataType : 'json',
		success : function(res) {
			cAlert.open(res.message,res.status,'refreshData');
		}
	});
}
</script>