<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class AssignmentSent extends Mailable
{
    use Queueable, SerializesModels;

    public $assignment;
    public $sender;
    public $assignedByOrganization;
    public $assignedToOrganization;
    public $publicEditUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($assignment, $sender, $assignedByOrganization, $assignedToOrganization)
    {
        //
        $this->assignment = $assignment;
        $this->sender = $sender;
        $this->assignedByOrganization = $assignedByOrganization;
        $this->assignedToOrganization = $assignedToOrganization;
        $this->publicEditUrl = URL::signedRoute('tenant:assignments.publicEdit', ['tenant' => tenant()->slug, 'assignment' => $this->assignment]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('emails.assignment_sent')
                    ->subject('Action requested by ' . $this->assignedByOrganization['name'])
                    ->replyTo($this->sender['email'], $this->sender['name']);

        $recipients = $this->assignedToOrganization->emailableContacts()->flatten()->unique('email');

        foreach ($recipients as $recipient) {
            $message->to($recipient['email'], $recipient['name'] ?? $recipient['email']);
        }

        return $message;
    }
}
