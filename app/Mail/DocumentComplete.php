<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentComplete extends Mailable
{
    use Queueable, SerializesModels;

    public $assignment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($assignment)
    {
        //
        $this->assignment = $assignment;}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('emails.document_complete')
                    ->subject('Document from ' . $this->assignment->assignedByOrganization['name'] . ' has been fully signed.');

        $recipients = $this->assignment->assignedToOrganization->emailableContacts();
        $recipients = $recipients->merge($this->assignment->assignedByOrganization->emailableContacts())->flatten()->unique('email');

        foreach ($recipients as $recipient) {
            $message->to($recipient['email'], $recipient['name'] ?? $recipient['email']);
        }
        return $message;
    }
}
