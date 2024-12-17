
$('#jawaban').summernote({
    container : '#modal-form',
    lang : $('#jawaban').attr('data-lang'),
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
			$('#jawaban').trigger('keyup');
	    }
	}
});
function updateSummernote() {
	$("#jawaban").summernote("code", $('#jawaban').val());
}
