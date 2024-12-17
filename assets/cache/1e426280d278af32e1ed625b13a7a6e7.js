
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
