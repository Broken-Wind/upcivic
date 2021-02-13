<?php
namespace tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Mail\ListedAsAdministrator;
use App\Organization;
use App\Person;
use App\User;
class ListedAsAdministratorTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function email_content_test()
    {
        $lister = factory(User::class)->create([
            'email' => 'lister@test.com',
        ]);
        $organization = factory(Organization::class)->create([
            'name' => 'JimminyCrickets!',
        ]);
        $person = factory(Person::class)->create([
            'email' => 'person@test.com',
        ]);
        $organization->administrators()->save($person, ['title' => 'President OF THE WORLD!']);
        $email = new ListedAsAdministrator($lister, $organization, $person);
        $rendered = $email->render();
        $this->assertStringContainsString($lister->name, $rendered);
        $this->assertStringContainsString($organization->name, $rendered);
        $this->assertStringContainsString($person->name, $rendered);
        $this->assertStringContainsString('President OF THE WORLD!', $rendered);
        $this->assertStringContainsString(route('root'), $rendered);
    }
}
