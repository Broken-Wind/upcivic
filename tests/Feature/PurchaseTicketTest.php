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
use App\Contributor;
use App\Facades\OrderConfirmationNumber;
use App\Facades\TicketCode;
use App\Mail\OrderConfirmationEmail;
use Illuminate\Http\Request;
use Mockery;

class PurchaseTicketTest extends TestCase
{
    use RefreshDatabase;

    public function validParams($overrides = [])
    {
        return array_merge([
            'stripeEmail' => 'personA@example.com',
            'ticket_quantity' => 3,
            'stripeToken' => $this->paymentGateway->getValidTestToken(),
            'participants' => [
                [
                    'first_name' => 'Lefty',
                    'last_name' => 'Hefty',
                    'birthday' => Carbon::parse('-2 years')->format('Y-m-d'),
                    'needs' => 'Really cool pants.',
                ],
                [
                    'first_name' => 'Lucy',
                    'last_name' => 'Hucy',
                    'birthday' => Carbon::parse('-9 years')->format('Y-m-d'),
                    'needs' => '18 dandelions.',
                ],
                [
                    'first_name' => 'Testy',
                    'last_name' => 'Hesty',
                    'birthday' => Carbon::parse('-15 years')->format('Y-m-d'),
                    'needs' => 'Better parenting.',
                ],
            ],
            'primary_contact' => [
                'first_name' => 'John',
                'last_name' => 'Smithenstein',
                'phone' => '2342342345',
                'alternate_phone' => '222.222.2222',
            ],
            'alternate_contact' => [
                'first_name' => 'Slon',
                'last_name' => 'Jonenstein',
                'phone' => '666-234-4213',
                'alternate_phone' => '555.333.6666',
            ],
        ], $overrides);
    }

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
        $response = $this->response = $this->followingRedirects()->postJson($ordersPath, $params);

        $this->app['request'] = $savedRequest;

