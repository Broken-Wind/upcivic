<?php

namespace Upcivic\Http\Controllers;

use Upcivic\Site;
use Illuminate\Http\Request;
use Upcivic\Http\Requests\StoreSite;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sites = Site::orderBy('name')->get();

        return view('tenant.admin.sites.index', compact('sites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('tenant.admin.sites.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSite $request)
    {
        //
        $validated = $request->validated();

        Site::create([

            'name' => $validated['name'],

            'address' => $validated['address'],

            'phone' => $validated['phone'],

        ]);

        return redirect()->route('tenant:admin.sites.index', tenant()['slug'])->withSuccess('Site added successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \Upcivic\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Upcivic\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Upcivic\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Upcivic\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        //
    }
}
