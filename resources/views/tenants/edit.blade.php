@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit {{ $organization['name'] }}</div>

                <div class="card-body">

                    <form method="POST" action="/organizations">

                        @csrf

                        @method('PATCH')

                        @include('shared.form_errors')

                        <div class="form-group">

                            <label for="createOrganization">Name</label>

                            <input type="text" class="form-control" name="name" value="{{ $organization['name'] }}" placeholder="Acme Rec">

                        </div>



                        <label for="slug">Vanity URL</label>

                        <div class="input-group mb-3">

                            <div class="input-group-prepend">

                                <span class="input-group-text">{{ config('app.url') }}/</span>

                            </div>

                            <input type="text" class="form-control" value="{{ $organization['slug'] }}" id="slug" aria-describedby="slugHelp" placeholder="acmerec">

                            <small class="text-muted" aria-label="slugHelp">Please contact support to change your vanity URL.</small>

                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
