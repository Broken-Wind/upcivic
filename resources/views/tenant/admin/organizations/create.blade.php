@extends('layouts.app')
@section('title', 'Add Organization')

@section('content')
<div class="container">

    <a href="{{ tenant()->route('tenant:admin.organizations.index')}}">
        <i class="fas fa-angle-left pb-3"></i> Back to Organizations 
    </a>

    @include('shared.form_errors')

    <div class="card mb-4">
        <div class="card-header">Organizations</div>
        <div class="card-body">
            <form method="POST" action="{{ tenant()->route('tenant:admin.organizations.store') }}">
                @csrf
                <div class="form-group">
                    <label for="organization-name">Name</label>
                    <input type="text" class="form-control" name="name" id="organization-name" required  placeholder="Fakeville Community Center">
                </div>
                <div class="form-group">
                    <label for="administrator-first-name">Contact Person First Name</label>
                    <input type="text" class="form-control" name="administrator[first_name]" id="administrator-first-name" required placeholder="Paul">
                </div>
                <div class="form-group">
                    <label for="administrator-last-name">Contact Person Last Name</label>
                    <input type="text" class="form-control" name="administrator[last_name]" id="administrator-last-name" required placeholder="Smith">
                </div>
                <div class="form-group">
                    <label for="administrator-email">Email</label>
                    <input type="text" class="form-control" name="administrator[email]" id="administrator-email" required placeholder="p.smith@fakeville.gov">
                </div>
                <div class="form-group">
                    <label for="administrator-phone">Phone</label>
                    <input type="text" class="form-control" name="administrator[phone]" id="administrator-phone" placeholder="415xxxxxx">
                </div>
                <div class="form-group">
                    <label for="administrator-title">Title</label>
                    <input type="text" class="form-control" name="administrator[title]" id="administrator-title" placeholder="Recreation Supervisor">
                </div>
                <div class="form-group">
                    <label for="enrollment-url">Registration Website Link</label>
                    <input type="text" class="form-control" name="enrollment_url" id="enrorllment-url"  placeholder="https://rec.center.com/fakeville/activities">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Add Organization</button>
            </form>
        </div>
    </div>
</div>
@endsection