<?php
namespace Tests\Feature;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Contributor;
use App\Mail\ProgramCanceledContributors;
use App\Mail\ProgramCanceledParticipants;
use App\Mail\ProposalSent;
use App\Organization;
use App\Program;
use App\Template;
use App\User;
class CancelProgramTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function cancelling_program_with_participants()
    {
        Mail::fake();
        $program = factory(Program::class)->states(['amCamp', 'published', 'withParticipants'])->create();
        $tenant = $program->contributors->first()->organization->tenant;
        $user = factory(User::class)->create();
        $user->joinTenant($tenant);
        $this->assertCount(1, $tenant->organization->programs);

        $response = $this->actingAs($user)->followingRedirects()->delete("/{$tenant->slug}/admin/programs/{$program->id}");
        $tenant->refresh();

        $this->assertCount(0, $tenant->organization->programs);

        // Participants notified
        $program->participants->map(function ($participant)  {
            return $participant->primaryContact()->email;
        })->unique()->each(function ($email) {
            Mail::assertSent(ProgramCanceledParticipants::class, function ($mail) use ($email) {
                return $mail->hasTo($email);
            });
        });

        // Contributors notified
        $program->contributors->map(function ($contributor) {
            return $contributor->organization->emailableContacts()->pluck('email');
        })->flatten()->unique()->each(function ($email) {
            Mail::assertSent(ProgramCanceledContributors::class, function ($mail) use ($email) {
                return $mail->hasTo($email);
            });
        });
    }

    /** @test */
    public function non_proposing_organization_cant_cancel_program_with_participants()
    {
        Mail::fake();
        $program = factory(Program::class)->states(['amCamp', 'published', 'withParticipants'])->create();
        $contributor = factory(Contributor::class)->states(['hasTenant'])->create([
            'program_id' => $program->id
        ]);
        $user = factory(User::class)->create();
        $tenant = $contributor->organization->tenant;
        $user->joinTenant($tenant);
        $this->assertCount(1, $tenant->organization->programs);

        $response = $this->actingAs($user)->followingRedirects()->delete("/{$tenant->slug}/admin/programs/{$program->id}");
        $tenant->refresh();

        $response->assertStatus(401);
        $this->assertCount(1, $tenant->organization->programs);
    }


    /** @test */
    public function cancelling_program_without_participants()
    {
        // Contributors notified
    }
}
