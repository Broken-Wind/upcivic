@extends('layouts.app')
@section('title', 'Subscription')
@section('content')

<div class="container">
    @include('shared.form_errors')
    <div class="card mb-4">
        <div class="card-header">Upgrade to Pro</div>
        <div class="card-body">
            <!-- Stripe Elements Placeholder -->
            <div id="card-element"></div>

            <div class="input-group mt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Number of Seats</span>
                </div>
                <input class="form-control" type="number" name="noOfSeats" id="noOfSeats" aria-describedby="helpId" value="3" min="1" max="100" aria-label="The maximum number of users from your organization on Upcivic.">
                <div class="input-group-append">
                    <span class="input-group-text">x $49/month</span>
                </div>
            </div>
            <small id="helpId" class="form-text text-muted">The maximum number of users from your organization on Upcivic.</small>

            <div class="form-group form-inline mt-4">
                <label><strong>Total Monthly Cost: $<span id="total-cost">147</span>.00</strong></label>
                <button id="card-button" class="btn btn-primary ml-3" data-secret="{{ $intent->client_secret }}">
                    Subscribe
                </button>
            </div>
        </div>
    </div>

</div>

<script type="application/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="application/javascript" src="https://js.stripe.com/v3/"></script>

<script type="application/javascript">
    const seatsElem = document.querySelector('#noOfSeats');
    seatsElem.addEventListener('input', function (e) {
        if (Number.isInteger(parseInt(e.target.value))) {
            document.querySelector('#total-cost').innerHTML = parseInt(e.target.value) * 49;
        }
    });
    const stripe = Stripe('pk_test_51I9XCwGuEpAR4AJ4vR7GrbA4AXqHKjEpQPPMNxYbBbJzjwa9pDkXe0HsqB57CT5JUlran00D4gN5tAosPmO2GWKQ00shLzd316');

    const elements = stripe.elements();

    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
            color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };


    const cardElement = elements.create('card', {style: style});

    cardElement.mount('#card-element');

    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    cardButton.addEventListener('click', async (e) => {
        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: cardElement,
                }
            }
        );

        if (error) {
            // Display "error.message" to the user...
        } else {
            // The card has been verified successfully...

            var url = "{{ route('tenant:api.billing.subscribe', tenant()->slug) }}";
            var data = {
                "paymentMethod": setupIntent.payment_method,
                "noOfSeats": parseInt(document.getElementById("noOfSeats").value)
            };

            return asyncRequest(data, url);
        }
    });

    async function asyncRequest(data = {}, url) {
        const response = await fetch(url, {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            body: JSON.stringify(data)
        });
        return response.json();
    }

</script>

<style>
    .StripeElement {
        box-sizing: border-box;

        height: 40px;

        padding: 10px 12px;

        border: 1px solid transparent;
        border-radius: 4px;
        background-color: white;

        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>

@endsection
