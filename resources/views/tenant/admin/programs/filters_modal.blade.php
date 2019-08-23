<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="filterModal">Filters</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <div class="form-row">

                    <div class="form-group col-md-6">

                        <label for="organization_id">Organization</label>

                        <select class="form-control" name="organization">

                            <option value="">All Organizations</option>

                            @forelse ($organizations as $organization)

                                <option value="{{ $organization['id'] }}"

                                @if (isset(request()['organization']) && $organization['id'] == request()['organization'])

                                    {{ 'selected '}}

                                @endif

                                >{{ $organization['name'] }}</option>

                            @empty

                                <option disabled>No organizations exist.</option>

                            @endforelse

                        </select>

                    </div>

                    <div class="form-group col-md-6">

                        <label for="site_id">Site</label>

                        <select class="form-control" name="site">

                            <option value="">All Sites</option>

                            <option value="unset">Site TBD</option>

                            @foreach ( $sites as $site )

                                <option value="{{ $site['id'] }}"

                                @if (isset(request()['site']) && $site['id'] == request()['site'])

                                    {{ 'selected '}}

                                @endif

                                >{{ $site['name'] }}</option>

                            @endforeach

                        </select>

                    </div>

                </div>



                <div class="form-row">

                    <div class="form-group col-md-6">

                        <label for="from_date">From</label>

                        <input type="date" class="form-control" name="from_date" value="{{ request()['from_date'] ?? null }}">

                    </div>

                    <div class="form-group col-md-6">

                        <label for="to_date">To</label>

                        <input type="date" class="form-control" name="to_date" value="{{ request()['to_date'] ?? null }}">

                    </div>

                </div>

                <div class="form-row">

                    <div class="form-group col-md-6">

                        <div class="form-check">

                            <label class="form-check-label">

                                <input type="checkbox" class="form-check-input" name="past" id="" value="true" {{ isset(request()['past']) && request()['past'] ? 'checked' : '' }}>

                                Include Past Sessions

                            </label>

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                <button type="submit" class="btn btn-primary">Update Filters</button>

            </div>

        </div>

    </div>

</div>
