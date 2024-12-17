
$('#informasi').summernote({
    container : '#modal-form',
    lang : $('#informasi').attr('data-lang'),
    height : 300,
    disableResizeEditor: true,
    toolbar: [
		['paragraf', ['style']],
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'picture', 'table']],
        ['line', ['hr']],
		['view', ['codeview']]
	],
	callbacks : {
		onKeyup: function(contents, $editable) {
			$('#informasi').trigger('keyup');
	    }
	}
});
function updateSummernote() {
	$("#informasi").summernote("code", $('#informasi').val());
}
