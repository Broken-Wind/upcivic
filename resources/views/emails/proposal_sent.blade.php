@component('mail::message')
{{ $proposal['sender']['name'] . " of " . $proposal['sending_organization']['name'] }} just proposed new programming with {{ $proposal['recipient_organization']['name'] }} via {{ config('app.name') }}.


@component('mail::button', ['url' => route('tenant:admin.programs.index', $proposal['recipient_organization']['slug'])])
View Proposal
@endcomponent

@endcomponent
