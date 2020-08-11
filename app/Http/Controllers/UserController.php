<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUser;
use App\User;

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
