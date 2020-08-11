<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrganizationTenant;
use App\Organization;
use App\Program;
use App\Tenant;

class OrganizationTenantController extends Controller
{
    //
    public function create(Organization $organization)
    {
        abort_if($organization->hasTenant(), 401, 'This action is unauthorized.');

        return view('organizations.tenant.create', compact('organization'));
    }

    public function store(StoreOrganizationTenant $request, Organization $organization)
    {
        $validated = $request->validated();

        $organization->tenant()->save(new Tenant([

            'slug' => $validated['slug'],

        ]));

        Auth::user()->joinTenant($organization->tenant);

        Program::createExample($organization);

        return redirect()->route('home');
    }
}
