<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    public function index()
    {
        return view('tenant.admin.billing.index');
    }
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
        $user->createOrGetStripeCustomer();

        $paymentMethod = $request['paymentMethod'];
        $noOfSeats = $request['noOfSeats'];
        $subscriptionName = config('app.subscription_name');

        $user->addPaymentMethod($paymentMethod);

        try {
            $response = $user->newSubscription(
                $subscriptionName,
                config('app.subscription_price_id')
            )->quantity($noOfSeats)->create($paymentMethod);
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

    public function cancelSubscription(Request $request)
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
