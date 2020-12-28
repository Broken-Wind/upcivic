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
    @include('shared.form_errors')

    <a class="btn btn-primary mb-4" href="{{ tenant()->route('tenant:admin.tasks.create') }}">Add Task</a>

    <div class="card">
        <div class="card-header">Tasks</div>

        <div class="card-body">

            @if($tasks->count() > 0)
                <div class="alert alert-info">
                    You can assign tasks to your partner organizations via the proposals view.
                </div>

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
