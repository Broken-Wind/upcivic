<?php

namespace App\Mail;

use App\Organization;
use App\Person;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
