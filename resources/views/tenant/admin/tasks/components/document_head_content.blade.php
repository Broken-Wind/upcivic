@push('css')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush
@push('scripts')
<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js" type="application/javascript"></script>

<!-- Initialize Quill editor -->
<script type="application/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var quill = new Quill('#editor', {
        theme: 'snow'
    });
    document.getElementById('submit').addEventListener('click', function (event) {
        var documentContent = document.getElementById('documentContent');
        var editorElement = document.getElementById('editor');
        documentContent.innerHTML = editorElement.children[0].innerHTML;
    });
});
</script>
@endpush
