@extends('layouts.app')
@section('title', 'Add Instructor')
@section('content')
<div class="container">
    @include('shared.form_errors')
    <div class="card">
        <div class="card-header">Add Instructor</div>
        <div class="card-body">
            <form method="POST" action="{{ tenant()->route('tenant:admin.instructors.store') }}">
                @csrf
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" class="form-control" name="first_name" required placeholder="Paul">
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" class="form-control" name="last_name" required placeholder="Smith">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email"required placeholder="p.smith@fakeville.gov">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" class="form-control" name="phone" placeholder="4155555555">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Add Instructor</button>
            </form>
        </div>
    </div>
</div>
@endsection
