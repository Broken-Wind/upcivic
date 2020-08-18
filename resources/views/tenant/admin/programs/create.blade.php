@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Propose Programs</div>

                <div class="card-body">

                    <form id="submit" method="POST" action="{{ tenant()->route('tenant:admin.programs.store') }}">

                        @csrf

                        @include('shared.form_errors')

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

                                <small class="text-muted">Can't find the site you'd like? <a href="{{ tenant()->route('tenant:admin.sites.create') }}">Add a site.</a></small>

                            </div>

                        </div>

                        <div class="form-row">
                            <div class="col-md-6">

                                <div class="form-group">
                                  <label for="emailProposalTo">Additional Recipient: <span class="text-muted">(Optional)</span></label>
                                  <input type="text" class="form-control" name="cc_emails[]" placeholder="email@example.com">
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emailProposalTo">Additional Recipient: <span class="text-muted">(Optional)</span></label>
                                    <input type="text" class="form-control" name="cc_emails[]" placeholder="email@example.com">
                                </div>

                            </div>
                        </div>

                        <div class="form-group">

                            <label for="">Program</label>

                            <select class="form-control form-control-sm" name="programs[0][template_id]" id="">

                                    @forelse($templates as $template)

                                        <option value="{{ $template->id }}">{{ $template->internal_name }}{{ $template->internal_name != $template->name ? " - " . $template->name : null }}</option>

                                    @empty

                                        <option disabled>No templates</option>

                                    @endforelse

                            </select>
                        </div>

                        <proposal-component></proposal-component>

                        <div class="form-group text-right">

                            <button type="submit" class="btn btn-primary btn-lg btn-block">Propose</button>

                        </div>


                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
