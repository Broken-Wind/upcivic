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

    public function ordersUrlPath($program) {
        $path = 'iframe';

        $tenantSlug = $program->contributors->first()->organization->tenant->slug;
        return "{$tenantSlug}/{$path}/{$program->id}/orders";
    }

    private function orderTickets($program, $params)
    {
        $savedRequest = $this->app['request'];

        $ordersPath = $this->ordersUrlPath($program);
        $response = $this->response = $this->postJson($ordersPath, $params);

        $this->app['request'] = $savedRequest;

        return $response;
    }

    /** @test */
    public function can_purchase_tickets_for_a_published_program()
    {

        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'published')->create()->addTickets(3);

        $response = $this->postJson($this->ordersUrlPath($program), [
            'email' => 'macarie@example.com',
            'ticket_quantity' => 3,
            'payment_token' => $paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'email' => 'macarie@example.com',
            'ticket_quantity' => 3,
            'amount' => 39000,
        ]);

        $this->assertEquals(39000, $paymentGateway->totalCharges());

        $this->assertTrue($program->hasOrderFor('macarie@example.com'));
        $this->assertEquals(3, $program->ordersFor('macarie@example.com')->first()->ticketQuantity());
    }
    
    /** @test */
    function cannot_purchase_tickets_another_customer_is_already_trying_to_purchase()
    {
        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway);

        $program = factory(Program::class)->states('amCamp', 'published')->create()->addTickets(3);

        $paymentGateway->beforeFirstCharge(function ($paymentGateway) use ($program) {
            $response = $this->orderTickets($program, [
                'email' => 'personB@example.com',
                'ticket_quantity' => 1,
                'payment_token' => $paymentGateway->getValidTestToken(),
            ]);

            $response->assertStatus(422);
            $this->assertFalse($program->hasOrderFor('personB@example.com'));
            $this->assertEquals(0, $paymentGateway->totalCharges());
        });

        $this->orderTickets($program, [
            'email' => 'personA@example.com',
            'ticket_quantity' => 3,
            'payment_token' => $paymentGateway->getValidTestToken(),
        ]);

        $this->assertEquals(39000, $paymentGateway->totalCharges());
        $this->assertTrue($program->hasOrderFor('personA@example.com'));
        $this->assertEquals(3, $program->ordersFor('personA@example.com')->first()->ticketQuantity());
    }

    /** @test */
    function email_is_required_to_purchase_tickets()
    {
        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->ordersUrlPath($program), [
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

        $response = $this->postJson($this->ordersUrlPath($program), [
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

        $response = $this->postJson($this->ordersUrlPath($program), [
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

        $response = $this->postJson($this->ordersUrlPath($program), [
            'email' => 'macarie@example.com',
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

        $response = $this->postJson($this->ordersUrlPath($program), [
            'email' => 'macarie@example.com',
            'ticket_quantity' => 0,
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('payment_token', $response->decodeResponseJson()['errors']); 

    }

    /** @test */
    function an_order_is_not_created_if_payment_fails()
    {
        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $program->addTickets(3);

        $response = $this->postJson($this->ordersUrlPath($program), [
            'email' => 'macarie@example.com',
            'ticket_quantity' => 3,
            'payment_token' => 'invalid-payment-token',
        ]);

        $response->assertStatus(422);
        $this->assertFalse($program->hasOrderFor('macarie@example.com'));
        $this->assertEquals(3, $program->ticketsRemaining());

    }

    /** @test */
    function cannot_purchase_tickets_for_an_unpublished_program()
    {
        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'unpublished')->create();
        $program->addTickets(3);

        $response = $this->postJson($this->ordersUrlPath($program), [
            'email' => 'macarie@example.com',
            'ticket_quantity' => 3,
            'payment_token' => $paymentGateway->getValidTestToken()
        ]);

        $response->assertStatus(404);
        $this->assertEquals(0, $program->orders()->count());
        $this->assertEquals(0, $paymentGateway->totalCharges());
    }

    /** @test */
    function cannot_purchase_more_tickets_than_remain() 
    {
        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'published')->create(); 
        $program->addTickets(50);

        $response = $this->postJson($this->ordersUrlPath($program), [
            'email' => 'macarie@example.com',
            'ticket_quantity' => 51,
            'payment_token' => $paymentGateway->getValidTestToken()
        ]);

        $response->assertStatus(422);
        $this->assertFalse($program->hasOrderFor('macarie@example.com'));

        $this->assertEquals(0, $paymentGateway->totalCharges());
        $this->assertEquals(50, $program->ticketsRemaining());

    }
}