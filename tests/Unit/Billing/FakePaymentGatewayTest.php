<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Billing\FakePaymentGateway;


class FakePaymentGatewayTest extends TestCase 
{

    use RefreshDatabase;

    /** @test */
    public function charges_with_a_valid_payment_token_are_succesful()
    {
        $paymentGateway = new FakePaymentGateway();

        $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());

        $this->assertEquals(2500, $paymentGateway->totalCharges());
    }

}