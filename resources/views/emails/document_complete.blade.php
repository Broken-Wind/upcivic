@component('mail::message')
All parties have now completed the action listed below via {{ config('app.name') }}.

@component('mail::panel')
## COMPLETED - {{ $assignment['name'] }}
{{ $assignment['description'] }}
@endcomponent

@component('mail::button', ['url' => $assignment->pdf_url])
Download PDF
@endcomponent

@endcomponent
