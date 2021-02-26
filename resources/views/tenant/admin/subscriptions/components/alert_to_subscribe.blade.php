<div class="alert alert-info">
    <h4><i class="fas fa-fw fa-lock "></i> Registration is a Pro Feature</h4>
    You must upgrade to {{ config('app.name') }} Pro to accept registrations. Once you upgrade, return to this page to configure your Stripe account.<br />
    <a href="{{ tenant()->route('tenant:admin.subscriptions.create') }}" class="btn btn-primary mt-3">Upgrade to Pro</a>
</div>
