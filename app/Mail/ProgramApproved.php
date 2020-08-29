<?php

namespace App\Mail;

use App\Program;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProgramApproved extends Mailable
{
    use Queueable, SerializesModels;
    public $program;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Program $program, User $user)
    {
        //
        $this->program = $program;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('emails.program_approved')
                    ->subject('[APPROVED] ' . $this->program->internal_name . " at " . $this->program->site->name)
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
