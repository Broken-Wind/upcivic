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
    private $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function create(Request $request, Program $program)
    {
        $program = Program::publishedForTenant()->findOrFail($program->id);

        $numberOfSpots = $request['numberOfSpots'];
        $amount = $program->price * $numberOfSpots;

        return view('tenant.programs.orders.create', compact('program', 'numberOfSpots'));
    }

    public function store(Program $program)
    {
        $program = Program::publishedForTenant()->findOrFail($program->id);

        $this->validate(request(), [
            'stripeEmail' => ['required', 'email'],
            'ticket_quantity' => ['required', 'integer', 'min:1'],
            'stripeToken' => ['required'],
        ]);

        try {
            $reservation = $program->reserveTickets(request('ticket_quantity'), request('stripeEmail'));

            $order = $reservation->complete($this->paymentGateway, request('stripeToken'));

            return redirect(tenant()->route('tenant:programs.orders.show', [$program, $order->confirmation_number]));

        } catch (PaymentFailedException $e) {
            $reservation->cancel();
            return response()->json([], 422);
        } catch (NotEnoughTicketsException $e) {
            return response()->json([], 422);
        }
    }

    public function show(Program $program, $confirmationNumber)
    {
        $order = Order::findByConfirmationNumber($confirmationNumber);

        return view('tenant.programs.orders.show', compact('program', 'order'));
    }
}
