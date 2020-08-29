@extends('layouts.app')

@section('content')
    <div class="container">
        @include('shared.form_errors')
        <div class="card mb-4">
            <div class="card-header">Info</div>
            <div class="card-body">

                <form method="POST" action="{{ tenant()->route('tenant:admin.update') }}">

                    @csrf

                    @method('PATCH')

                    <div class="form-group">

                        <label for="createOrganization">Name</label>

                        <input type="text" class="form-control" name="name" value="{{ $tenant['name'] }}"
                               placeholder="Acme Rec">

                    </div>


                    <div class="form-group mb-3">

                        <label for="slug">Vanity URL</label>

                        <input type="text" class="form-control" value="{{ config('app.url') . "/" . $tenant['slug'] }}"
                               id="slug" aria-describedby="slugHelp" placeholder="acmerec" disabled>

                        <small class="text-muted" aria-label="slugHelp">Please contact support to change your vanity
                            URL.</small>


                    </div>

                    <button type="submit" class="btn btn-secondary">Update</button>

                </form>

            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Administrators</div>
            <div class="card-body">

                <table class="table table-striped">

                    @forelse($tenant['aggregated_administrators'] as $administrator)

                        @if($loop->first)

                            <tr>
                                <td>Name</td>
                                <td>Email</td>

                            </tr>

                        @endif

                        <tr>
                            <td>{{ $administrator['name'] }}</td>
                            <td>{{ $administrator['email'] }}</td>

                        </tr>

                    @empty
                        <tr>
                            <td>No administrators found.</td>
                        </tr>
                    @endforelse
                </table>

                <hr/>
                <p>Add users to {{tenant()->name}}</p>
                <form method="POST" action="{{ tenant()->route('tenant:admin.users.invites.store') }}">

                    @csrf

                    <div class="form-group">
                        <label for="email">User Email</label>
                        <input type="text" class="form-control" name="email" id="email" aria-describedby="userEmail" placeholder="" value="{{ $email }}">
                    </div>

                    <button type="submit" id="submit" class="btn btn-secondary">Grant Access</button>

                </form>

            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Publishing</div>
            <div class="card-body">
                Embed this code snippet to your website to inform your users in <b>real-time</b> about upcoming programs schedules.
                <div class="bg-light my-2">
                    <code>&lt;iframe src="{{ tenant()->route('tenant:iframe.index') }}" title="Scheduled programs"
                        style="width: 100%; min-height: 600px; height: 100%"/&gt;</code>
                </div>
                <small class="text-muted">WordPress and other content management systems may require additional setup.
                    If need assistance, email support@upcivic.com</small>
                <hr/>
                <p> Preview </p>
                <iframe style="width: 100%; min-height: 600px; height: 100%"
                        src="{{ tenant()->route('tenant:iframe.index') }}" title="Scheduled programs"/>
            </div>
        </div>
    </div>
@endsection
