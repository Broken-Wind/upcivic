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
            @foreach(['1', '2', '3'] as $task)
                @include('tenant.admin.organizations.components.assignment_list_row', ['taks' => $task])
            @endforeach
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header"><strong>Annette Meyer</strong></div>
        <div class="card-body"></div>
    </div>
</div>
@endsection