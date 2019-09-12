@component('mail::message')
{{ $proposal['sender']['name'] . " of " . $proposal['sending_organization']['name'] }} just proposed new programming with {{ $proposal['recipient_organization']['name'] }} via {{ config('app.name') }}.


@foreach($proposal['programs'] as $program)
@component('mail::panel')
##{{ $program['name'] . " at " . $program->site['name'] }}
* {{ $program['description_of_meetings'] }}<br />
* {{ $program['start_time'] }}-{{ $program['end_time'] }}
* {{ $program['description_of_age_range'] }}
* Min/Max Enrollments: {{ $program['min_enrollments'] }}/{{ $program['max_enrollments'] }}

##Proposed Compensation:
@foreach($program->contributors as $contributor)
@if(isset($contributor['formatted_invoice_amount']))
* {{ $contributor['name'] }} to receive ${{ $contributor['formatted_invoice_amount'] }} {{ $contributor['invoice_type'] }}
@else
* {{ $contributor['name'] }} compensation TBD
@endif
@endforeach
@endcomponent
@endforeach



@component('mail::button', ['url' => route('root')])
View Details on Upcivic
@endcomponent
Descriptions, notes, specific meeting dates, and more will be available when you [sign up free]({{ route('register') }}) **with the same email this message was addressed to.**

@endcomponent
