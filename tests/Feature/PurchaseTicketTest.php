<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Program;
use App\OrderConfirmationNumberGenerator;
use App\Billing\FakePaymentGateway;
use App\Billing\PaymentGateway;

class PurchaseTicketTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $this->paymentGateway);
    }

    public function ordersUrlPath($program) {
        $path = 'programs';

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
        //$orderConfirmationNumberGenerator->generate();

        $program = factory(Program::class)->states('amCamp', 'published')->create()->addTickets(3);

        $response = $this->postJson($this->ordersUrlPath($program), [
            'stripeEmail' => 'macarie@example.com',
            'ticket_quantity' => 3,
            'stripeToken' => $this->paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'email' => 'macarie@example.com',
            'ticket_quantity' => 3,
            'amount' => 39000,
        ]);

        $this->assertEquals(39000, $this->paymentGateway->totalCharges());

        $this->assertTrue($program->hasOrderFor('macarie@example.com'));
        $this->assertEquals(3, $program->ordersFor('macarie@example.com')->first()->ticketQuantity());
    }
    
    /** @test */
    function cannot_purchase_tickets_another_customer_is_already_trying_to_purchase()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create()->addTickets(3);

        $this->paymentGateway->beforeFirstCharge(function ($paymentGateway) use ($program) {
            $response = $this->orderTickets($program, [
                'stripeEmail' => 'personB@example.com',
                'ticket_quantity' => 1,
                'stripeToken' => $this->paymentGateway->getValidTestToken(),
            ]);

            $response->assertStatus(422);
            $this->assertFalse($program->hasOrderFor('personB@example.com'));
            $this->assertEquals(0, $this->paymentGateway->totalCharges());
        });

        $this->orderTickets($program, [
            'stripeEmail' => 'personA@example.com',
            'ticket_quantity' => 3,
            'stripeToken' => $this->paymentGateway->getValidTestToken(),
        ]);

        $this->assertEquals(39000, $this->paymentGateway->totalCharges());
        $this->assertTrue($program->hasOrderFor('personA@example.com'));
        $this->assertEquals(3, $program->ordersFor('personA@example.com')->first()->ticketQuantity());
    }

    /** @test */
    function email_is_required_to_purchase_tickets()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->ordersUrlPath($program), [
            'ticket_quantity' => 3,
            'stripeToken' => $this->paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('stripeEmail', $response->decodeResponseJson()['errors']);

    }

    /** @test */
    function email_must_be_valid_to_purchase_rerigstrations()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->ordersUrlPath($program), [
            'stripeEmail' => 'not-an-email-address',
            'ticket_quantity' => 3,
            'stripeToken' => $this->paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('stripeEmail', $response->decodeResponseJson()['errors']);
    }

    /** @test */
    function ticket_quantity_is_required_to_purchase_rerigstrations()
    {
        $this->app->instance(PaymentGateway::class, $this->paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->ordersUrlPath($program), [
            'stripeEmail' => 'not-an-email-address',
            'stripeToken' => $this->paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('ticket_quantity', $response->decodeResponseJson()['errors']);
    }

    /** @test */
    function ticket_quantity_must_be_at_least_1_to_purchase_tickets()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->ordersUrlPath($program), [
            'stripeEmail' => 'macarie@example.com',
            'ticket_quantity' => 0,
            'stripeToken' => $this->paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('ticket_quantity', $response->decodeResponseJson()['errors']); 

    }

    /** @test */
    function payment_token_is_required()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->ordersUrlPath($program), [
            'stripeEmail' => 'macarie@example.com',
            'ticket_quantity' => 0,
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('stripeToken', $response->decodeResponseJson()['errors']); 

    }

    /** @test */
    function an_order_is_not_created_if_payment_fails()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $program->addTickets(3);

        $response = $this->postJson($this->ordersUrlPath($program), [
            'stripeEmail' => 'macarie@example.com',
            'ticket_quantity' => 3,
            'stripeToken' => 'invalid-payment-token',
        ]);

        $response->assertStatus(422);
        $this->assertFalse($program->hasOrderFor('macarie@example.com'));
        $this->assertEquals(3, $program->ticketsRemaining());

    }

    /** @test */
    function cannot_purchase_tickets_for_an_unpublished_program()
    {
        $program = factory(Program::class)->states('amCamp', 'unpublished')->create();
        $program->addTickets(3);

        $response = $this->postJson($this->ordersUrlPath($program), [
            'stripeEmail' => 'macarie@example.com',
            'ticket_quantity' => 3,
            'stripeToken' => $this->paymentGateway->getValidTestToken()
        ]);

        $response->assertStatus(404);
        $this->assertEquals(0, $program->orders()->count());
        $this->assertEquals(0, $this->paymentGateway->totalCharges());
    }

    /** @test */
    function cannot_purchase_more_tickets_than_remain() 
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create(); 
        $program->addTickets(50);

        $response = $this->postJson($this->ordersUrlPath($program), [
            'stripeEmail' => 'macarie@example.com',
            'ticket_quantity' => 51,
            'stripeToken' => $this->paymentGateway->getValidTestToken()
        ]);

        $response->assertStatus(422);
        $this->assertFalse($program->hasOrderFor('macarie@example.com'));

        $this->assertEquals(0, $this->paymentGateway->totalCharges());
        $this->assertEquals(50, $program->ticketsRemaining());

    }
}