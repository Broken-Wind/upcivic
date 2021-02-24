<?php

namespace App\Mail;

use App\Program;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProgramCanceledContributors extends Mailable
{
    use Queueable, SerializesModels;
    public $program;
    public $message;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Program $program, $message, User $user)
    {
        //
        $this->program = $program;
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('emails.program_canceled_contributors')
                    ->subject('[CANCELED] ' . $this->program->internal_name . " at " . $this->program->site->name)
                    ->replyTo($this->user['email'], $this->user['name']);

        return $message;
    }
}
