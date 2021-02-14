<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProgramRoster;
use App\Program;
use Illuminate\Http\Request;

class ProgramRosterController extends Controller
{
    //
    public function edit(Program $program)
    {
        return view('tenant.admin.programs.roster.edit', compact('program'));
    }
    public function update(UpdateProgramRoster $request, Program $program)
    {
        $validated = $request->validated();
        $program->price = !empty($validated['price']) ? $validated['price'] * 100 : null;
        $program->enrollment_url = $validated['enrollment_url'] ?? null;
        $program->enrollment_instructions = $validated['enrollment_instructions'] ?? null;
        $program->min_enrollments = $validated['min_enrollments'];
        // If a program allows registration via Upcivic, we should not allow manual updating of the current enrollments.
        if ($program->allowsRegistration()) {
            $program->setMaxEnrollments($validated['max_enrollments']);
        } else {
            $program->updateEnrollments($validated['enrollments'], $validated['max_enrollments']);
        }
        $program->save();
        return back()->withSuccess('Program updated successfully.');
    }
}
