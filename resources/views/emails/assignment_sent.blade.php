@component('mail::message')
{{ $sender['name'] . " of " . $assignedByOrganization['name'] }} is requesting you complete a task via {{ config('app.name') }}.

@component('mail::panel')
## {{ $assignment['name'] }}
{{ $assignment['description'] }}
@endcomponent

@component('mail::button', ['url' => $publicEditUrl])
View Details on Upcivic
@endcomponent

@endcomponent
