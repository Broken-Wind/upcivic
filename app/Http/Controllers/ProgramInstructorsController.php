<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProgramInstructors;
use App\Instructor;
use App\Program;
use Illuminate\Http\Request;

class ProgramInstructorsController extends Controller
{
    //
    public function update(UpdateProgramInstructors $request, Program $program)
    {
        $validated = $request->validated();
        $instructor = Instructor::findOrFail($validated['instructor_id']);
        switch ($validated['action']) {
            case ('add_selected'):
                $instructor->meetings()->attach($validated['meeting_ids']);
                return back()->withSuccess($instructor->name . ' was added to ' . count($validated['meeting_ids']) . ' meetings of ' . $program->name . ', #' . $program->id . '.');
            case ('remove_selected'):
                $instructor->meetings()->detach($validated['meeting_ids']);
                return back()->withSuccess($instructor->name . ' was removed from ' . count($validated['meeting_ids']) . ' meetings of ' . $program->name . ', #' . $program->id . '.');
            case ('remove_all'):
        }
        return back()->withErrors('Instructor assignment error');
    }
}
