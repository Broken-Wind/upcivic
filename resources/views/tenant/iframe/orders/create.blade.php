@extends('layouts.app')
@section('title')
    Enter Order Details
@endsection
@section('content')
<div class="container">
    <form method="POST" action="/program_sessions/111/orders">        
        @include('shared.form_errors')
        {{ csrf_field() }}
        <input type="hidden" name="ticket_quantity" value="{{ $numberOfSpots }}">
        <div class="card">
            <div class="card-header">
                Participant Information
            </div>
            <div class="card-body">
                @for($i = 1; $i <= $numberOfSpots; $i++)
                    <h4>Participant #{{ $i }}</h4>
                    <div class="form-group form-row">
                        <div class="col-md-4 form-group">
                            <label>Participant First Name</label>
                            <input name="participants[{{ $i }}][first_name]" type="text" class="form-control" required value="{{ old("participants.{$i}.first_name") }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Participant Last Name</label>
                            <input name="participants[{{ $i }}][last_name]" type="text" class="form-control" required value="{{ old("participants.{$i}.last_name") }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Birthday</label>
                            <input name="participants[{{ $i }}][birthday]" type="date" class="form-control" required value="{{ old("participants.{$i}.birthday") }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Allergies, medical considerations, and special needs</label>
                        <textarea class="form-control" name="participants[{{ $i }}][needs]" rows="3">{{ old("participants.{$i}.needs") }}</textarea>
                    </div>
                    @if($i < $numberOfSpots)
                        <hr />
                    @endif
                @endfor
            </div>
        </div>
        <p />
        <div class="card">
            <div class="card-header">
                Primary Contact Information
            </div>
            <div class="card-body">                
                <div class="form-group form-row">
                    <div class="col-md-6 form-group">
                        <label>Primary Contact First Name</label>
                        <input name="primary_contact[first_name]" type="text" class="form-control" required value="{{ old("primary_contact.first_name") }}">
                    </div>
                    <div class="col-md-6 form-group">
                            <label>Primary Contact Last Name</label>
                        <input name="primary_contact[last_name]" type="text" class="form-control" required value="{{ old("primary_contact.last_name") }}">
                    </div>
                </div>              
                <div class="form-group form-row">
                    <div class="col-md-6 form-group">
                        <label>Phone Number</label>
                        <input name="primary_contact[phone]" type="text" class="form-control" required value="{{ old("primary_contact.phone") }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Alternate Phone Number</label>
                        <input name="primary_contact[alternate_phone]" type="text" class="form-control" required value="{{ old("primary_contact.alternate_phone") }}">
                    </div>
                </div> 
            </div>
        </div>
        <p />
        <div class="card">
            <div class="card-header">
                Additional Emergency Contact Information <text class="text-muted">(Optional)</text>
            </div>
            <div class="card-body">               
                <div class="form-group form-row">
                    <div class="col-md-6 form-group">
                        <label>First Name</label>
                        <input name="alternate_contact[first_name]" type="text" class="form-control" value="{{ old("alternate_contact.first_name") }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Last Name</label>
                        <input name="alternate_contact[last_name]" type="text" class="form-control" value="{{ old("alternate_contact.last_name") }}">
                    </div>
                </div>             
                <div class="form-group form-row">
                    <div class="col-md-6 form-group">
                        <label>Phone Number</label>
                        <input name="alternate_contact[phone]" type="text" class="form-control" value="{{ old("alternate_contact.phone") }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Alternate Phone Number</label>
                        <input name="alternate_contact[alternate_phone]" type="text" class="form-control" value="{{ old("alternate_contact.alternate_phone") }}">
                    </div>
                </div> 
            </div>
        </div>
    </form>
</div>
@endsection


<script type="application/javascript" src="https://js.stripe.com/v3/"></script>
<script type="application/javascript">
    var stripe = Stripe('pk_test_51I9XCwGuEpAR4AJ4vR7GrbA4AXqHKjEpQPPMNxYbBbJzjwa9pDkXe0HsqB57CT5JUlran00D4gN5tAosPmO2GWKQ00shLzd316');
    var elements = stripe.elements();
    var style = {
        base: {
            color: "#32325d",
        }
    };
    var card = elements.create("card", { style: style });
    card.mount("#card-element");

    card.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var form = document.getElementById('payment-form');

    form.addEventListener('submit', function(ev) {
        ev.preventDefault();
        stripe.confirmCardPayment('imooaa', {
            payment_method: {
            card: card,
            billing_details: {
                name: 'Jenny Rosen'
            }
        }
        }).then(function(result) {
            if (result.error) {
                // Show error to your customer (e.g., insufficient funds)
                console.log(result.error.message);
            } else {
            // The payment has been processed!
                if (result.paymentIntent.status === 'succeeded') {
                    // Show a success message to your customer
                    // There's a risk of the customer closing the window before callback
                    // execution. Set up a webhook or plugin to listen for the
                    // payment_intent.succeeded event that handles any business critical
                    // post-payment actions.
                }
            }
        });
    });
</script>