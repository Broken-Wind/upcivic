<?php

namespace App\Http\Controllers;

use App\Area;
use App\County;
use App\Http\Requests\StoreSite;
use App\Site;
use Illuminate\Http\Request;

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
        $sitesJson = $sites->map(function ($site) {
            return [
                'id' => $site->id,
                'name' => $site->name,
                'area_id' => $site->area->id ?? null
            ];
        })->toJson();
        $areas = Area::orderBy('name')->get();

        return view('tenant.admin.sites.index', compact('sites', 'sitesJson', 'areas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $areas = tenant()->organization->areas()->orderBy('name')->get();

        return view('tenant.admin.sites.create', compact('areas'));
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

        $site = Site::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
        ]);

        $site->areas()->sync($validated['area_id']);

        // return redirect()->route('tenant:admin.sites.index', tenant()['slug'])->withSuccess('Site added successfully.');
        return back()->withSuccess('Site added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Site  $site
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
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        //
    }
}
