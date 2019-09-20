@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Edit {{ $tenant['name'] }}</div>

                <div class="card-body">

                    <form method="POST" action="{{ tenant()->route('tenant:admin.update') }}">

                        @csrf

                        @method('PATCH')

                        @include('shared.form_errors')

                        <div class="form-group">

                            <label for="createOrganization">Name</label>

                            <input type="text" class="form-control" name="name" value="{{ $tenant['name'] }}" placeholder="Acme Rec">

                        </div>


                        <div class="form-group mb-3">

                            <label for="slug">Vanity URL</label>

                                <input type="text" class="form-control" value="{{ config('app.url') . "/" . $tenant['slug'] }}" id="slug" aria-describedby="slugHelp" placeholder="acmerec" disabled>

                            <small class="text-muted" aria-label="slugHelp">Please contact support to change your vanity URL.</small>


                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>

                    </form>

                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">{{ $tenant['name'] }} Administrators</div>
                <div class="card-body">

                    <table class="table table-striped">

                        @forelse($tenant['aggregated_administrators'] as $administrator)

                            @if($loop->first)

                                <tr>
                                    <td>Name</td>
                                    <td>Email</td>
                                    <td>Listed</td>

                                </tr>

                            @endif

                            <tr>
                                <td>{{ $administrator['name'] }}</td>
                                <td>{{ $administrator['email'] }}</td>
                                <td>{{ $administrator['is_administrator'] ? 'Yes' : '' }}</td>

                            </tr>

                        @empty
                            <tr>
                                <td>No administrators found.</td>
                            </tr>
                        @endforelse
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
