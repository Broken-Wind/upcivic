<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Person;
use App\Tenant;
use App\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function joining_tenant_lists_user_as_administrator()
    {
        $user = factory(User::class)->create([

            'name' => 'Roberto Consiglio',

        ]);

        $tenant = factory(Tenant::class)->create();

        $user->joinTenant($tenant);

        $tenant->refresh();

        $this->assertEquals($tenant->organization->administrators->first()['name'], $user['name']);

        $this->assertEquals(1, $tenant->organization->administrators->count());
    }

    /** @test */
    public function duplicate_administrator_not_created_when_user_joins_tenant()
    {
        $user = factory(User::class)->create([

            'name' => 'Roberto Consiglio',

            'email' => 'roberto@consig.lio',

        ]);

        $tenant = factory(Tenant::class)->create();

        $tenant->organization->administrators()->save(factory(Person::class)->create([

            'first_name' => 'Roberto',

            'last_name' => 'Consiglio',

            'email' => 'roberto@consig.lio',

        ]));

        $tenant->refresh();

        $user->joinTenant($tenant);

        $tenant->refresh();

        $this->assertEquals(1, $tenant->organization->administrators->count());
    }
}
