<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Organization;
use Upcivic\Program;
use Upcivic\Template;
use Upcivic\User;

class ProgramTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function user_can_create_program()
    {

        $this->withoutExceptionHandling();
        $user = factory(User::class)->states('hasOrganization')->create();

        $organization = $user->organizations()->first();

        $organization2 = factory(Organization::class)->create();

        $this->assertEquals(0, $organization->templatesWithoutScope->count());

        $template = factory(Template::class)->create([

            'name' => 'Template Name',
            'internal_name' => 'Internal Name',
            'description' => 'Long description',
            'public_notes' => 'Pubnotes',
            'contributor_notes' => 'Contnotes',
            'min_age' => '12',
            'max_age' => '13',
            'ages_type' => 'grades',
            'invoice_amount' => '1199',
            'invoice_type' => 'per participant',
            'meeting_minutes' => '120',
            'meeting_interval' => '7',
            'meeting_count' => '3',

            'organization_id' => $organization->id,

        ]);


        $response = $this->actingAs($user)->followingRedirects()->post("/{$organization->slug}/admin/programs", [

            'organization_id' => $organization2->id,
            'site_id' => null,
            'programs' => [
                0 => [
                    'start_date' => '1212-01-01',
                    'start_time' => '09:00',
                    'end_date' => null,
                    'end_time' => null,
                    'template_id' => $template->id,
                    'ages_type' => null,
                    'min_age' => null,
                    'max_age' => null,
                ],

            ],


        ]);

        $organization->refresh();


        $response->assertStatus(200);

        $program = Program::first();

        $this->assertEquals($program['name'], 'Template Name');
        $this->assertEquals($program['internal_name'], 'Internal Name');
        $this->assertEquals($program['description'], 'Long description');
        $this->assertEquals($program['public_notes'], 'Pubnotes');
        $this->assertEquals($program['contributor_notes'], 'Contnotes');
        $this->assertEquals($program['min_age'], '12');
        $this->assertEquals($program['max_age'], '13');
        $this->assertEquals($program['ages_type'], 'grades');
        $this->assertEquals($program['formatted_base_fee'], '11.99');
        $this->assertEquals($program['start_time'], '9:00am');
        $this->assertEquals($program['end_time'], '11:00am');
        $this->assertEquals(Carbon::parse($program->meetings[0]['start_datetime'])->diffInDays($program->meetings[1]['start_datetime']), 7);
        $this->assertEquals($program->meetings->count(), 3);

    }

}

