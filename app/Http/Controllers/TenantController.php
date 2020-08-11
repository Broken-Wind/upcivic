<?php

namespace Upcivic\Http\Controllers;

use Illuminate\Http\Request;
use Upcivic\Http\Requests\StoreTenant;
use Upcivic\Http\Requests\UpdateTenant;
use Upcivic\Organization;
use Upcivic\Program;
use Upcivic\Tenant;

class TenantController extends Controller
{
    //
    public function index()
    {
        return view('tenant.admin.home');
    }

    public function create()
    {
        return view('tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Upcivic\Http\Requests\StoreTenant  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTenant $request)
    {
        //
        $validated = $request->validated();

        $organization = Organization::create([

            'name' => $validated['name'],

        ]);

        $organization->refresh();

        $tenant = new Tenant([

            'slug' => $validated['slug'],

        ]);

        $tenant['organization_id'] = $organization->id;

        $tenant->save();

        \Auth::user()->joinTenant($tenant);

        Program::createExample($organization);

        return redirect('/home');
    }

    public function edit()
    {
        $tenant = tenant();

        return view('tenant.admin.settings', compact('tenant'));
    }

    public function update(UpdateTenant $request, Tenant $tenant)
    {
        //

        $validated = $request->validated();

        $tenant->organization->update([

            'name' => $validated['name'],

        ]);

        return back()->withSuccess('Organization updated.');
    }
}
