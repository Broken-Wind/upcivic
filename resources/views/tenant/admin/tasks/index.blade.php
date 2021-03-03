@extends('layouts.app')
@section('title', 'Task Templates')
@section('content')
<div class="container">

    </ul>
    @include('shared.form_errors')
    <a class="btn btn-primary mb-4" href="{{ tenant()->route('tenant:admin.tasks.create') }}">Add Task Template</a>

    <div class="card">
        <div class="card-header">Task Templates</div>

        <div class="card-body">

            @if($tasks->count() > 0)

                <table class="table table-striped">

                    @foreach($tasks as $task)

                        <tr>

                            <td>{{ $task->name }}</td>

                            <td class="text-right">
                                <a href="{{ tenant()->route('tenant:admin.tasks.edit', ['task' => $task]) }}">
                                    Edit Template
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

                No tasks yet. <a href="{{ tenant()->route('tenant:admin.tasks.create') }}">Add a task template</a>

            @endif

        </div>
    </div>
</div>
@endsection
