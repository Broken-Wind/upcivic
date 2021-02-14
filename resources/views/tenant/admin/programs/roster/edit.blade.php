@extends('layouts.app')

@section('title', '#' . $program->id . ' - ' . $program->name . ' Roster')
@section('content')
<div class="container">
    @include('shared.form_errors')
    @include('tenant.admin.programs.components.summary')
    <div class="row mb-3">
        <div class="col">
            <div class="progress" style="height: 20px">
                <div class="progress-bar {{ $program->progress_bar_class }}" role="progressbar" style="width: {{ $program->enrollment_percent }}%;" aria-valuenow="{{ $program->enrollment_percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ 100 - $program->enrollment_percent }}%;" aria-valuenow="{{ 100 - $program->enrollment_percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar-title" style="position: absolute; text-align: center; line-height: 20px; overflow: hidden; color: #fff; right: 0; left: 0; top: 0;">
                    {{ $program->enrollments }} of {{ $program->max_enrollments }} enrolled
                </div>
            </div>
        </div>
    </div>
    @include('tenant.admin.programs.roster.components.enrollment_details')
    @if($program->allowsRegistration())
        @include('tenant.admin.programs.roster.components.roster')
    @else
    @endif
</div>
@endsection
