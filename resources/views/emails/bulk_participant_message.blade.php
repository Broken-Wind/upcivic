@component('mail::message')
# A message from {{ $sendingOrganization->name }} via {{ config('app.name') }}
{{ $message }}
@endcomponent
