<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Billing\FakePaymentGateway;
use App\Billing\PaymentFailedException;


class FakePaymentGatewayTest extends TestCase
{
    use RefreshDatabase;

    protected function getPaymentGateway()
    {
        return new FakePaymentGateway;
    }

    /** @test */
    public function charges_with_a_valid_payment_token_are_succesful()
    {
        $paymentGateway = new FakePaymentGateway();

        $paymentGateway->charge(2500, $paymentGateway->getValidTestToken(), 'test_acct_1234');

        $this->assertEquals(2500, $paymentGateway->totalCharges());
    }


    /** @test */
    public function charges_with_an_invalid_payment_token_fail()
    {

        $paymentGateway = new FakePaymentGateway;

        $newCharges = $paymentGateway->newChargesDuring(function ($paymentGateway) {
            try {
                $paymentGateway->charge(2500, 'invalid-payment-token', 'test_acct_1234');
            } catch (PaymentFailedException $e) {
                return;
            }

            $this->fail("Charging with an invalid payment token did not throw a PaymentFailedException.");
        });

        $this->assertCount(0, $newCharges);
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

    /** @test */
    function can_get_total_charges_for_a_specific_account()
    {
        $paymentGateway = new FakePaymentGateway;

        $paymentGateway->charge(1000, $paymentGateway->getValidTestToken(), 'test_acct_0000');
        $paymentGateway->charge(2500, $paymentGateway->getValidTestToken(), 'test_acct_1234');
        $paymentGateway->charge(4000, $paymentGateway->getValidTestToken(), 'test_acct_1234');

        $this->assertEquals(6500, $paymentGateway->totalChargesFor('test_acct_1234'));
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
}
