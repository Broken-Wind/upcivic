@component('mail::message')
All parties have now signed the document listed below via {{ config('app.name') }}.

@component('mail::panel')
## FULLY SIGNED - {{ $assignment['name'] }}
{{ $assignment['description'] }}
@endcomponent

@component('mail::button', ['url' => $assignment->pdf_url])
Download PDF
@endcomponent

@endcomponent
