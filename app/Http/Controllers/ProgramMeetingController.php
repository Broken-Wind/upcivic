<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyProgramMeetings;
use App\Http\Requests\StoreProgramMeeting;
use App\Http\Requests\UpdateProgramMeetings;
use App\Meeting;
use App\Program;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProgramMeetingController extends Controller
{
    //
    public function store(StoreProgramMeeting $request, Program $program)
    {
        $validated = $request->validated();
        $meeting = new Meeting([
            'start_datetime' => Carbon::parse($validated['start_datetime'])->format('Y-m-d H:i:s'),
            'end_datetime' => Carbon::parse($validated['end_datetime'])->format('Y-m-d H:i:s'),
        ]);
        $meeting['program_id'] = $program['id'];
        $meeting['site_id'] = $validated['site_id'];
        $meeting->save();

        return back()->withSuccess('Meeting added successfully.');
    }

    public function update(UpdateProgramMeetings $request, Program $program)
    {
        $validated = $request->validated();
        if ($request['delete_meetings']) {
            if (! isset($validated['meeting_ids'])) {
                return back()->withErrors(['error' => 'You must select one or more meetings to delete.']);
            }
            if (count($validated['meeting_ids']) >= $program->meetings->count()) {
                return back()->withErrors(['error' => 'A program must have at least one meeting.']);
            }
            Meeting::whereIn('id', $validated['meeting_ids'])->get()->each(function ($meeting) {
                $meeting->delete();
            });

            return back()->withSuccess('Meetings removed successfully.');
        }

        if ($request['update_all']) {
            $validated['meeting_ids'] = $program->meetings->pluck('id');
        }
        collect($validated['meeting_ids'])->each(function ($id) use ($validated, $program) {
            $meeting = Meeting::find($id);
            if (! empty($validated['start_time'])) {
                $startDatetime = Carbon::parse($meeting['start_datetime'])->format('Y-m-d');
                $startDatetime = Carbon::parse($startDatetime.' '.$validated['start_time'].' '.$validated['shift_meetings'].' days')->format('Y-m-d H:i:s');
                $meeting['start_datetime'] = $startDatetime;
            }
            if (! empty($validated['end_time'])) {
                $endDatetime = Carbon::parse($meeting['end_datetime'])->format('Y-m-d');
                $endDatetime = Carbon::parse($endDatetime.' '.$validated['end_time'].' '.$validated['shift_meetings'].' days')->format('Y-m-d H:i:s');
                $meeting['end_datetime'] = $endDatetime;
            }
            if (! empty($validated['shift_meetings'])) {
                $meeting['start_datetime'] = Carbon::parse($meeting['start_datetime'])->addDays($validated['shift_meetings'])->format('Y-m-d H:i:s');
                $meeting['end_datetime'] = Carbon::parse($meeting['end_datetime'])->addDays($validated['shift_meetings'])->format('Y-m-d H:i:s');
            }
            $meeting['program_id'] = $program['id'];
            $meeting['site_id'] = $validated['site_id'];
            $meeting->save();
        });

        return back()->withSuccess('Meetings updated successfully.');
    }
}
