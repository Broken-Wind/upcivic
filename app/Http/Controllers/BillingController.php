<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    public function billingPortal(Request $request)
    {
        $user = $request->user();

        $user->createOrGetStripeCustomer();
        
        return $user->redirectToBillingPortal();
    }

    public function updatePaymentMethod(Request $request)
    {
        $user = $request->user();
        return view('tenant.admin.payments', ['intent' => $user->createSetupIntent()]);
    }

    public function subscribe(Request $request)
    {
        $user = $request->user();

        $paymentMethod = $request['paymentMethod'];
        $noOfSeats = $request['noOfSeats'];

        $user->addPaymentMethod($paymentMethod);

        $user->newSubscription(
            config('app.subscription_name'), 
            config('app.subscription_price_id')
        )->quantity($noOfSeats)->create($paymentMethod);

        return [42]; //TODO: Redirect to the user account page
    }

    public function cancelSubscription(Request $request)
    {

        $user = $request->user();

        $subscription_name = config('app.subscription_name');
        $stripe_subscription = $user->subscription($subscription_name);
        if ($stripe_subscription) {
            $stripe_subscription->cancelNow(); //TODO: Cancel subscription after the grace period
        }

        return redirect()->route('tenant:admin.users.edit', [tenant()->slug]);
    }
}