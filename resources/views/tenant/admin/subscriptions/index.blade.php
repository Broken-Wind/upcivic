@extends('layouts.app')
@section('title', 'Billing')
@section('content')
<div class="container">
    @include('shared.form_errors')
    <div class="card mb-4">
        <div class="card-header">Current Plan</div>
        <div class="card-body">
            @include('tenant.admin.subscriptions.components.current_plan')
        </div>
    </div>

    @if(!tenant()->isSubscribed())
        <div class="card">
            <div class="card-header" id="availablePlans">Available Plans</div>
            <div class="card-body">
                <div class="jumbotron">
                    <h1 class="display-4 text-primary">Upcivic Pro</h1>
                    <p class="lead">All the tools you need to go paperless, reduce manual data entry, manage instructors, and optimize your activity schedule.</p>

                    <div class="row mb-4">
                        <div class="col-4">
                            <i class="fas fa-check"></i> Instant Proposal Generation <br/>
                            <i class="fas fa-check"></i> Schedule Summary <br/>
                            <i class="fas fa-check"></i> Partner Paperwork Submission <br/>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-check"></i> Document Generation & E-Signing <br/>
                            <i class="fas fa-check"></i> Instructor / Facility Management <br/>
                            <i class="fas fa-check"></i> Full Compliance Management <br/>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-check"></i> One-Click Exports <br/>
                        </div>
                    </div>

                    @if (!(tenant()->hasStripeCustomer()) || $user->isPaymentCardHolder())
                        <div class="form-row">
                            <div class="col-md-3">
                                <a type="submit" class="btn btn-primary btn-lg mt-3" style="height:53%; display: inline-flex; align-items: center; justify-content: center;" href="{{ tenant()->route('tenant:admin.subscriptions.create') }}">
                                    Upgrade to Pro
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="lead text-danger">All Upcivic Pro seats are taken.</p>
                        <h5>Contact your card holder to increase the number of seats.</h5>
                    @endif
                    <div class="text-mutted" role="alert">
                        Upgrading to Pro you'll instantly gain access to Partner Paperwork Submission, Document Generation & E-Signing, Instructor / Facility Management,
                        Full Compliance Management and One-Click Exports.
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
