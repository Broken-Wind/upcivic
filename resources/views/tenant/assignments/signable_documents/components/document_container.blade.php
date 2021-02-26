@push('scripts')
<script>
    var assignment = {
        'id': {{ $assignment->id }},
        'name': '{{ $assignment->name }}',
        'assigned_by_organization_id': {{ $assignment->assigned_by_organization_id }},
        'assigned_to_organization_id': {{ $assignment->assigned_to_organization_id }},
        'signed_at': '{{ $assignment->signableDocument->created_at }}',
    };
</script>
<script src="{{ asset('js/views/edit_assignment.js') }}" defer></script>
@endpush

<div class="card my-3">
    <div class="card-header">Document from {{ $assignment->assignedByOrganization->name }}</div>
    <div class="card-body">
        @include('tenant.assignments.signable_documents.components.document_content')
        <hr>
        <h3>Signatures</h3>
        @include('tenant.assignments.signable_documents.components.signature_area', [
            'organization' => $assignment->assignedToOrganization,
            'signature' => $assignment->getSignatureFrom($assignment->assignedToOrganization)
        ])
        @include('tenant.assignments.signable_documents.components.signature_area', [
            'organization' => $assignment->assignedByOrganization,
            'signature' => $assignment->getSignatureFrom($assignment->assignedByOrganization)
        ])

        <a class="btn btn-primary" href="{{ $assignment->pdf_url }}">Download as PDF</a>

        @if(!Auth::check())
            <form action="{{ route('login') }}" class="my-auto pt-5">
                <p>If your organization assigned this task, please log in to manage it. </p>
                <input type="submit" class="btn btn-primary" value="Log In" />
            </form>
        @endif
    </div>
</div>
