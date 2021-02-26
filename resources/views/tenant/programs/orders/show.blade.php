@extends('layouts.app')

@section('title')
    Order Summary
@endsection

@section('content')

<div class="container">
    <p />
    <div class="container-fluid">
        <h5>Order Total: ${{number_format($order->amount / 100, 2)}}</h5>
        <strong>Billed to Card: **** **** **** {{$order->card_last_four}}</strong><br />
        Confirmation #: <a href="{{$order->confirmation_number}}">{{$order->confirmation_number}}</a><br />
    </div>
    <p />
    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <h4>{{ $program->name }} <i class="fa fa-fw fa-info-circle text-muted" title="{{ $program->description }}"></i></h4>
                <h5>
                    By
                    @foreach ($program['contributors'] as $contributor)
                        @if(!$loop->last)
                            {{ $contributor->name }},
                        @else
                            {{ $contributor->name }}
                        @endif
                    @endforeach
                </h5>
            </div>
        </div>

        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="text-center">Tickets:</h4>
                        @forelse($order->tickets as $ticket)
                            <h5>{{ $ticket->participant->first_name }} {{ $ticket->participant->last_name }}<span class="text-muted ml-2">{{ $ticket->code }}</span></h5>
                            <strong>Contacts:</strong>
                            <ul>
                            @forelse($ticket->participant->contacts as $contact)
                                <li>{{ $contact->name }} - {{ $contact->phone }}</li>
                            @empty
                                <li>No contacts for {{ $ticket->participant->first_name }}.</li>
                            @endforelse
                            </ul>

                            <hr />

                        @empty
                        @endforelse
                    </div>

                    <div class="col-md-6 text-center">
                        <h4>Meetings:</h4>
                        @include('tenant.iframe.components.meetings', ['meetings' => $program->meetings])
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        @if(!empty($program->public_notes))
                            <hr />
                            <h4  class="text-center">Additional Information:</h4>
                            <ul>
                                <li>{{ $program->public_notes }}</li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
