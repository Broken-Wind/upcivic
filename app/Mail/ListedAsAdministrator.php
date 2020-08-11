<?php

namespace Upcivic\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Upcivic\Organization;
use Upcivic\Person;
use Upcivic\User;

class ListedAsAdministrator extends Mailable
{
    use Queueable, SerializesModels;

    public $lister;

    public $organization;

    public $person;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $lister, Organization $organization, Person $person)
    {
        //
        $this->lister = $lister;

        $this->organization = $organization;

        $this->person = $person;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('emails.listed_as_administrator')
                    ->subject($this->lister['name'].' listed you on '.config('app.name').'.');

        $message->to($this->person['email'], $this->person['name']);

        return $message;
    }
}
