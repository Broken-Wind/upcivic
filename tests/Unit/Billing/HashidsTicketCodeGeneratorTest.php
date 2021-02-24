<?php

namespace Tests\Unit\Billing;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\HashidsTicketCodeGenerator;
use App\Ticket;


class HashidsTicketCodeGeneratorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function must_be_6_characters_long()
    {
        $generator = new HashidsTicketCodeGenerator('fake_salt_1');;

        $ticketCode = $generator->generateFor(new Ticket(['id' => 3]));

        $this->assertTrue(strlen($ticketCode) >= 6);
    }

    /** @test */
    public function can_only_contain_uppercase_letters()
    {
        $generator = new HashidsTicketCodeGenerator('fake_salt_1');;

        $ticketCode = $generator->generateFor(new Ticket(['id' => 3]));

        $this->assertRegexp('/^[A-Z]+$/', $ticketCode);
    }

    /** @test */
    public function ticket_codes_for_the_same_ticket_id_are_the_same()
    {
        $generator = new HashidsTicketCodeGenerator('fake_salt_1');;

        $ticketCode1 = $generator->generateFor(new Ticket(['id' => 2]));
        $ticketCode2 = $generator->generateFor(new Ticket(['id' => 2]));

        $this->assertEquals($ticketCode1, $ticketCode2);
    }

    /** @test */
    public function ticket_codes_must_be_unique()
    {
        $generator = new HashidsTicketCodeGenerator('fake_salt_1');;

        $ticketCodes = array_map(function ($i) use ($generator) {
            return $generator->generateFor(new Ticket(['id' =>$i]));
        }, range(1, 100));

        $this->assertCount(100, array_unique($ticketCodes));

    }

    /** @test */
    public function ticket_codes_generated_with_different_salts_are_different()
    {
        $generator1 = new HashidsTicketCodeGenerator('fake_salt_1');
        $generator2 = new HashidsTicketCodeGenerator('fake_salt_2');

        $ticketCode1 = $generator1->generateFor(new Ticket(['id' => 2]));
        $ticketCode2 = $generator2->generateFor(new Ticket(['id' => 2]));

        $this->assertNotEquals($ticketCode1, $ticketCode2);
    }

}
