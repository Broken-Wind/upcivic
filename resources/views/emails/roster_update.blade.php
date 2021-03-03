@component('mail::message')
The roster, sign-in sheet, and daily attendance sheets for this program have been updated due to a change in enrollment. Please find the updated documents attached.

@component('mail::panel')
## {{ $program['name'] . " at " . $program->site['name'] }}
* {{ $program['description_of_meetings'] }}<br />
* {{ $program['start_time'] }}-{{ $program['end_time'] }}
* {{ $program['description_of_age_range'] }}
@endcomponent

@endcomponent
