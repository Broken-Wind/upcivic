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
        @include('tenant.admin.programs.components.summary')
        @include('tenant.admin.programs.components.status_actions')
        @include('tenant.admin.programs.components.registration_options')
        @include('tenant.admin.programs.components.edit_contributors')
        @include('tenant.admin.programs.components.edit_details')
        @include('tenant.admin.programs.components.edit_meetings')
    </div>
@endsection
