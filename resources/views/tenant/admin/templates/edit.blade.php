@extends('layouts.app')
@section('title', $template->name . ' Program')
@section('content')
<div class="container">
    @include('shared.form_errors')
    <div class="card">
        <div class="card-header">Program</div>

        <div class="card-body">

            <form id="delete_template" method="POST" action="{{ tenant()->route('tenant:admin.templates.destroy', [$template]) }}">

                @method('delete')

                @csrf

            </form>

            <form method="POST" action="{{ tenant()->route('tenant:admin.templates.update', [$template]) }}">

                @method('put')

                @csrf

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') ?: $template['name'] }}" id="name" placeholder="Adventures in Coding" required>
                </div>

                <div class="form-group">
                    <label for="internal_name">Internal Nickname</label>
                    <input type="text" class="form-control" name="internal_name" value="{{ old('internal_name') ?: $template['internal_name'] }}" id="internal_name" aria-describedby="internalNameHelp" placeholder="Coding (camp)">
                    <small id="internalNameHelp" class="form-text text-muted">This is optional, but recommended to distinguish camps and classes of the same name, and to save space in your backend schedule.</small>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="6" required>{{ old('description') ?: $template['description'] }}</textarea>
                </div>

                <div class="form-group">
                    <label for="description">Public Notes</label>
                    <textarea class="form-control" name="public_notes" id="public_notes" aria-describedby="publicNotesHelp" rows="6">{{ old('public_notes') ?: $template['public_notes'] }}</textarea>
                    <small id="internalNameHelp" class="form-text text-muted">These are notes that should be published alongside the course description. Ex: "There is a $20 materials fee due on the first day of this program."</small>
                </div>

                <div class="form-group">
                    <label for="description">Contributor Notes</label>
                    <textarea class="form-control" name="contributor_notes" id="contributor_notes" aria-describedby="contributorNotesHelp" rows="6">{{ old('contributor_notes') ?: $template['contributor_notes'] }}</textarea>
                    <small id="contributorNotesHelp" class="form-text text-muted">These notes will be shared with contributors and should not be published. Ex: "Please put us in a room with a projector."</small>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <select class="form-control" name="ages_type" id="ages_type" required>
                            <option value="ages" {{ old('ages_type') == 'ages' ? 'selected' : (empty(old('ages_type')) && $template['ages_type'] == 'ages' ? 'selected' : '') }}>Ages</option>
                            <option value="grades" {{ old('ages_type') == 'grades' ? 'selected' : (empty(old('ages_type')) && $template['ages_type'] == 'grades' ? 'selected' : '') }}>Grades</option>
                        </select>
                    </div>
                    <input type="number" aria-label="Minimum" placeholder="Minimum" name="min_age" value="{{ old('min_age') ?: $template['min_age'] }}" class="form-control" required>
                    <input type="number" aria-label="Maximum" placeholder="Maximum" name="max_age" value="{{ old('max_age') ?: $template['max_age'] }}" class="form-control" required>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">

                        <label for="min_enrollments">Minimum Enrollments</label>
                        <input type="number" class="form-control" name="min_enrollments" placeholder="5" value="{{ old('min_enrollments') ?: $template['min_enrollments'] }}">

                    </div>

                    <div class="form-group col-md-6">

                        <label for="max_enrollments">Maximum Enrollments</label>
                        <input type="number" class="form-control" name="max_enrollments" placeholder="12" value="{{ old('max_enrollments') ?: $template['max_enrollments'] }}">

                    </div>

                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                            <span class="input-group-text">Your Compensation</span>
                            <span class="input-group-text">$</span>
                    </div>
                    <input type="text" aria-label="Your Compensation" placeholder="123.00" name="invoice_amount" value="{{ old('invoice_amount') ?: $template['formatted_invoice_amount'] }}" class="form-control" required>
                    <select class="form-control" name="invoice_type" id="invoice_type">
                        <option value="per participant" {{ old('invoice_type') == 'per participant' ? 'selected' : (empty(old('invoice_type')) && $template['invoice_type'] == 'per participant' ? 'selected' : '') }}>per participant</option>
                        <option value="per hour" {{ old('invoice_type') == 'per hour' ? 'selected' : (empty(old('invoice_type')) && $template['invoice_type'] == 'per hour' ? 'selected' : '') }}>per hour</option>
                        <option value="per session" {{ old('invoice_type') == 'per session' ? 'selected' : (empty(old('invoice_type')) && $template['invoice_type'] == 'per session' ? 'selected' : '') }}>per session</option>
                        <option value="per meeting" {{ old('invoice_type') == 'per meeting' ? 'selected' : (empty(old('invoice_type')) && $template['invoice_type'] == 'per meeting' ? 'selected' : '') }}>per meeting</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="meeting_interval">Meets</label>
                    <select class="form-control" name="meeting_interval" value="{{ old('meeting_interval') ?: $template['meeting_interval'] }}" id="meeting_interval" required>
                        <option value="1" {{ old('meeting_interval') == '1' ? 'selected' : (empty(old('meeting_interval')) && $template['meeting_interval'] == '1' ? 'selected' : '') }}>Every day</option>
                        <option value="7" {{ old('meeting_interval') == '7' ? 'selected' : (empty(old('meeting_interval')) && $template['meeting_interval'] == '7' ? 'selected' : '') }}>Once a week</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="meeting_count">Standard Number of Meetings</label>
                    <input type="number" class="form-control" name="meeting_count" value="{{ old('meeting_count') ?: $template['meeting_count'] }}" id="meeting_count" placeholder="5" required>
                </div>

                <div class="form-group">
                    <label for="meeting_minutes">Standard Meeting Length (minutes)</label>
                    <input type="number" class="form-control" name="meeting_minutes" value="{{ old('meeting_minutes') ?: $template['meeting_minutes'] }}" id="meeting_minutes" placeholder="180" required>
                </div>

                <div class="form-row">
                    <button type="submit" id="submit" class="btn btn-primary mx-1">Update</button>
                    <button type="submit" id="submit"  form="delete_template" class="btn btn-secondary"  onClick="return confirm('Are you sure you want to delete this program? This cannot be undone.');">Delete</button>
                </div>


            </form>
        </div>
    </div>
</div>
@endsection
