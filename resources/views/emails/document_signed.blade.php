@component('mail::message')
{{ $organization->name }} has signed the document listed below via {{ config('app.name') }}.

@component('mail::panel')
## {{ $organization->name }} signed {{ $assignment['name'] }}
{{ $assignment['description'] }}
@endcomponent

@component('mail::button', ['url' => $signedUrl])
View Details on Upcivic
@endcomponent

@endcomponent