@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Sites</div>

                <div class="card-body">

                    <form method="POST" action="{{ tenant()->route('tenant:admin.sites.create') }}">

                        @csrf

                        @include('shared.form_errors')

                        <div class="form-group">
                          <label for="site_name">Site Name</label>
                          <input type="text" class="form-control" name="name" id="site_name" placeholder="Townville Rec Center" required>
                        </div>

                        <div class="form-group">
                          <label for="site_address">Site Address</label>
                          <input type="text" class="form-control" name="address" id="site_address" placeholder="123 Main St." required>
                        </div>

                        <div class="form-group">
                          <label for="site_phone">Site Phone</label>
                          <input type="text" class="form-control" name="phone" id="site_phone" placeholder="555-555-5555">
                        </div>

                        <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block">Add Site</button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
