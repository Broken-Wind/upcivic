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
                $locationId = (int)$siteLocation[0];
                $siteId = (int)$siteLocation[1];
            } else {
                $locationId = (int)$siteLocation[0];
                $siteId = Location::find($locationId)->site_id ?? null;
            }
            $program->meetings->each(function ($meeting) use ($locationId, $siteId) {
                $meeting->site_id =  empty($siteId) ? null : $siteId;
                $meeting->location_id = empty($locationId) ? null : $locationId;
                $meeting->save();
            });
        } catch (Exception $e) {

            return json_encode($e);
        }
        return json_encode([
            'title' => $program->timeline_title,
            'site_id' => empty($siteId) ? null : $siteId,
            'location_id' => empty($locationId) ? null : $locationId,
            'site_name' => $program->site->name
        ]);
    }
}
