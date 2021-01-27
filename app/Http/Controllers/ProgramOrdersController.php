<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing\PaymentGateway;  
use App\Billing\PaymentFailedException;  
use App\Program;
use App\Exceptions\NotEnoughTicketsException;

class ProgramOrdersController extends Controller
{
    // 
    
    private $paymentGateway;    

    public function __construct(PaymentGateway $paymentGateway)                 
    {                                                                           
        $this->paymentGateway = $paymentGateway;                                
    }   

    public function store($programId)
    {
        $program = Program::publishedForTenant()->findOrFail($programId);

        $this->validate(request(), [
            'email' => ['required', 'email'],
            'ticket_quantity' => ['required', 'integer', 'min:1'],
            'payment_token' => ['required'],
        ]);

        try {
            
            $tickets = $program->findTickets(request('tickets_quantity'));

            $this->paymentGateway->charge(request('ticket_quantity') * $program->contributors->first()->invoice_amount, request('payment_token'));
            
            $order = $program->createOrder(request('email'), $tickets);

            return response()->json($order, 201);

        } catch (PaymentFailedException $e) {
            return response()->json([], 422);
        } catch (NotEnoughTicketsException $e) {
            return response()->json([], 422);
        }
    }
}
