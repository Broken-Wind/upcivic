@component('mail::message')
{{ $organization->name }} has signed the document listed below via {{ config('app.name') }}.

@component('mail::panel')
## {{ $assignment['name'] }}
{{ $assignment['description'] }}
@endcomponent

@component('mail::button', ['url' => $signedUrl])
View Details on {{ config('app.name') }}
@endcomponent

@endcomponent
