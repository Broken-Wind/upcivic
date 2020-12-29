@extends('layouts.app')
@section('title', 'Programs')
@section('content')
<div class="container">
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ tenant()->route('tenant:admin.programs.index') }}">Proposals</a>
        </li>
        @if(tenant()->isSubscribed())
            <li class="nav-item">
                <a class="nav-link" href="{{ tenant()->route('tenant:admin.resource_timeline.meetings') }}">Calendar</a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link active" href="{{ tenant()->route('tenant:admin.templates.index') }}">Program Templates</a>
        </li>
    </ul>
    @include('shared.form_errors')

    <a class="btn btn-primary mb-4" href="{{ tenant()->route('tenant:admin.templates.create') }}">Add Program</a>

    <div class="card">
        <div class="card-header">Programs</div>

        <div class="card-body">

            @if($templates->count() > 0)

                <table class="table table-striped">

                    @foreach($templates as $template)

                        <tr>

                                <td>{{ $template->name }}</td>

                                <td>{{ $template->internal_name != $template->name ? $template->internal_name : null }}</td>

                                <td class="text-right">
                                    <a href="{{ tenant()->route('tenant:admin.templates.edit', ['template' => $template->id]) }}">
                                        <i class="far fa-edit mr-2"></i>
                                    </a>
                                    <a href="{{ tenant()->route('tenant:admin.programs.create', ['template_id' => $template->id]) }}">
                                        <i class="fas fa-angle-double-right"></i>
                                    </a>
                                </td>

                        </tr>

                    @endforeach

                </table>

            @else

                No programs yet. <a href="{{ tenant()->route('tenant:admin.templates.create') }}">Add a program</a>

            @endif

        </div>
    </div>
</div>
@endsection
