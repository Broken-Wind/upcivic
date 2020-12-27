@extends('layouts.app')
@section('title', 'Review Task Assignments')
@include('tenant.admin.tasks.components.document_head_content')
@section('content')
<div class="container">
    @include('shared.form_errors')
    <div class="card mb-4">
        <div class="card-header">Assignment Preview</div>
        <div class="card-body">
            <h2>{{ $task->name }}</h2>
            {{ $task->description }}
            @if($task->isSignableDocument())
                <hr>
                <h3>{{ $task->signableDocument->title ?? 'Untitled Document' }}</h3>
                {!! $task->signableDocument->content ?? 'Document not found.' !!}
            @endif
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">Assign to Organizations</div>

        <div class="card-body">
            <form method="POST" action="{{ tenant()->route('tenant:admin.assignments.store_many') }}" id="storeAssignments">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task->id }}">

                @foreach($organizations as $organization)
                    @include('tenant.admin.assignments.components.organization_assignment_preview')
                @endforeach

                <button type="submit" id="submit" class="btn btn-primary">Send Assignments</button>
            </form>
        </div>
    </div>
</div>
@endsection

