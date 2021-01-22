{{-- Program Info --}}

<p>{{$program->name}}</p>
<p>{{$program->description}}</p>
<p>{{$program->public_notes}}</p>

<p>{{$program->ages_string}} {{$program->min_age}} {{$program->max_age}}</p>


{{-- Program Pricing --}}

<p>{{$program->formatted_base_fee}}<p>
<p>{{$program->shared_invoice_type}}<p>
    
{{-- Program Meetings --}}

<p>Duration: {{$program->meeting_minutes}}</p>
<p>Meets: {{$program->meeting_interval}}</p>
<p>No. of sessions: {{$program->meeting_count}}</p>

<p>{{$program->start_datetime}}</p>
<p>{{$program->end_datetime}}</p>
    
{{-- Program Location --}}

<p>{{$program->site->name}}</p>

{{-- Program Instructor --}}
<p>{{$program->instructors}}</p>