<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Organization;
use App\Tenant;
use App\User;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_visit_organizations_index()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenant = $user->tenants()->first();

        $response = $this->actingAs($user)->get("/{$tenant->slug}/admin/organizations");

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_see_claimed_organizations_in_organizations_index()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenant = $user->tenants()->first();

        $organizationToSee = factory(Organization::class)->create(['name' => 'Visible']);

        $tenantToSee = factory(Tenant::class)->create([

            'organization_id' => $organizationToSee->id,

            'slug' => 'testslug',

        ]);

        $claimantOfTenantToSee = factory(User::class)->create();

        $claimantOfTenantToSee->joinTenant($tenantToSee);

        $response = $this->actingAs($user)->get("/{$tenant->slug}/admin/organizations");

        $response->assertStatus(200);

        $response->assertSeeText('Visible');

        $response->assertDontSee($tenant->route('tenant:admin.organizations.edit', [$organizationToSee]));
    }

    /** @test */
    public function user_can_see_unclaimed_organizations_in_organizations_index()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenant = $user->tenants()->first();

        $organizationToSee = factory(Organization::class)->create(['name' => 'Visible']);

        $tenantToSee = factory(Tenant::class)->create([

            'organization_id' => $organizationToSee->id,

            'slug' => 'testslug',

        ]);

        $response = $this->actingAs($user)->get("/{$tenant->slug}/admin/organizations");

        $response->assertStatus(200);

        $response->assertSeeText('Visible');

        $response->assertSee($tenant->route('tenant:admin.organizations.edit', [$organizationToSee]));
    }

    /** @test */
    public function own_organization_exluded_from_organizations_index()
    {
        $organization = factory(Organization::class)->create(['name' => 'Is it visible?']);

        $tenant = factory(Tenant::class)->create([

            'organization_id' => $organization->id,

        ]);

        $user = factory(User::class)->create();

        $user->joinTenant($tenant);

        $response = $this->actingAs($user)->get("/{$tenant->slug}/admin/organizations");

        $response->assertStatus(200);

        $response->assertDontSee($tenant->route('tenant:admin.organizations.edit', [$organization]));
    }

    /** @test */
    public function guest_cannot_visit_organizations_index()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenant = $user->tenants()->first();

        $response = $this->followingRedirects()->get("/{$tenant->slug}/admin/organizations");

        $response->assertStatus(200);

        $this->assertEquals(url()->current(), config('app.url').'/login');
    }

    /** @test */
    public function user_can_create_organization()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenant = $user->tenants()->first();

        $response = $this->actingAs($user)->followingRedirects()->post("/{$tenant->slug}/admin/organizations", [

            'name' => 'Bobby Dodgekins',

        ]);

        $response->assertStatus(200);

        $this->assertNotNull(Organization::where('name', 'Bobby Dodgekins')->first());
    }

    /** @test */
    public function user_can_visit_edit_page_of_unclaimed_organization()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenant = $user->tenants()->first();

        $organization = factory(Organization::class)->create(['name' => 'Will it edit?']);

        $response = $this->actingAs($user)->followingRedirects()->get("/{$tenant->slug}/admin/organizations/{$organization->id}/edit");

        $response->assertStatus(200);

        $response->assertSeeText('Edit Will it edit?');
    }

    /** @test */
    public function user_cannot_visit_edit_page_of_claimed_organization()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenantToEditFrom = $user->tenants()->first();

        $organizationToEdit = factory(Organization::class)->create(['name' => 'Will it edit?']);

        $tenantToEdit = factory(Tenant::class)->create([

            'organization_id' => $organizationToEdit->id,

        ]);

        $claimantOfTenantToEdit = factory(User::class)->create();

        $claimantOfTenantToEdit->joinTenant($tenantToEdit);

        $response = $this->actingAs($user)->followingRedirects()->get("/{$tenantToEditFrom->slug}/admin/organizations/{$organizationToEdit->id}/edit");

        $response->assertStatus(401);

        $response->assertDontSeeText('Edit Will it edit?');
    }

    /** @test */
    public function user_can_update_unclaimed_organization()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenant = $user->tenants()->first();

        $organization = factory(Organization::class)->create(['name' => 'Will it update?']);

        $response = $this->actingAs($user)->followingRedirects()->put("/{$tenant->slug}/admin/organizations/{$organization->id}", [

            'name' => 'Updated!',

        ]);

        $response->assertStatus(200);

        $this->assertEquals($organization->fresh()->name, 'Updated!');
    }

    /** @test */
    public function user_cannot_update_claimed_organization()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenantToUpdateFrom = $user->tenants()->first();

        $organizationToUpdate = factory(Organization::class)->create(['name' => 'Will it update?']);

        $tenantToUpdate = factory(Tenant::class)->create([

            'organization_id' => $organizationToUpdate->id,

        ]);

        $claimantOfTenantToUpdate = factory(User::class)->create();

        $claimantOfTenantToUpdate->joinTenant($tenantToUpdate);

        $response = $this->actingAs($user)->followingRedirects()->put("/{$tenantToUpdateFrom->slug}/admin/organizations/{$organizationToUpdate->id}", [

            'name' => 'Updated!',

        ]);

        $response->assertStatus(401);

        $this->assertEquals($organizationToUpdate->fresh()->name, 'Will it update?');
    }

    /** @test */
    public function guest_cannot_create_organization()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenant = $user->tenants()->first();

        $response = $this->followingRedirects()->post("/{$tenant->slug}/admin/organizations", [

            'name' => 'Bobby Dodgekins',

        ]);

        $response->assertStatus(200);

        $this->assertEquals(url()->current(), config('app.url').'/login');

        $this->assertNull(Organization::where('name', 'Bobby Dodgekins')->first());
    }

    /** @test */
    public function guest_cannot_visit_edit_page_of_unclaimed_organization()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenant = $user->tenants()->first();

        $organization = factory(Organization::class)->create(['name' => 'Will it edit?']);

        $response = $this->followingRedirects()->get("/{$tenant->slug}/admin/organizations/{$organization->id}/edit");

        $response->assertStatus(200);

        $this->assertEquals(url()->current(), config('app.url').'/login');
    }

    /** @test */
    public function guest_cannot_update_unclaimed_organization()
    {
        $user = factory(User::class)->states('hasTenant')->create();

        $tenant = $user->tenants()->first();

        $organization = factory(Organization::class)->create(['name' => 'Will it update?']);

        $response = $this->followingRedirects()->put("/{$tenant->slug}/admin/organizations/{$organization->id}", [

            'name' => 'Updated!',

        ]);

        $response->assertStatus(200);

        $this->assertEquals(url()->current(), config('app.url').'/login');

        $this->assertEquals($organization->fresh()->name, 'Will it update?');
    }
}
