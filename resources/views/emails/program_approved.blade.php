@component('mail::message')
{{ $user['name'] }} approved your proposal on behalf of {{ $organizationString }}.

@component('mail::panel')
## {{ $program['name'] . " at " . $program->site['name'] }}
* {{ $program['description_of_meetings'] }}<br />
* {{ $program['start_time'] }}-{{ $program['end_time'] }}
* {{ $program['description_of_age_range'] }}
@endcomponent

@if(!empty($approve_next_steps))
@component('mail::panel')
## Next Steps
{{ $approve_next_steps }}
@endcomponent
@endif

Please contact {{ $user['name'] }} by email at {{$user['email']}}
@if(!empty($user->phone))
or by phone at {{$user['phone']}}
@endif
for more information.

@endcomponent
