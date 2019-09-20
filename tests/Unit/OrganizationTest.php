<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Organization;
use Upcivic\Tenant;
use Upcivic\User;

class OrganizationTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function users_returns_tenant_users()
    {

        $tenant = factory(Tenant::class)->states('hasTwoUsers')->create();


        $this->assertEquals(2, $tenant->organization->users->count());

        $this->assertEquals($tenant->users[0]['email'], $tenant->organization->users[0]['email']);

        $this->assertEquals($tenant->users[1]['email'], $tenant->organization->users[1]['email']);


    }

    /** @test */
    public function has_tenant_method_detects_presence_of_tenant()
    {
        $tenant = factory(Tenant::class)->create();

        $organization = $tenant->organization;


        $this->assertTrue($organization->hasTenant());

    }

    /** @test */
    public function has_tenant_method_detects_absence_of_tenant()
    {
        $organization = factory(Organization::class)->create();


        $this->assertFalse($organization->hasTenant());

    }

    /** @test */
    public function vacant_method_detects_absence_of_users_and_administrators()
    {

        $tenant = factory(Tenant::class)->create();

        $organization = $tenant->organization;


        $this->assertTrue($organization->isVacant());

    }

    /** @test */
    public function vacant_method_detects_presence_of_users()
    {

        $tenant = factory(Tenant::class)->states('hasTwoUsers')->create();

        $organization = $tenant->organization;


        $this->assertFalse($organization->isVacant());

    }

    /** @test */
    public function vacant_method_detects_presence_of_administrators()
    {

        $organization = factory(Organization::class)->states('hasAdministrator')->create();


        $this->assertFalse($organization->isVacant());

    }

    /** @test */
    public function has_administrator_email_method_detects_presence_of_administrator_email()
    {

        $organization = factory(Organization::class)->states('hasAdministrator')->create();

        $administrator = $organization->administrators()->first();


        $this->assertTrue($organization->hasAdministratorEmail($administrator['email']));

    }

    /** @test */
    public function has_administrator_email_method_detects_absence_of_administrator_email()
    {

        $organization = factory(Organization::class)->create();


        $this->assertFalse($organization->hasAdministratorEmail('jimmmy@two.boots'));

    }


}
