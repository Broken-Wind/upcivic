@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit {{ $organization['name'] }}</div>

                <div class="card-body">

                    <form method="POST" action="{{ tenant()->route('tenant:admin.organizations.update', [$organization->id])}}">

                        @csrf

                        @method('PUT')

                        @include('shared.form_errors')

                        <div class="form-group">

                            <label for="createOrganization">Name</label>

                            <input type="text" class="form-control" name="name" value="{{ $organization['name'] }}" placeholder="Acme Rec">

                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
