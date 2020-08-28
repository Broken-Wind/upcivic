@component('mail::message')
{{ $user['name'] }} approved your proposal.

@component('mail::panel')
## {{ $program['name'] . " at " . $program->site['name'] }}
* {{ $program['description_of_meetings'] }}<br />
* {{ $program['start_time'] }}-{{ $program['end_time'] }}
* {{ $program['description_of_age_range'] }}
@endcomponent

Please contact {{ $user['name'] }} for more information.

@endcomponent