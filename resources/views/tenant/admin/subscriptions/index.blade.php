@extends('layouts.app')
@section('title', 'Available Plans')
@section('content')
<div class="container">
    @include('shared.form_errors')
    @include('shared.loader')
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
                            <i class="fas fa-check"></i> Online Registration <br/>
                            <i class="fas fa-check"></i> Instructor / Facility Management <br/>
                            <i class="fas fa-check"></i> Document Generation & E-Signing <br/>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-check"></i> Embeddable Widget <br/>
                            <i class="fas fa-check"></i> Full Compliance Management <br/>
                            <i class="fas fa-check"></i> Partner Paperwork Submission <br/>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-check"></i> One-Click Exports <br/>
                        </div>
                    </div>

                    @if (!(tenant()->hasStripeCustomer()) || $user->isPaymentCardHolder())
                        <div class="form-row">
                            <div class="col-md-3">
                                <a type="submit" class="btn btn-primary btn-lg my-3" href="{{ tenant()->route('tenant:admin.subscriptions.create') }}">
                                    Upgrade to Pro
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="lead text-danger">All Upcivic Pro seats are taken.</p>
                        <h5>Contact your card holder to increase the number of seats.</h5>
                    @endif
                    <div class="text-mutted" role="alert">
                        You'll gain instant access to all features including Document Generation & E-Signing, Instructor / Facility Management,
                        Full Compliance Management and One-Click Exports.
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
