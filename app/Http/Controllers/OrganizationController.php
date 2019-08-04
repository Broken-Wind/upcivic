<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrganization;
use App\Site;

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
     * @param  App\Http\Requests\StoreOrganization  $request
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

        return redirect('home');

    }

}
