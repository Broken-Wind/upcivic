<h3>{{ $assignment->signableDocument->title ?? 'Untitled Document' }}</h3>
{!! $assignment->signableDocument->content ?? 'Document not found.' !!}
<hr>
<h3>Programs ({{ $programs->count() }} total)</h3>
    @forelse($programs as $program)
        <div style="background-color: #f8f9fa; margin-bottom: 10px; padding: 8px;">
            <strong>{{ "{$program->id} - {$program->name}" }}</strong> ({{ $program->description_of_age_range }})<br />
            {{ "{$program->description_of_meetings} at {$program->site->name} - {$program->location->name}" }}<br />
            {{ $program->description }}<br />
            <ul>
                @foreach($program->contributors as $contributor)
                    @if(isset($contributor['formatted_invoice_amount']))
                        <li>{{ $contributor['name'] }} to receive ${{ $contributor['formatted_invoice_amount'] }} {{ $contributor['invoice_type'] }}</li>
                    @else
                        <li>{{ $contributor['name'] }} compensation TBD</li>
                    @endif
                @endforeach
            </ul>
        </div>
    @empty
    @endforelse
