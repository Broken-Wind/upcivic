@extends('layouts.app')
@section('title', $assignment->name . ' - Assigned to ' . $assignment->assignee->name)
@section('content')
<div class="container">
    @include('tenant.assignments.components.generated_documents.edit')
</div>
@endsection
