@extends('layouts.iframe')
@section('content')
    <div class="pt-3 pl-3">
        <a href="{{URL::previous()}}">&laquo; Back to Program Listing</a>
    </div>
    <p />
    <div class="card">
        <div class="card-header">
            Session Information <text class="text-muted"><em> - {{ tenant()['name'] }} Barcode #{{ $program['id'] }}</em></text>
        </div>
        <div class="card-body">
            <p class="lead">
                {{ $program['name'] }} at {{ $program['site']['name'] }}<br />
                {{ $program['description_of_meetings'] }}<br />
                {{ $program['start_time'] }}-{{ $program['end_time'] }}
            </p>
            <hr />
            {{ $program['description'] }}
            <hr />
            <strong>{{ ucfirst($program['ages_type']) }}:</strong> {{ $program['min_age'] }}-{{ $program['max_age'] }}<br />
            @if($program->otherContributors()->count() > 1)
                <strong>Partners:</strong>
                    @foreach($program->otherContributors() as $contributor)
                        {{ $contributor['name'] }}
                        @if(!$loop->last)
                            ,
                        @endif
                    @endforeach
            @elseif($program->hasOtherContributors())
                <strong>Partner:</strong> {{ $program->otherContributors()->first()['name'] }}
            @endif
        </div>
    </div>
    <p />
    @include('tenant.iframe.components.enrollment_information')
    <p />
    @include('tenant.iframe.components.contributor_information')
    <p />
    @include('tenant.iframe.components.map', ['site' => $program->site])
    <p />
    @if(!empty($program['public_notes']))
        <div class="card">
            <div class="card-header">
                Session Notes
            </div>
            <div class="card-body">
                {{ $program['public_notes' ]}}
            </div>
        </div>
    @endif
    <p />
    <div class="card">
        <div class="card-header">
            Meetings
        </div>

        <div class="card-body">
            @include('tenant.iframe.components.meetings', ['meetings' => $program->meetings])
        </div>
    </div>
    <p />
@endsection
