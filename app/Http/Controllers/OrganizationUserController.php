<?php

namespace Upcivic\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Upcivic\Organization;

class OrganizationUserController extends Controller
{
    //

    public function store(Organization $organization)
    {

        $user = Auth::user();


        if (!$organization->hasTenant()) {

            return; // redirect() -> OrganizationTenantController@create;

        }


        if ($organization->isVacant() || $organization->hasAdministratorEmail($user->email)) {

            $user->joinTenant($organization->tenant);

            return redirect('home');

        }

        //RequestToJoinEmail($user, $organization);

    }
}
