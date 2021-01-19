<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1" />


<label for="card-element">
    Credit or Debit Card
</label>

<!-- Stripe Elements Placeholder -->
<div id="card-element"></div>

Number of Seats: 
<input type="text" id="noOfSeats" name="noOfSeats" value="3" min="1" max="100">


<button id="card-button" data-secret="{{ $intent->client_secret }}">
    Subscribe
</button>

<script type="application/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="application/javascript" src="https://js.stripe.com/v3/"></script>

<script type="application/javascript">
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