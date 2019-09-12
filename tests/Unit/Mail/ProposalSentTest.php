<?php

namespace Tests\Unit\Mail;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Mail\ProposalSent;
use Upcivic\Organization;
use Upcivic\Program;
use Upcivic\Tenant;

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

        $proposal = collect([

            'sender' => $proposingTenant->users()->first(),

            'sending_organization' => $proposingTenant->organization,

            'recipient_organization' => $recipientTenant->organization,

            'programs' => [$program],

        ]);



        $email = new ProposalSent($proposal);

        $rendered = $email->render();



        $this->assertContains($proposal['sender']->name, $rendered);

        $this->assertContains('Proposing Organization', $rendered);

        $this->assertContains('Recipient Organization', $rendered);

        $this->assertContains(route('root'), $rendered);

    }

    public function render($mailable)
    {

        $mailable->build();

        return view($mailable->view(), $mailable->buildViewData())->render();

    }
}
