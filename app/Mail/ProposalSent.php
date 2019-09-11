<?php

namespace Upcivic\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

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
                    ->subject($this->proposal['sending_organization']['name'] . ' sent you a proposal.')
                    ->replyTo($this->proposal['sender']['email'], $this->proposal['sender']['name']);

        foreach($this->proposal['recipient_organization']->tenant->users as $recipient) {

            $message->to($recipient['email'], $recipient['name']);

        }

        return $message;
    }
}
