@extends('layouts.app')
@section('title', $tenant->name . ' Registration Settings')
@section('content')
    <div class="container">
        @include('shared.form_errors')
        <div class="card mb-4">
            <div class="card-header">Registration Settings</div>
            <div class="card-body">
                @if($tenant->acceptsRegistrations())
                <div class="alert alert-success text-center">
                    <h3>
                        You're all set! {{ $tenant->name }} may accept registrations via {{ config('app.name') }}.
                    </h3>
                </div>
                @else
                    <div class="alert alert-warning text-center">
                        <h3 class="mb-3">{{ $tenant->name }} is not configured to accept registrations via {{ config('app.name') }}</h3>
                        <a href="{{ $tenant->route('tenant:admin.stripe_connect.authorize') }}" class="btn btn-primary">
                            Configure Registration
                        </a>
                    </div>
                @endif
            </div>
        </div>
@endsection
