@extends('layouts.app')
@section('title', ' Assignments')
@section('content')
<div class="container">
    @include('shared.form_errors')
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#">Tasks for Techsplosion</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Tasks for Redwood City </a>
        </li>
    </ul>
    <div class="card mb-3">
        <div class="card-header"><strong>General</strong></div>
        <div class="card-body">
            @foreach(['Sign a contract', 'Provide a copy of your liability insurance policy', '3'] as $task)
                @include('tenant.admin.organizations.components.assignment_list_row', ['taks' => $task])
            @endforeach
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header"><strong>Instructors</strong></div>
        <div class="card-body">
            <h5 class="card-title text-muted">Ilene Reese</h5>
            @foreach(['TB Test', 'Affidavit', 'Background check'] as $task)
                @include('tenant.admin.organizations.components.assignment_list_row', ['taks' => $task])
            @endforeach
            <h5 class="card-title pb-1 text-muted">Rudy Morin</h5>
        </div>
    </div>
</div>
@endsection