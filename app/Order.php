<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Facades\OrderConfirmationNumber;

class Order extends Model
{
    protected $guarded = [];

    public static function forTickets($tickets, $email, $charge)
    {
        $order = self::create([
            'confirmation_number' => OrderConfirmationNumber::generate(),
            'email' => $email,
            'amount' => $charge->amount(),
            'card_last_four' => $charge->cardLastFour(),
        ]);

        $tickets->each->claimFor($order);

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

    public function ticketQuantity()
    {
        return $this->tickets()->count();
    }

    public static function findByConfirmationNumber($confirmationNumber)
    {
        return self::where('confirmation_number', $confirmationNumber)->firstOrFail();
    }

    public function attachParticipants($participants, $email, $primaryContact, $alternateContact)
    {
        $participants = array_values($participants);
        if ($this->tickets->count() != count($participants)) {
            abort(500);
        }
        $primaryContact = Person::create([
            'first_name' => $primaryContact['first_name'],
            'last_name' => $primaryContact['last_name'],
            'email' => $email,
            'phone' => $primaryContact['phone'],
        ]);
        if (!empty($alternateContact['first_name'])) {
            $alternateContact = Person::create([
                'first_name' => $alternateContact['first_name'],
                'last_name' => $alternateContact['last_name'],
                'phone' => $alternateContact['phone'],
            ]);
        }
        $this->tickets->each(function ($ticket, $key) use ($participants, $primaryContact, $alternateContact) {
            $participant = $participants[$key];
            $participant = Participant::create([
                'first_name' => $participant['first_name'],
                'last_name' => $participant['last_name'],
                'birthday' => $participant['birthday'],
                'needs' => $participant['needs']
            ]);
            $participant->contacts()->attach($primaryContact, ['type' => 'primary']);
            if (!empty($alternateContact->id)) {
                $participant->contacts()->attach($alternateContact, ['type' => 'alternate']);
            }
            $participant->tickets()->save($ticket);
        });
    }

    public function toArray()
    {
        return [
            'confirmation_number' => $this->confirmation_number,
            'email' => $this->email,
            'amount' => $this->amount,
            'ticket_quantity' => $this->ticketQuantity(),
            'tickets' => $this->tickets->map(function ($ticket) {
                return ['code' => $ticket->code];
            })->all(),
        ];
    }
}
