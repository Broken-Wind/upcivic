<?php

namespace Upcivic\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Upcivic\Http\Requests\StoreOrganizationUser;
use Upcivic\Mail\UserRequestsInviteToTenant;
use Upcivic\Organization;

class OrganizationUserController extends Controller
{
    //

    public function store(StoreOrganizationUser $request)
    {
        $validated = $request->validated();

        $organization = Organization::find($validated['organization_id']);

        $user = Auth::user();

        if (! $organization->hasTenant()) {
            return redirect()->route('organizations.tenant.create', $organization);
        }

        if ($organization->isVacant() || $organization->hasAdministratorEmail($user->email)) {
            $user->joinTenant($organization->tenant);

            return redirect('home');
        }

        \Mail::send(new UserRequestsInviteToTenant($user, $organization->tenant));

        return redirect('home')->withSuccess("We emailed {$organization->name} administrators your request to join. If you need additional assistance, please contact them directly.");
    }
}
