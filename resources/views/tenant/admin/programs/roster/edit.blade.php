@extends('layouts.app')

@section('title', '#' . $program->id . ' - ' . $program->name . ' Roster')
@section('content')
<div class="container">
    @include('shared.form_errors')
    @include('tenant.admin.programs.components.summary')
    <div class="row mb-3">
        <div class="col">
            @include('tenant.admin.programs.components.enrollment_progress_bar')
        </div>
    </div>
    @include('tenant.admin.programs.roster.components.enrollment_details')
    @if($program->getContributorFor(tenant())->allowsRegistration())
        @include('tenant.admin.programs.roster.components.roster')
    @else
    @endif
</div>
@endsection
