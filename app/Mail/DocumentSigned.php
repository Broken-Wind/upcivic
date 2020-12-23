<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentSigned extends Mailable
{
    use Queueable, SerializesModels;

    public $assignment;
    public $organization;
    public $assignmentUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($assignment, $organization)
    {
        //
        $this->assignment = $assignment;
        $this->organization = $organization;
        $this->assignmentUrl = tenant()->route('tenant:admin.assignments.edit', [$this->assignment->id]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('emails.document_signed')
                    ->subject('Document from ' . $this->assignment->assignedByOrganization['name'] . ' has been signed by ' . $this->organization->name);

        $recipients = $this->assignment->assignedByOrganization->emailableContacts();
        $recipients = $recipients->merge($this->assignment->assignedToOrganization->emailableContacts())->flatten()->unique('email');

        foreach ($recipients as $recipient) {
            $message->to($recipient['email'], $recipient['name'] ?? $recipient['email']);
        }
        return $message;
    }
}
