<div class="row">
    <div class="col-12">
        <p class="lead">
            #{{ $program['id'] }} - {{ $program['name'] }} at {{ $program['site']['name'] }}<br/>
            {{ $program['description_of_meetings'] }}
            {{ $program['start_time'] }}-{{ $program['end_time'] }}<br/>
            @foreach ($program['contributors'] as $contributor)
                @if(!$loop->last)
                    {{ $contributor->name }},
                @else
                    {{ $contributor->name }}
                @endif
            @endforeach
        </p>
    </div>
</div>
