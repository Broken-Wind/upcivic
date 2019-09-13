<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Administrator;
use Upcivic\Organization;
use Upcivic\Person;
use Upcivic\User;

class HomeTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function user_can_see_existing_organizations()
    {
        $organization1 = factory(Organization::class)->create([

            'name' => 'Deez Rex',

        ]);

        $organization2 = factory(Organization::class)->create([

            'name' => 'Notrex',

        ]);

        $person = factory(Person::class)->create([

            'first_name' => 'Johnny',

            'last_name' => 'Tsunami',

            'email' => 'wave@runner.com',

        ]);


        $administrator = Administrator::make([

            'title' => 'Waverunner',

        ]);

        $administrator['organization_id'] = $organization1->id;

        $administrator['person_id'] = $person->id;

        $administrator->save();

        $user = factory(User::class)->create([

            'email' => 'wave@runner.com',

        ]);


        $response = $this->actingAs($user)->get("/home");


        $response->assertSeeText('Find Your Organization:');

        $response->assertSee('Deez Rex');

        $response->assertSee('Notrex');

        $response->assertSee(route('organizations.users.store'));

    }
}
