@extends('layouts.app')
@section('title', 'Add Proposal')
@include('tenant.admin.templates.components.add_template_modal')
@include('tenant.admin.organizations.components.add_organization_modal')
@include('tenant.admin.sites.components.add_site_modal')

@section('content')
<div class="container">
    @include('shared.form_errors')
    <div class="card">
        <div class="card-header">Add Proposal</div>
        <div class="card-body">
            <form id="submit" method="POST" action="{{ tenant()->route('tenant:admin.programs.store') }}">
                @csrf
                <div class="form-group">
                    <label for="">Program</label>
                    <select class="form-control" name="template_id" id="">
                        @forelse($templates as $template)
                            <option value="{{ $template->id }}" {{ request()->input('template_id') == $template->id ? 'selected' : '' }}>
                                {{ $template->internal_name }}{{ $template->internal_name != $template->name ? " - " . $template->name : null }}
                            </option>
                        @empty
                            <option disabled>No programs</option>
                        @endforelse
                    </select>
                    <small id="add-template" class="text-muted">Can't find the program you'd like? <a href="" data-toggle="modal" data-target="#add-template-modal">Add a program </a></small>
                </div>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="organization_id">Proposal Recipient</label>
                        <select class="form-control" name="recipient_organization_id" id="" required>
                                <option value="">--------</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization['id'] }}">{{ $organization['name'] }} ({{ $organization->emailableContacts()->pluck('name')->implode(', ') }})</option>
                            @endforeach
                        </select>
                        <small id="add-organization" class="text-muted">Select the organization you'd like to send this proposal to. Can't find the organization you'd like? <a href="" data-toggle="modal" data-target="#add-organization-modal">Add an organization</a></small>
                    </div>
                    <div class="form-group col">
                        <label for="site_id">Site</label>
                        <select class="form-control" name="site_id" id="">
                            <option value="">Site TBD</option>
                            @foreach ($sites as $site)
                                <option value="{{ $site['id'] }}">{{ $site['name'] }} - {{ $site['address'] }}</option>
                            @endforeach
                        </select>
                        <small id="add-site" class="text-muted">For virtual programs, select [VIRTUAL]. Can't find the site you'd like? <a href="" data-toggle="modal" data-target="#add-site-modal">Add a site</a></small>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Start Time</label>
                                <input type="time" class="form-control" name="start_time" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>End Time</label>
                                <input type="time" class="form-control" name="end_time">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
