@if(!$organization->programs)
    Error. No upcoming programs with this partner.
@else
    <div class="row mb-3">
        <div class="col-auto mr-auto">
            <h3>{{ $organization->name }}</h3>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-danger" onClick="return confirm('Are you sure you don\'t want to assign the task to this organization?');">
                <i class="fas fa-fw fa-times"></i>
            </button>
        </div>
    </div>
    @if($task->isSignableDocument())
        <table class="table table-striped table-sm">
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
