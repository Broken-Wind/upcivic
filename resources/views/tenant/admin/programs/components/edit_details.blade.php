<div class="card mb-4">
    <div class="card-header">Program</div>
    <div class="card-body">
        <form action="{{ tenant()->route('tenant:admin.programs.destroy', [$program]) }}" method="post"
              id="delete_program">
            @method('delete')
            @csrf
            @if(!$program->isEditable())
                <fieldset disabled="disabled"/>
            @endif
        </form>
        <form method="POST" action="{{ tenant()->route('tenant:admin.programs.update', [$program]) }}">
            @method('put')
            @csrf
            @if(!$program->isEditable())
                <fieldset disabled="disabled"/>
            @endif
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name"
                       value="{{ old('name') ?: $program['name'] }}" id="name"
                       placeholder="Adventures in Coding" required>
            </div>
            <div class="form-group">
                <label for="internal_name">Internal Nickname</label>
                <input type="text" class="form-control" name="internal_name"
                       value="{{ old('internal_name') ?: $program['internal_name'] }}"
                       id="internal_name" aria-describedby="internalNameHelp"
                       placeholder="Coding (camp)">
                <small id="internalNameHelp" class="form-text text-muted">This is optional, but
                    recommended to distinguish camps and classes of the same name, and to save space in
                    your backend schedule.</small>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" rows="3"
                          required>{{ old('description') ?: $program['description'] }}</textarea>
            </div>
            <div class="form-group">
                <label for="description">Public Notes</label>
                <textarea class="form-control" name="public_notes" id="public_notes"
                          aria-describedby="publicNotesHelp"
                          rows="3">{{ old('public_notes') ?: $program['public_notes'] }}</textarea>
                <small id="internalNameHelp" class="form-text text-muted">These are notes that should be
                    published alongside the course description. Ex: "There is a $20 materials fee due on
                    the first day of this program."</small>
            </div>
            <div class="form-group">
                <label for="description">Contributor Notes</label>
                <textarea class="form-control" name="contributor_notes" id="contributor_notes"
                          aria-describedby="contributorNotesHelp"
                          rows="3">{{ old('contributor_notes') ?: $program['contributor_notes'] }}</textarea>
                <small id="contributorNotesHelp" class="form-text text-muted">These notes will be shared
                    with contributors and should not be published. Ex: "Please put us in a room with a
                    projector."</small>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <select class="form-control" name="ages_type" id="ages_type" required>
                        <option
                            value="ages" {{ old('ages_type') == 'ages' ? 'selected' : (empty(old('ages_type')) && $program['ages_type'] == 'ages' ? 'selected' : '') }}>
                            Ages
                        </option>
                        <option
                            value="grades" {{ old('ages_type') == 'grades' ? 'selected' : (empty(old('ages_type')) && $program['ages_type'] == 'grades' ? 'selected' : '') }}>
                            Grades
                        </option>
                    </select>
                </div>
                <input type="number" aria-label="Minimum" placeholder="Minimum" name="min_age"
                       value="{{ old('min_age') ?: $program['min_age'] }}" class="form-control"
                       required>
                <input type="number" aria-label="Maximum" placeholder="Maximum" name="max_age"
                       value="{{ old('max_age') ?: $program['max_age'] }}" class="form-control"
                       required>
            </div>
            <div class="form-row">
                <button type="submit" id="update_program" name="update_program"
                        class="btn btn-secondary mx-1">Update
                </button>
                <!-- <button type="submit" id="delete" form="delete_program" class="btn btn-danger" onClick="return confirm('Are you sure you want to delete this program? This cannot be undone.');">Delete Program</button> -->
            </div>

        </form>
    </div>
</div>
