@extends('layouts.iframe')
@section('content')
<div class="container-fluid">
    <div class="pt-3 pl-3">
        <a href="{{URL::previous()}}">&laquo; Back to Program Listing</a>
    </div>
    <p />
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <span class="text-muted">#{{ $program['id'] }} - </span>{{ $program->name }}
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-3 text-center">
                            <i class="fas fa-fw fa-2x fa-calendar-alt text-secondary"></i>
                        </div>
                        <div class="col-9">
                            {{ $program['description_of_meetings'] }}<br />
                            {{ $program['start_time'] }}-{{ $program['end_time'] }}<br />
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-3 text-center">
                            <i class="fas fa-fw fa-2x fa-map-marked-alt text-secondary"></i>
                        </div>
                        <div class="col-9">
                            {{ $program['site']['name'] }}
                        </div>
                    </div>
                    <hr>
                    <small class="text-muted">
                        For {{ $program['ages_type'] }} {{ $program['min_age'] }}-{{ $program['max_age'] }}<br />
                    </small>
                    {{ $program['description'] }}
                </div>
                @include('tenant.iframe.components.contributor_information')
            </div>
        </div>
        <div class="col-md-6">
            @include('tenant.iframe.components.enrollment_information')

            @if(!empty($program['public_notes']))
                <div class="card mt-3">
                    <div class="card-header">
                        Session Notes
                    </div>
                    <div class="card-body">
                        {{ $program['public_notes' ]}}
                    </div>
                </div>
            @endif

            @include('tenant.iframe.components.map', ['site' => $program->site])
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            Meetings
        </div>

        <div class="card-body">
            @include('tenant.iframe.components.meetings', ['meetings' => $program->meetings])
        </div>
    </div>
</div>
@endsection
