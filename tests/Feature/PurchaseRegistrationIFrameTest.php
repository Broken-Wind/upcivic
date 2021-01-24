<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Program;
use App\Billing\FakePaymentGateway;
use App\Billing\PaymentGateway;

class PurchaseRegistrationIFrameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_purchase_registrations()
    {

        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway); 

        $program = factory(Program::class)->states('amCamp', 'published')->create();

        $tenantSlug = $program->contributors->first()->organization->tenant->slug;
        
        $response = $this->postJson("{$tenantSlug}/iframe/{$program->id}/orders", [
            'email' => 'macarie@example.com',
            'registration_quantity' => 3,
            'payment_token' => $paymentGateway->getValidTestToken(),
        ]);

        $response->assertStatus(201);

        // Make sure the customer was charged the right amount
        $this->assertEquals(39000, $paymentGateway->totalCharges());

        // Make sure that an order exists for this customer 
        $this->assertTrue($program->orders()->contains(function($order){
            return $order->email == 'macarie@example.com';
        }));

        $order = $concert->orders()->where('email', 'macarie@example.com')->first();
        $this->assertEquals(3, $order->registrations->count());
    }
}