        return $response;
    }

    /** @test */
    public function can_purchase_tickets_for_a_published_program()
    {
        $this->withoutExceptionHandling();
        Mail::fake();
        OrderConfirmationNumber::shouldReceive('generate')->andReturn('ORDERCONFIRMATION1234');
        TicketCode::shouldReceive('generateFor')->andReturn('TICKETCODE1', 'TICKETCODE2', 'TICKETCODE3');
        $program = factory(Program::class)->states('amCamp', 'published')->create(['price' => 9900])->addTickets(3);
        $tenant = $program->contributors->first()->organization->tenant;
        $tenant->stripe_account_id = 'testly_acct_id';
        $tenant->save();
        $tenantSlug = $tenant->slug;

        $response = $this->orderTickets($program, $this->validParams());

        $response->assertStatus(200);
        $this->assertEquals(url()->current(), config('app.url')."/{$tenantSlug}/programs/{$program->id}/orders/ORDERCONFIRMATION1234");
        // $response->assertSeeText('macarie@example.com');
        $response->assertSeeText('ORDERCONFIRMATION1234');
        $response->assertSeeText('$297.00');
        $response->assertSeeText('TICKETCODE1');
        $response->assertSeeText('TICKETCODE2');
        $response->assertSeeText('TICKETCODE3');
        $this->assertEquals(29700, $this->paymentGateway->totalChargesFor('testly_acct_id'));
        $this->assertTrue($program->hasOrderFor('personA@example.com'));
        $order = $program->ordersFor('personA@example.com')->first();
        $this->assertEquals(3, $order->ticketQuantity());
        $this->assertEquals(['Lefty', 'Lucy', 'Testy'], $program->participants->pluck('first_name')->toArray());

        Mail::assertSent(OrderConfirmationEmail::class, function ($mail) use ($order) {
            return $mail->hasTo($order->email)
            && $mail->order->id == $order->id;
        });
    }
    /** @test */
    public function cannot_purchase_tickets_when_no_internal_registration()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create()->addTickets(3);
        $program->contributors->first()->update([
            'internal_registration' => false
        ]);

        $response = $this->orderTickets($program, $this->validParams());

        $response->assertStatus(401);
        $this->assertCount(0, $program->participants);
    }

    /** @test */
    public function charges_paid_to_correct_tenant_when_multiple()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create()->addTickets(3);
        $tenant = $program->contributors->first()->organization->tenant;
        $tenant->stripe_account_id = 'testly_acct_id';
        $tenant->save();
        factory(Contributor::class)->states(['hasTenant'])->create([
            'program_id' => $program->id,
            'internal_registration' => true,
        ])->organization->tenant->update([
            'stripe_account_id' => 'test_acct_2_id',
        ]);

        $response = $this->orderTickets($program, $this->validParams());
        $response->assertStatus(200);
        $this->assertEquals(9900, $this->paymentGateway->totalChargesFor('testly_acct_id'));
        $this->assertEquals(0, $this->paymentGateway->totalChargesFor('test_acct_2_id'));
        $order = $program->ordersFor('personA@example.com')->first();
        $this->assertEquals(3, $order->ticketQuantity());
        $this->assertCount(3, $program->participants);
    }

    /** @test */
    public function can_purchase_tickets_from_allowing_tenant()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create()->addTickets(3);
        $tenant = $program->contributors->first()->organization->tenant;
        $tenant->stripe_account_id = 'testly_acct_id';
        $tenant->save();
        factory(Contributor::class)->states(['hasTenant'])->create([
            'program_id' => $program->id,
            'internal_registration' => false,
        ])->organization->tenant->update([
            'stripe_account_id' => 'test_acct_2_id',
        ]);

        $response = $this->orderTickets($program, $this->validParams());
        $response->assertStatus(200);
        $this->assertEquals(9900, $this->paymentGateway->totalChargesFor('testly_acct_id'));
        $this->assertEquals(0, $this->paymentGateway->totalChargesFor('test_acct_2_id'));
        $order = $program->ordersFor('personA@example.com')->first();
        $this->assertEquals(3, $order->ticketQuantity());
        $this->assertCount(3, $program->participants);
    }

    /** @test */
    public function cannot_purchase_tickets_from_disallowing_tenant()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create()->addTickets(3);
        $contributor = $program->contributors->first();
        $contributor->update(['internal_registration' => false]);
        $tenant = $contributor->organization->tenant;
        $tenant->stripe_account_id = 'testly_acct_id';
        $tenant->save();
        factory(Contributor::class)->states(['hasTenant'])->create([
            'program_id' => $program->id,
            'internal_registration' => true,
        ])->organization->tenant->update([
            'stripe_account_id' => 'test_acct_2_id',
        ]);

        $response = $this->orderTickets($program, $this->validParams());

        $response->assertStatus(401);
        $this->assertCount(0, $program->participants);
        $this->assertEquals(0, $this->paymentGateway->totalChargesFor('testly_acct_id'));
        $this->assertEquals(0, $this->paymentGateway->totalChargesFor('test_acct_2_id'));
        $order = $program->ordersFor('personA@example.com')->first();
        $this->assertNull($order);
        $this->assertCount(0, $program->participants);
    }

    /** @test */
    function cannot_purchase_tickets_another_customer_is_already_trying_to_purchase()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create()->addTickets(3);

        $this->paymentGateway->beforeFirstCharge(function ($paymentGateway) use ($program) {
            $response = $this->orderTickets($program, $this->validParams([
                'stripeEmail' => 'cool@mc.dude',
                'ticket_quantity' => 1
            ]));

            $response->assertStatus(422);
            $this->assertFalse($program->hasOrderFor('personB@example.com'));
            $this->assertEquals(0, $this->paymentGateway->totalCharges());
        });

        $this->orderTickets($program, $this->validParams());

        $this->assertEquals(9900, $this->paymentGateway->totalCharges());
        $this->assertTrue($program->hasOrderFor('personA@example.com'));
        $this->assertEquals(3, $program->ordersFor('personA@example.com')->first()->ticketQuantity());
    }

    /** @test */
    function email_is_required_to_purchase_tickets()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $params = $this->validParams();
        unset($params['stripeEmail']);
        $response = $this->postJson($this->ordersUrlPath($program), $params);

        $response->assertStatus(422);
        $this->assertArrayHasKey('stripeEmail', $response->decodeResponseJson()['errors']);

    }

    /** @test */
    function primary_contact_required()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $params = $this->validParams();
        unset($params['primary_contact']);
        $response = $this->postJson($this->ordersUrlPath($program), $params);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('primary_contact');
    }

    /** @test */
    function primary_contact_first_name_required()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $params = $this->validParams();
        unset($params['primary_contact']['first_name']);
        $response = $this->postJson($this->ordersUrlPath($program), $params);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('primary_contact.first_name');
    }

    /** @test */
    function primary_contact_last_name_required()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $params = $this->validParams();
        unset($params['primary_contact']['last_name']);
        $response = $this->postJson($this->ordersUrlPath($program), $params);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('primary_contact.last_name');
    }

    /** @test */
    function primary_contact_phone_required()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $params = $this->validParams();
        unset($params['primary_contact']['phone']);
        $response = $this->postJson($this->ordersUrlPath($program), $params);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('primary_contact.phone');
    }

    /** @test */
    function participants_array_required()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $params = $this->validParams();
        unset($params['participants']);
        $response = $this->postJson($this->ordersUrlPath($program), $params);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('participants');
    }

    /** @test */
    function participant_required()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $params = $this->validParams();
        $params['participants'] = [];
        $response = $this->postJson($this->ordersUrlPath($program), $params);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('participants');
    }

    /** @test */
    function participant_first_name_required()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $params = $this->validParams();
        unset($params['participants'][1]['first_name']);

        $response = $this->postJson($this->ordersUrlPath($program), $params);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('participants.1.first_name');
    }

    /** @test */
    function participant_last_name_required()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $params = $this->validParams();
        unset($params['participants'][1]['last_name']);

        $response = $this->postJson($this->ordersUrlPath($program), $params);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('participants.1.last_name');
    }

    /** @test */
    function participant_birthday_required()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $params = $this->validParams();
        unset($params['participants'][1]['birthday']);

        $response = $this->postJson($this->ordersUrlPath($program), $params);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('participants.1.birthday');
    }

    /** @test */
    function email_must_be_valid_to_purchase_tickets()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->ordersUrlPath($program), $this->validParams(['stripeEmail' => 'not-an-email-address']));

        $response->assertStatus(422);
        $this->assertArrayHasKey('stripeEmail', $response->decodeResponseJson()['errors']);
    }

    /** @test */
    function ticket_quantity_is_required_to_purchase_rerigstrations()
    {
        $this->app->instance(PaymentGateway::class, $this->paymentGateway);
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $params = $this->validParams();
        unset($params['ticket_quantity']);

        $response = $this->postJson($this->ordersUrlPath($program), $params);

        $response->assertStatus(422);
        $this->assertArrayHasKey('ticket_quantity', $response->decodeResponseJson()['errors']);
    }

    /** @test */
    function ticket_quantity_must_be_at_least_1_to_purchase_tickets()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $response = $this->postJson($this->ordersUrlPath($program), $this->validParams(['ticket_quantity' => 0]));

        $response->assertStatus(422);
        $this->assertArrayHasKey('ticket_quantity', $response->decodeResponseJson()['errors']);

    }

    /** @test */
    function payment_token_is_required()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $params = $this->validParams();
        unset($params['stripeToken']);

        $response = $this->postJson($this->ordersUrlPath($program), $params);

        $response->assertStatus(422);
        $this->assertArrayHasKey('stripeToken', $response->decodeResponseJson()['errors']);

    }

    /** @test */
    function an_order_is_not_created_if_payment_fails()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $program->addTickets(3);

        $response = $this->followingRedirects()->postJson($this->ordersUrlPath($program), $this->validParams([
            'stripeToken' => 'invalid-payment-token',
        ]));

        $response->assertStatus(422);
        $this->assertFalse($program->hasOrderFor('macarie@example.com'));
        $this->assertEquals(3, $program->ticketsRemaining());

    }

    /** @test */
    function cannot_purchase_tickets_for_an_unpublished_program()
    {
        $program = factory(Program::class)->states('amCamp', 'unpublished')->create();
        $program->addTickets(3);

        $response = $this->postJson($this->ordersUrlPath($program), $this->validParams());

        $response->assertStatus(404);
        $this->assertEquals(0, $program->orders()->count());
        $this->assertEquals(0, $this->paymentGateway->totalCharges());
    }

    /** @test */
    function cannot_purchase_more_tickets_than_remain()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $program->addTickets(50);

        $response = $this->postJson($this->ordersUrlPath($program), $this->validParams([
            'ticket_quantity' => 51
        ]));

        $response->assertStatus(422);
        $this->assertFalse($program->hasOrderFor('macarie@example.com'));

        $this->assertEquals(0, $this->paymentGateway->totalCharges());
        $this->assertEquals(50, $program->ticketsRemaining());
    }
}
