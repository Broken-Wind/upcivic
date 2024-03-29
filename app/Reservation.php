<?php

namespace App;

class Reservation
{
    private $tickets;
    private $email;

    public function __construct($tickets, $email)
    {
        $this->tickets = $tickets;
        $this->email = $email;
    }

    public function totalCost()
    {
        return $this->tickets->sum('price');
    }

    public function tickets()
    {
        return $this->tickets;
    }

    public function email()
    {
        return $this->email;
    }

    public function complete($paymentGateway, $paymentToken, $destinationAccountId, $organizationId)
    {
        $metadata = [
            'program_id' => $this->tickets()->first()->program_id,
        ];
        $charge = $paymentGateway->charge($this->totalCost(), $paymentToken, $destinationAccountId, $metadata);

        return Order::forTickets($this->tickets(), $this->email(), $charge, $organizationId);
    }

    public function cancel()
    {
        foreach ($this->tickets as $ticket) {
            $ticket->release();
        }
    }
}
