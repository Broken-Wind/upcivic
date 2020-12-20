@component('mail::message')
{{ $lister['name'] }} just made it easier for organizations to book programs with you, by listing you as an administrator of {{ $organization['name'] }} on {{ config('app.name') }}! {{ config('app.name') }} is the tool you need to get organized with proposal management.

Please verify the following information, and sign up for a free {{ config('app.name') }} account to manage your activity proposals.

## {{ $organization['name'] }} Administrators
@foreach($organization->administrators as $administrator)
* {{ $administrator['name'] }} - {{ $administrator['email'] }}{{ !empty($administrator->administrator['title']) ? ' -' . $administrator->administrator['title'] : '' }}
@endforeach

@component('mail::button', ['url' => route('register')])
Sign up for {{ config('app.name') }}
@endcomponent

@endcomponent
