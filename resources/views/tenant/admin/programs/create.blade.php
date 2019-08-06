@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Programs</div>

                <div class="card-body">

                    <form method="POST" action="{{ tenant()->route('tenant:admin.programs.store') }}">

                        @csrf

                        @include('shared.form_errors')

                        <div class="form-row">

                            <div class="form-group col">

                                <label for="organization_id">Host</label>

                                <select class="form-control" name="organization_id" id="" required>

                                        <option value="">--------</option>

                                    @foreach ($organizations as $organization)

                                        <option value="{{ $organization['id'] }}">{{ $organization['name'] }}</option>

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


                        <div class="table">


                            <small class="form-text text-muted">Blank end dates/times will pull the defaults from the selected template.</small>

                            <table class="table-sm text-center" style="width:100%;">

                                <tbody>

                                    @for ($n=0; $n<6; $n++)

                                        <tr>

                                            <td colspan="3"><h5>Proposal {{ $n+1 }}{{ $n != 0 ? ' (Optional)' : '' }}</h5>

                                        </tr>

                                        <tr>

                                            <td>

                                                <div class="form-group">

                                                    <label for="">Start Date</label>

                                                    <input type="date" class="form-control form-control-sm" name="programs[{{ $n }}][start_date]">

                                                </div>

                                                <div class="form-group">

                                                    <label for="">Start Time</label>

                                                    <input type="time" class="form-control form-control-sm" name="programs[{{ $n }}][start_time]">

                                                </div>

                                            </td>

                                            <td>

                                                <div class="form-group">

                                                    <label for="">End Date</label>

                                                    <input type="date" class="form-control form-control-sm" name="programs[{{ $n }}][end_date]">

                                                </div>

                                                <div class="form-group">

                                                    <label for="">End Time</label>

                                                    <input type="time" class="form-control form-control-sm" name="programs[{{ $n }}][end_time]">

                                                </div>

                                            </td>

                                            <td>

                                                <div class="form-group">

                                                    <label for="">Template</label>

                                                    <select class="form-control form-control-sm" name="programs[{{ $n }}][template_id]" id="">

                                                            @forelse($templates as $template)

                                                                <option value="{{ $template->id }}">{{ $template->internal_name }}</option>

                                                            @empty

                                                                <option disabled>No templates</option>

                                                            @endforelse

                                                    </select>
                                                </div>




                                                <label for="">Ages/Grades</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <select class="form-control form-control-sm" name="programs[{{ $n }}][ages_type]" id="ages_type">
                                                            <option value="" {{ old("ages_types[{$n}]") == '' ? 'selected' : '' }}>Default</option>
                                                            <option value="ages" {{ old("ages_types[{$n}]") == 'ages' ? 'selected' : '' }}>Ages</option>
                                                            <option value="grades" {{ old("ages_types[{$n}]") == 'grades' ? 'selected' : '' }}>Grades</option>
                                                        </select>
                                                    </div>
                                                    <input type="number" aria-label="Minimum" placeholder="Minimum" name="programs[{{ $n }}][min_age]" value="{{ old("min_ages[{$n}]") }}" class="form-control form-control-sm">
                                                    <input type="number" aria-label="Maximum" placeholder="Maximum" name="programs[{{ $n }}][max_age]" value="{{ old("max_ages[{$n}]") }}" class="form-control form-control-sm">
                                                </div>

                                            </td>

                                        </tr>

                                    @endfor

                                </tbody>

                            </table>

                        </div>


                        <div class="form-group text-right">

                            <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block">Propose</button>

                        </div>


                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
