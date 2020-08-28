@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">Invite administrators to {{ tenant()->name }}</div>

        <div class="card-body">

            <form method="POST" action="{{ tenant()->route('tenant:admin.users.invites.create') }}">

                @csrf

                @include('shared.form_errors')

                <div class="form-group">
                  <label for="email">User Email</label>
                  <input type="text"
                    class="form-control" name="email" id="email" aria-describedby="userEmail" placeholder="" value="{{ $email }}">
                  <small id="userEmail" class="form-text text-muted">To invite a user to your organization, they must first create and verify an account with {{ config('app.name') }}</small>
                </div>

                <button type="submit" id="submit" class="btn btn-primary">Invite Administrator</button>

            </form>

        </div>
    </div>
</div>
@endsection
