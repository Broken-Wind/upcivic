@component('mail::message')
{{ $sender['name'] . " of " . $assignedByOrganization['name'] }} is requesting you complete a task via {{ config('app.name') }}.

@component('mail::button', ['url' => $signedUrl])
URL BRO on Upcivic
@endcomponent

@endcomponent
