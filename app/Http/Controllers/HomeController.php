<?php

namespace Upcivic\Http\Controllers;

use Illuminate\Http\Request;
use Upcivic\Organization;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if (\Auth::user()->hasTenant()) {

            return redirect()->route('tenant:admin.home', \Auth::user()->tenants()->first()->slug);

        }

        $recommendedOrganizations = \Auth::user()->recommendedOrganizations();

        $allOrganizations = Organization::orderBy('name')->get();

        return view('home', compact('recommendedOrganizations', 'allOrganizations'));
    }
}
