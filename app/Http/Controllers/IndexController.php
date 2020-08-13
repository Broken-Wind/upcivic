<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user() && Auth::user()->hasTenant()) {
            return redirect()->route('tenant:admin.programs.index', \Auth::user()->tenants()->first()->slug);
        }

        return view('welcome');
    }
}
