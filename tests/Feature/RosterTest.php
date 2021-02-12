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
class RosterTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_see_roster()
    {
        //
    }

}
