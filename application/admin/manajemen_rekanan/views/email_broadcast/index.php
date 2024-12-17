<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php if($menu_access['access_input']) {
				?>
				<button type="button" class="btn btn-primary btn-sm btn-kirim"><i class="fa-envelope"></i><?php echo lang('kirim_email'); ?></button>
				<?php
			} ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('manajemen_rekanan/email_broadcast/data'),'tbl_email_broadcast');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('penerima'),'','data-content="vendor"');
				th(lang('subjek'),'','data-content="subjek"');
				th(lang('pesan'),'','data-content="konten"');
				th(lang('tanggal'),'','data-content="tanggal" data-type="daterange"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-email',$title,'modal-lg');
	modal_body();
		form_open(base_url('manajemen_rekanan/email_broadcast/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			select2(lang('penerima'),'id_user_penerima[]','required',$vendor,'id','nama','','multiple');
			input('text',lang('subjek'),'subjek','required');
			textarea(lang('pesan'),'konten_html','required');
			form_button(lang('kirim'),lang('batal'));
		form_close();
modal_close();
?>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js'); ?>"></script>
<script type="text/javascript">
$('.btn-kirim').click(function(){
	$('select[multiple] option').prop('selected',false);
	$('#id_user_penerima').trigger('change');
	CKEDITOR.instances['konten_html'].setData('');	
	$('#subjek,#konten_html').html('');
	$('#modal-email form .is-invalid').each(function(){
		$(this).removeClass('is-invalid');
		$(this).closest('.form-group').find('.error').remove();
	});
	$('#modal-email').modal();
});
$(document).ready(function(){
	$('#konten_html').closest('.modal').removeAttr('tabindex');
	setTimeout(function(){
		CKEDITOR.replace( 'konten_html' ,{
			toolbar : [
				{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
				{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
				{ name: 'colors', items: [ 'TextColor', 'BGColor' ] }
			],
			width : 'auto',
			height : 200,
		});
		CKEDITOR.instances['konten_html'].on('change', function() { 
			var vdata = CKEDITOR.instances['konten_html'].getData();
			$('#konten_html').val(vdata);
			$('#konten_html').parent().find('span.error').remove();
		});
	},500);
	$.fn.modal.Constructor.prototype._enforceFocus = function() {
		var _this4 = this;
		$(document).off('focusin.bs.modal').on('focusin.bs.modal', $.proxy((function(event) {
			if (
				document !== event.target
				&& _this4._element !== event.target
				&& $(_this4._element).has(event.target).length === 0
				&& !$(event.target.parentNode).hasClass('cke_dialog_ui_input_select')
				&& !$(event.target.parentNode).hasClass('cke_dialog_ui_input_text')
			) {
				_this4._element.focus();
			}
		}), this));
	};
});
</script>