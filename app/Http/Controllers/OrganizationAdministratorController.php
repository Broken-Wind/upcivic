<?php

namespace Upcivic\Http\Controllers;

use Illuminate\Http\Request;
use Upcivic\Administrator;
use Upcivic\Http\Requests\StoreAdministrator;
use Upcivic\Mail\ListedAsAdministrator;
use Upcivic\Organization;
use Upcivic\Person;

class OrganizationAdministratorController extends Controller
{
    //
    public function store(StoreAdministrator $request, Organization $organization)
    {
        $validated = $request->validated();

        $person = Person::create([

            'first_name' => $validated['first_name'],

            'last_name' => $validated['last_name'],

            'email' => $validated['email'],

        ]);

        $organization->administrators()->save($person, ['title' => $validated['title']]);

        \Mail::send(new ListedAsAdministrator(\Auth::user(), $organization, $person));

        return back()->withSuccess("Added {$person->name} to {$organization->name}!");
    }
}
