<?php

namespace Upcivic\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Upcivic\Program;

class CalendarController extends Controller
{
    //
    public function index()
    {
        $resourceCollection = collect([
            [
              'id' => 'cab_1',
              'site' => 'CAB',
              'title' => 'Room 1'
            ],
            [
              'id' => 'cab_2',
              'site' => 'CAB',
              'title' => 'Room 2'
            ],
            [
              'id' => 'cab_3',
              'site' => 'CAB',
              'title' => 'Room 3'
            ],
            [
              'id' => 'cab_kitchen',
              'site' => 'CAB',
              'title' => 'Kitchen'
            ],
            [
              'id' => 'cab_field_1',
              'site' => 'CAB',
              'title' => 'Field 1'
            ],
            [
              'id' => 'cab_field_2',
              'site' => 'CAB',
              'title' => 'Field 2'
            ],
            [ 'id' => 'sand_1',
              'site' => 'Sandpiper',
              'title' => 'Room 1'
            ],
            [ 'id' => 'sand_2',
              'site' => 'Sandpiper',
              'title' => 'Room 2'
            ],
            [ 'id' => 'sand_3',
              'site' => 'Sandpiper',
              'title' => 'Room 3'
            ],
            [ 'id' => 'vet_1',
              'site' => 'Vet\'s Memorial',
              'title' => 'Room 1'
            ],
            [ 'id' => 'vet_2',
              'site' => 'Vet\'s Memorial',
              'title' => 'Room 2'
            ],
            [ 'id' => 'vet_aud',
              'site' => 'Vet\'s Memorial',
              'title' => 'Auditorium'
            ],
          ]);
        $resources = json_encode($resourceCollection);
        $siteIds = $resourceCollection->pluck('id')->unique();
        $programs = Program::with(['meetings.site', 'contributors.organization'])->get()->sortBy('start_datetime');
        $events = $programs->map(function ($program) use ($siteIds) {
            return [
                'resourceId' => $siteIds->random(),
                'title' => $program->internal_name,
                'start' => $program->start_datetime,
                'end' => $program->end_datetime
            ];
        })->values()->toJson();

        return view('tenant.admin.programs.calendar', compact('resources', 'events'));
    }
}
