@extends('layouts.app')

@section('title', 'Sites')

@section('content')
<div class="container">
    @include('shared.form_errors')

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
