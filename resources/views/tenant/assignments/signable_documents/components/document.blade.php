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
        <h3>{{ $assignment->signableDocument->title ?? 'Untitled Document' }}</h3>
        {!! $assignment->signableDocument->content ?? 'Document not found.' !!}
        <hr>
        <h3>Programs ({{ $programs->count() }} total)</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name & Location</th>
                <th>Date & Time</th>
                <th>Base Fee</th>
            </tr>
            @forelse($programs as $program)
                <tr>
                    <td>{{ $program->id }}</td>
                    <td>
                        {{ $program->name }}<br />
                        {{ $program->site->name }} - {{ $program->location->name }}</td>
                    <td>
                        {{ $program['start_date'] . " - " . $program['end_date'] }}
                        <br />
                        {{ $program['start_time'] }}-{{ $program['end_time'] }}
                    </td>
                    <td>${{ $program->formatted_base_fee }} {{ $program->shared_invoice_type }}</td>
                </tr>
            @empty
            @endforelse
        </table>
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
