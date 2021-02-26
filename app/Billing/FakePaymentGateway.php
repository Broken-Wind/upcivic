<?php

namespace App\Billing;
use App\Billing\PaymentFailedException;
use App\Billing\PaymentGateway;
use Illuminate\Support\Str;


class FakePaymentGateway implements PaymentGateway
{
    const TEST_CARD_NUMBER = '4242424242424242';

    private $charges;
    private $tokens;
    private $beforeFirstChargeCallback;

    public function __construct()
    {
        $this->charges = collect();
        $this->tokens = collect();
    }

    public function getValidTestToken($cardNumber = self::TEST_CARD_NUMBER)
    {
        $token = 'fake-tok_'.Str::random(24);
        $this->tokens[$token] = $cardNumber;
        return $token;
    }

    public function charge($amount, $token, $destinationAccountId)
    {
        if ($this->beforeFirstChargeCallback !== null) {
            $callback = $this->beforeFirstChargeCallback;
            $this->beforeFirstChargeCallback = null;
            $callback($this);
        }

        if (! $this->tokens->has($token)) {

            throw new PaymentFailedException;
        }

        return $this->charges[] = new Charge([
            'amount' => $amount,
            'card_last_four' => substr($this->tokens[$token], -4),
            'destination' => $destinationAccountId,
            'stripe_charge_id' => 'ch_fakeid_yo'
        ]);
    }

    public function newChargesDuring($callback)
    {
        $chargesFrom = $this->charges->count();
        $callback($this);
        return $this->charges->slice($chargesFrom)->reverse()->values();
    }

    public function totalCharges()
    {
        return $this->charges->map->amount()->sum();
    }
    public function totalChargesFor($accountId)
    {
        return $this->charges->filter(function ($charge) use ($accountId) {
            return $charge->destination() === $accountId;
        })->map->amount()->sum();
    }

    /** @test */
    function can_get_details_about_a_successful_charge()
    {
        $paymentGateway = $this->getPaymentGateway();

        $charge = $paymentGateway->charge(2500, $paymentGateway->getValidTestToken($paymentGateway::TEST_CARD_NUMBER), 'test_acct_1234');

        $this->assertEquals(substr($paymentGateway::TEST_CARD_NUMBER, -4), $charge->cardLastFour());
        $this->assertEquals(2500, $charge->amount());
        $this->assertEquals('test_acct_1234', $charge->destination());
    }

    public function beforeFirstCharge($callback)
    {
        $this->beforeFirstChargeCallback = $callback;
    }

}
