<div class="form-group">
    <label for="documentTitle">Document Title</label>
    <input type="text"
    class="form-control" name="documentTitle" id="documentTitle" aria-describedby="helpId" placeholder="Letter of Agreement" value="{{ old('documentTitle') ?? $task->signableDocument->title ?? '' }}" form="createOrUpdateTask">
</div>
<div class="form-group">
    <label for="editor">Document Contents</label>
    <div id="editor">{!! $task->signableDocument->content ?? '' !!}</div>
    <textarea name="documentContent" id="documentContent" style="display:none;" form="createOrUpdateTask"></textarea>
</div>
