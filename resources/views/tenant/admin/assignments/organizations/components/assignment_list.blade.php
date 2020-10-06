<container>
    @forelse($assignments as $assignment)
    <div class="row">
        <div class="col-10">
            <a href="{{ tenant()->route('tenant:admin.assignments.edit', ['assignment' => $assignment->id]) }}">
                {{ $assignment->name }} 
            </a>
            <i class="fas fa-info-circle" title="{{ $assignment->description }}"></i>
        </div>

        <div class="col-2">
            <div class="{{ $assignment->class_string }} p-1 font-weight-bold text-center mr-2 px-3">
                {{ $assignment->status_string }}
            </div>
        </div>

        <hr/>
    </div>
    @empty
        No tasks assigned yet.
    @endforelse
</container>