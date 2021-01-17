@extends('layouts.app')
@section('title', '#' . $program->id . ' - ' . $program->name . ' Proposal')
@section('content')
    <div class="container">
        <!-- Proposal Info -->
        @include('tenant.admin.programs.components.summary')

        <!-- Alerts and main actions -->
        @include('tenant.admin.programs.components.status_actions')

        <!-- Program -->
        <div class="card mb-4">
            <div class="card-header">Program</div>
            <div class="card-body">
                <form id="delete_program">
                    <fieldset disabled="disabled"/>
                </form>
                <form>
                    <fieldset disabled="disabled"/>
                    @include('shared.form_errors')
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
                        <div class="form-group col-md-6">
                            <label for="min_enrollments">Minimum Enrollments</label>
                            <input type="number" class="form-control" name="min_enrollments" placeholder="5"
                                   value="{{ old('min_enrollments') ?: $program['min_enrollments'] }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="max_enrollments">Maximum Enrollments</label>
                            <input type="number" class="form-control" name="max_enrollments" placeholder="12"
                                   value="{{ old('max_enrollments') ?: $program['max_enrollments'] }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Meetings -->
        <div class="card mb-4">
            <div class="card-header">Meetings <span class="text-muted ml-1">({{ $program->meetings->count() }} total)</span> </div>
            <div class="card-body">
                <form>
                    <fieldset disabled="disabled"/>
                    <table class="table table-striped">
                        @forelse($program->meetings->sortBy('start_datetime') as $meeting)
                            <tr>
                                <td>{{ $meeting['start_date'] }}{{ $meeting['start_date'] != $meeting['end_date'] ? '-' . $meeting['end_date'] : '' }}</td>
                                <td>{{ $meeting['start_time'] . "-" . $meeting['end_time'] }}</td>
                                <td>{{ $meeting->site['name'] }}</td>
                                <td>
                                    @if($meeting->hasInstructors())
                                        {{ $meeting->instructor_list }}
                                    @else
                                        <span class="bg-warning">
                                            <i class="fas fa-fw fa-exclamation-triangle"></i> No instructors assigned!
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>Error! Please contact support.</td>
                            </tr>
                        @endforelse
                    </table>
                </form>
            </div>
        </div>

        <!-- Contributors -->
        <div class="card">
            <div class="card-header">Contributors</div>
            <div class="card-body">
                <form id="update_program_contributors">
                    <fieldset disabled="disabled"/>
                </form>
                <table class="table table-striped">
                    @if($program['shared_invoice_type'])
                        <tr>
                            <th>
                                Total Program Base Fee:
                            </th>
                            <th>
                                ${{ $program->formatted_base_fee }} {{ $program->shared_invoice_type }}
                            </th>
                        </tr>
                    @endif
                    @foreach($program->contributors as $contributor)
                        <tr>
                            <td>{{ $contributor->organization->name }}</td>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Compensation</span>
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="text" form="update_program_contributors"
                                           aria-label="Compensation" placeholder="TBD"
                                           name="contributors[{{$contributor->id}}][invoice_amount]"
                                           value="{{ old("contributors[{$contributor->id}][invoice_amount]") ?: $contributor['formatted_invoice_amount'] }}"
                                           class="form-control">
                                    <select form="update_program_contributors" class="form-control"
                                            name="contributors[{{$contributor->id}}][invoice_type]"
                                            id="invoice_type">
                                        <option
                                            value="per participant" {{ old("contributors[{$contributor->id}][invoice_type]") == 'per participant' ? 'selected' : (empty(old("contributors[{$contributor->id}][invoice_type]")) && $contributor['invoice_type'] == 'per participant' ? 'selected' : '') }}>
                                            per participant
                                        </option>
                                        <option
                                            value="per hour" {{ old("contributors[{$contributor->id}][invoice_type]") == 'per hour' ? 'selected' : (empty(old("contributors[{$contributor->id}][invoice_type]")) && $contributor['invoice_type'] == 'per hour' ? 'selected' : '') }}>
                                            per hour
                                        </option>
                                        <option
                                            value="per session" {{ old("contributors[{$contributor->id}][invoice_type]") == 'per session' ? 'selected' : (empty(old("contributors[{$contributor->id}][invoice_type]")) && $contributor['invoice_type'] == 'per session' ? 'selected' : '') }}>
                                            per session
                                        </option>
                                        <option
                                            value="per meeting" {{ old("contributors[{$contributor->id}][invoice_type]") == 'per meeting' ? 'selected' : (empty(old("contributors[{$contributor->id}][invoice_type]")) && $contributor['invoice_type'] == 'per meeting' ? 'selected' : '') }}>
                                            per meeting
                                        </option>
                                    </select>
                                    @if($program->contributors->count() > 1 && $program['shared_invoice_type'])
                                        <div class="input-group-append">
                                            <span class="input-group-text text-muted">({{ $contributor['percentage_of_total_fee'] }}%)</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
