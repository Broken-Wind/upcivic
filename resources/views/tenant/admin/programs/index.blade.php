@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Proposed Programs</div>

                <div class="card-body">

                    @include('shared.form_errors')


                    @if($programs->count() > 0)

                        @include('tenant.admin.programs.filters_modal')

                        <div class="form-row mb-4">

                            <div class="col">

                                    @if($templateCount > 0)

                                        <a class="btn btn-primary" href="{{ tenant()->route('tenant:admin.programs.create') }}">Propose a new program</a>

                                    @else

                                        Want to propose your own program? <a href="{{ tenant()->route('tenant:admin.templates.create') }}">Create a template!</a>

                                    @endif

                            </div>

                            <div class="col text-right">

                                <form action="{{ URL::current() }}" method="GET">

                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filterModal">

                                        Set Filters

                                    </button>

                                    <a href="{{ url()->current() }}" class="btn btn-secondary">

                                        Reset Filters

                                    </a>

                                </form>

                            </div>

                        </div>

                        <table class="table table-responsive table-striped">

                            <thead>

                                <th>#</th>
                                <th>Dates</th>
                                <th>Times</th>
                                <th>Name</th>
                                <th>Site</th>
                                <th>With</th>
                                <th></th>

                            </thead>

                            @foreach($programs as $program)

                                <tr>

                                    <td>{{ $program->id }}</td>

                                    <th>{{ $program->start_date }}-{{ $program->end_date }}</th>

                                    <td>{{ $program->start_time }}-{{ $program->end_time }}</td>

                                    <td>{{ $program->internal_name }}</td>

                                    <td>{{ $program->site->name }}</td>

                                    <td>

                                        @foreach($program->contributors->where('organization_id', '!==', tenant()['id']) as $contributor)

                                            {{ $contributor->organization->name . (!$loop->last ? ',' :'') }}

                                        @endforeach

                                    </td>


                                    <td><a href="{{ tenant()->route('tenant:admin.programs.edit', ['program' => $program->id]) }}">Edit</a></td>

                                </tr>

                            @endforeach

                        </table>

                    @else

                        <p>No programs yet.</p>

                        <ul>

                            <li><a href="{{ tenant()->route('tenant:admin.templates.create') }}">Create a program template?</a></li>

                            @if(tenant()->templates->count() > 0)

                                <li><a href="{{ tenant()->route('tenant:admin.programs.create') }}">Propose a program?</a></li>

                            @endif

                            <li>Ask your partners to propose programs to you via {{ config('app.name') }}!</li>

                        </ul>


                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
