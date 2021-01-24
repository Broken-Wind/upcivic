<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing\PaymentGateway;  
use App\Program;

class IframeOrdersController extends Controller
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

        $quantity = request('registration_quantity');
        $token = request('payment_token');

        $amount = $quantity * $program->contributors->last()->invoice_amount;
        $this->paymentGateway->charge($amount, $token);
        return response()->json([], 201);
    }
}
