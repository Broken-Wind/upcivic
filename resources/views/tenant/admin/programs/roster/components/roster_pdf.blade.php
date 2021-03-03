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
    <title>{{ "#{$program->id} {$program->name} at {$program->site->name} - Roster" }}</title>
</head>
<body>
    <div class="footer">
        Page <span class="pagenum"></span>, Generated via {{ config('app.name') }}
    </div>
    <h2>{{ "#{$program->id} {$program->name} at {$program->site->name} - Roster" }}</h2>

    <table class="table table-striped">
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
                        @if(!empty($ticket->participant->needs))
                            - {{ $ticket->participant->needs }}
                        @endif
                        @if(!empty($ticket->participant->birthday))
                            <span class="text-muted">- {{ $ticket->participant->formatted_birthday }}</span>
                        @endif
                    @else
                        Unknown Participant
                    @endif
                    @if(isset($ticket->participant))
                        <br>
                        @forelse($ticket->participant->contacts as $contact)
                            <small>{{ $contact->name }} - {{ $contact->phone }} - {{ $contact->email ?? 'No known email address.' }}</small>
                            @if(!$loop->last)
                                <br>
                            @endif
                        @empty
                            <small>No contacts.</small>
                        @endforelse
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

</body>
