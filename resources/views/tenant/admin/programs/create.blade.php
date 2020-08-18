@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Proposals</div>

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


                        <div class="table">

                            <table class="table-sm text-center" style="width:100%;">

                                <tbody>
                                        <tr>

                                            <td>

                                                <div class="form-group">

                                                    <label for="">Start Date</label>

                                                    <input type="date" class="form-control form-control-sm" name="programs[0][start_date]">

                                                </div>

                                                <div class="form-group">

                                                    <label for="">Start Time</label>

                                                    <input type="time" class="form-control form-control-sm" name="programs[0][start_time]">

                                                </div>

                                            </td>

                                            <td>

                                                <div class="form-group">

                                                    <label for="">End Date</label>

                                                    <input type="date" class="form-control form-control-sm" name="programs[0][end_date]">

                                                </div>

                                                <div class="form-group">

                                                    <label for="">End Time</label>

                                                    <input type="time" class="form-control form-control-sm" name="programs[0][end_time]">

                                                </div>

                                            </td>

                                            <td>

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




                                                <label for="">Ages/Grades</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <select class="form-control form-control-sm" name="programs[0][ages_type]" id="ages_type">
                                                            <option value="" {{ old("ages_types[0]") == '' ? 'selected' : '' }}>Default</option>
                                                            <option value="ages" {{ old("ages_types[0]") == 'ages' ? 'selected' : '' }}>Ages</option>
                                                            <option value="grades" {{ old("ages_types[0]") == 'grades' ? 'selected' : '' }}>Grades</option>
                                                        </select>
                                                    </div>
                                                    <input type="number" aria-label="Minimum" placeholder="Minimum" name="programs[0][min_age]" value="{{ old("min_ages[0]") }}" class="form-control form-control-sm">
                                                    <input type="number" aria-label="Maximum" placeholder="Maximum" name="programs[0][max_age]" value="{{ old("max_ages[0]") }}" class="form-control form-control-sm">
                                                </div>

                                            </td>

                                        </tr>

                                </tbody>

                            </table>

                        </div>
						<button onclick="addMore()" type="button" class="btn btn-link addMore">Add more</button>

                        <div class="form-group text-right">

                            <button type="submit" class="btn btn-primary btn-lg btn-block">Propose</button>

                        </div>


                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    mixpanel.track_forms("#submit", "Create Proposal");
	function addMore() {
        var itm = document.getElementsByClassName("table-sm text-center")[0].firstElementChild;
        var cln = itm.cloneNode(true);
		document.getElementsByClassName("table-sm text-center")[0].appendChild(cln);
	}
</script>
@endsection
