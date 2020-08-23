<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Location;
use App\Program;
use Exception;
use Illuminate\Http\Request;

class ProgramLocationsController extends Controller
{
    //
    public function update(Request $request) {
        try {
            $program = Program::findOrFail($request['program_id']);
            $siteLocation = explode('_', $request['location_ids'][0]);
            if (count($siteLocation) > 1) {
                $locationId = $siteLocation[1];
                $siteId = $siteLocation[0];
            } else {
                $locationId = (int)$siteLocation[0];
                $siteId = Location::findOrFail($locationId)->site_id;
            }
            $program->meetings->each(function ($meeting) use ($locationId, $siteId) {
                $meeting->site_id = $siteId;
                $meeting->location_id = empty($locationId) ? null : $locationId;
                $meeting->save();
            });
        } catch (Exception $e) {
            return json_encode($e);
        }
        return json_encode(['title' => 'To be pooptinued']);
    }
}
