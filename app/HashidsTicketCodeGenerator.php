<?php

namespace App;

use Hashids\Hashids;

use App\Ticket;

class HashidsTicketCodeGenerator implements TicketCodeGenerator
{
    private $generator;

    public function __construct($salt)
    {
        $pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $this->generator = new Hashids($salt, 6, $pool);
    }

    public function generateFor($ticket)
    {
        $pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $this->generator->encode($ticket->id);
    }

}

