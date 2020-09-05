<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProgramEnrollment;
use App\Program;
use Illuminate\Http\Request;

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
