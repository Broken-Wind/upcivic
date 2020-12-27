@if(!$organization->programs)
    Error. No upcoming programs with this partner.
@else
    <div class="row mb-2">
        <div class="col-auto mr-auto">
            <h3>{{ $organization->name }}</h3>
        </div>
        <div class="col-auto">
            <a href="{{ route('tenant:admin.assignments.review', ['tenant' => tenant()->slug, 'task_id' => $task->id, 'organization_ids' => $organizations->except($organization->id)->pluck('id')->toArray()]) }}" class="btn btn-danger" onClick="return confirm('Are you sure you don\'t want to assign the task to this organization?');">
                <i class="fas fa-fw fa-trash"></i>
            </a>
        </div>
    </div>
    @if($task->isSignableDocument())
        <table class="table table-striped table-sm mb-4">
            <thead>
                <tr>
                    <th colspan="6">
                        Include programs:
                    </th>
                </tr>
            </thead>
            @foreach($organization->programs as $program)
                @include('tenant.admin.programs.components.assignment_review_program_row')
            @endforeach
        </table>
    @endif
@endif
