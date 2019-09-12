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
    public function user_can_see_recommended_organizations()
    {
        $recommendedOrganization = factory(Organization::class)->create([

            'name' => 'Deez Rex',

        ]);

        $nonRecommendedOrganization = factory(Organization::class)->create([

            'name' => 'Notrex',

        ]);

        $person = factory(Person::class)->create([

            'first_name' => 'Johnny',

            'last_name' => 'Tsunami',

            'email' => 'wave@runner.com',

        ]);


        $administrator = Administrator::make([

            'title' => 'SomeGuy',

        ]);

        $administrator['organization_id'] = $recommendedOrganization->id;

        $administrator['person_id'] = $person->id;

        $administrator->save();

        $user = factory(User::class)->create([

            'email' => 'wave@runner.com',

        ]);


        $response = $this->actingAs($user)->get("/home");


        $response->assertSeeText('Recommended Organizations:');

        $response->assertSeeText('Deez Rex');

        $response->assertDontSeeText('Notrex');

    }
}
