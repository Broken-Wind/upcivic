<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BulkParticipantMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $message;
    public $sendingOrganization;
    public function __construct($subject, $message, $sendingOrganization)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->sendingOrganization = $sendingOrganization;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('emails.bulk_participant_message')
                    ->subject($this->subject);
        return $message;
    }
}
