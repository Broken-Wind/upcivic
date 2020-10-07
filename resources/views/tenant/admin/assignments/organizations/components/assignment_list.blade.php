<container>
    @forelse($assignments as $assignment)
    <div class="row">
        <div class="col-md-9">
            <a href="{{ tenant()->route($editRouteString, ['assignment' => $assignment->id]) }}">
                {{ $assignment->name }}
            </a>
            <span class="text-muted"> - {{ $assignment->description }}<span>
        </div>
        <div class="col-md-2">
            <div class="{{ $assignment->class_string }} p-1 font-weight-bold text-center mr-2 px-3">
                {{ $assignment->status_string }}
            </div>
        </div>
        <div class="col-md-1 mt-1 text-right">
            <a href="{{ tenant()->route($editRouteString, ['assignment' => $assignment->id]) }}">
                <i class="far fa-edit"></i>
            </a>
        </div>

        <hr/>
    </div>
    @empty
        No tasks assigned yet.
    @endforelse
</container>