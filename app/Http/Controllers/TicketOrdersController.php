<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing\PaymentGateway;  
use App\Billing\PaymentFailedException;  
use App\Program;

class TicketOrdersController extends Controller
{
    // 
    
    private $paymentGateway;    

    public function __construct(PaymentGateway $paymentGateway)                 
    {                                                                           
        $this->paymentGateway = $paymentGateway;                                
    }   

    public function store($programId)
    {
        $this->validate(request(), [
            'email' => ['required', 'email'],
            'ticket_quantity' => ['required', 'integer', 'min:1'],
            'payment_token' => ['required'],
        ]);

        try {
            $program = Program::publishedForTenant()->findOrFail($programId);

            $this->paymentGateway->charge(request('ticket_quantity') * $program->contributors->first()->invoice_amount, request('payment_token'));
        
            $order = $program->orderTickets(request('email'), request('ticket_quantity'));
        } catch (PaymentFailedException $e) {

            return response()->json([], 422);

        }

        return response()->json([], 201);
    }
}
