@extends('layouts.app')
@section('title', 'Tasks')
@section('content')
<div class="container">
    @include('shared.form_errors')

    <a class="btn btn-primary mb-4" href="{{ tenant()->route('tenant:admin.tasks.create') }}">Add Task</a>

    <div class="card">
        <div class="card-header">Tasks</div>

        <div class="card-body">

            @if($tasks > 0)

                <table class="table table-striped">

                    @foreach($tasks as $task)

                        <tr>

                            <td>{{ $task }}</td>

                            <td class="text-right">
                                <a href="{{ tenant()->route('tenant:admin.tasks.edit', ['task' => 1]) }}">
                                    <i class="far fa-edit mr-2"></i>
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
