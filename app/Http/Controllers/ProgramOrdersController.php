<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing\PaymentGateway;
use App\Billing\PaymentFailedException;
use App\Program;
use App\Order;
use App\Reservation;
use App\Exceptions\NotEnoughTicketsException;
use App\Http\Requests\StoreProgramOrder;
use App\Mail\OrderConfirmationEmail;

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

    public function store(StoreProgramOrder $request, Program $program)
    {
        abort_if(!$program->isPublished(), 404);
        abort_if(!$program->allowsRegistration(), 401);
        $validated = $request->validated();
        try {
            $reservation = $program->reserveTickets($validated['ticket_quantity'], $validated['stripeEmail']);

            $order = $reservation->complete($this->paymentGateway, $validated['stripeToken']);
            $order->attachParticipants($validated['participants'], $validated['stripeEmail'], $validated['primary_contact'], $validated['alternate_contact'] ?? null);

            \Mail::to($order->email)->send(new OrderConfirmationEmail($order, tenant(), $program));

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
