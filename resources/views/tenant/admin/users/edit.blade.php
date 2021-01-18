@extends('layouts.app')
@section('title', 'Account')
@section('content')
<div class="container">
    @include('shared.form_errors')
    <div class="card mb-4">
        <div class="card-header">Your Profile</div>

            <div class="card-body">

                <form method="POST" action="{{ tenant()->route('tenant:admin.users.update', [$user]) }}">

                    @method('put')

                    @csrf

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') ?: $user['name'] }}" id="name" placeholder="John Smith" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Phone</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone') ?: $user['phone'] }}" id="phone" required>
                    </div>

                    <button type="submit" id="submit" class="btn btn-secondary">Update</button>

                </form>
            </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Current Plan</div>
        <div class="card-body">
            @if(!tenant()->isSubscribed())
                <div class="jumbotron">
                    <h1>Upcivic Free</h1>
                    <p class="lead">Reduce manual data entry and optimize your activity schedule.</p>
                    <div class="row mb-4">
                        <div class="col">
                            <i class="fas fa-check"></i> Instant Proposal Generation <br/> 
                            <i class="fas fa-check"></i> Schedule Summary <br/> 
                            <i class="fas fa-check"></i> Partner Paperwork Submission <br/> 
                        </div>
                    </div>
                    <p class="lead text-success">Active</p>
                </div> 
            @else
            <div class="jumbotron">
                <h1 class="display-4 text-primary">Upcivic Pro</h1>
                <p class="lead">All the tools you need to go paperless, reduce manual data entry, manage instructors, and optimize your activity schedule.</p>
                <hr/>
                
                @if ($user->isPaymentCardHolder())
                    @if ($user->subscription(config('app.subscription_name'))->onGracePeriod())
                        <p class="lead text-primary"> Canceles on {{$user->subscription(config('app.subscription_name'))->ends_at->format('j F, Y')}} </p>
                    @else
                        <p class="lead text-primary">Need more of seats for your organization? Contact us at <a href="mailto:support@upcivic.com?subject=Increase number of seats">support@upcivic.com</a>.</p>
                        <p class="lead">
                            <a class="btn btn-secondary" href="{{ tenant()->route('tenant:admin.billing.subscriptions.cancel') }}" role="button" onClick="return confirm('Are you sure you want to cancel your Upcivic Pro plan?');">Cancel Pro</a>
                        </p>
                        <div class="text-mutted small" role="alert">
                            Canceling Pro plan will automatically stiwtch to Free plan. You'll no longer benefit of Partner Paperwork Submission, Document Generation & E-Signing, Instructor / Facility Management, 
                            Full Compliance Management or One-Click Exports.
                        </div>
                    @endif
                @endif
            </div>
            @endif
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
                        
                    @if ($user->isPaymentCardHolder())
                        <p class="lead">
                            <a class="btn btn-primary btn-lg" href="{{ tenant()->route('tenant:admin.billing.payments') }}">Upgrade to Pro</a>
                        </p>
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
