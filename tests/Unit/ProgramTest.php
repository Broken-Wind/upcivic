<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Program;
use App\Exceptions\NotEnoughTicketsException;

class ProgramTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_order_program_tickets()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $program->addTickets(3);

        $order = $program->orderTickets('jane@example.com', 3);

        $this->assertEquals('jane@example.com', $order->email); 
        $this->assertEquals(3 , $order->ticketsQuantity()); 

    }

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
        $program->addTickets(12);
        $order = $program->orderTickets('jane@example.com', 3);

        $this->assertEquals(9 , $program->ticketsRemaining()); 
    }

    /** @test */
    function trying_to_purchase_more_tickets_than_remain_throws_an_exception()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $program->addTickets(10);

        try {
            $order = $program->orderTickets('jane@example.com', 11);
        } catch (NotEnoughTicketsException $e) {
            $this->assertFalse($program->hasOrderFor('macarie@example.com'));
            $this->assertEquals(10 , $program->ticketsRemaining()); 
            return;
        }
            
        $this->fail("Order succseeded eve though there were not enough tickets remaining.");

    }

    /** @test */
    public function cannot_order_tickets_that_have_already_been_purchased()
    {
        $program = factory(Program::class)->states('amCamp', 'published')->create();
        $program->addTickets(10);
        $order = $program->orderTickets('jane@example.com', 8);

        try {
            $order = $program->orderTickets('ilona@example.com', 3);
        } catch (NotEnoughTicketsException $e) {
            $this->assertFalse($program->hasOrderFor('ilona@example.com'));
            $this->assertEquals(2 , $program->ticketsRemaining()); 
            return;
        }
        
        $this->fail("Order succseeded eve though there were not enough tickets remaining.");
    }


}