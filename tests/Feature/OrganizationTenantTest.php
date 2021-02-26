<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Organization;
use App\User;
class OrganizationTenantTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function organization_tenant_create_view_content_test()
    {
        $user = factory(User::class)->create();
        $organization = factory(Organization::class)->create();

        $response = $this->actingAs($user)->followingRedirects()->get("/organizations/{$organization->id}/tenant");

        $response->assertStatus(200);
        $this->assertEquals(url()->current(), route('organizations.tenant.create', $organization));
        $response->assertSeeText("Since {$organization->name} is unclaimed, you may select its vanity URL below.");
        $response->assertSeeText('Not your organization? Go home.');
        $response->assertSeeText('Confirm your Organization');
        $response->assertSee(route('organizations.tenant.store', $organization));
    }
    /** @test */
    public function can_store_organization_tenant()
    {
        $user = factory(User::class)->create();
        $organization = factory(Organization::class)->create(['name' => 'Dat Org']);

        $response = $this->actingAs($user)->followingRedirects()->post("/organizations/{$organization->id}/tenant", ['slug' => 'yaas']);

        $response->assertStatus(200);
        $this->assertEquals(url()->current(), route('tenant:admin.resource_timeline.meetings', $organization->tenant->slug));
        $response->assertSeeText('Dat Org');
    }
}
