@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Your Profile</div>

        <div class="card-body">

            <form method="POST" action="{{ tenant()->route('tenant:admin.users.update', [$user]) }}">

                @method('put')

                @csrf

                @include('shared.form_errors')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') ?: $user['name'] }}" id="name" placeholder="John Smith" required>
                </div>

                <div class="form-group">
                    <label for="name">Phone</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone') ?: $user['phone'] }}" id="phone" required>
                </div>

                <button type="submit" id="submit" class="btn btn-primary">Update Profile</button>

            </form>
        </div>
    </div>
</div>
@endsection
