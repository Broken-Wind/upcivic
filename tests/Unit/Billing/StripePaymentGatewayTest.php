<?php

namespace Tests\Unit\Billing;

use Tests\TestCase;
use App\Billing\StripePaymentGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group integration
 */
class StripePaymentGatewayTest extends TestCase
{
    use RefreshDatabase;

    protected function getPaymentGateway()
    {
        return new StripePaymentGateway(config('app.stripe.secret'));
    }

    /** @test */
    function charges_with_a_valid_payment_token_are_successful()
    {
        $paymentGateway = $this->getPaymentGateway();

        $newCharges = $paymentGateway->newChargesDuring(function ($paymentGateway) {
            $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());
        });

        $this->assertCount(1, $newCharges);
        $this->assertEquals(2500, $newCharges->sum());
    }

}