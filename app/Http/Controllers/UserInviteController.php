<?php

namespace Upcivic\Http\Controllers;

use Illuminate\Http\Request;
use Upcivic\Http\Requests\StoreUserInvite;
use Upcivic\User;

class UserInviteController extends Controller
{
    //
    public function create()
    {

        return view('tenant.admin.users.invites.create');

    }

    public function store(StoreUserInvite $request)
    {

        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (empty($user)) {

            return back()->withErrors(['error' => "Account not found. User must already have an account with " . config('app.name') . " to be invited to your organization."]);

        }



        $user->organizations()->attach(tenant());

        return back()->withSuccess($validated['email'] . " has been invited to your organization.");

    }
}
