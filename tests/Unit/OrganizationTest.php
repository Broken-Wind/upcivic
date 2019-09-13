<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Organization;
use Upcivic\Tenant;

class OrganizationTest extends TestCase
{

    use RefreshDatabase;

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


        $this->assertTrue($organization->vacant());

    }

    /** @test */
    public function vacant_method_detects_presence_of_users()
    {

        $tenant = factory(Tenant::class)->states('hasTwoUsers')->create();

        $organization = $tenant->organization;


        $this->assertFalse($organization->vacant());

    }

    /** @test */
    public function vacant_method_detects_presence_of_administrators()
    {

        $organization = factory(Organization::class)->states('hasAdministrator')->create();


        $this->assertFalse($organization->vacant());

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
