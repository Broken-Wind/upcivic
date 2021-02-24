@component('mail::message')
There is an updated roster for this program.

@component('mail::panel')
## {{ $program['name'] . " at " . $program->site['name'] }}
* {{ $program['description_of_meetings'] }}<br />
* {{ $program['start_time'] }}-{{ $program['end_time'] }}
* {{ $program['description_of_age_range'] }}
@endcomponent

@component('mail::button', ['url' => route('root')])
View Roster on {{ config('app.name') }}
@endcomponent

@endcomponent
