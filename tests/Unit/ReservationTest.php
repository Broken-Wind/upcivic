<?php

namespace Tests\Unit;

use Mockery;
use App\Ticket;
use App\Program;
use App\Reservation;
use Tests\TestCase;
use App\Billing\FakePaymentGateway;
use App\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function calculating_the_total_cost()
    {
        $tickets = collect([
            (object) ['price' => 13000],
            (object) ['price' => 13000],
            (object) ['price' => 13000],
        ]);

        $reservation = new Reservation($tickets, 'remus@example.com');

        $this->assertEquals(39000, $reservation->totalCost());
    }

    /** @test */
    function retrieving_the_customers_email()
    {
        $reservation = new Reservation(collect(), 'remus@example.com');

        $this->assertEquals('remus@example.com', $reservation->email());
    }

    /** @test */
    function retrieving_the_reservations_tickets()
    {
        $tickets = collect([
            (object) ['price' => 13000],
            (object) ['price' => 13000],
            (object) ['price' => 13000],
        ]);

        $reservation = new Reservation($tickets, 'remus@example.com');

        $this->assertEquals($tickets, $reservation->tickets());
    }

    /** @test */
    function reserved_tickets_are_released_when_a_reservation_is_cancelled()
    {
        $tickets = collect([
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
        ]);

        $reservation = new Reservation($tickets, 'remus@example.com');

        $reservation->cancel();

        foreach ($tickets as $ticket) {
            $ticket->shouldHaveReceived('release');
        }
    }

    /** @test */
    function completing_a_reservation()
    {
        $program = factory(Program::class)->states('amCamp')->create();
        $tickets = factory(Ticket::class, 3)->create(['program_id' => $program->id]);
        $organization = factory(Organization::class)->create();
        $reservation = new Reservation($tickets, 'john@example.com');
        $paymentGateway = new FakePaymentGateway;

        $order = $reservation->complete($paymentGateway, $paymentGateway->getValidTestToken(), 'test_acct_1234', $organization->id);

        $this->assertEquals('john@example.com', $order->email);
        $this->assertEquals(3, $order->ticketQuantity());
        $this->assertEquals(9900, $order->amount);
        $this->assertEquals(9900, $paymentGateway->totalChargesFor('test_acct_1234'));
    }
}
