<?php

namespace App\Http\Controllers;

use App\Area;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrganizationAreas;
use App\Organization;
use Illuminate\Http\Request;

class OrganizationAreasController extends Controller
{
    //
    public function update(Organization $organization, UpdateOrganizationAreas $request)
    {
        $validated = $request->validated();
        $area = Area::find($validated['area_id']);
        $organization->areas()->sync($validated['area_id']);
        return back()->withSuccess($organization->name . " area was set to " . $area->name);
    }
}
