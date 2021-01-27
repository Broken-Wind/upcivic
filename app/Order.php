<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

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

    public function ticketsQuantity()
    {
        return $this->tickets()->count();
    }

    public function toArray()
    {
        return [
            //'confirmation_number' => $this->confirmation_number,
            'email' => $this->email,
            'amount' => $this->amount,
            'ticket_quantity' => $this->ticketsQuantity(),
            // 'tickets' => $this->tickets->map(function ($ticket) {
            //     return ['code' => $ticket->code];
            // })->all(),
        ];
    }
}
