<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Mail\ListedAsAdministrator;
use Upcivic\Organization;
use Upcivic\User;
use Upcivic\Person;

class ListedAsAdministratorTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function email_content_test()
    {

        $lister = factory(User::class)->create([

            'email' => 'lister@test.com'

        ]);

        $organization = factory(Organization::class)->create([

            'name' => 'JimminyCrickets!'

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

    public function render($mailable)
    {

        $mailable->build();

        return view($mailable->view(), $mailable->buildViewData())->render();

    }
}
