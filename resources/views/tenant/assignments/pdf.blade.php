<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: 'Otto';
            src: url({{ storage_path('fonts\Otto.ttf') }}) format("truetype");
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
    <title>{{ $assignment->signableDocument->title }}</title>
</head>
<body>
    <h1>{{ $assignment->signableDocument->title }}</h1>
    {!! $assignment->signableDocument->content !!}
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

    <h3>Signatures</h3>
    @include('tenant.assignments.signable_documents.components.signature_area', [
        'organization' => $assignment->assignedToOrganization,
        'signature' => $assignment->getSignatureFrom($assignment->assignedToOrganization)
    ])
    @include('tenant.assignments.signable_documents.components.signature_area', [
        'organization' => $assignment->assignedByOrganization,
        'signature' => $assignment->getSignatureFrom($assignment->assignedByOrganization)
    ])

</body>
