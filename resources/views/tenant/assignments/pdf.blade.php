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
        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
            bottom: 0px;
        }
        .pagenum:before {
            content: counter(page);
        }
    </style>
    <title>{{ $assignment->signableDocument->title }}</title>
</head>
<body>
    <div class="footer">
        Page <span class="pagenum"></span>
    </div>
    @include('tenant.assignments.signable_documents.components.document_content')

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
