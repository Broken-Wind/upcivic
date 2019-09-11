@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Organizations</div>

                <div class="card-body">

                    @include('shared.form_errors')

                    <form class="form-inline" method="POST" action="{{ tenant()->route('tenant:admin.organizations.store') }}">

                        @csrf
                        <label class="sr-only" for="inlineFormInputName">Organization Name</label>
                        <input type="text" name ="name" class="form-control mb-2 mr-sm-2" id="inlineFormInputName" placeholder="Exampleville Parks & Recreation">

                        <button type="submit" class="btn btn-primary mb-2">Add New Organization</button>
                        </form>

                    @if($organizations->count() > 0)


                        <p>The following is a list of all organizations listed in {{ config('app.name') }}.</p>
                        <table class="table table-striped">

                            @foreach($organizations as $organization)

                                <tr>

                                    <td>

                                        {{ $organization->name }}

                                    </td>

                                    <td>

                                        @if(!$organization->isClaimed())

                                            <a href="{{ tenant()->route('tenant:admin.organizations.edit', [$organization]) }}">Edit</a>

                                        @endif

                                    </td>


                                </tr>

                            @endforeach

                        </table>

                    @else

                        No sites yet! <a href="{{ tenant()->route('tenant:admin.sites.create') }}">Add a new site here.</a>

                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
