@extends('layouts.app')
@section('title', 'Programs')
@section('content')
<div class="container">
    @include('shared.form_errors')

    <a class="btn btn-primary mb-4" href="{{ tenant()->route('tenant:admin.templates.create') }}">Add Program Template</a>

    <div class="card">
        <div class="card-header">Program Templates</div>

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

                No templates yet. <a href="{{ tenant()->route('tenant:admin.templates.create') }}">Add a program template</a>

            @endif

        </div>
    </div>
</div>
@endsection
