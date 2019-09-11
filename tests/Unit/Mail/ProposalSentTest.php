<?php

namespace Tests\Unit\Mail;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Upcivic\Mail\ProposalSent;
use Upcivic\Organization;

class ProposalSentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_content_test()
    {
        $proposingOrganization = factory(Organization::class)->states('hasTwoUsers')->create([

            'name' => 'Proposing Organization',

        ]);

        $recipientOrganization = factory(Organization::class)->states('hasTwoUsers')->create([

            'name' => 'Recipient Organization',

        ]);

        $proposal = collect([

            'sender' => $proposingOrganization->users()->first(),

            'sending_organization' => $proposingOrganization,

            'recipient_organization' => $recipientOrganization,

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
