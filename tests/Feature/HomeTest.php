<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Administrator;
use App\Organization;
use App\Person;
use App\User;
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
        $organization1->administrators()->save($person, ['title' => 'Waverunner']);
        $user = factory(User::class)->create([
            'email' => 'wave@runner.com',
        ]);

        $response = $this->actingAs($user)->get('/home');

        $response->assertSeeText('Is your organization already listed?');
        $response->assertSee('Deez Rex');
        $response->assertSee('Notrex');
        $response->assertSee(route('organizations.users.store'));
    }
}
