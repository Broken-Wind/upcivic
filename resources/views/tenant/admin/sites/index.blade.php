@extends('layouts.app')

@section('title', 'Sites')
@push('scripts')
<script type="application/javascript">
    var sites = {!! $sitesJson !!};
var updateSiteAreaUrl = "{{ tenant()->route('tenant:admin.sites.index') }}";
</script>
<script src="{{ asset('js/views/sites/index.js')}}"></script>
@endpush
@section('content')
<div class="container">
    @include('shared.form_errors')
    @include('tenant.admin.sites.components.select_area_modal')

    <div class="card mb-4">
        <div class="card-header">Sites</div>

        <div class="card-body">

            @if($sites->count() > 0)

                <p>The following is a list of all sites listed in {{ config('app.name') }}. If you'd like to offer programs at a site that isn't listed below, please <a href="{{ tenant()->route('tenant:admin.sites.create') }}">add a new site here.</a></p>
                <table class="table table-striped">

                    @foreach($sites as $site)

                        <tr>

                            <td>

                                {{ $site->name }} - <span class="text-muted">{{ $site->address }}</span>

                            </td>
                            <td class="text-right">
                                @if(isset($site->area->id))
                                    <button type="button" class="btn btn-sm btn-light select-area-button" data-site-id="{{ $site->id }}"data-toggle="modal" data-target="#select-area-modal">
                                        {{ $site->area->name }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-secondary select-area-button" data-site-id="{{ $site->id }}"data-toggle="modal" data-target="#select-area-modal">
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
