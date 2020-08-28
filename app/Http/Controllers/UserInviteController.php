<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserInvite;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UserInviteController extends Controller
{
    //
    public function create(Request $request)
    {
        $email = $request->input('email');
        return view('tenant.admin.users.invites.create', compact('email'));
    }

    public function store(StoreUserInvite $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (empty($user)) {
            return back()->withErrors(['error' => 'Account not found. User must already have an account with '.config('app.name').' to be invited to your organization.']);
        }

        $user->tenants()->attach(tenant());

        return back()->withSuccess($validated['email'].' has been invited to your organization.');
    }
}
