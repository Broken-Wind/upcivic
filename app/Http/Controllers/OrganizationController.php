<?php

namespace Upcivic\Http\Controllers;

use Upcivic\Organization;
use Illuminate\Http\Request;
use Upcivic\Http\Requests\StoreOrganization;
use Upcivic\Site;
use Upcivic\Program;
use Carbon\Carbon;
use Upcivic\Http\Requests\UpdateOrganization;

class OrganizationController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('organizations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Upcivic\Http\Requests\StoreOrganization  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrganization $request)
    {
        //
        dd('Out of order');

    // }


    // public function update(UpdateOrganization $request, Organization $organization)
    // {
    //     //

    //     $validated = $request->validated();

    //     $organization->update([

    //         'name' => $validated['name'],

    //     ]);

    //     return back()->withSuccess('Organization updated.');

    // }



}
