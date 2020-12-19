@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add your Organization</div>

                <div class="card-body">
                    <form method="POST" action="/tenants">
                        @csrf
                        @include('shared.form_errors')
                        <div class="form-group">

                            <label for="createOrganization">Create your Organization</label>

                            <input type="text" class="form-control" name="name" id="createOrganization" aria-describedby="createOrganization" placeholder="Acme Rec">

                        </div>

                        <div class="form-group mb-3">

                                <label for="slug">Organization Web Address</label>

                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="input-group-text">{{ config('app.url') }}/</span>

                                    </div>

                                    <input type="text" class="form-control" name="slug" id="slug" aria-describedby="slugHelp" placeholder="fakevillerec">

                                </div>

                                <small id="slugHelp" class="form-text text-muted">This is the URL you'll see when you visit your site. The Fakeville Parks & Recreation Department might use "fakevillerec" as their URL. Only letters, numbers, and dashes are allowed.</small>

                        </div>

                        <button type="submit" class="btn btn-primary">Add your Organization</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
