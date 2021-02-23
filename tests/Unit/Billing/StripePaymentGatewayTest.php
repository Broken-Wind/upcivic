<?php

namespace Tests\Unit\Billing;

use Tests\TestCase;
use App\Billing\StripePaymentGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;

/**
 * @group integration
 */
class StripePaymentGatewayTest extends TestCase
{
    use RefreshDatabase;

    protected function getPaymentGateway()
    {
        return new StripePaymentGateway(config('services.stripe.secret'));
    }

    /** @test */
    function charges_with_a_valid_payment_token_are_successful()
    {
        $paymentGateway = $this->getPaymentGateway();

        $newCharges = $paymentGateway->newChargesDuring(function ($paymentGateway) {
            $paymentGateway->charge(2500, $paymentGateway->getValidTestToken($paymentGateway::TEST_CARD_NUMBER), env('STRIPE_TEST_ACCOUNT_ID'));
        }, env('STRIPE_TEST_ACCOUNT_TOKEN'));

        $this->assertCount(1, $newCharges);
        $this->assertEquals(2500, $newCharges->map->amount()->sum());
    }

    /** @test */
    function can_get_details_about_a_successful_charge()
    {
        $paymentGateway = $this->getPaymentGateway();

        $charge = $paymentGateway->charge(2500, $paymentGateway->getValidTestToken($paymentGateway::TEST_CARD_NUMBER), env('STRIPE_TEST_ACCOUNT_ID'));

        $this->assertEquals(substr($paymentGateway::TEST_CARD_NUMBER, -4), $charge->cardLastFour());
        $this->assertEquals(2500, $charge->amount());
        $this->assertEquals(env('STRIPE_TEST_ACCOUNT_ID'), $charge->destination());
    }

    /** @test */
    public function two_point_one_percent_of_the_payment_is_transfered_to_platform()
    {
        $paymentGateway = $this->getPaymentGateway();

        $paymentGateway->charge(10000, $paymentGateway->getValidTestToken(), env('STRIPE_TEST_ACCOUNT_ID'));

        $lastStripeCharge = Arr::first(\Stripe\Charge::all([
            'limit' => 1
        ], ['api_key' => env('STRIPE_TEST_ACCOUNT_TOKEN')])['data']);

        $this->assertEquals(10000, $lastStripeCharge['amount']);
        $this->assertEquals(210, $lastStripeCharge['application_fee_amount']);

        $applicationFee = \Stripe\ApplicationFee::retrieve($lastStripeCharge['application_fee'], ['api_key' => config('services.stripe.secret')]);
        $this->assertEquals(210, $applicationFee['amount']);
    }

    /** @test */
    public function minimum_application_charge_is_seventy_cents()
    {
        $paymentGateway = $this->getPaymentGateway();

        $paymentGateway->charge(1000, $paymentGateway->getValidTestToken(), env('STRIPE_TEST_ACCOUNT_ID'));

        $lastStripeCharge = Arr::first(\Stripe\Charge::all([
            'limit' => 1
        ], ['api_key' => env('STRIPE_TEST_ACCOUNT_TOKEN')])['data']);

        $this->assertEquals(1000, $lastStripeCharge['amount']);
        $this->assertEquals(70, $lastStripeCharge['application_fee_amount']);

        $applicationFee = \Stripe\ApplicationFee::retrieve($lastStripeCharge['application_fee'], ['api_key' => config('services.stripe.secret')]);
        $this->assertEquals(70, $applicationFee['amount']);
    }

    /** @test */
    public function application_fees_are_rounded()
    {
        $paymentGateway = $this->getPaymentGateway();

        $paymentGateway->charge(39500, $paymentGateway->getValidTestToken(), env('STRIPE_TEST_ACCOUNT_ID'));

        $lastStripeCharge = Arr::first(\Stripe\Charge::all([
            'limit' => 1
        ], ['api_key' => env('STRIPE_TEST_ACCOUNT_TOKEN')])['data']);

        $this->assertEquals(39500, $lastStripeCharge['amount']);
        $this->assertEquals(830, $lastStripeCharge['application_fee_amount']);

        $applicationFee = \Stripe\ApplicationFee::retrieve($lastStripeCharge['application_fee'], ['api_key' => config('services.stripe.secret')]);
        $this->assertEquals(830, $applicationFee['amount']);
    }

}
