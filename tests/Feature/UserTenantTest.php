<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Organization;
use App\Tenant;
use App\User;
class UserTenantTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_with_tenant_can_edit_tenant()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->states('hasTenant')->create();
        $tenant = $user->tenants()->first();
        $response = $this->actingAs($user)->followingRedirects()->patch("/{$tenant->slug}/admin/settings", [
            'name' => 'Bobby Dodgekins',
            'phone' => '415-222-2222',
            'email' => 'bobby@dodge.kins'
        ]);
        $tenant->refresh();
        $response->assertStatus(200);
        $this->assertEquals('Bobby Dodgekins', $tenant->name);
    }
    /** @test */
    public function user_cannot_edit_tenant_if_not_member()
    {
        $user = factory(User::class)->states('hasTenant')->create();
        $organizationUserDoesNotBelongTo = factory(Organization::class)->create([
            'name' => 'Should not change.',
        ]);
        $tenantUserDoesNotBelongTo = factory(Tenant::class)->create([
            'slug' => 'nochangey',
            'organization_id' => $organizationUserDoesNotBelongTo->id,
        ]);
        $response = $this->actingAs($user)->followingRedirects()->patch("/{$tenantUserDoesNotBelongTo->slug}/admin/settings", [
            'name' => 'Bobby Dodgekins',
        ]);
        $organizationUserDoesNotBelongTo->refresh();
        $response->assertStatus(401);
        $this->assertEquals('Should not change.', $organizationUserDoesNotBelongTo->name);
    }
}
