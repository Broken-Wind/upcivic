@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Confirm your Organization</div>

                <div class="card-body">

                <form method="POST" action="{{ route('organizations.tenant.store', $organization) }}">

                        @csrf

                        @include('shared.form_errors')

                        <div class="form-group">

                            <label for="createOrganization">Your Organization's Name</label>

                            <input type="text" class="form-control" id="createOrganization" aria-describedby="createOrganization" value="{{ $organization->name }}" disabled>

                            <small class="text-muted">Not your organization? <a href="{{ route('home') }}">Go home.</a></small>

                        </div>

                        <div class="form-group mb-3">

                                <label for="slug">Choose a Vanity URL</label>

                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="input-group-text">{{ config('app.url') }}/</span>

                                    </div>

                                    <input type="text" class="form-control" name="slug" id="slug" aria-describedby="slugHelp" placeholder="acmerec">

                                </div>

                                <small id="slugHelp" class="form-text text-muted">Your vanity URL cannot be easily changed. Choose well.</small>

                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
