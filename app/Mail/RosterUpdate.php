<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RosterUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $program;
    public function __construct($program)
    {
        $this->program = $program;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('emails.roster_update')
                    ->subject("Updated roster for #{$this->program->id} - "  . $this->program->internal_name . " at " . $this->program->site->name);

        foreach ($this->program->rosterRecipients() as $recipient) {
            $message->to($recipient['email'], $recipient['name'] ?? $recipient['email']);
        }

        return $message;
    }
}
