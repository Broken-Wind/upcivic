<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Administrator;
use Upcivic\Person;
use Upcivic\Tenant;
use Upcivic\User;

class OrganizationUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_join_vacant_tenant()
    {

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $tenant = factory(Tenant::class)->create();


        $response = $this->actingAs($user)->post("/organizations/{$tenant->organization->id}/users");


        $response->assertStatus(302);

        $this->assertTrue($user->fresh()->memberOfTenant($tenant));

    }

    /** @test */
    public function can_join_tenant_if_user_email_matches_existing_administrator_email()
    {

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([

            'email' => 'goody@two.shoes',

        ]);

        $tenant = factory(Tenant::class)->create();

        $person = factory(Person::class)->create([

            'email' => 'goody@two.shoes',

        ]);

        $administrator = new Administrator();

        $administrator['person_id'] = $person['id'];

        $administrator['organization_id'] = $tenant->organization['id'];

        $administrator->save();


        $response = $this->actingAs($user)->post("/organizations/{$tenant->organization->id}/users");


        $response->assertStatus(302);

        $this->assertTrue($user->fresh()->memberOfTenant($tenant));

    }
}
