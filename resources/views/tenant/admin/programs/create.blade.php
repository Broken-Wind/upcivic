@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add Proposal</div>

                <div class="card-body">

                    <form id="submit" method="POST" action="{{ tenant()->route('tenant:admin.programs.store') }}">

                        @csrf

                        @include('shared.form_errors')

                        <div class="form-group">

                            <label for="">Program</label>

                            <select class="form-control" name="programs[0][template_id]" id="">

                                @forelse($templates as $template)

                                    <option value="{{ $template->id }}">{{ $template->internal_name }}{{ $template->internal_name != $template->name ? " - " . $template->name : null }}</option>

                                @empty

                                    <option disabled>No templates</option>

                                @endforelse

                            </select>
                        </div>

                        <div class="form-row">

                            <div class="form-group col">

                                <label for="organization_id">Host</label>

                                <select class="form-control" name="recipient_organization_id" id="" required>

                                        <option value="">--------</option>

                                    @foreach ($organizations as $organization)

                                        <option value="{{ $organization['id'] }}">{{ $organization['name'] }} ({{ $organization->emailableContacts()->pluck('name')->implode(', ') }})</option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="form-group col">

                                <label for="site_id">Site</label>

                                <select class="form-control" name="site_id" id="">

                                    <option value="">Site TBD</option>

                                    @foreach ($sites as $site)

                                        <option value="{{ $site['id'] }}">{{ $site['name'] }} - {{ $site['address'] }}</option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                        <div class="form-group">

                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" class="form-control" name="programs[0][start_date]">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" class="form-control" name="programs[0][end_date]">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">

                                        <label>Start Time</label>

                                        <input type="time" class="form-control" name="programs[0][start_time]">

                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">

                                        <label>End Time</label>

                                        <input type="time" class="form-control" name="programs[0][end_time]">

                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="form-group">

                            <button type="submit" class="btn btn-primary">Add</button>

                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
