<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PriceChange extends Mailable
{
    use Queueable, SerializesModels;

    public $program;
    public $newPrice;
    public $tenant;
    public $user;
    public function __construct($program, $newPrice, $tenant, $user)
    {
        $this->program = $program;
        $this->newPrice = $newPrice;
        $this->tenant = $tenant;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('emails.price_change')
                    ->subject('[PRICE CHANGE] ' . $this->program->name . " at " . $this->program->site->name)
                    ->replyTo($this->user['email'], $this->user['name']);

        $recipients = $this->program->contributors->map(function ($contributor) {
            return $contributor->organization->emailableContacts();
        })->flatten()->unique('email');

        foreach ($recipients as $recipient) {
            $message->to($recipient['email'], $recipient['name'] ?? $recipient['email']);
        }

        return $message;
    }
}
