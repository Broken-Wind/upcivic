@forelse($assignments as $assignment)
    <div class="row mb-2">
        <div class="col-md-9">
            <strong>
                {{ $assignment->name }}
            </strong>
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
    @if($isOutgoingFromTenant)
        Manage your task assignments <a href="{{ tenant()->route('tenant:admin.tasks.index') }}">here.</a>
    @endif
@endforelse
