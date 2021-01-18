@forelse($programGroups as $startDate => $sites)
    <div class="card bg-light mb-3">
        <div class="card-header">
            <strong>Starting {{ $startDate }}</strong>
        </div>
        <div class="card-body pl-0 pr-0">
            @foreach($sites as $programs)
                @foreach($programs as $program)
                    @include('tenant.admin.programs.components.program_list_row', ['program' => $program])
                @endforeach
            @endforeach
        </div>
    </div>
@empty
    @include('tenant.admin.programs.components.no_programs_in_list')
@endforelse
