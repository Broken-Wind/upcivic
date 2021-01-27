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
}
