<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Program;
use App\Billing\FakePaymentGateway;
use App\Billing\PaymentGateway;

class PurchaseTicketTest extends TestCase
{
    use RefreshDatabase;

    public function purchase_ticket_path($program) {
        $path = 'iframe';

        $tenantSlug = $program->contributors->first()->organization->tenant->slug;
        return "{$tenantSlug}/{$path}/{$program->id}/orders";
    }

    /** @test */
    public function user_can_purchase_tickets()
    {

        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->purchase_ticket_path($program), [
            'email' => 'macarie@example.com',
            'ticket_quantity' => 3,
            'payment_token' => $paymentGateway->getValidTestToken(),
        ]);

        

        $response->assertStatus(201);

        // Make sure the customer was charged the right amount
        $this->assertEquals(39000, $paymentGateway->totalCharges());

        // Make sure that an order exists for this customer 
        $order = $program->orders()->where('email', 'macarie@example.com')->first();

        $this->assertNotNull($order);
        
        $this->assertEquals(3, $order->tickets()->count());
    }

    /** @test */
    function email_is_required_to_purchase_tickets()
    {
        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->purchase_ticket_path($program), [
            'ticket_quantity' => 3,
            'payment_token' => $paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('email', $response->decodeResponseJson()['errors']);

    }

    /** @test */
    function email_must_be_valid_to_purchase_rerigstrations()
    {

        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->purchase_ticket_path($program), [
            'email' => 'not-an-email-address',
            'ticket_quantity' => 3,
            'payment_token' => $paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('email', $response->decodeResponseJson()['errors']);
    }


    /** @test */
    function ticket_quantity_is_required_to_purchase_rerigstrations()
    {

        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->purchase_ticket_path($program), [
            'email' => 'not-an-email-address',
            'payment_token' => $paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('ticket_quantity', $response->decodeResponseJson()['errors']);
    }


    /** @test */
    function ticket_quantity_must_be_at_least_1_to_purchase_tickets()
    {
        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->purchase_ticket_path($program), [
            'email' => 'not-an-email-address',
            'ticket_quantity' => 0,
            'payment_token' => $paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('ticket_quantity', $response->decodeResponseJson()['errors']); 

    }

    /** @test */
    function payment_token_is_required()
    {
        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->purchase_ticket_path($program), [
            'email' => 'not-an-email-address',
            'ticket_quantity' => 0,
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('payment_token', $response->decodeResponseJson()['errors']); 

    }
}