<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailParticipants;
use App\Http\Requests\UpdateProgramRoster;
use App\Mail\BulkParticipantMessage;
use App\Person;
use App\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        if ($program->getContributorFor(tenant())->allowsRegistration()) {
            $program->setMaxEnrollments($validated['max_enrollments']);
        } else {
            $program->updateEnrollments($validated['enrollments'], $validated['max_enrollments']);
        }
        $program->save();
        return back()->withSuccess('Program updated successfully.');
    }

    public function emailParticipants(EmailParticipants $request, Program $program)
    {
        $validated = $request->validated();
        $emails = $program->participants->map(function ($participant) {
            return $participant->primaryContact()->email;
        });
        $emails = $emails->merge(Person::find($request->contact_ids)->pluck('email'));
        if (!empty($validated['cc_address_1'])) {
            $emails = $emails->push($validated['cc_address_1']);
        }
        if (!empty($validated['cc_address_2'])) {
            $emails = $emails->push($validated['cc_address_2']);
        }
        $emails = $emails->unique();

        $emails->each(function ($email) use ($validated) {
            Mail::to($email)->send(new BulkParticipantMessage($validated['subject'], $validated['message'], tenant()->organization));
        });
        return back()->withSuccess('Emails sent!');
    }
}
