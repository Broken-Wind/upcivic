<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserInvite;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Exceptions\NoMoreSeatsException;

class UserInviteController extends Controller
{
    //
    public function store(StoreUserInvite $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (empty($user)) {
            return back()->withErrors(['error' => 'Account not found. User must already have an account with '.config('app.name').' to be invited to your organization.']);
        }

        try {
            $user->joinTenant(tenant());
        } catch (NoMoreSeatsException $th) {
            return back()->withErrors(tenant()->name . " already using all available seats for the current plan. Please contact " . config('mail.support_email') . " to add more seats.");
        }

        return back()->withSuccess($validated['email'].' has been added to your organization.');
    }
}
