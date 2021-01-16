<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}