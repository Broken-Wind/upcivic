<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\SubscriptionSeats;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('tenant.admin.subscriptions.index', compact('user'));
    }
    // public function billingPortal(Request $request)
    // {
    //     $user = $request->user();
    //     $user->createOrGetStripeCustomer();
    //     return $user->redirectToBillingPortal();
    // }

    public function create(Request $request)
    {
        $user = $request->user();
        return view('tenant.admin.payments', ['intent' => $user->createSetupIntent()]);
    }

    public function store(StoreSubscription $request)
    {
        $validated = $request->validated();
        $userCount = tenant()->users->count();
        if ($validated['numberOfSeats'] < $userCount) {
            return json_encode([
                'message' => tenant()->name . ' has ' . $userCount . ' users on ' . config('app.name') . '. You must purchase at least that many seats to upgrade to pro.',
                'status' => 'fail'
            ]);
        }
        $user = $request->user();
        $user->createOrGetStripeCustomer();

        $paymentMethod = $request['paymentMethod'];
        $numberOfSeats = $request['numberOfSeats'];
        $subscriptionName = config('app.subscription_name');

        $user->addPaymentMethod($paymentMethod);

        try {
            $response = $user->newSubscription(
                $subscriptionName,
                config('app.subscription_price_id')
            )->quantity($numberOfSeats)->create($paymentMethod);
        } catch (\Throwable $th) {
            return json_encode([
                'message' => $th->getMessage(),
                'status' => 'fail'
            ]);
        }
        return json_encode([
            'message' => 'Successfully subcribed to Upcivic Pro.',
            'status' => 'success'
        ]);
    }

    public function destroy(Request $request)
    {

        $user = $request->user();

        $subscriptionName = config('app.subscription_name');
        $stripeSubscription = $user->subscription($subscriptionName);
        if ($stripeSubscription) {
            $stripeSubscription->cancel();
        }

        return redirect()->route('tenant:admin.users.edit', [tenant()->slug]);
    }
}
