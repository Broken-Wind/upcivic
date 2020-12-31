@extends('layouts.app')
@section('title', 'Review Task Assignments')
@include('tenant.admin.tasks.components.document_head_content')

@push('scripts')
<script>
    var task = {
        'id': {{ $task->id }},
        'name': '{{ $task->name }}',
        'type': '{{ $task->type }}',
        'entity': '{{ $task->assign_to_entity }}',
        'assigned_to_organization_ids': {{ $organizations->pluck('id')->toJson()}},
        'assigned_by_organization_id': {{ tenant()->organization_id }},
    };
</script>
<script src="{{ asset('js/views/edit_assignment.js') }}" defer></script>
@endpush

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
                @if($task->shouldAssociatePrograms())
                    <input type="hidden" name="should_associate_programs" value="true">
                @endif

                @foreach($organizations as $organization)
                    @include('tenant.admin.assignments.components.organization_assignment_preview')
                @endforeach

                <button id="assign-task-button" type="submit" id="submit" class="btn btn-primary">Send Assignments</button>
            </form>
        </div>
    </div>
</div>
@endsection

