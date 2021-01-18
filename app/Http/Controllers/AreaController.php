<?php

namespace App\Http\Controllers;

use App\Area;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArea;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    //
    public function index()
    {
        $areas = Area::orderBy('name')->get();
        return view('tenant.admin.areas.index', compact('areas'));
    }
    public function store(StoreArea $request) {
        $validated = $request->validated();
        $area = Area::make([
            'name' => $validated['name']
        ]);
        $area->organization_id = tenant()->organization_id;
        $area->save();
        return back()->withSuccess('Added ' . $area->name);
    }
}
