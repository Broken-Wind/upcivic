@extends('layouts.app')
@section('title', 'Billing')
@section('content')
<div class="container">
    @include('shared.form_errors')
    <div class="card mb-4">
        <div class="card-header">Current Plan</div>
        <div class="card-body">
            @include('tenant.admin.billing.components.current_plan')
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
                        <form method="POST" action="{{ tenant()->route('tenant:admin.billing.payments') }}" enctype="multipart/form-data" id="updatePaymentMethod">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="number-of-seats">Number of Users</label>
                                      <input type="number"
                                          class="form-control" name="number-of-seats" id="number-of-seats" aria-describedby="seats-help" value="{{ tenant()->users->count() }}">
                                      <small id="seats-help" class="form-text text-muted">The maximum number of administrators from your organization using {{ config('app.name') }}.</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-lg mt-3" style="height:53%; display: inline-flex; align-items: center; justify-content: center;" href="">
                                        Upgrade to Pro
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <p class="lead text-danger">All Upcivic Pro seats are taken.</p>
                        <h5>Contact your card holder to increase the number of seats.</h5>
                    @endif
                    <div class="text-mutted" role="alert">
                        Upgrading to Pro you'll instantly gain access to Partner Paperwork Submission, Document Generation & E-Signing, Instructor / Facility Management,
                        Full Compliance Management and One-Click Exports.
                    </div>
                    {{-- <a href="{{ tenant()->route('tenant:admin.billing.portal') }}" role="button">Access Portal</a> --}}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
