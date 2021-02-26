<?php

namespace Tests\Unit\Mail;

use App\Contributor;
use App\Mail\OrderConfirmationEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Mail\ProposalSent;
use App\Organization;
use App\Program;
use App\Tenant;

class OrderConfirmationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_content_test()
    {
        $program = factory(Program::class)->states(['amCamp', 'published', 'withParticipants'])->create();
        $order = $program->tickets->first()->order;
        $tenant = $program->contributors->first()->organization->tenant;
        $email = new OrderConfirmationEmail($order, $tenant, $program);
        $rendered = $email->render();

        $this->assertStringContainsString($program->id, $rendered);
        $this->assertStringContainsString($program->name, $rendered);
        $this->assertStringContainsString($order->confirmation_number, $rendered);
        $route = $tenant->route('tenant:programs.orders.show', [$program, $order->confirmation_number]);
        $this->assertStringContainsString($route, $rendered);
    }

    /** @test */
    public function email_subject_test()
    {
        $program = factory(Program::class)->states(['amCamp', 'published', 'withParticipants'])->create();
        $order = $program->tickets->first()->order;
        $tenant = $program->contributors->first()->organization->tenant;
        $email = new OrderConfirmationEmail($order, $tenant, $program);
        $this->assertEquals("You're enrolled for {$program->name }!", $email->build()->subject);

    }
}
