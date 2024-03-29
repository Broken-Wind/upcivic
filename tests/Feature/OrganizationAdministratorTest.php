<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Administrator;
use App\Mail\ListedAsAdministrator;
use App\Organization;
use App\Person;
use App\User;
class OrganizationAdministratorTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_see_option_to_add_administrator_to_unclaimed_organization()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->states('hasTenant')->create();
        $tenant = $user->tenants()->first();
        $unclaimedOrganization = factory(Organization::class)->create();

        $response = $this->actingAs($user)->followingRedirects()->get("/{$tenant->slug}/admin/organizations/{$unclaimedOrganization->id}/edit");

        $response->assertStatus(200);
        $response->assertSeeText('Add Administrator');
        $response->assertSee($tenant->route('tenant:admin.organizations.administrators.store', [$unclaimedOrganization]));
    }
    /** @test */
    public function user_can_add_administrator_to_unclaimed_organization()
    {
        Mail::fake();
        $this->withoutExceptionHandling();
        $user = factory(User::class)->states('hasTenant')->create();
        $tenant = $user->tenants()->first();
        $unclaimedOrganization = factory(Organization::class)->create();

        $response = $this->actingAs($user)->followingRedirects()->post("/{$tenant->slug}/admin/organizations/{$unclaimedOrganization->id}/administrators", [
            'first_name' => 'Yo',
            'last_name' => 'Mamma',
            'email' => 'cool@email.com',
            'phone' => '234-456-5678',
            'title' => 'Mystery Man',
        ]);

        $response->assertStatus(200);
        Mail::assertSent(ListedAsAdministrator::class);
        $admin = $unclaimedOrganization->administrators->first();
        $this->assertEquals(1, $unclaimedOrganization->administrators->count());
        $this->assertEquals($admin['name'], 'Yo Mamma');
        $this->assertEquals($admin['email'], 'cool@email.com');
        $this->assertEquals($admin['title'], 'Mystery Man');
        $this->assertEquals($admin['phone'], '234-456-5678');
    }
    /** @test */
    public function user_can_see_administrators_of_unclaimed_organization()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->states('hasTenant')->create();
        $tenant = $user->tenants()->first();
        $unclaimedOrganization = factory(Organization::class)->create();
        $person = factory(Person::class)->create([
            'first_name' => 'John',
            'last_name' => 'Fakeman',
            'email' => 'John@fakeman.com',
            'phone' => '333-456-5678',
        ]);
        $unclaimedOrganization->administrators()->save($person, ['title' => 'FakeTitle']);

        $response = $this->actingAs($user)->get("/{$tenant->slug}/admin/organizations/{$unclaimedOrganization->id}/edit");

        $response->assertStatus(200);
        $response->assertSeeText('John Fakeman');
        $response->assertSeeText('John@fakeman.com');
        $response->assertSeeText('FakeTitle');
        $response->assertSeeText('333-456-5678');
    }
}
