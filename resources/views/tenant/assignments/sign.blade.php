@extends('layouts.app')
@section('title', $assignment->name . ' - Assigned to ' . $assignment->assignee->name)
@section('content')
<div class="container">
    @if($assignment->isGeneratedDocument())
        @include('tenant.assignments.generated_documents.components.document')
    @else
        Error!
    @endif
</div>
@endsection
