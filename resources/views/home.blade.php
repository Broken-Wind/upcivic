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

                    @if(Auth::user()->hasRecommendedOrganizations())

                        <h5>Recommended Organizations:</h5>

                        <ul>

                            @foreach(Auth::user()->recommendedOrganizations() as $recommendedOrganization)

                                <li>{{ $recommendedOrganization->name }}</li>

                            @endforeach

                        </ul>

                        <hr />

                        <h5>Is your organization already using {{ config('app.name') }} but not listed above?</h5>

                    @else

                        <h5>Is your organization already using {{ config('app.name') }}?</h5>

                    @endif

                    <p>Ask your administrator to invite you!</p>

                    <hr />

                    <h5>Is your organization new to {{ config('app.name') }}?</h5>

                    <p><a href="/tenants/create">Add your organization.</a></p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
