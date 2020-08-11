<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Upcivic\Administrator;
use Upcivic\Mail\UserRequestsInviteToTenant;
use Upcivic\Organization;
use Upcivic\Person;
use Upcivic\Tenant;
use Upcivic\User;

class OrganizationUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_join_vacant_tenant()
    {
        $user = factory(User::class)->create();

        $tenant = factory(Tenant::class)->create();

        $response = $this->actingAs($user)->post('/organizations/users', ['organization_id' => $tenant->organization->id]);

        $response->assertStatus(302);

        $this->assertTrue($user->fresh()->memberOfTenant($tenant));
    }

    /** @test */
    public function can_join_tenant_if_user_email_matches_existing_administrator_email()
    {
        $user = factory(User::class)->create([

            'email' => 'goody@two.shoes',

        ]);

        $tenant = factory(Tenant::class)->create();

        $person = factory(Person::class)->create([

            'email' => 'goody@two.shoes',

        ]);

        $tenant->organization->administrators()->save($person);

        $response = $this->actingAs($user)->post('/organizations/users', ['organization_id' => $tenant->organization->id]);

        $response->assertStatus(302);

        $this->assertTrue($user->fresh()->memberOfTenant($tenant));
    }

    /** @test */
    public function request_to_join_organization_without_tenant_redirects_to_organization_tenant_create_view()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $organization = factory(Organization::class)->create();

        $response = $this->actingAs($user)->followingRedirects()->post('/organizations/users', ['organization_id' => $organization->id]);

        $response->assertStatus(200);

        $this->assertEquals(url()->current(), route('organizations.tenant.create', $organization));
    }

    /** @test */
    public function request_to_join_email_sent_if_user_email_doesnt_match_existing_administrator_email()
    {
        $this->withoutExceptionHandling();

        Mail::fake();

        $user = factory(User::class)->create([

            'email' => 'goody@two.shoes',

        ]);

        $tenant = factory(Tenant::class)->create();

        $person = factory(Person::class)->create([

            'email' => 'leathery@bad.lose',

        ]);

        $tenant->organization->administrators()->save($person);

        $response = $this->actingAs($user)->followingRedirects()->post('/organizations/users', ['organization_id' => $tenant->organization->id]);

        $response->assertStatus(200);

        $this->assertFalse($user->fresh()->memberOfTenant($tenant));

        Mail::assertSent(UserRequestsInviteToTenant::class);

        $this->assertEquals(url()->current(), route('home'));

        $response->assertSeeText("We emailed {$tenant->name} administrators your request to join. If you need additional assistance, please contact them directly.");
    }
}
