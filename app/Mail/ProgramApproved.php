<?php

namespace App\Mail;

use App\Organization;
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
    public $approvingOrganization;
    public $organizationString;
    public $proposalNextSteps;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Program $program, User $user, Organization $approvingOrganization, $contributors, $proposalNextSteps)
    {
        //
        $this->program = $program;
        $this->user = $user;
        $this->approvingOrganization = $approvingOrganization;
        $this->organizationString = $this->getOrganizationString($contributors);
        $this->proposalNextSteps = $proposalNextSteps;
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

    protected function getOrganizationString($contributors){
        if ($contributors->count() == 1){
            return $contributors->first()->organization['name'];
        }
        $organizations = $contributors->map(function($contributor) {
            return $contributor->organization['name'];
        });

        $result = '';
        for ($i=0; $i<($organizations->count()-1); $i++) {
            $result .= $organizations[$i] . ', ';
        }
        return $result .= 'and ' . $organizations->last();
    }
}
