<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Tenant;
use App\User;
class TenantSettingsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function settings_content_test()
    {
        $tenant = factory(Tenant::class)->states('hasTwoUsers', 'hasTwoAdministrators')->create();
        $user = $tenant->users()->first();

        $response = $this->actingAs($user)->followingRedirects()->get($tenant->route('tenant:admin.edit'));

        $response->assertStatus(200);
        $this->assertEquals(url()->current(), config('app.url')."/{$tenant->slug}/admin/settings");
        $response->assertSeeText($user->email);
        $response->assertSeeText($tenant->organization->administrators[0]['name']);
        $response->assertSeeText($tenant->organization->administrators[0]['email']);
        $response->assertSeeText($tenant->organization->administrators[1]['name']);
        $response->assertSeeText($tenant->organization->administrators[1]['email']);
    }
}
