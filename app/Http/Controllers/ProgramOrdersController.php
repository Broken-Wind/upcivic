<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing\PaymentGateway;  
use App\Billing\PaymentFailedException;  
use App\Program;
use App\Order;
use App\Reservation;
use App\Exceptions\NotEnoughTicketsException;

class ProgramOrdersController extends Controller
{
    // 
    
    // private $paymentGateway;    

    // public function __construct(PaymentGateway $paymentGateway)                 
    // {                                                                           
    //     $this->paymentGateway = $paymentGateway;                                
    // }   

    public function create(Request $request, Program $program)
    {
        \Stripe\Stripe::setApiKey('sk_test_51I9XCwGuEpAR4AJ4k0MYvYGnNHZT2UB55MyOI7CQsgma0zf1JU5VhP6s8A3wy8KQkBGn2hHXi0kMYb8WKRuEpk6C00fL2nTt5d');

        $numberOfSpots = $request['numberOfSpots'];
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $program->price * $numberOfSpots,
            'currency' => 'usd',
            // Verify your integration in this guide by including this parameter
            'metadata' => ['integration_check' => 'accept_a_payment'],
        ]);

        return view('tenant.iframe.orders.create', compact('intent', 'numberOfSpots'));
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
            
            $reservation = $program->reserveTickets(request('ticket_quantity'), request('email'));

            $order = $reservation->complete($this->paymentGateway, request('payment_token'));

            return response()->json($order, 201);

        } catch (PaymentFailedException $e) {
            $reservation->cancel();
            return response()->json([], 422);
        } catch (NotEnoughTicketsException $e) {
            return response()->json([], 422);
        }
    }
}
