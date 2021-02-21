<?php

namespace App\Billing;

use Stripe\Error\InvalidRequest;
use Illuminate\Support\Arr;

class StripePaymentGateway implements PaymentGateway
{
    const TEST_CARD_NUMBER = '4242424242424242';

    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function charge($amount, $token, $destinationAccountId)
    {
        try {
            $stripeCharge = \Stripe\Charge::create([
                'amount' => $amount,
                'source' => $token,
                'currency' => 'usd',
                'application_fee' => max(100, $amount * .05),
            ], [
                'api_key' => $this->apiKey,
                'stripe_account' => $destinationAccountId,
            ]);

            return new Charge([
                'amount' => $stripeCharge['amount'],
                'card_last_four' => $stripeCharge['source']['last4'],
                'destination' => $destinationAccountId,
            ]);
        } catch (InvalidRequest $e) {
            throw new PaymentFailedException;
        }
    }

    public function getValidTestToken($cardNumber = self::TEST_CARD_NUMBER)
    {
        return \Stripe\Token::create([
            "card" => [
                "number" => $cardNumber,
                "exp_month" => 1,
                "exp_year" => date('Y') + 1,
                "cvc" => "123"
            ]
        ], ['api_key' => $this->apiKey])->id;
    }

    public function newChargesDuring($callback, $apiKey = null)
    {
        $latestCharge = $this->lastCharge($apiKey);
        $callback($this);
        return $this->newChargesSince($latestCharge, $apiKey)->map(function ($stripeCharge) {
            return new Charge([
                'amount' => $stripeCharge['amount'],
                'card_last_four' => $stripeCharge['source']['last4'],
            ]);
        });
    }

    private function lastCharge($apiKey = null)
    {
        return Arr::first(\Stripe\Charge::all([
            'limit' => 1
        ], ['api_key' => $apiKey ?? $this->apiKey])['data']);
    }

    private function newChargesSince($charge = null, $apiKey = null)
    {
        $newCharges = \Stripe\Charge::all([
            'ending_before' => $charge ? $charge->id : null,
        ], ['api_key' => $apiKey ?? $this->apiKey])['data'];

        return collect($newCharges);
    }
}
