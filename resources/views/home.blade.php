@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Get Started with {{ config('app.name') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @include('shared.form_errors')


                    <h5>Find Your Organization:</h5>

                    <form action="{{ route('organizations.users.store') }}" method="POST">

                        @csrf

                        <div class="form-row">
                            <div class="col-md-8">

                                    <div class="form-group">
                                        <select class="form-control" name="organization_id">
                                            @forelse($organizations as $organization)
                                            <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                            @empty
                                            <option>None found.</option>
                                            @endforelse
                                        </select>
                                    </div>

                                </div>
                            <div class="col-md-4">

                                <button type="submit" class="btn btn-primary btn-block">Request to Join</button>

                            </div>
                        </div>

                    </form>

                    <hr />

                    <h5>Is your organization not listed above?</h5>

                    <p><a href="/tenants/create">Add your organization.</a></p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
