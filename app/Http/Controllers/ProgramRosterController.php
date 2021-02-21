<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailParticipants;
use App\Http\Requests\UpdateProgramRoster;
use App\Mail\BulkParticipantMessage;
use App\Mail\PriceChange;
use App\Person;
use App\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProgramRosterController extends Controller
{
    //
    public function edit(Program $program)
    {
        $contributor = $program->getContributorFor(tenant());
        return view('tenant.admin.programs.roster.edit', compact('program', 'contributor'));
    }
    public function update(UpdateProgramRoster $request, Program $program)
    {
        $validated = $request->validated();
        if ($program->isProposalSent() && $program->hasOtherContributors() && $program->formatted_price != $validated['price']) {
            \Mail::send(new PriceChange($program, $validated['price'], tenant(), Auth::user()));
            $program->price = isset($validated['price']) ? $validated['price'] * 100 : null;
        }
        $program->min_enrollments = $validated['min_enrollments'];
        // If a program allows registration via Upcivic, we should not allow manual updating of the current enrollments.
        if ($program->getContributorFor(tenant())->allowsRegistration()) {
            $program->setMaxEnrollments($validated['max_enrollments']);
        } else {
            $program->updateEnrollments($validated['enrollments'], $validated['max_enrollments']);
        }
        $program->save();
        $contributor = $program->getContributorFor(tenant());
        $contributor->update([
            'enrollment_url' => $validated['enrollment_url'] ?? null,
            'enrollment_instructions' => $validated['enrollment_instructions'] ?? null,
        ]);
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
