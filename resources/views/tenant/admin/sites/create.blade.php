@extends('layouts.app')
@section('title', 'Add Site')

@section('content')
<div class="container">
    <a href="{{ tenant()->route('tenant:admin.sites.index')}}">
        <i class="fas fa-angle-left pb-3"></i> Back to Sites
    </a>
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
                {{-- <div class="form-group">
                    <label for="county_id">County</label>
                    <select class="form-control" name="county_id">
                        <option value="">Choose a county...</option>
                        @forelse ($counties as $county)
                            <option value="{{ $county['id'] }}">{{ $county['name'] }}</option>
                        @empty
                            <option disabled>No counties exist.</option>
                        @endforelse
                    </select>
                </div> --}}
                <button type="submit" id="submit" class="btn btn-primary">Add Site</button>
            </form>
        </div>
    </div>
</div>
@endsection
