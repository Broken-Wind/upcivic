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
            case ('add_instructor'):
                $program->addInstructor($instructor);
                return back()->withSuccess($instructor->name . ' was added to ' . $program->name . ', ID ' . $program->id . '.');
            case ('remove_instructor'):
                $program->removeInstructor($instructor);
                return back()->withSuccess($instructor->name . ' was removed from ' . $program->name . ', ID ' . $program->id . '.');
            default:
                return back()->withErrors('Instructor assignment error');
        }
    }
}
