@component('mail::message')
This program has been canceled by {{ $organization->name }}.

@component('mail::panel')
## {{ $program['name'] . " at " . $program->site['name'] }}
* {{ $program['description_of_meetings'] }}<br />
* {{ $program['start_time'] }}-{{ $program['end_time'] }}
* {{ $program['description_of_age_range'] }}
@endcomponent

Please reply to this email
@if(!empty($organization->phone))
or call {{ $organization->name }} at {{ $organization->phone }}
@endif
for more information.

@endcomponent
