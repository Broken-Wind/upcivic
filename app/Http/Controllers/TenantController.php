<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenant;
use App\Http\Requests\UpdateTenant;
use App\Organization;
use App\Program;
use App\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantController extends Controller
{
    //
    public function index()
    {
        if (tenant()->isSubscribed()) {
            return redirect()->route('tenant:admin.resource_timeline.index', tenant()->slug);
        }
        return redirect()->route('tenant:admin.programs.index', tenant()->slug);
    }

    public function create()
    {
        return view('tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreTenant  $request
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

        // Program::createExample($organization);

        return redirect()->route('home');
    }

    public function edit(Request $request)
    {
        $tenant = tenant();
        $email = $request->input('email');
        return view('tenant.admin.settings', compact('tenant', 'email'));
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
