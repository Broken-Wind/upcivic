<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Program;
use App\Ticket;
use App\Order;
use App\Exceptions\NotEnoughTicketsException;

class ProgramTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_add_program_tickets() 
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $program->addTickets(50);
        $this->assertEquals(50 , $program->ticketsRemaining()); 

    }

    /** @test */
    function tickets_remaining_does_not_include_tickets_associated_with_an_order()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $program->tickets()->saveMany(factory(Ticket::class, 3)->create(['order_id' => 1]));
        $program->tickets()->saveMany(factory(Ticket::class, 2)->create(['order_id' => null]));

        $this->assertEquals(2, $program->ticketsRemaining());
    }

    /** @test */
    function trying_to_reserve_more_tickets_than_remain_throws_an_exception()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $program->addTickets(10);

        try {
            $reservation = $program->reserveTickets(11, 'dumitru@example.com');
        } catch (NotEnoughTicketsException $e) {
            $this->assertFalse($program->hasOrderFor('macarie@example.com'));
            $this->assertEquals(10 , $program->ticketsRemaining()); 
            return;
        }
            
        $this->fail("Order succseeded even though there were not enough tickets remaining.");

    }

    /** @test */
    function cannot_reserve_tickets_that_have_already_been_reserved()
    {
         $program = factory(Program::class)->create()->addTickets(3);

         $order = $program->reserveTickets(2, 'ilona@example.com');

         try {
             $program->reserveTickets(2, 'jane@example.com');
         } catch (NotEnoughTicketsException $e) {
             $this->assertEquals(1, $program->ticketsRemaining());
             return;
         }

         $this->fail("Reserving tickets succeeded even though the tickets were already reserved.");
    }

    /** @test */
    function cannot_reserve_tickets_that_have_already_been_purchased()
    {
        $program = factory(Program::class)->create()->addTickets(3);
        $order = factory(Order::class)->create();
        $order->tickets()->saveMany($program->tickets->take(2));

        try {
            $program->reserveTickets(2, 'john@example.com');
        } catch (NotEnoughTicketsException $e) {
            $this->assertEquals(1, $program->ticketsRemaining());
            return;
        }

        $this->fail("Reserving tickets succeeded even though the tickets were already sold.");
    }

    /** @test */
    function can_reserve_available_tickets()
    {
        $program = factory(Program::class)->create()->addTickets(3);
        $this->assertEquals(3, $program->ticketsRemaining());

        $reservation = $program->reserveTickets(2, 'dumitru@example.com');

        $this->assertCount(2, $reservation->tickets());
        $this->assertEquals('dumitru@example.com', $reservation->email());
        $this->assertEquals(1, $program->ticketsRemaining());
    }


}