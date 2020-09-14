<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
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
<h3>Letter of Agreement</h3>
<p>
    I hereby agree that I shall instruct the class(es) listed below. I agree that if I am unable to teach the class(es) listed below, I will give the host written notification not less than two weeks prior to the first class meeting. I understand that if I am teaching a youth class, I am responsible for waiting with the children until they are picked up.
</p>
<p>
    I agree that the rate of the activity fees below will be paid to me at a percentage agreed upon in writing for my services. My sole compensation shall be provided as below. I understand that I will receive no compensation if the class is cancelled by myself or the host.
</p>
    I hold harmless the host, its officers, employees and agent from any and all claims for liability, losses or damage arising out of or alleged to arise from my performance of this agreement.
</p>
<p>
    I understand and agree that in the performance of this agreement, I shall have the status of an independent contractor and shall not be deemed to be an employee, agent, or officer of the host.
</p>
<p>
    I understand and agree that the host has no right to control the manner and means of the work performed under this Agreement.
</p>
<p>
    I understand and agree that I will (a) pay my assigned personnel’s wages and provide them with the benefits that I offer to my employees; (b) pay, withhold, and transmit payroll taxes; provide unemployment insurance and workers’ compensation benefits; and handle unemployment and workers’ compensation claims involving assigned personnel; and (c) comply with all applicable federal, state and local laws and regulations, including all applicable federal, state, and local laws and regulations prohibiting discrimination and harassment.
</p>
<table>
    @forelse($programs->first()->contributors as $contributor)
    <tr>
        <th colspan="5" style="text-align: left; padding-top:20px;">{{ $contributor->organization->name }}</th>
    </tr>
    <tr>
        <td>____________________________</td>
        <td></td>
        <td>____________________________</td>
        <td></td>
        <td>___________</td>
    </tr>
    <tr>
        <td>Name</td>
        <td></td>
        <td>Signature</td>
        <td></td>
        <td>Date</td>
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
