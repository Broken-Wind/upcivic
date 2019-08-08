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

                    <p>If you've been invited to propose a program to an organization using {{ config('app.name') }}, start by clicking <strong>Templates,</strong> then <strong>Create new template.</strong> Once you've created one or more templates, you may propose a program to any {{ config('app.name') }} user by clicking <strong>Schedule</strong> then <strong>Propose a new program</strong>.</p>

                    <hr />

                    <h5>Activity Hosts</h5>

                    <p>Your next step is to add any of your sites that are not already in our system. Click <strong>Sites,</strong> then, if your sites are not listed, click <strong>add a new site here.</strong> Once you've added sites, you're ready to go! Just ask your activity providers use our system to propose programs. When an organization proposes a new program, you'll see it on your <strong>Schedule</strong> page.</p>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
