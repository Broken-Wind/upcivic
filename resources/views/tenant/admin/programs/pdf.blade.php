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
    <title>Letter of Agreement</title>
</head>
<body>

@forelse($contributorGroups as $programs)
<h3>{{ $task->metadata['document_title'] }}</h3>
{{ $task->metadata['document_text'] }}
<table>
    @forelse($programs->first()->contributors as $contributor)
    <tr>
        <th colspan="5" style="text-align: left; padding-top:20px;">{{ $contributor->organization->name }}</th>
    </tr>
    <tr>
        <td class="signature">Jimbob Johnson</td>
        <td></td>
        <td style="vertical-align: bottom; padding-bottom: 10px;">12/24/2020 at 2:46pm</td>
    </tr>
    @empty
    @endforelse
</table>
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
    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@empty
@endforelse

</body>
