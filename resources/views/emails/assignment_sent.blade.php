@component('mail::message')
{{ $sender['name'] . " of " . $assignedByOrganization['name'] }} is requesting you complete a task via {{ config('app.name') }}.

@component('mail::button', ['url' => route('root')])
View Details on Upcivic
@endcomponent

@endcomponent
