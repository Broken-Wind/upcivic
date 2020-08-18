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

                    <ol>
                        <li>Click <strong>Templates</strong> above, then add a template for each of your programs.</li>
                        <li>Click <strong>Proposals</strong> to view existing proposed programs and propose new ones.</li>
                        <li>To submit a proposal with an organization or site not yet listed, click <strong>Organizations</strong> or <strong>Sites</strong> to add them!</li>
                    </ol>
                    <hr />

                    <h5>Activity Hosts</h5>

                    <ol>
                        <li>Click <strong>Proposals</strong> then <strong>Edit</strong> on the example program proposal and note the information we collect automatically from activity providers.</li>
                        <li>Request that your activity providers submit proposals for free via {{ config('app.name') }}.</li>
                        <li>Organize, edit, and track proposed programs via the <strong>Proposals</strong> link!</li>
                    </ol>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
