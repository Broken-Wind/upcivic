@extends('layouts.app')
@section('title', $organization->name)
@section('content')
<div class="container">
    <a href="{{ tenant()->route('tenant:admin.organizations.index')}}">
        <i class="fas fa-angle-left pb-3"></i> Back to Organizations
    </a>
    <div class="card">
        <div class="card-header">Edit {{ $organization['name'] }}</div>

        <div class="card-body">

            <form method="POST" action="{{ tenant()->route('tenant:admin.organizations.update', [$organization->id])}}">

                @csrf

                @method('PUT')

                @include('shared.form_errors')

                <div class="form-group">

                    <label for="name">Name</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ $organization['name'] }}" placeholder="Fakeville Rec" />

                    <label class="mt-2" for="enrollment-url">Registration Website Link</label>
                    <input id="enrollment-url" type="text" class="form-control" name="enrollment_url" value="{{ $organization['enrollment_url'] }}" placeholder="https://rec.center.com/fakeville/activities" />

                </div>

                <button type="submit" class="btn btn-primary mb-2">Update</button>

            </form>

            <hr />

            <table class="table table-striped">

                <tr>
                    <th class="text-center" colspan="5">Administrators</th>
                </tr>

                @forelse($organization->administrators as $administrator)

                    <tr>

                        <td>

                            {{ $administrator->name}}

                        </td>

                        <td>

                            {{ $administrator->email}}

                        </td>

                        <td>

                            {{ $administrator->phone}}

                        </td>

                        <td>

                            {{ $administrator->title}}

                        </td>

                        <td class="text-right">
                            <a href="{{ tenant()->route('tenant:admin.organizations.administrators.edit', [$organization, $administrator]) }}">
                                <i class="far fa-edit mr-2"></i>
                            </a>
                        </td>

                    </tr>

                @empty

                @endforelse


            </table>

            <form method="POST" action="{{ tenant()->route('tenant:admin.organizations.administrators.store', [$organization->id])}}">

                @csrf

                @method('POST')

                <div class="form-row">

                    <div class="col-lg-2">

                        <label class="sr-only" for="firstName">First Name</label>
                        <input type="text" name ="first_name" class="form-control mb-2 mr-sm-2" id="firstName" placeholder="First Name">

                    </div>

                    <div class="col-lg-2">

                        <label class="sr-only" for="lastName">Last Name</label>
                        <input type="text" name ="last_name" class="form-control mb-2 mr-sm-2" id="lastName" placeholder="Last Name">

                    </div>

                    <div class="col-lg-2">

                        <label class="sr-only" for="email">Email Address</label>
                        <input type="text" name ="email" class="form-control mb-2 mr-sm-2" id="email" placeholder="Email">

                    </div>

                    <div class="col-lg-2">

                        <label class="sr-only" for="phone">Phone</label>
                        <input type="text" name ="phone" class="form-control mb-2 mr-sm-2" id="phone" placeholder="Phone">

                    </div>

                    <div class="col-lg-2">

                        <label class="sr-only" for="title">Title</label>
                        <input type="text" name ="title" class="form-control mb-2 mr-sm-2" id="title" placeholder="Title">

                    </div>

                    <div class="col-lg-2">

                        <button type="submit" class="btn btn-secondary btn-block mb-2">Add Administrator</button>

                    </div>

                </div>



            </form>

        </div>
    </div>
</div>
@endsection
