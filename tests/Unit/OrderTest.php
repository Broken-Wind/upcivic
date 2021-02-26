<?php
namespace Tests\Unit;

use App\Billing\Charge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Program;
use App\Order;
use App\Exceptions\NotEnoughTicketsException;
use App\Organization;
use App\Ticket;
use Mockery;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function create_an_order_from_tickets_email_and_charge()
    {
        $charge = new Charge([
            'amount' => 900,
            'card_last_four' => 4321,
            'stripe_charge_id' => 'ch_yomama'
        ]);
        $tickets = collect([
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
        ]);
        $organization = factory(Organization::class)->create();

        $order = Order::forTickets($tickets, 'jane@techsplosion.org', $charge, $organization->id);

        $this->assertEquals('jane@techsplosion.org', $order->email);
        $this->assertEquals(900, $order->amount);
        $this->assertEquals(4321, $order->card_last_four);
        $this->assertEquals($organization->id, $order->organization_id);
        $tickets->each->shouldHaveReceived('claimFor', [$order]);
    }

    /** @test */
    public function converting_to_an_array()
    {
        $order = factory(Order::class)->create([
            'confirmation_number' => 'ORDERCONFIRMATION321',
            'email' => 'john@doe.com',
            'amount' => 3212
        ]);
        $order->tickets()->saveMany([
            factory(Ticket::class)->create(['code' => 'TICKETCODE1']),
            factory(Ticket::class)->create(['code' => 'TICKETCODE2']),
            factory(Ticket::class)->create(['code' => 'TICKETCODE3']),
        ]);
        $result = $order->toArray();
        $this->assertEquals([
            'confirmation_number' => 'ORDERCONFIRMATION321',
            'email' => 'john@doe.com',
            'amount' => 3212,
            'ticket_quantity' => 3,
            'tickets' => [
                ['code' => 'TICKETCODE1'],
                ['code' => 'TICKETCODE2'],
                ['code' => 'TICKETCODE3'],
            ],
        ], $result);
    }
}
