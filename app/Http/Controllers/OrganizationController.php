<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganization;
use App\Http\Requests\UpdateOrganization;
use App\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::where('id', '!=', tenant()->organization_id)->orderBy('name')->get();

        return view('tenant.admin.organizations.index', compact('organizations'));
    }

    public function store(StoreOrganization $request)
    {
        $validated = $request;

        $organization = Organization::create([

            'name' => $validated->name,

        ]);

        return redirect()->route('tenant:admin.organizations.edit', [\Auth::user()->tenants()->first()->slug, $organization->id]);
    }

    public function edit(Organization $organization)
    {
        return view('tenant.admin.organizations.edit', compact('organization'));
    }

    public function update(UpdateOrganization $request, Organization $organization)
    {
        $validated = $request->validated();

        $organization->update([

            'name' => $validated['name'],

        ]);

        return back()->withSuccess('Organization updated!');
    }
}
