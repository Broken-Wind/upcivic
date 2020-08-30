<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganization;
use App\Http\Requests\UpdateOrganization;
use App\Organization;
use App\Person;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::where('id', '!=', tenant()->organization_id)->orderBy('name')->get();

        return view('tenant.admin.organizations.index', compact('organizations'));
    }

    /**
     * Currently, the only way to access this method is via the program creation view.
     * If we re-add the dedicated organization create view, we'll need to ensure that in all flows, this function redirects appropriately.
     */
    public function store(StoreOrganization $request)
    {
        $validated = $request;

        $organization = Organization::create([

            'name' => $validated->name,

        ]);

        if ($validated->administrator['email']){
            $administrator = Person::create([
                'first_name' => $validated->administrator['first_name'],
                'last_name' => $validated->administrator['last_name'],
                'email' => $validated->administrator['email'],
            ]);
            $organization->administrators()->save($administrator, ['title' => $validated->administrator['title']]);
        }

        return back()->withSuccess('Organization added successfully.');
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

        return back()->withSuccess('Organization updated.');
    }
}
