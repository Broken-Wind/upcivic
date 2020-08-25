@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Programs</div>

                <div class="card-body">

                    @include('shared.form_errors')

                    @if($templates->count() > 0)

                    <p><a href="{{ tenant()->route('tenant:admin.templates.create') }}">Add a program</a></p>

                        <table class="table table-striped">

                            @foreach($templates as $template)

                                <tr>

                                        <td>{{ $template->name }}</td>

                                        <td>{{ $template->internal_name != $template->name ? $template->internal_name : null }}</td>

                                        <td class="text-right">
                                            <a href="{{ tenant()->route('tenant:admin.templates.edit', ['template' => $template->id]) }}">
                                                <i class="far fa-edit"></i>
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
    </div>
</div>
@endsection
