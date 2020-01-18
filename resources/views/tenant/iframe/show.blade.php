@extends('layouts.iframe')
@section('content')
    <div class="pt-3 pl-3">
        <a href="{{URL::previous()}}">&laquo; Back to Program Listing</a>
    </div>
    <p />
    <div class="card">
        <div class="card-header">
            Session Information <text class="text-muted"><em> - {{ tenant()['name'] }} Barcode #{{ $program['id'] }}</em></type>
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
    <div class="card">
        <div class="card-header">
            Getting Enrolled:
        </div>
        <div class="card-body">
            @if(isset($program['enrollment_url']))
                <li>Pricing is determined by {{ $program->organization['name'] }}. Please visit their website or contact them directly for more information. </li>
                <form action="{{ $program['enrollment_url'] }}" method="GET">
                    <button type="submit" class="btn btn-primary btn-block">Enroll Now <i class="fas fa-fw fa-external-link-alt ml-2"></i></button>
                    <small class="form-text text-muted text-center">You will be redirected to the enrollment website of our organization.</small>
                </form>
            @else
                <li>We don't yet have enrollment information for this program.</li>
            @endif
                <hr />
                @include('tenant.iframe.components.registration_problems')
            <hr />
            <h5>Questions about our programs?</h5>
            <ul>
                <li>Read our <a href="http://techsplosion.org/summer-camp-descriptions/#{{ $program->enrichmentProgram['standard_name'] }}" target="_blank">camp descriptions</a></li>
                <li>Read our <a href="http://techsplosion.org/faq/" target="_blank">FAQ</a></li>
                <li>Contact us via camp@techsplosion.org or 415.223.4312 (we can respond to email fastest)</li>
            </ul>
        </div>
    </div>
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
@endsection
