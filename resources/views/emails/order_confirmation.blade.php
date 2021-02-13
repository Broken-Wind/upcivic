@component('mail::message')
You're enrolled! Here are your order details:

@component('mail::panel')
## {{ $program['name'] . " at " . $program->site['name'] }}
* {{ $program['description_of_meetings'] }}<br />
* {{ $program['start_time'] }}-{{ $program['end_time'] }}
* {{ $program['description_of_age_range'] }}
@endcomponent

@foreach($order->tickets as $ticket)
@component('mail::panel')
## {{ $ticket->participant->name . " - " . $ticket->code }}
* {{ $ticket->participant->needs ?? 'No special needs listed.' }}
* Birthday: {{ $ticket->participant->birthday->format('n/j/Y') }}
@forelse($ticket->participant->contacts as $contact)
* {{ $contact->name }} - {{ $contact->phone }} - {{ $contact->email }}
@empty
* *No contacts provided*
@endforelse
@endcomponent
@endforeach

@component('mail::button', ['url' => tenant()->route('tenant:programs.orders.show', [$program, $order->confirmation_number])])
View details on {{ config('app.name') }}
@endcomponent

@endcomponent
