<?php
namespace Tests\Feature;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Contributor;
use App\Mail\ProposalSent;
use App\Organization;
use App\Program;
use App\Template;
use App\User;
class ProgramTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_see_program_create_view()
    {
        $user = factory(User::class)->states('hasTenant')->create();
        $tenant = $user->tenants()->first();
        $template = factory(Template::class)->create(['organization_id' => $tenant->organization->id]);
        $response = $this->actingAs($user)->followingRedirects()->get("/{$tenant->slug}/admin/programs/create");
        $response->assertSeeText('Program');
        $response->assertSeeText('Site');
        $response->assertSeeText('Start Date');
        $response->assertSeeText('End Date');
    }
    /** @test */
    public function user_can_create_program()
    {
        $this->withoutExceptionHandling();
        Mail::fake();
        $user = factory(User::class)->states('hasTenant')->create();
        $tenant = $user->tenants()->first();
        $recipientOrganization = factory(Organization::class)->create();
        $this->assertEquals(0, $tenant->organization->templatesWithoutScope->count());
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
            'min_enrollments' => '11',
            'max_enrollments' => '111',
            'organization_id' => $tenant->organization->id,
		]);

        $response = $this->actingAs($user)->followingRedirects()->post("/{$tenant->slug}/admin/programs", [
            'recipient_organization_id' => $recipientOrganization->id,
            'site_id' => null,
			'start_date' => '1212-01-01',
			'start_time' => '09:00',
			'end_date' => null,
			'end_time' => null,
			'template_id' => $template->id,
			'ages_type' => null,
			'min_age' => null,
			'max_age' => null,
        ]);
		$tenant->refresh();

        $response->assertStatus(200);
        Mail::assertNotSent(ProposalSent::class);
        $program = Program::first();
        $this->assertEquals($program->name, 'Template Name');
        $this->assertEquals($program->internal_name, 'Internal Name');
        $this->assertEquals($program->description, 'Long description');
        $this->assertEquals($program->public_notes, 'Pubnotes');
        $this->assertEquals($program->contributor_notes, 'Contnotes');
        $this->assertEquals($program->min_age, '12');
        $this->assertEquals($program->max_age, '13');
        $this->assertEquals($program->min_enrollments, '11');
        $this->assertEquals($program->tickets->count(), '111');
        $this->assertEquals($program->ages_type, 'grades');
        $this->assertEquals($program->formatted_base_fee, '11.99');
        $this->assertEquals($program->start_time, '9:00am');
        $this->assertEquals($program->end_time, '11:00am');
        $this->assertEquals(Carbon::parse($program->meetings[0]['start_datetime'])->diffInDays($program->meetings[1]['start_datetime']), 7);
        $this->assertEquals($program->meetings->count(), 3);
    }
    /** @test */
    public function user_can_edit_program()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->states('hasTenant')->create();
        $tenant = $user->tenants()->first();
        $this->assertEquals(0, $tenant->organization->templatesWithoutScope->count());
        $program = factory(Program::class)->state('amCamp', 'published')->create();
        $program->proposing_organization_id = $tenant->organization_id;
        $program->save();
        factory(Contributor::class)->create([
            'program_id' => $program->id,
            'organization_id' => $tenant->organization_id,
            'internal_registration' => false
        ]);

        $response = $this->actingAs($user)->followingRedirects()->put("/{$tenant->slug}/admin/programs/{$program->id}", [
            'name' => 'Sweet Radcamp',
            'internal_name' => 'Sweet',
            'description' => 'Radcamp',
            'public_notes' => 'Cool beens',
            'contributor_notes' => 'Hot notez',
            'ages_type' => 'ages',
            'min_age' => '89',
            'max_age' => '99',
        ]);
		$program->refresh();

        $response->assertStatus(200);
        $this->assertEquals($program->name, 'Sweet Radcamp');
        $this->assertEquals($program->internal_name, 'Sweet');
        $this->assertEquals($program->description, 'Radcamp');
        $this->assertEquals($program->public_notes, 'Cool beens');
        $this->assertEquals($program->contributor_notes, 'Hot notez');
        $this->assertEquals($program->ages_type, 'ages');
        $this->assertEquals($program->min_age, '89');
        $this->assertEquals($program->max_age, '99');
    }

    /** @test */
    public function user_can_edit_registration_options()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->states('hasTenant')->create();
        $tenant = $user->tenants()->first();
        $this->assertEquals(0, $tenant->organization->templatesWithoutScope->count());
        $program = factory(Program::class)->state('amCamp', 'published')->create();
        $program->contributors->first()->update(['internal_registration' => false]);
        $program->proposing_organization_id = $tenant->organization_id;
        $program->save();
        factory(Contributor::class)->create([
            'program_id' => $program->id,
            'organization_id' => $tenant->organization_id,
            'internal_registration' => false
        ]);

        $response = $this->actingAs($user)->followingRedirects()->put("/{$tenant->slug}/admin/programs/{$program->id}/update_registration_options", [
            'min_enrollments' => 393,
            'max_enrollments' => 494,
            'enrollment_url' => 'https://google.com',
            'enrollment_message' => 'You rock, homeboy!',
            'enrollment_instructions' => 'Get enrolled today!',
        ]);
		$program->refresh();

        $response->assertStatus(200);
        $this->assertEquals($program->min_enrollments, 393);
        $this->assertEquals($program->max_enrollments, 494);
        $contributor = $program->getContributorFor($tenant);
        $this->assertEquals($contributor->enrollment_url, 'https://google.com');
        $this->assertEquals($contributor->enrollment_message, 'You rock, homeboy!');
        $this->assertEquals($contributor->enrollment_instructions, 'Get enrolled today!');
    }

    /** @test */
    public function other_contributor_can_update_limited_enrollment_info()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->states('hasTenant')->create();
        $tenant = $user->tenants()->first();
        $program = factory(Program::class)->state('amCamp', 'published')->create([
            'min_enrollments' => 3
        ])->addTickets(10);
        factory(Contributor::class)->create([
            'program_id' => $program->id,
            'organization_id' => $tenant->organization_id,
            'internal_registration' => false
        ]);

        $response = $this->actingAs($user)->followingRedirects()->put("/{$tenant->slug}/admin/programs/{$program->id}/update_registration_options", [
            'min_enrollments' => 393,
            'min_enrollments' => 10,
            'max_enrollments' => 494,
            'enrollment_url' => 'https://google.com',
            'enrollment_message' => 'You rock, homeboy!',
            'enrollment_instructions' => 'Get enrolled today!',
        ]);
		$program->refresh();

        $response->assertStatus(200);
        $this->assertEquals($program->min_enrollments, 3);
        $this->assertEquals($program->enrollments, 0);
        $this->assertEquals($program->max_enrollments, 10);
        $contributor = $program->getContributorFor($tenant);
        $this->assertEquals($contributor->enrollment_url, 'https://google.com');
        $this->assertEquals($contributor->enrollment_message, 'You rock, homeboy!');
        $this->assertEquals($contributor->enrollment_instructions, 'Get enrolled today!');
    }
}
