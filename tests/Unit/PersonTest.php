<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Person;

class PersonTest extends TestCase
{

    use RefreshDatabase;
    /** @test */
    public function person_has_a_name()
    {
        $person = factory(Person::class)->make([

            'first_name' => 'Smitty',

            'last_name' => 'Johansson',

        ]);


        $this->assertEquals($person->name, 'Smitty Johansson');

    }
}
