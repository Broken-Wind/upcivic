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
            border: 1px solid black;
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
    <title>{{ "#{$program->id} {$program->name} at {$program->site->name} - Daily Attendance" }}</title>
</head>
<body>
    <div class="footer">
        Page <span class="pagenum"></span>, Generated via {{ config('app.name') }}
    </div>
    @foreach($program->meetings as $meeting)
        <h2>{{ "{$meeting->start_date} Attendance - #{$program->id} {$program->name} at {$program->site->name}" }}</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Participant</th>
                    <th>Signature In</th>
                    <th>Signature Out</th>
                </tr>
            </thead>
            @foreach($program->tickets()->unavailable()->get()->sortBy('participant.last_name') as $ticket)
                <tr>
                    <td style="text-align: center;">
                        #{{ $loop->iteration }}
                    </td>
                    <td>
                        @if(isset($ticket->participant))
                            <strong>
                                {{ $ticket->participant->name }}
                            </strong>
                        @else
                            Unknown Participant
                        @endif
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </table>
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
