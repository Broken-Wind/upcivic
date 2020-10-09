@extends('layouts.app')
@section('title', $instructor->name)
@section('content')
<div class="container">
    @include('shared.form_errors')
    <a href="{{ tenant()->route('tenant:admin.instructors.index') }}">
        <i class="fas fa-angle-left pb-3"></i> Back to Instructors
    </a>
    <div class="card">
        <div class="card-header">Instructor</div>

        <div class="card-body">

            <form id="delete_instructor" method="POST" action="{{ tenant()->route('tenant:admin.instructors.destroy', [$instructor]) }}">

                @method('delete')

                @csrf

            </form>

            <form method="POST" action="{{ tenant()->route('tenant:admin.instructors.update', [$instructor]) }}">

                @method('put')

                @csrf
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" class="form-control" name="first_name" value="{{ $instructor->first_name }}">
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="{{ $instructor->last_name }}">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" value="{{ $instructor->email }}">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="{{ $instructor->phone }}">
                </div>
                <div class="form-row">
                    <button type="submit" id="submit" class="btn btn-primary mx-1">Update</button>
                    <button type="submit" id="submit"  form="delete_instructor" class="btn btn-secondary"  onClick="return confirm('Are you sure you want to delete this instructor?');">Delete</button>
                </div>


            </form>
        </div>
    </div>
</div>
@endsection
