@extends('layouts.app')
@section('title', '#' . $program->id . ' - ' . $program->name)
@section('content')
    <div class="container">
        @include('shared.form_errors')
        @include('tenant.admin.programs.components.summary')
        @include('tenant.admin.programs.components.status_actions')
        @include('tenant.admin.programs.components.registration_options', ['contributor' => $program->getContributorFor(tenant())])
        @include('tenant.admin.programs.components.edit_contributors')
        @include('tenant.admin.programs.components.edit_details')
        @include('tenant.admin.programs.components.edit_meetings')
    </div>
@endsection
