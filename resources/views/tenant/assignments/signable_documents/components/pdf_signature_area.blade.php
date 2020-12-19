
<div class="signature-container alert alert-secondary mb-4">
    <h4>
        {{ $organization->name }} Representative
    </h4>
    @if(empty($signature))
        {{ $organization->name }} hasn't signed this document yet.
    @else
        <span class="signature">
            {{ $signature->signature }}
        </span>
        <br>
        <small class="text-muted">
            {{ $signature->created_at }}
            {{ $signature->ip }}
        </small>
    @endif
</div>
