@extends('layouts.app')

@section('title', 'Organizations')
@push('scripts')
<script type="application/javascript">
    var organizations = {!! $organizationsJson !!};
    var updateOrganizationAreaUrl = "{{ tenant()->route('tenant:admin.organizations.index') }}";
</script>
<script src="{{ asset('js/views/organizations/index.js')}}"></script>
@endpush
@section('content')
@include('tenant.admin.areas.components.select_area_modal')
<div class="container">
    @include('shared.form_errors')

    <div class="card mb-4">
        <div class="card-header">Organizations</div>

        <div class="card-body">

            @if($organizations->count() > 0)

                <p>The following is a list of all organizations listed in {{ config('app.name') }}. If you'd like to offer programs with an organization that isn't listed below, please <a href="{{ tenant()->route('tenant:admin.organizations.create') }}">add a new organization here.</a></p>

                <table class="table table-striped">

                    @foreach($organizations as $organization)

                        <tr>

                            <td>

                                {{ $organization->name }}

                            </td>


                            <td class="text-right">
                                @if(!$organization->isClaimed())
                                    <a href="{{ tenant()->route('tenant:admin.organizations.edit', [$organization]) }}">
                                        <i class="far fa-edit mr-2"></i>
                                    </a>
                                @endif
                                @if(isset($organization->area->id))
                                    <button type="button" class="btn btn-sm btn-light select-area-button" data-organization-id="{{ $organization->id }}"data-toggle="modal" data-target="#select-area-modal">
                                        {{ $organization->area->name }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-secondary select-area-button" data-organization-id="{{ $organization->id }}"data-toggle="modal" data-target="#select-area-modal">
                                        Set an Area
                                    </button>
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
@endsection
