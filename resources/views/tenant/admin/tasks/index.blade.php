@extends('layouts.app')
@section('title', 'Tasks')
@push('scripts')
<script type="application/javascript">
var tasks = {!! $taskJson !!};
var taskAssignmentBaseUrl = "{!! tenant()->route('tenant:admin.tasks.index') !!}";
</script>
<script src="{{ asset('js/views/tasks/index.js') }}"></script>
@endpush
@section('content')
<div class="container">
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ tenant()->route('tenant:admin.assignments.incoming.index') }}">Incoming Assignments</a>
        </li>
        @if(tenant()->isSubscribed())
            <li class="nav-item">
                <a class="nav-link" href="{{ tenant()->route('tenant:admin.assignments.outgoing.index') }}">Outgoing Assignments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ tenant()->route('tenant:admin.tasks.index') }}">Task Templates</a>
            </li>
        @endif

    </ul>
    @include('shared.form_errors')
    <a class="btn btn-primary mb-4" href="{{ tenant()->route('tenant:admin.tasks.create') }}">Add Task</a>

    <div class="card">
        <div class="card-header">Tasks</div>

        <div class="card-body">

            @if($tasks->count() > 0)

                <table class="table table-striped">

                    @foreach($tasks as $task)

                        <tr>

                            <td>{{ $task->name }}</td>

                            <td class="text-right">
                                <a href="{{ tenant()->route('tenant:admin.tasks.edit', ['task' => $task]) }}">
                                    Edit Task
                                </a>
                                &nbsp;|&nbsp;
                                <a href="{{ tenant()->route('tenant:admin.assignments.create', ['task_id' => $task->id]) }}">
                                    Assign Task
                                </a>
                            </td>

                        </tr>

                    @endforeach

                </table>

            @else

                No tasks yet. <a href="{{ tenant()->route('tenant:admin.tasks.create') }}">Add a task</a>

            @endif

        </div>
    </div>
</div>
@endsection
