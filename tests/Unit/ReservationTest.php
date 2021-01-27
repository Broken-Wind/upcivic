<?php

namespace Tests\Unit;

use Mockery;
use App\Ticket;
use App\Program;
use App\Reservation;
use Tests\TestCase;
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

        $reservation = new Reservation($tickets);

        $this->assertEquals(39000, $reservation->totalCost());
    }

    /** @test */
    function reserved_tickets_are_released_when_a_reservation_is_cancelled()
    {
        $tickets = collect([
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
        ]);

        $reservation = new Reservation($tickets, 'john@example.com');

        $reservation->cancel();

        foreach ($tickets as $ticket) {
            $ticket->shouldHaveReceived('release');
        }
    }
}
