@push('css')
    <style>
        @font-face {
            font-family: Otto;
            src: url({{ asset('fonts/Otto.ttf') }}) format("truetype");
            font-weight: 400; // use the matching font-weight here ( 100, 200, 300, 400, etc).
            font-style: normal; // use the matching font-style here
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        .signature {
            font-family: Otto, Times, serif;
            font-size: 36px;
        }
        .page-break {
            page-break-after: always;
        }
        table {
            width: 100%;
        }
        th {
            text-align: left;
            padding: 5px;
        }
        td {
            padding: 5px;
        }
    </style>
@endpush
<div class="card">
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
    </div>
</div>
