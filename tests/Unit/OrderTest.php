<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Program;
use App\Order;
use App\Exceptions\NotEnoughTicketsException;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function tickets_are_released_when_an_order_is_cancelled()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $program->addTickets(10);
        $order = $program->orderTickets('jane@example.com', 5);
        $this->assertEquals(5, $program->ticketsRemaining());

        $order->cancel();

        $this->assertEquals(10, $program->ticketsRemaining());

        $this->assertNull(Order::find($order->id)); 
    }

}