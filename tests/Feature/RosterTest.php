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
use App\Tenant;
use App\User;
class RosterTest extends TestCase
{
    use RefreshDatabase;

    protected function getProgramWithEnrollments(Tenant $tenant)
    {
        $program = factory(Program::class)->state('amCamp', 'published', 'withParticipants')->create([
            'proposing_organization_id' => $tenant->organization_id,
        ]);
        $contributor = new Contributor();
        $contributor['organization_id'] = $tenant->organization_id;
        $program->contributors()->save($contributor);
        return $program;
    }

    /** @test */
    public function user_can_see_roster()
    {
        //
        $this->withoutExceptionHandling();
        $user = factory(User::class)->states('hasTenant')->create();
        $tenant = $user->tenants()->first();
        $program = $this->getProgramWithEnrollments($tenant);

        $response = $this->actingAs($user)->followingRedirects()->get(route('tenant:admin.programs.roster.edit', [$tenant->slug, $program]));

        $response->assertStatus(200);

        $program->tickets->each(function ($ticket) use ($response) {
            $participant = $ticket->participant;
            $response->assertSeeText($participant->first_name);
            $response->assertSeeText($participant->last_name);
            $response->assertSeeText($participant->needs);
            $response->assertSeeText($ticket->code);
            $response->assertSeeText($ticket->order_confirmation_number);
            $participant->contacts->each(function ($contact) use ($response) {
                $response->assertSeeText($contact->first_name);
                $response->assertSeeText($contact->last_name);
                $response->assertSeeText($contact->phone);
                $response->assertSeeText($contact->email);
            });
        });
    }

}
