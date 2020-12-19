@extends('layouts.app')
@section('title', $administrator->name)
@section('content')
<div class="container">
    @include('shared.form_errors')
    <a href="{{ tenant()->route('tenant:admin.organizations.edit', [$organization])}}">
        <i class="fas fa-angle-left pb-3"></i> Back to editing {{$organization->name}}
    </a>
    <div class="card">
        <div class="card-header">Administrator</div>

        <div class="card-body">

            <form id="delete_administrator" method="POST" action="{{ tenant()->route('tenant:admin.organizations.administrators.destroy', [$organization, $administrator]) }}">

                @method('delete')

                @csrf

            </form>

            <form method="POST" action="{{ tenant()->route('tenant:admin.organizations.administrators.update', [$organization, $administrator]) }}">

                @method('put')

                @csrf
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" class="form-control" name="first_name" value="{{ $administrator->first_name }}">
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="{{ $administrator->last_name }}">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" value="{{ $administrator->email }}">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="{{ $administrator->phone }}">
                </div>
                <div class="form-row">
                    <button type="submit" id="submit" class="btn btn-primary mx-1">Update</button>
                    <button type="submit" id="submit"  form="delete_administrator" class="btn btn-secondary"  onClick="return confirm('Are you sure you want to delete this administrator?');">Delete</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
