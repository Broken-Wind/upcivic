<?php

namespace Upcivic\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Upcivic\User;
use Upcivic\Http\Requests\UpdateUser;

class UserController extends Controller
{
    //
    public function edit()
    {

        $user = Auth::user();

        return view('tenant.admin.users.edit', compact('user'));

    }

    public function update(UpdateUser $request, User $user)
    {

        $validated = $request->validated();

        $user->update([

            'name' => $validated['name'],

        ]);

        return back()->withSuccess('Profile updated successfully.');

    }
}
