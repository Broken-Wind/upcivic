@forelse($programGroups as $startDate => $areas)
    <div class="card bg-light mb-3">
        <div class="card-header">
            <strong>Starting {{ \Carbon\Carbon::parse($startDate)->format('l') }} {{ $startDate }}</strong>
        </div>
        <div class="card-body pl-0 pr-0 pt-2">
            @foreach($areas as $name => $sites)
                <div class="ml-2 pb-2">
                    <strong>{{ $name }}</strong>
                </div>
                @foreach($sites as $programs)
                    @foreach($programs as $program)
                        @include('tenant.admin.programs.components.program_list_row', ['program' => $program])
                    @endforeach
                @endforeach
            @endforeach
        </div>
    </div>
@empty
    @include('tenant.admin.programs.components.no_programs_in_list')
@endforelse
