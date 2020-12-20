@extends('layouts.app')
@section('title', $assignment->name . ' - Assigned to ' . $assignment->assignee->name)
@section('content')
<div class="container">
    @if($assignment->isSignableDocument())
        @include('tenant.assignments.signable_documents.components.document')
    @else
        Error!
    @endif
</div>
@endsection
