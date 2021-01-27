<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Billing\FakePaymentGateway; 
use App\Billing\PaymentFailedException; 


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


    /** @test */
    public function charges_with_an_invalid_payment_token_fail()
    {
        try {
            $paymentGateway = new FakePaymentGateway;

            $paymentGateway->charge(2500, 'invalid-payment-token');

        } catch (PaymentFailedException $e) {

            return; 
        }

        $this->fail(); 
    }

    /** @test */
    function running_a_hook_before_the_first_charge()
    {
        $paymentGateway = new FakePaymentGateway;
        $timesCallbackRan = 0;

        $paymentGateway->beforeFirstCharge(function ($paymentGateway) use (&$timesCallbackRan) {
            $timesCallbackRan++;
            $paymentGateway->charge(2500, $paymentGateway->getValidTestToken(), 'test_acct_1234');
            $this->assertEquals(2500, $paymentGateway->totalCharges());
        });

        $paymentGateway->charge(2500, $paymentGateway->getValidTestToken(), 'test_acct_1234');
        $this->assertEquals(1, $timesCallbackRan);
        $this->assertEquals(5000, $paymentGateway->totalCharges());
    }
}