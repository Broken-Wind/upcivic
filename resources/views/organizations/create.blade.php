@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add your Organization</div>

                <div class="card-body">

                    <form method="POST" action="/organizations">

                        @csrf

                        @include('shared.form_errors')

                        <div class="form-group">

                            <label for="createOrganization">Create your Organization</label>

                            <input type="text" class="form-control" name="name" id="createOrganization" aria-describedby="createOrganization" placeholder="Acme Rec">

                            <label for="slug">Vanity URL</label>

                            <div class="input-group">

                                <div class="input-group-prepend">

                                    <span class="input-group-text">{{ config('app.url') }}/</span>

                                </div>

                                <input type="text" class="form-control" name="slug" id="slug" aria-describedby="slug" placeholder="acmerec">

                            </div>

                            <small id="createOrganizationHelp" class="form-text text-muted">You must belong to at least one organization to start using {{ config('app.name') }}.</small>

                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
