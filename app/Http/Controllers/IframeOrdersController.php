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

        $this->paymentGateway->charge(request('registration_quantity') * $program->contributors->first()->invoice_amount, request('payment_token'));
        
        $order = $program->orderRegistration(request('email'), request('registration_quantity'));

        return response()->json([], 201);
    }
}
