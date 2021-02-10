<?php

namespace Tests\Unit\Mail;

use App\Contributor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Mail\ProposalSent;
use App\Organization;
use App\Program;
use App\Tenant;

class ProposalSentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_content_test()
    {
        $proposingOrganization = factory(Organization::class)->create([
            'name' => 'Proposing Organization',
        ]);
        $recipientOrganization = factory(Organization::class)->create([
            'name' => 'Recipient Organization',
        ]);
        $proposingTenant = factory(Tenant::class)->states('hasTwoUsers')->create([
            'organization_id' => $proposingOrganization->id,
        ]);
        $recipientTenant = factory(Tenant::class)->states('hasTwoUsers')->create([
            'organization_id' => $recipientOrganization->id,
        ]);

        $program = factory(Program::class)->states('amCamp')->create();
        factory(Contributor::class)->create([
            'program_id' => $program->id,
            'organization_id' => $proposingOrganization->id
        ]);
        factory(Contributor::class)->create([
            'program_id' => $program->id,
            'organization_id' => $recipientOrganization->id
        ]);
        $proposal = collect([
            'sender' => $proposingTenant->users()->first(),
            'sending_organization' => $proposingTenant->organization,
            'recipient_organizations' => collect([$recipientTenant->organization]),
            'programs' => [$program],
        ]);

        $email = new ProposalSent($proposal);

        $rendered = $email->render();

        $this->assertStringContainsString($proposal['sender']->name, $rendered);

        $this->assertStringContainsString($proposingOrganization->name, $rendered);

        $this->assertStringContainsString($recipientOrganization->name, $rendered);

        $this->assertStringContainsString(route('root'), $rendered);
    }

    public function render($mailable)
    {
        $mailable->build();

        return view($mailable->view(), $mailable->buildViewData())->render();
    }
}
