@component('mail::message')
{{ $lister['name'] }} just made it easier for organizations to book programs with you, by listing you as an administrator of {{ $organization['name'] }} on {{ config('app.name') }}!

Please verify the following information, and sign up for a free {{ config('app.name') }} account to manage your enrichment proposals and programs.

## {{ $organization['name'] }} Administrators
@foreach($organization->administrators as $administrator)
* {{ $administrator['name'] }} - {{ $administrator['email'] }}{{ !empty($administrator->administrator['title']) ? ' -' . $administrator->administrator['title'] : '' }}
@endforeach

@component('mail::button', ['url' => route('register')])
Sign up for {{ config('app.name') }}
@endcomponent

@endcomponent
