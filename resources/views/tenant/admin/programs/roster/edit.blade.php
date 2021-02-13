@extends('layouts.app')

@section('title', '#' . $program->id . ' - ' . $program->name . ' Roster')
@section('content')
<div class="container">
    @include('shared.form_errors')

    <div class="card mb-4">
        <div class="card-header">Roster</div>
        <div class="card-body">
            @if($program->participants->count() > 0)
                <table class="table table-striped">
                    @foreach($program->tickets()->unavailable()->get() as $ticket)
                        <tr>
                            <td>
                                {{ $ticket->participant->name }} <span class="text-muted">- {{ $ticket->participant->birthday }}</span>
                                <br>
                                <small>{{ $ticket->participant->needs }}</small>
                                <br>
                                <small class="text-muted">{{ $ticket->order->confirmation_number }} - {{ $ticket->code }}</small>
                            </td>
                            <td>
                                @forelse($ticket->participant->contacts as $contact)
                                    {{ $contact->name }} - {{ $contact->phone }} - {{ $contact->email }}
                                    @if(!$loop->last)
                                        <br>
                                    @endif
                                @empty
                                    No contacts.
                                @endforelse
                            </td>

                        </tr>

                    @endforeach

                </table>

            @else

                No registrations yet!

            @endif

        </div>
    </div>
</div>
@endsection
