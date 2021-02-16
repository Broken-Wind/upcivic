@extends('layouts.app')
@section('title', '#' . $program->id . ' - ' . $program->name)
@push('scripts')
<script>
    @if($newlyCreated)
        var newlyCreated = true;
    @endif
    var program = {
        'id': {{ $program->id }},
        'name': '{{ $program->name }}',
        'proposing_organization_id': {{ $program->proposing_organization_id }},
        'recipient_organization_ids': {{ $program->recipientContributors()->pluck('organization_id')->toJson() }},
        'site_ids': {{ $program->meetings->pluck('site_id')->whereNotNull()->unique()->toJson() }},
        'location_ids': {{ $program->meetings->pluck('location_id')->whereNotNull()->unique()->toJson() }},
        'start_date': '{{ $program->start_date }}',
        'end_date': '{{ $program->end_date }}',
        'start_time': '{{ $program->start_time }}',
        'end_time': '{{ $program->end_time }}',
        'meeting_start_dates': {!! $program->meetings->pluck('start_datetime')->toJson() !!},
        'meeting_count': {{ $program->meetings->count() }},
        'created_at': '{{ $program->created_at }}'
    };
</script>
<script src="{{ asset('js/views/edit_program.js') }}" defer></script>
@endpush
@section('content')
    <div class="container">
        @include('shared.form_errors')
        <!-- Proposal Info -->
        @include('tenant.admin.programs.components.summary')

        <!-- Alerts and main actions -->
        @include('tenant.admin.programs.components.status_actions')

        <!-- Registration Options -->
        @include('tenant.admin.programs.components.registration_options')

        <!-- Program -->
        <div class="card mb-4">
            <div class="card-header">Program</div>
            <div class="card-body">
                <form action="{{ tenant()->route('tenant:admin.programs.destroy', [$program]) }}" method="post"
                      id="delete_program">
                    @method('delete')
                    @csrf
                    @if($program->isProposalSent())
                        <fieldset disabled="disabled"/>
                    @endif
                </form>
                <form method="POST" action="{{ tenant()->route('tenant:admin.programs.update', [$program]) }}">
                    @method('put')
                    @csrf
                    @if($program->isProposalSent())
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

        <!-- Meetings -->
        <div class="card mb-4">
            <div class="card-header">Meetings <span class="text-muted ml-1">({{ $program->meetings->count() }} total)</span> </div>
            <div class="card-body">
                <form method="POST" action="{{ tenant()->route('tenant:admin.programs.meetings.update', [$program]) }}">
                    @csrf
                    @if($program->isProposalSent())
                        <fieldset disabled="disabled"/>
                    @endif
                    <table class="table table-striped">
                        @forelse($program->meetings->sortBy('start_datetime') as $meeting)
                            <tr>
                                <td><input type="checkbox" name="meeting_ids[]" value="{{ $meeting['id'] }}">
                                </td>
                                <td>{{ $meeting['start_date'] }}{{ $meeting['start_date'] != $meeting['end_date'] ? '-' . $meeting['end_date'] : '' }}</td>
                                <td>{{ $meeting['start_time'] . "-" . $meeting['end_time'] }}</td>
                                <td>{{ $meeting->site['name'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td>Error! Please contact support.</td>
                            </tr>
                        @endforelse
                    </table>
                    <hr/>
                    <div class="form-row">
                        <div class="form-group">
                            <label for=meeting_start_time"">Start Time</label>
                            @if($program->isProposalSent())
                                <fieldset disabled="disabled"/>
                            @endif
                            <input type="time" class="form-control" name="start_time" id="" placeholder=""
                                   value="">
                        </div>

                        <div class="form-group mx-1">
                            @if($program->isProposalSent())
                                <fieldset disabled="disabled"/>
                            @endif
                            <label for="meeting_end_time">End Time</label>
                            <input type="time" class="form-control" name="end_time" id="" placeholder=""
                                   value="">
                        </div>
                        <div class="form-group mx-1">
                            @if($program->isProposalSent())
                                <fieldset disabled="disabled"/>
                            @endif
                            <label for="site_id">Site</label>
                            <select class="form-control" name="site_id">
                                <option value="">-----</option>
                                @foreach ( $sites as $site )
                                    <option
                                        value="{{ $site['id'] }}" {{ $program->site['id'] == $site['id'] ? 'selected' : '' }}>{{ $site['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            @if($program->isProposalSent())
                                <fieldset disabled="disabled"/>
                            @endif
                            <label for="shift_meetings">Shift Meeting Dates</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="shift_meetings" value="0">
                                <div class="input-group-append"><span class="input-group-text">days</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        @if($program->isProposalSent())
                            <fieldset disabled="disabled"/>
                        @endif
                        <button type="submit" class="btn btn-secondary mr-1" name="update_all" value="update_all">
                            Update All
                        </button>
                        <button type="submit" class="btn btn-secondary" name="delete_meetings"
                                value=="delete_meetings">Delete Selected
                        </button>
                    </div>
                </form>

                {{--
                <hr/>
                <form method="POST"
                      action="{{ tenant()->route('tenant:admin.programs.meetings.store', [$program]) }}">
                    @csrf
                    @if($program->isProposalSent())
                        <fieldset disabled="disabled"/>
                    @endif
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="new_meeting_start_datetime">Start Date & Time</label>
                            <input type="datetime-local" class="form-control" name="start_datetime"
                                   value="{{ $program['next_meeting_start_datetime'] }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="new_meeting_end_datetime">End Date & Time</label>
                            <input type="datetime-local" class="form-control" name="end_datetime"
                                   value="{{ $program['next_meeting_end_datetime'] }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="new_meeting_site_id">Site</label>
                            <select class="form-control" name="site_id">
                                <option value="">Site TBD</option>
                                @foreach ( $sites as $site )
                                    <option value="{{ $site['id'] }}"
                                    @if ($site['id'] == $program->site['id'])
                                        {{ 'selected '}}
                                        @endif
                                    >{{ $site['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-secondary">Add</button>
                </form>
                --}}
            </div>
        </div>

        <!-- Contributors -->
        <div class="card">
            <div class="card-header">Contributors</div>
            <div class="card-body">
                <form method="POST" id="update_program_contributors"
                      action="{{ tenant()->route('tenant:admin.programs.contributors.update', [$program]) }}">
                    @method('put')
                    @csrf
                    @if($program->isProposalSent())
                        <fieldset disabled="disabled"/>
                    @endif
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
                                            <form method="POST" id="destroy_contributor_{{ $contributor->id }}"
                                                  action="{{ tenant()->route('tenant:admin.programs.contributors.destroy', [$program, $contributor]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onClick="return confirm('Are you sure you want to remove this contributor? They will lose access to this program.');"
                                                        class="btn btn-sm btn-secondary ml-1"
                                                        form="destroy_contributor_{{ $contributor->id }}">X
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>
                            <select form="update_program_contributors" class="form-control form-control-sm"
                                    name="newContributor[organization_id]" id="">
                                <option value="">Add Contributor</option>
                                @forelse($organizations as $organization)
                                    <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                @empty
                                    <option disabled>None found.</option>
                                @endforelse
                            </select>
                        </td>
                        <td>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Compensation</span>
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="text" form="update_program_contributors" aria-label="Compensation"
                                       placeholder="TBD" name="newContributor[invoice_amount]"
                                       value="{{ old("newContributor[invoice_amount]") }}" class="form-control">
                                <select form="update_program_contributors" class="form-control"
                                        name="newContributor[invoice_type]" id="invoice_type">
                                    <option
                                        value="per participant" {{ old("newContributor[invoice_type]") == 'per participant' ? 'selected' : '' }}>
                                        per participant
                                    </option>
                                    <option
                                        value="per hour" {{ old("newContributor[invoice_type]") == 'per hour' ? 'selected' : '' }}>
                                        per hour
                                    </option>
                                    <option
                                        value="per session" {{ old("newContributor[invoice_type]") == 'per session' ? 'selected' : '' }}>
                                        per session
                                    </option>
                                    <option
                                        value="per meeting" {{ old("newContributor[invoice_type]") == 'per meeting' ? 'selected' : '' }}>
                                        per meeting
                                    </option>
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>
                <button type="submit" form="update_program_contributors" id="submit" class="btn btn-secondary">
                    Update
                </button>
            </div>
        </div>
    </div>
@endsection
