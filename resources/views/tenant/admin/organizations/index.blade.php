@extends('layouts.app')

@section('title', 'Clients')

@include('tenant.admin.organizations.components.add_organization_modal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Organizations</div>

                <div class="card-body">

                    @if($organizations->count() > 0)

                        <p>The following is a list of all organizations listed in {{ config('app.name') }}. If you'd like to offer programs at an organization that isn't listed below, please <a href="" data-toggle="modal" data-target="#add-organization-modal">add a new organization here.</a></p>
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
