@component('mail::message')
{{ $user['name'] }} rejected your proposal.

@component('mail::panel')
## {{ $program['name'] . " at " . $program->site['name'] }}
* {{ $program['description_of_meetings'] }}<br />
* {{ $program['start_time'] }}-{{ $program['end_time'] }}
* {{ $program['description_of_age_range'] }}
@endcomponent

@component('mail::panel')
@if(empty($reason))
## No reason specified.
@else
## Rejection Reason:
{{ $reason }}
@endif
@endcomponent

Please contact {{ $user['name'] }} by email at {{$user['email']}}
@if(!empty($user->phone))
    or by phone at {{$user['phone']}}
@endif
for more information.

@endcomponent
