@component('mail::message')
{{ $requestor['name'] }} just requested administrator access to {{ $tenant['name'] }} on {{ config('app.name') }}.

If this person is not an administrator of {{ $tenant['name'] }}, you may ignore this message. Otherwise, please visit the link below to grant them access.


@component('mail::button', ['url' => $tenant->route('tenant:admin.edit', ['email' => $requestor['email']])])
Grant {{ $requestor['name'] }} Access
@endcomponent

@endcomponent
