<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zttp\Zttp;

class StripeConnectController extends Controller
{
    //
    public function settings()
    {
        $tenant = tenant();
        return view('tenant.admin.stripe_connect.settings', compact('tenant'));
    }
    public function authorizeRedirect()
    {
        $url = vsprintf('%s?%s', [
            'https://connect.stripe.com/oauth/authorize',
            http_build_query([
                'response_type' => 'code',
                'scope' => 'read_write',
                'client_id' => config('services.stripe.client_id')
            ])
        ]);
        return redirect($url);
    }

    public function redirect(Request $request)
    {
        $accessTokenResponse = Zttp::asFormParams()->post('https://connect.stripe.com/oauth/token', [
            'grant_type' => 'authorization_code',
            'code' => $request['code'],
            'client_secret' => config('services.stripe.secret')
        ])->json();
        info($accessTokenResponse);
        // We are concerned about saving Stripe credentials to the wrong tenant in case a user somehow ends up with 2 tenants.
        // In newer implementations of Stripe Connect, we can configure the return URL to include the tenant slug as an identifier,
        // but I'm not aware of how we can do that with this implementation.
        $user = Auth::user();
        abort_if($user->tenants->count() > 1, 401, 'You may not configure payments if you are a member of more than one organization.');
        $tenant = $user->tenants()->first();
        $tenant->stripe_account_id = $accessTokenResponse['stripe_user_id'];
        $tenant->stripe_access_token = $accessTokenResponse['access_token'];
        $tenant->save();
        return redirect($tenant->route('tenant:admin.stripe_connect.settings'));
    }
}
