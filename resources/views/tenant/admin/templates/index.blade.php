@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Templates</div>

                <div class="card-body">

                    @include('shared.form_errors')

                    @if($templates->count() > 0)

                    <p><a href="{{ tenant()->route('tenant:admin.templates.create') }}">Create a new template</a></p>

                        <table class="table table-responsive table-striped">

                            @foreach($templates as $template)

                                <tr>

                                        <td>{{ $template->internal_name }}</td>

                                        <td><a href="{{ tenant()->route('tenant:admin.templates.edit', ['template' => $template->id]) }}">Edit</a></td>

                                </tr>

                            @endforeach

                        </table>

                    @else

                        No templates yet. <a href="{{ tenant()->route('tenant:admin.templates.create') }}">Create one?</a>

                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
