@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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

                        <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block">Update Profile</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
