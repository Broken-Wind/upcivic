<div class="card mb-4">
    <div class="card-header">Contributors</div>
    <div class="card-body">
        <form method="POST" id="update_program_contributors"
              action="{{ tenant()->route('tenant:admin.programs.contributors.update', [$program]) }}">
            @method('put')
            @csrf
            @if(!$program->isEditable())
                <fieldset disabled="disabled"/>
            @endif
        </form>
        <table class="table table-striped">
            @if($program['shared_invoice_type'])
                <tr>
                    <th>
                        Estimated Program Base Fee:
                    </th>
                    <th>
                        ${{ $program->formatted_base_fee }} {{ $program->shared_invoice_type }}<br>
                        <small>ESTIMATE ONLY. <i class="fas fa-fw fa-info-circle" title="To charge this amount to register, select 'Register via {{ config('app.name') }}' and set the price."></i></small>
                    </th>
                </tr>
            @endif
            @foreach($program->contributors as $contributor)
                <tr>
                    <td>{{ $contributor->organization->name }} Compensation</td>
                    <td>
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" form="update_program_contributors"
                                   aria-label="Compensation" placeholder="TBD"
                                   name="contributors[{{$contributor->id}}][invoice_amount]"
                                   value="{{ old("contributors[{$contributor->id}][invoice_amount]") ?: $contributor['formatted_invoice_amount'] }}"
                                   class="form-control">
                            <select form="update_program_contributors" class="form-control"
                                    name="contributors[{{$contributor->id}}][invoice_type]"
                                    id="invoice_type">
                                <option
                                    value="per participant" {{ old("contributors[{$contributor->id}][invoice_type]") == 'per participant' ? 'selected' : (empty(old("contributors[{$contributor->id}][invoice_type]")) && $contributor['invoice_type'] == 'per participant' ? 'selected' : '') }}>
                                    per participant
                                </option>
                                <option
                                    value="per hour" {{ old("contributors[{$contributor->id}][invoice_type]") == 'per hour' ? 'selected' : (empty(old("contributors[{$contributor->id}][invoice_type]")) && $contributor['invoice_type'] == 'per hour' ? 'selected' : '') }}>
                                    per hour
                                </option>
                                <option
                                    value="per session" {{ old("contributors[{$contributor->id}][invoice_type]") == 'per session' ? 'selected' : (empty(old("contributors[{$contributor->id}][invoice_type]")) && $contributor['invoice_type'] == 'per session' ? 'selected' : '') }}>
                                    per session
                                </option>
                                <option
                                    value="per meeting" {{ old("contributors[{$contributor->id}][invoice_type]") == 'per meeting' ? 'selected' : (empty(old("contributors[{$contributor->id}][invoice_type]")) && $contributor['invoice_type'] == 'per meeting' ? 'selected' : '') }}>
                                    per meeting
                                </option>
                            </select>
                            @if($program->contributors->count() > 1 && $program['shared_invoice_type'])
                                <div class="input-group-append">
                                    <span class="input-group-text text-muted">({{ $contributor['percentage_of_total_fee'] }}%)</span>
                                    <form method="POST" id="destroy_contributor_{{ $contributor->id }}"
                                          action="{{ tenant()->route('tenant:admin.programs.contributors.destroy', [$program, $contributor]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onClick="return confirm('Are you sure you want to remove this contributor? They will lose access to this program.');"
                                                class="btn btn-sm btn-secondary ml-1"
                                                form="destroy_contributor_{{ $contributor->id }}">X
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>
                    <select form="update_program_contributors" class="form-control form-control-sm"
                            name="newContributor[organization_id]" id="">
                        <option value="">Add Contributor</option>
                        @forelse($organizations as $organization)
                            <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                        @empty
                            <option disabled>None found.</option>
                        @endforelse
                    </select>
                </td>
                <td>
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Compensation</span>
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="text" form="update_program_contributors" aria-label="Compensation"
                               placeholder="TBD" name="newContributor[invoice_amount]"
                               value="{{ old("newContributor[invoice_amount]") }}" class="form-control">
                        <select form="update_program_contributors" class="form-control"
                                name="newContributor[invoice_type]" id="invoice_type">
                            <option
                                value="per participant" {{ old("newContributor[invoice_type]") == 'per participant' ? 'selected' : '' }}>
                                per participant
                            </option>
                            <option
                                value="per hour" {{ old("newContributor[invoice_type]") == 'per hour' ? 'selected' : '' }}>
                                per hour
                            </option>
                            <option
                                value="per session" {{ old("newContributor[invoice_type]") == 'per session' ? 'selected' : '' }}>
                                per session
                            </option>
                            <option
                                value="per meeting" {{ old("newContributor[invoice_type]") == 'per meeting' ? 'selected' : '' }}>
                                per meeting
                            </option>
                        </select>
                    </div>
                </td>
            </tr>
        </table>
        <button type="submit" form="update_program_contributors" id="submit" class="btn btn-secondary">
            Update
        </button>
    </div>
</div>
