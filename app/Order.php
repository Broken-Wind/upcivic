<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public static function forTickets($tickets, $email, $amount)
    {
        $order = self::create([
            'email' => $email,
            'amount' => $amount,
        ]);

        foreach ($tickets as $ticket) {
            $order->tickets()->save($ticket);
        }

        return $order;
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function cancel()
    {
        foreach ($this->tickets as $ticket) {
            $ticket->release();
        }

        $this->delete();
    }

    public function ticketQuantity()
    {
        return $this->tickets()->count();
    }

    public function toArray()
    {
        return [
            //'confirmation_number' => $this->confirmation_number,
            'email' => $this->email,
            'amount' => $this->amount,
            'ticket_quantity' => $this->ticketQuantity(),
            // 'tickets' => $this->tickets->map(function ($ticket) {
            //     return ['code' => $ticket->code];
            // })->all(),
        ];
    }
}
