<?php

namespace Tests\Unit;

use App\Order;
use App\Ticket;
use App\Program;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_ticket_can_be_released()
    {
        $program = factory(Program::class)->create();
        $program->addTickets(3);
        $order = $program->orderTickets('jane@example.com', 3);
        $ticket = $order->tickets()->first();
        $this->assertEquals($order->id, $ticket->order_id);

        $ticket->release();

        $this->assertNull($ticket->fresh()->order_id);
    }

}
