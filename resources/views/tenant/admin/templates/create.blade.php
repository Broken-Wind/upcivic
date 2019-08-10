@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a Program Template</div>

                <div class="card-body">

                    <form method="POST" action="{{ tenant()->route('tenant:admin.templates.store') }}">

                        @csrf

                        @include('shared.form_errors')

                        <div class="form-group">
                            <label for="name">Program Name</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="name" placeholder="Adventures in Coding" required>
                        </div>

                        <div class="form-group">
                            <label for="internal_name">Internal Nickname</label>
                            <input type="text" class="form-control" name="internal_name" value="{{ old('internal_name') }}" id="internal_name" aria-describedby="internalNameHelp" placeholder="Coding (camp)">
                            <small id="internalNameHelp" class="form-text text-muted">This is optional, but recommended to distinguish camps and classes of the same name, and to save space in your backend schedule.</small>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="3" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="description">Public Notes</label>
                            <textarea class="form-control" name="public_notes" id="public_notes" aria-describedby="publicNotesHelp" rows="3">{{ old('public_notes') }}</textarea>
                            <small id="internalNameHelp" class="form-text text-muted">These are notes that should be published alongside the course description.</small>
                        </div>

                        <div class="form-group">
                            <label for="description">Contributor Notes</label>
                            <textarea class="form-control" name="contributor_notes" id="contributor_notes" aria-describedby="contributorNotesHelp" rows="3">{{ old('contributor_notes') }}</textarea>
                            <small id="contributorNotesHelp" class="form-text text-muted">These notes will be shared with contributors and should not be published.</small>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <select class="form-control" name="ages_type" id="ages_type" required>
                                    <option value="ages" {{ old('ages_type') == 'ages' ? 'selected' : '' }}>Ages</option>
                                    <option value="grades" {{ old('ages_type') == 'grades' ? 'selected' : '' }}>Grades</option>
                                </select>
                            </div>
                            <input type="number" aria-label="Minimum" placeholder="Minimum" name="min_age" value="{{ old('min_age') }}" class="form-control" required>
                            <input type="number" aria-label="Maximum" placeholder="Maximum" name="max_age" value="{{ old('max_age') }}" class="form-control" required>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                    <span class="input-group-text">Your Compensation</span>
                                    <span class="input-group-text">$</span>
                            </div>
                            <input type="text" aria-label="Your Compensation" placeholder="123.00" name="invoice_amount" value="{{ old('invoice_amount') }}" class="form-control" required>
                            <select class="form-control" name="invoice_type" id="invoice_type">
                                <option value="per participant" {{ old('invoice_type') == 'per participant' ? 'selected' : '' }}>per participant</option>
                                <option value="per hour" {{ old('invoice_type') == 'per hour' ? 'selected' : '' }}>per hour</option>
                                <option value="per session" {{ old('invoice_type') == 'per session' ? 'selected' : '' }}>per session</option>
                                <option value="per meeting" {{ old('invoice_type') == 'per meeting' ? 'selected' : '' }}>per meeting</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="meeting_interval">Meets</label>
                            <select class="form-control" name="meeting_interval" value="{{ old('meeting_interval') }}" id="meeting_interval" required>
                                <option value="1" {{ old('meeting_interval') == '1' ? 'selected' : '' }}>Every day</option>
                                <option value="7" {{ old('meeting_interval') == '7' ? 'selected' : '' }}>Once a week</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="meeting_count">Standard Number of Meetings</label>
                            <input type="number" class="form-control" name="meeting_count" value="{{ old('meeting_count') }}" id="meeting_count" placeholder="5" required>
                        </div>

                        <div class="form-group">
                            <label for="meeting_minutes">Standard Meeting Length (minutes)</label>
                            <input type="number" class="form-control" name="meeting_minutes" value="{{ old('meeting_minutes') }}" id="meeting_minutes" placeholder="180" required>
                        </div>

                        <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block">Create Template</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
