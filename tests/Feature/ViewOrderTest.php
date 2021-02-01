<?php

namespace Tests\Feature;

use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Program;
use App\Order;
use App\Ticket;

class ViewOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_view_their_order_confirmation()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create()->addTickets(3);

        $order = factory(Order::class)->create([
            'confirmation_number' => 'ORDERCONFIRMATION1234',
        ]);

        $ticket = factory(Ticket::class)->create([
            'program_id' => $program->id,
            'order_id' => $order->id,
        ]);

        $response = $this->get("/orders/ORDERCONFIRMATION1234");

        $response->assertStatus(200);
        
        $response->assertViewHas('order', function ($viewOrder) use ($order) {
            return $order->id === $viewOrder->id;
        });
    }

}