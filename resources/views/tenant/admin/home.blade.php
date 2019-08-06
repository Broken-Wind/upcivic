@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Welcome to {{ config('app.name') }}!</h4>

                    <hr />

                    <h5>Activity Providers</h5>

                    <p>If you've been invited to propose a program to an organization using {{ config('app.name') }}, start by <a href="{{ tenant()->route('tenant:admin.templates.create') }}">creating a program template.</a> Once you've created one or more templates, you may <a href="{{ tenant()->route('tenant:admin.programs.create') }}">propose a program</a> to any {{ config('app.name') }} user.</p>

                    <hr />

                    <h5>Activity Hosts</h5>

                    <p>Now that you've created a profile for your organization, you may request that your activity providers use our system to propose programs. Once your activity providers have created their accounts and generated proposals, you may <a href="{{ tenant()->route('tenant:admin.programs.index') }}">view proposed programs here.</a></p>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
