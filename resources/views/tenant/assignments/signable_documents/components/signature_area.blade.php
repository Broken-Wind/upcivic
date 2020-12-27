@push('scripts')
<script>
    var assignment = {
        'id': {{ $assignment->id }},
        'name': '{{ $assignment->name }}',
        'assigned_by_organization_id': {{ $assignment->assigned_by_organization_id }},
        'assigned_to_organization_id': {{ $assignment->assigned_to_organization_id }},
    };
</script>
<script src="{{ asset('js/views/edit_assignment.js') }}" defer></script>
@endpush


<div class="signature-container alert alert-secondary mb-4">
    <h4>
        {{ $organization->name }} Representative
    </h4>
    @if(empty($signature))
        @if($assignment->isSignableBy($organization, \Request::route()->getName()))
            <form method="POST" action="{{ tenant()->route('tenant:assignments.signatures.store', [$assignment]) }}">
                @csrf
                <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Your signature" name="signature">
                    <div class="input-group-append">
                        <button id="apply-signature-button" class="btn btn-primary" type="submit" onClick="return confirm('Are you sure you want to sign this document?')">Apply Signature</button>
                    </div>
                </div>
                <small class="form-text text-muted">Type your name</small>
            </form>
        @else
            {{ $organization->name }} hasn't signed this document yet.
        @endif
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
