@extends('layouts.app')
@section('title', 'Instructors')
@section('content')
<div class="container">
    @include('shared.form_errors')

    <a class="btn btn-primary mb-4" href="{{ tenant()->route('tenant:admin.instructors.create') }}">Add Instructor</a>

    <div class="card">
        <div class="card-header">Instructors</div>

        <div class="card-body">

            @if($instructors->count() > 0)

                <table class="table table-striped">

                    @foreach($instructors as $instructor)

                        <tr>

                                <td>{{ $instructor->person->first_name }} {{ $instructor->person->last_name }}</td>
                                <td>{{ $instructor->person->email }}</td>
                                <td>{{ $instructor->person->phone }}</td>

                                <td class="text-right">
                                    <a href="{{ tenant()->route('tenant:admin.instructors.edit', ['instructor' => $instructor->id]) }}">
                                        <i class="far fa-edit mr-2"></i>
                                    </a>
                                </td>

                        </tr>

                    @endforeach

                </table>

            @else

                No instructors yet. 

            @endif

        </div>
    </div>
</div>
@endsection
