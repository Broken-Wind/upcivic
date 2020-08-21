<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalSent extends Mailable
{
    use Queueable, SerializesModels;

    public $proposal;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($proposal)
    {
        //
        $this->proposal = $proposal;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('emails.proposal_sent')
                    ->subject($this->proposal['sending_organization']['name'].' sent you a proposal.')
                    ->replyTo($this->proposal['sender']['email'], $this->proposal['sender']['name']);

        $arrayOfCcEmails = array_filter(array_map(function ($email) {
            if ($email !== null) {
                return ['email' => $email, 'name' => null];
            }
        }, $this->proposal['cc_emails'] ?? []));

        $recipients = $this->proposal['recipient_organizations']->map(function ($organization) {
            return $organization->emailableContacts();
        })->concat($arrayOfCcEmails)->flatten()->unique('email');

        foreach ($recipients as $recipient) {
            $message->to($recipient['email'], $recipient['name'] ?? $recipient['email']);
        }

        return $message;
    }
}
