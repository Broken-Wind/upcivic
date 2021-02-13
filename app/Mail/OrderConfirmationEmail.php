<?php

namespace App\Mail;

use App\Order;
use App\Program;
use App\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $tenant;
    public $program;
    public function __construct(Order $order, Tenant $tenant, Program $program)
    {
        $this->order = $order;
        $this->tenant = $tenant;
        $this->program = $program;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->markdown('emails.order_confirmation')
                    ->subject("You're enrolled for {$this->program->name}!");
        return $message;
    }
}
