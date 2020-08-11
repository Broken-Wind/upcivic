<?php

namespace App\Mail;

use App\Tenant;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRequestsInviteToTenant extends Mailable
{
    use Queueable, SerializesModels;

    public $requestor;

    public $tenant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $requestor, Tenant $tenant)
    {
        //
        $this->requestor = $requestor;

        $this->tenant = $tenant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('emails.user_requests_invite_to_tenant')
                    ->subject($this->requestor['name'].' requests access to '.$this->tenant['name'].'.')
                    ->replyTo($this->requestor['email'], $this->requestor['name']);

        foreach ($this->tenant->organization->emailableContacts() as $recipient) {
            $message->to($recipient['email'], $recipient['name']);
        }

        return $message;
    }
}
