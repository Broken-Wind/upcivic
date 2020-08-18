@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-3">
                <div class="card-header">Proposed Programs</div>
                <div class="card-body">
                    @include('shared.form_errors')
                    <form id="filters" action="{{ URL::current() }}" method="GET">
                        @include('tenant.admin.programs.filters_modal')
                    </form>

                    @if($programsExist)
                        <div class="form-row mb-4">
                            <div class="col">
                                    @if($templateCount > 0)
                                        <a href="{{ tenant()->route('tenant:admin.programs.create') }}">Make a new program proposal</a>
                                    @else
                                        Want to propose your own program? <a href="{{ tenant()->route('tenant:admin.templates.create') }}">Create a template!</a>
                                    @endif
                            </div>
                            <div class="col text-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filterModal" form="filters">
                                        Set Filters
                                    </button>
                                    <a href="{{ url()->current() }}" class="btn btn-secondary">
                                        Reset Filters
                                    </a>

                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @forelse($programGroups as $startDate => $programs)
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <strong>Starting {{ $startDate }}</strong>
                    </div>
                    <div class="card-body pl-0 pr-0">
                        @foreach($programs as $program)
                            @include('tenant.admin.programs.components.program_list_row', ['program' => $program])
                        @endforeach
                    </div>
                </div>
            @empty
                <p>No programs yet.</p>
                <ul>
                    <li><a href="{{ tenant()->route('tenant:admin.templates.create') }}">Create a program template?</a></li>
                    @if(tenant()->organization->templates->count() > 0)
                        <li><a href="{{ tenant()->route('tenant:admin.programs.create') }}">Propose a program?</a></li>
                    @endif
                    <li>Ask your partners to propose programs to you via {{ config('app.name') }}!</li>
                </ul>
            @endforelse
        </div>
    </div>
</div>
@endsection
