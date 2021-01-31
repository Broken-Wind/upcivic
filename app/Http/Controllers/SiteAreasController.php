<?php

namespace App\Http\Controllers;

use App\Area;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSiteAreas;
use App\Site;
use Illuminate\Http\Request;

class SiteAreasController extends Controller
{
    //
    public function update(Site $site, UpdateSiteAreas $request)
    {
        $validated = $request->validated();
        if ($validated['action'] == 'unset') {
            $site->areas()->sync(null);
            return back()->withSuccess($site->name . " area was unset.");
        }
        $area = Area::find($validated['area_id']);
        $site->areas()->sync($validated['area_id'] ?? null);
        return back()->withSuccess($site->name . " area was updated.");
    }
}
