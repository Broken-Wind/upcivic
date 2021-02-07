@extends('layouts.app')
@section('title', 'Subscription')
@section('content')

<div class="container">
    @include('shared.form_errors')
    @include('shared.loader')
    <div id="statusSuccess" class="alert alert-success" style="display: none"></div>
    <div id="statusFailContainer" class="alert alert-danger" style="display: none">
        <span id="statusFail"></span>
        <a href="{{ \URL::current() }}">Please try again.</a>
    </div>
    <div id="paymentCard" class="card mb-4">
        <div class="card-header">Upgrade to Pro</div>
        <div class="card-body">
            <!-- Stripe Elements Placeholder -->
            <div id="card-element"></div>

            <div class="input-group mt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Number of Seats</span>
                </div>
                <input class="form-control" type="number" name="numberOfSeats" id="numberOfSeats" aria-describedby="helpId" value="{{tenant()->users->count()}}" min="1" max="100" aria-label="The maximum number of users from your organization on Upcivic.">
                <div class="input-group-append">
                    <span class="input-group-text">x $49/month</span>
                </div>
            </div>
            <small id="helpId" class="form-text text-muted">The maximum number of users from your organization on Upcivic. Must be between {{ tenant()->users->count() }} and 20. For more than 20 seats, contact {{ config('mail.sales_email') }}.</small>

            <div class="row">
                <div class="col-6 mt-4 mb-3">
                    <strong>Total Monthly Cost: $<span id="total-cost">{{ tenant()->users->count() * 49 }}</span>.00</strong>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <button id="card-button" class="btn btn-primary btn-lg" data-secret="{{ $intent->client_secret }}">
                        Subscribe
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="application/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="application/javascript" src="https://js.stripe.com/v3/"></script>

<script type="application/javascript">
    const cardButton = document.getElementById('card-button');
    const seatsElem = document.querySelector('#numberOfSeats');
    const minSeats = {{ tenant()->users->count() }}
    seatsElem.addEventListener('input', function (e) {
        let quantity = parseInt(e.target.value);
        if (Number.isInteger(quantity)) {
            if (quantity > 20 || quantity < minSeats) {
                cardButton.disabled = true;
            } else {
                cardButton.disabled = false;
            }
            document.querySelector('#total-cost').innerHTML = quantity * 49;
        }
    });
    const stripe = Stripe('{{ config('app.stripe_public_key') }}');

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

    const clientSecret = cardButton.dataset.secret;
    cardButton.addEventListener('click', async (e) => {
        document.getElementById('statusFailContainer').style.display = 'none';
        document.getElementById('loader').style.display = 'block';
        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: cardElement,
                }
            }
        );

        if (error) {
            document.getElementById('statusFailContainer').style.display = 'block';
            document.getElementById('statusFail').innerHTML = error.message;
        } else {
            // The card has been verified successfully...

            var url = "{{ route('tenant:api.subscriptions.store', tenant()->slug) }}";
            var data = {
                "paymentMethod": setupIntent.payment_method,
                "numberOfSeats": parseInt(document.getElementById("numberOfSeats").value)
            };

            asyncRequest(data, url).then(data => {
                document.getElementById('loader').style.display = 'none';
                document.getElementById('paymentCard').style.display = 'none';
                if (data.status == 'success') {
                    document.getElementById('upgradeProBadge').style.display = 'none';
                    document.getElementById('statusSuccess').style.display = 'block';
                    document.getElementById('statusSuccess').innerHTML = data.message;
                } else {
                    document.getElementById('statusFailContainer').style.display = 'block';
                    document.getElementById('statusFail').innerHTML = data.message;
                }
            });

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
