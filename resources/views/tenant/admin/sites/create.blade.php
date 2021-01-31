@extends('layouts.app')
@section('title', 'Add Site')

@section('content')
<div class="container">

    <a href="{{ tenant()->route('tenant:admin.sites.index')}}">
        <i class="fas fa-angle-left pb-3"></i> Back to Sites
    </a>

    @include('shared.form_errors')

    <div class="card mb-4">
        <div class="card-header">Sites</div>
        <div class="card-body">
            <form method="POST" action="{{ tenant()->route('tenant:admin.sites.create') }}">
                @csrf
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
                @if($areas->isNotEmpty())
                    <div class="form-group">
                        <label for="county_id">Area</label>
                        <select class="form-control" name="area_id">
                            <option value="">Choose an area...</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area['id'] }}">{{ $area['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <button type="submit" id="submit" class="btn btn-primary">Add Site</button>
            </form>
        </div>
    </div>
</div>
@endsection
