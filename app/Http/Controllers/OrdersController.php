<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing\PaymentGateway;  
use App\Program;

class OrdersController extends Controller
{
    // 
    
    private $paymentGateway;    

    public function __construct(PaymentGateway $paymentGateway)                 
    {                                                                           
        $this->paymentGateway = $paymentGateway;                                
    }   

    public function store($programId)
    {
        $program = Program::find($programId);

        $this->validate(request(), [
            'email' => ['required', 'email'],
            'ticket_quantity' => ['required', 'integer', 'min:1'],
            'payment_token' => ['required'],
        ]);

        $this->paymentGateway->charge(request('ticket_quantity') * $program->contributors->first()->invoice_amount, request('payment_token'));
        
        $order = $program->orderTickets(request('email'), request('ticket_quantity'));

        return response()->json([], 201);
    }
}
