<?php

namespace Upcivic\Http\Controllers;

use Upcivic\Organization;
use Illuminate\Http\Request;
use Upcivic\Http\Requests\StoreOrganization;
use Upcivic\Site;
use Upcivic\Program;

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
        $validated = $request->validated();

        $organization = Organization::create([

            'name' => $validated['name'],

            'slug' => $validated['slug'],

            ]);

        \Auth::user()->join($organization);

        Program::createExample($organization->fresh());

        return redirect('home');

    }

}
