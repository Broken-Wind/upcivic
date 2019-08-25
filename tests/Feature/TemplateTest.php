<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Template;
use Upcivic\User;

class TemplateTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function user_can_create_template()
    {

        $user = factory(User::class)->states('hasOrganization')->create();

        $organization = $user->organizations()->first();

        $this->assertEquals(0, $organization->templatesWithoutScope->count());


        $response = $this->actingAs($user)->followingRedirects()->post("/{$organization->slug}/admin/templates", [

            'name' => 'Template Name',
            'internal_name' => 'Internal Name',
            'description' => 'Long description',
            'public_notes' => 'Pubnotes',
            'contributor_notes' => 'Contnotes',
            'min_age' => '12',
            'max_age' => '13',
            'ages_type' => 'grades',
            'invoice_amount' => '11.99',
            'invoice_type' => 'per participant',
            'meeting_minutes' => '120',
            'meeting_interval' => '7',
            'meeting_count' => '3',

        ]);


        $response->assertStatus(200);

        $this->assertEquals(1, $organization->templates->count());

        $template = $organization->templates->first();

        $this->assertEquals($template['name'], 'Template Name');
        $this->assertEquals($template['internal_name'], 'Internal Name');
        $this->assertEquals($template['description'], 'Long description');
        $this->assertEquals($template['public_notes'], 'Pubnotes');
        $this->assertEquals($template['contributor_notes'], 'Contnotes');
        $this->assertEquals($template['min_age'], '12');
        $this->assertEquals($template['max_age'], '13');
        $this->assertEquals($template['ages_type'], 'grades');
        $this->assertEquals($template['invoice_amount'], '1199');
        $this->assertEquals($template['invoice_type'], 'per participant');
        $this->assertEquals($template['meeting_minutes'], '120');
        $this->assertEquals($template['meeting_interval'], '7');
        $this->assertEquals($template['meeting_count'], '3');


    }

    /** @test */
    public function user_can_edit_template()
    {

        $user = factory(User::class)->states('hasOrganization')->create();

        $organization = $user->organizations()->first();

        $this->assertEquals(0, $organization->templatesWithoutScope->count());

        $template = factory(Template::class)->create([

            'organization_id' => $organization->id,

        ]);


        $response = $this->actingAs($user)->followingRedirects()->put("/{$organization->slug}/admin/templates/{$template->id}", [

            'name' => 'Dat Tempo',
            'internal_name' => 'Interno',
            'description' => 'Descyio',
            'public_notes' => 'Poobster',
            'contributor_notes' => 'Contso',
            'min_age' => '12',
            'max_age' => '32',
            'ages_type' => 'grades',
            'invoice_amount' => '29.98',
            'invoice_type' => 'per participant',
            'meeting_minutes' => '321',
            'meeting_interval' => '7',
            'meeting_count' => '3',

        ]);


        $response->assertStatus(200);

        $this->assertEquals(1, $organization->templates->count());

        $template->refresh();



        $this->assertEquals($template['name'], 'Dat Tempo');
        $this->assertEquals($template['internal_name'], 'Interno');
        $this->assertEquals($template['description'], 'Descyio');
        $this->assertEquals($template['public_notes'], 'Poobster');
        $this->assertEquals($template['contributor_notes'], 'Contso');
        $this->assertEquals($template['min_age'], '12');
        $this->assertEquals($template['max_age'], '32');
        $this->assertEquals($template['ages_type'], 'grades');
        $this->assertEquals($template['invoice_amount'], '2998');
        $this->assertEquals($template['invoice_type'], 'per participant');
        $this->assertEquals($template['meeting_minutes'], '321');
        $this->assertEquals($template['meeting_interval'], '7');
        $this->assertEquals($template['meeting_count'], '3');


    }

    /** @test */
    public function user_can_delete_template()
    {

        $user = factory(User::class)->states('hasOrganization')->create();

        $organization = $user->organizations()->first();

        $this->assertEquals(0, $organization->templatesWithoutScope->count());

        $template = factory(Template::class)->create([

            'organization_id' => $organization->id,

        ]);

        $organization->refresh();

        $this->assertEquals(1, $organization->templatesWithoutScope->count());



        $response = $this->actingAs($user)->followingRedirects()->delete("/{$organization->slug}/admin/templates/{$template->id}");

        $organization->refresh();



        $response->assertStatus(200);

        $this->assertEquals(0, $organization->templatesWithoutScope->count());

        $response->assertSeeText('Template has been deleted.');


    }
}
