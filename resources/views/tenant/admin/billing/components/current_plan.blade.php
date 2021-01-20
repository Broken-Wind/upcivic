@if(!tenant()->isSubscribed())
    <h1>Current Plan: Upcivic Free</h1>
    <p class="lead">Reduce manual data entry and optimize your activity schedule.</p>
@else
    <div class="jumbotron">
        <h1 class="display-4 text-primary">Upcivic Pro</h1>
        <p class="lead">All the tools you need to go paperless, reduce manual data entry, manage instructors, and optimize your activity schedule.</p>
        <hr/>

        @if ($user->isPaymentCardHolder())
            @if ($user->subscription(config('app.subscription_name'))->onGracePeriod())
                <p class="lead text-primary"> Cancels on {{ $user->subscription(config('app.subscription_name'))->ends_at->format('j F, Y') }} </p>
            @else
                <p class="lead text-primary">Need more seats for your organization? Contact us at <a href="mailto:support@upcivic.com?subject=Increase number of seats">support@upcivic.com</a>.</p>
                <p class="lead">
                    <a class="btn btn-secondary" href="{{ tenant()->route('tenant:admin.billing.subscriptions.cancel') }}" role="button" onClick="return confirm('Are you sure you want to cancel your Upcivic Pro plan?');">Cancel Pro</a>
                </p>
                <div class="text-mutted small" role="alert">
                    Canceling Pro plan will automatically switch to the Free plan. You'll no longer benefit from Partner Paperwork Submission, Document Generation & E-Signing, Instructor / Facility Management,
                    Full Compliance Management or One-Click Exports.
                </div>
            @endif
        @endif
    </div>
@endif
