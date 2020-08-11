<?php

namespace Upcivic\Http\Controllers;

use Illuminate\Http\Request;
use Upcivic\Http\Requests\UpdateProgramEnrollment;
use Upcivic\Program;

class ProgramEnrollmentController extends Controller
{
    //
    public function update(UpdateProgramEnrollment $request, Program $program)
    {
        $validated = $request->validated();
        $program->update([
            'enrollments' => $validated['enrollments'],
            'max_enrollments' => $validated['max_enrollments'],
        ]);

        return back();
    }
}
